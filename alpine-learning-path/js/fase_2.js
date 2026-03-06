/**
 * 🧠 EL CEREBRO (Componente Alpine)
 * 
 * En lugar de escribirlo en el HTML, lo registramos aquí.
 * 'KdsApp' es el nombre que usaremos en el HTML.
 */
document.addEventListener('alpine:init', () => {
    
    Alpine.data('KdsApp', (datosIniciales) => ({
        // 📦 DATOS (Estado)
        comandas: datosIniciales,
        mostrarJson: false,

        // 🚀 INICIALIZACIÓN
        init() {
            console.log('¡KDS Listo y funcionando!');
        },

        // ⚡ ACCIONES (Métodos)
        toggleJson() {
            this.mostrarJson = !this.mostrarJson;
        },

        get totalComandas() {
            return this.comandas.length;
        }
    }));

});
