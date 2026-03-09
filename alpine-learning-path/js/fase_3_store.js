/**
 * ARCHIVO: js/fase_3_store.js
 * PATRÓN: Singleton (Fuente única de verdad)
 */
document.addEventListener('alpine:init', () => {
    
    Alpine.store('kds', {
        tickets: [
            { id: 1, mesa: 'Mesa 4', estado: 'pendiente', urgente: false },
            { id: 2, mesa: 'Barra', estado: 'preparando', urgente: true }
        ],
        filtro: 'todos',

        // --- FACTORY: Generación consistente ---
        crearTicket(nombre, esUrgente = false) {
            this.tickets.push({
                id: Date.now(),
                mesa: nombre,
                estado: 'pendiente',
                urgente: esUrgente
            });
        },

        // --- ACTIONS: Mutaciones del estado ---
        avanzarTicket(id) {
            let t = this.tickets.find(i => i.id === id);
            if (t.estado === 'pendiente') t.estado = 'preparando';
            else this.tickets = this.tickets.filter(i => i.id !== id);
        },

        // --- STRATEGY / GETTERS: Lógica derivada ---
        get listaFiltrada() {
            if (this.filtro === 'todos') return this.tickets;
            return this.tickets.filter(t => t.estado === this.filtro);
        },

        get totalPendientes() {
            return this.tickets.filter(t => t.estado === 'pendiente').length;
        }
    });
});