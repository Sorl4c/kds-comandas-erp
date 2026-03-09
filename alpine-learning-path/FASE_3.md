# FASE 3 — Estado Global (Alpine.store)
## El App.jsx de Alpine

> **Estado:** ✅ Completada
> **Ejemplo:** `ejemplo_fase_3.php`

### 🧠 La idea central
En el KDS, el filtro seleccionado ("Ver solo Horno") le importa al `Header` (para pintar el botón naranja) y le importa al `KanbanBoard` (para ocultar columnas). Si usas `x-data` locales, no se pueden comunicar bien. Necesitas un **Estado Global**.

### 📖 Concepto 1: Alpine.store()
Es una variable reactiva disponible en toda la aplicación.

```javascript
// js/store.js
document.addEventListener('alpine:init', () => {
    Alpine.store('kds', {
        items: [],
        filtroActivo: 'todo',
        
        cambiarFiltro(nuevo) {
            this.filtroActivo = nuevo;
        }
    });
});
```

Uso en CUALQUIER archivo HTML, sin importar dónde esté:
```html
<button @click="$store.kds.cambiarFiltro('horno')">Horno</button>
<div x-show="$store.kds.filtroActivo === 'horno'">Columna Horno</div>
```

### 📖 Concepto 2: Getters (El superpoder de la derivación)
En React usaste `useMemo` para agrupar pizzas idénticas en un "x2". En Alpine, la forma experta de hacer esto es usando un "Getter" de JavaScript dentro del store.

```javascript
Alpine.store('kds', {
    items: [{id:1, prod:'Pizza', estado:'horno'}, {id:2, prod:'Pizza', estado:'horno'}],
    
    // Este código simula tu agrupador.
    // Alpine lo cachea automáticamente. Solo se recalcula si 'this.items' cambia.
    get columnasAgrupadas() {
        let agrupado = {};
        this.items.forEach(item => {
            if(!agrupado[item.estado]) agrupado[item.estado] = [];
            agrupado[item.estado].push(item);
        });
        return agrupado;
    }
});
```

### 🧪 Ejercicio
Recrea la lógica completa de agrupación (el `useMemo` de `App.jsx` que creaba las agrupaciones basándose en producto, estado y mesa) dentro de un *Getter* en tu `Alpine.store`. Llámalo desde el HTML iterando: `<template x-for="grupo in $store.kds.columnasAgrupadas.horno">`.