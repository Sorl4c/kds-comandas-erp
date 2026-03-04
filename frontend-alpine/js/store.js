document.addEventListener('alpine:init', () => {
    Alpine.store('kds', {
        // --- ESTADO ---
        items: [],
        toasts: [],
        online: false,
        activeView: Alpine.$persist('kanban').as('activeView'),
        activeFilter: Alpine.$persist('todo').as('activeFilter'),
        lastUpdate: Date.now(),
        
        // --- CONFIGURACIÓN SSE ---
        sseSource: null,
        reconnectTimeout: null,

        // --- INICIALIZACIÓN ---
        init() {
            this.connectSSE();
            
            // Timer global para forzar re-renders de tiempos si fuera necesario
            setInterval(() => {
                this.lastUpdate = Date.now();
            }, 1000);
        },

        connectSSE() {
            if (this.sseSource) {
                this.sseSource.close();
            }

            console.log('Intentando conectar a SSE...');
            this.sseSource = new EventSource('../backend/kds_api.php');

            this.sseSource.onopen = () => {
                console.log('Conexión SSE establecida');
                this.online = true;
                if (this.reconnectTimeout) {
                    clearTimeout(this.reconnectTimeout);
                    this.reconnectTimeout = null;
                }
            };

            this.sseSource.onmessage = (event) => {
                try {
                    const data = JSON.parse(event.data);
                    
                    // Comprobar si hay IDs nuevos para hacer sonar la alerta
                    if (this.items.length > 0) {
                        const currentIds = new Set(this.items.map(i => i.id));
                        const hasNewOrders = data.some(item => !currentIds.has(item.id) && item.estado === 'pendiente');
                        
                        if (hasNewOrders) {
                            const audio = document.getElementById('new-order-sound');
                            if (audio) {
                                audio.play().catch(e => console.log('Autoplay prevent prevented sound', e));
                            }
                        }
                    }

                    // Actualización reactiva de items
                    this.items = data;
                } catch (e) {
                    console.error('Error parseando datos SSE:', e);
                }
            };

            this.sseSource.onerror = (err) => {
                console.error('Error en conexión SSE:', err);
                this.online = false;
                this.sseSource.close();
                
                // Reconexión automática tras 3 segundos
                if (!this.reconnectTimeout) {
                    this.reconnectTimeout = setTimeout(() => {
                        this.connectSSE();
                    }, 3000);
                }
            };
        },

        // --- ACCIONES ---
        async advanceState(ids, newState) {
            // Lógica Optimista: Actualizar localmente antes del fetch
            const originalItems = JSON.parse(JSON.stringify(this.items));
            const timestamp = Date.now();
            
            this.items = this.items.map(item => {
                if (ids.includes(item.id)) {
                    return { ...item, estado: newState, estado_timestamp: timestamp };
                }
                return item;
            });

            // Si pasa a 'listo', crear un Toast
            if (newState === 'listo') {
                const item = originalItems.find(i => ids.includes(i.id));
                if (item) {
                    this.addToast(item, ids, timestamp);
                }
            }

            try {
                const response = await fetch('../backend/update_comanda.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids, estado: newState })
                });
                
                if (!response.ok) throw new Error('Error en el servidor');
            } catch (error) {
                console.error('Fallo al actualizar estado:', error);
                this.items = originalItems; // Rollback
            }
        },

        async goBackState(ids) {
            const item = this.items.find(i => ids.includes(i.id));
            if (!item) return;

            let prevState = 'pendiente';
            if (item.estado === 'emplatado') {
                prevState = item.station;
            } else if (item.estado === item.station) {
                prevState = 'pendiente';
            }

            if (prevState !== item.estado) {
                await this.advanceState(ids, prevState);
                if (navigator.vibrate) navigator.vibrate(50);
            }
        },

        // --- GETTERS (Derivados) ---
        get groupedColumns() {
            let filtered = this.items.filter(i => i.estado !== 'listo');
            if (this.activeFilter !== 'todo') {
                filtered = filtered.filter(i => i.station === this.activeFilter);
            }

            const map = new Map();
            const grouped = [];
            
            filtered.forEach(item => {
                const notesArray = Array.isArray(item.notes) ? item.notes : [];
                // La clave incluye orderId para no mezclar mesas, pero agrupa productos idénticos
                const key = `${item.orderId}-${item.producto}-${item.estado}-${notesArray.join(',')}`;
                
                if (map.has(key)) {
                    const existing = map.get(key);
                    existing.ids.push(item.id);
                    existing.qty += 1;
                    if (item.estado_timestamp < existing.estado_timestamp) {
                        existing.estado_timestamp = item.estado_timestamp;
                    }
                } else {
                    const newItemGroup = { 
                        ...item, 
                        ids: [item.id], 
                        qty: 1,
                        notes: notesArray // Asegurar que es array
                    };
                    map.set(key, newItemGroup);
                    grouped.push(newItemGroup);
                }
            });

            return {
                pendiente: grouped.filter(i => i.estado === 'pendiente'),
                cocina: grouped.filter(i => i.estado === 'cocina'),
                horno: grouped.filter(i => i.estado === 'horno'),
                barra: grouped.filter(i => i.estado === 'barra'),
                emplatado: grouped.filter(i => i.estado === 'emplatado'),
            };
        },

        // --- GESTIÓN DE TOASTS ---
        addToast(item, ids, prevTimestamp) {
            const toastId = Date.now() + Math.random();
            this.toasts.push({
                id: toastId,
                itemId: item.id,
                ids: ids, // Guardamos todos los IDs si era un grupo
                mesa: item.mesa,
                producto: item.producto,
                prevState: item.estado,
                prevTimestamp: prevTimestamp
            });

            // Auto-eliminar tras 5 segundos
            setTimeout(() => {
                this.removeToast(toastId);
            }, 5000);
        },

        removeToast(toastId) {
            this.toasts = this.toasts.filter(t => t.id !== toastId);
        },

        async undoToast(toastId) {
            const toast = this.toasts.find(t => t.id === toastId);
            if (toast) {
                await this.advanceState(toast.ids, toast.prevState);
                this.removeToast(toastId);
            }
        }
    });
});
