# FASE 2 — Extracción de Lógica (Alpine.data)
## Limpiando el código espagueti

> **Estado:** ⬜ Pendiente

### 🧠 La idea central
Escribir `{ isOpen: false, toggle() { this.isOpen = !this.isOpen } }` dentro de un atributo HTML es una chapuza si la lógica crece. Para crear aplicaciones serias, **sacamos la lógica a un archivo JS**.

Este es el equivalente de Alpine a crear un Componente Funcional de React.

### 📖 Concepto: Alpine.data()
Registramos una "fábrica de componentes" en JavaScript.

```javascript
// En tu archivo js/components/tarjeta.js
document.addEventListener('alpine:init', () => {
    
    Alpine.data('tarjetaComanda', (comanda) => ({
        // Estado local (variables)
        segundos: 0,
        timerId: null,
        
        // Métodos de ciclo de vida (El equivalente a useEffect vacío [])
        init() {
            this.timerId = setInterval(() => {
                this.segundos++;
            }, 1000);
        },

        // Métodos de limpieza (Cleanup de useEffect)
        destroy() {
            clearInterval(this.timerId);
        },
        
        // Lógica de negocio (El handler)
        completar() {
            alert('Has completado: ' + comanda.producto);
        }
    }));
    
});
```

En tu HTML (ej. `components/kanban_card.php`), el código queda limpio y profesional:

```html
<!-- "Instanciamos" el componente pasándole los datos iniciales -->
<div x-data="tarjetaComanda({ producto: 'Pizza' })">
    <span x-text="segundos + 's'"></span>
    <button @click="completar()">Hecho</button>
</div>
```

### 🧪 Ejercicio
Transforma la lógica del Long Press (mantener pulsado el ratón para deshacer) de la versión React en un componente `Alpine.data('longPressCard')`. Necesitarás variables locales para `isHolding` y un método que se dispare en `@pointerdown` y `@pointerup`.