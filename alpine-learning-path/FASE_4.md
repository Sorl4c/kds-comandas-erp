# FASE 4 — Efectos, Plugins y Magia ($persist)
## El toque profesional

> **Estado:** ⬜ Pendiente

### 🧠 La idea central
Llevar la aplicación de "funcional" a "robusta". Vamos a usar las utilidades oficiales de Alpine para resolver problemas complejos con una sola línea de código.

### 📖 Concepto 1: Persistencia Mágica ($persist)
¿Recuerdas el esfuerzo de leer y escribir de `localStorage` en React? Con el plugin de Alpine, solo tienes que envolver tu variable inicial en `$persist()`.

```javascript
Alpine.store('kds', {
    // Si refrescas (F5), recordará que estabas en la vista de Mesas
    vistaActiva: Alpine.$persist('kanban'),
    
    // Puedes incluso darle un nombre clave para el localStorage
    filtro: Alpine.$persist('todo').as('kds_filtro_preferido')
});
```

### 📖 Concepto 2: x-effect (Reaccionar al entorno)
A veces quieres que "pase algo" (un efecto secundario) cada vez que un dato cambia. Por ejemplo, reproducir un sonido si el número de ítems aumenta.

```html
<!-- Esto ejecutará el console.log cada vez que items cambie de tamaño -->
<div x-effect="console.log('Ahora hay ' + $store.kds.items.length + ' comandas')"></div>
```
*En JavaScript puro, dentro de un componente `Alpine.data`, se usa `this.$watch('variable', callback)` para lograr lo mismo.*

### 📖 Concepto 3: x-teleport (Portales)
Tus "Toasts" (notificaciones de deshacer) deben aparecer en la esquina inferior derecha, siempre por encima de todo. Si los defines dentro de una columna Kanban, el CSS `overflow-hidden` podría cortarlos.

```html
<!-- No importa dónde escribas esto en tu HTML -->
<template x-teleport="body">
    <!-- Se inyectará mágicamente al final del <body> -->
    <div class="fixed bottom-0 right-0 z-50">
        Toasts de notificación aquí...
    </div>
</template>
```

### 🧪 Ejercicio Final (El MVP en Alpine)
Une todas las fases:
1. Crea un `Alpine.store` con SSE que alimente el array de `items`.
2. Crea el Getter para agrupar.
3. Usa `x-teleport` para las notificaciones.
4. Usa componentes `.php` para mantener tu código organizado.

Si lo consigues, habrás replicado el poder de React, pero en el ecosistema "Server-First" de PHP.