document.addEventListener('alpine:init', () => {
    const WORKFLOW = {
        pendiente: (station) => station,
        barra: () => 'emplatado',
        cocina: () => 'emplatado',
        horno: () => 'emplatado',
        emplatado: () => 'listo'
    };

    const STATION_COLORS = {
        barra: '#06b6d4',
        cocina: '#ef4444',
        horno: '#f97316'
    };

    const HOLD_DURATION = 600;

    Alpine.data('kanbanCard', (item) => ({
        isHolding: false,
        holdTimeout: null,
        hasLongPressed: false,
        
        // --- PROPIEDADES DERIVADAS ---
        get elapsedMs() {
            // Dependemos de $store.kds.lastUpdate para la reactividad del timer
            return this.$store.kds.lastUpdate - item.estado_timestamp;
        },

        get timeString() {
            const minutes = Math.floor(this.elapsedMs / 60000);
            const seconds = Math.floor((this.elapsedMs % 60000) / 1000);
            return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        },

        get minutesElapsed() {
            return Math.floor(this.elapsedMs / 60000);
        },

        get statusClasses() {
            if (this.minutesElapsed >= item.estimated_time) {
                return "bg-red-900/40 border-red-500/50 shadow-[0_0_15px_rgba(220,38,38,0.2)]";
            } else if (this.minutesElapsed >= item.estimated_time * 0.75) {
                return "bg-amber-900/30 border-amber-500/50";
            }
            return "bg-slate-700/80 border-slate-600 text-slate-200";
        },

        get timeTextClass() {
            if (this.minutesElapsed >= item.estimated_time) {
                return "text-red-400 font-bold animate-pulse";
            } else if (this.minutesElapsed >= item.estimated_time * 0.75) {
                return "text-amber-400 font-bold";
            }
            return "text-slate-300";
        },

        get stationColor() {
            return STATION_COLORS[item.station] || '#475569';
        },

        get nextState() {
            return WORKFLOW[item.estado] ? WORKFLOW[item.estado](item.station) : null;
        },

        get nextStateName() {
            const state = this.nextState;
            if (!state) return null;
            if (state === 'listo') return 'Terminar';
            return state.charAt(0).toUpperCase() + state.slice(1);
        },

        get hasPrevState() {
            return item.estado === 'emplatado' || item.estado === item.station;
        },

        // --- MANEJADORES DE EVENTOS ---
        handlePointerDown(e) {
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            
            this.hasLongPressed = false;
            this.isHolding = true;
            
            this.holdTimeout = setTimeout(() => {
                this.hasLongPressed = true;
                if (this.hasPrevState) {
                    this.$store.kds.goBackState(item.ids);
                }
                this.isHolding = false;
            }, HOLD_DURATION);
        },

        handlePointerUpOrLeave(e) {
            this.isHolding = false;
            if (this.holdTimeout) {
                clearTimeout(this.holdTimeout);
                this.holdTimeout = null;
            }
            
            // Si es un click (no long press) y hay un estado siguiente
            if (e.type === 'pointerup' && !this.hasLongPressed) {
                if (this.nextState) {
                    this.$store.kds.advanceState(item.ids, this.nextState);
                }
            }
        }
    }));
});
