# FASE 1 — Reactividad Básica
## Haciendo que el HTML hable

> **Estado:** ⬜ Pendiente

### 🧠 La idea central
En Vanilla JS (JS puro), seleccionas elementos y los mutas (`document.getElementById('mesa').innerText = 'hola'`). En Alpine, el HTML "declara" a qué variable está atento, y cuando la variable cambia, el HTML muta solo.

### 📖 Concepto 1: x-data (El inicio de todo)
Todo bloque de Alpine DEBE estar envuelto en un `x-data`. Esto define el "alcance" (scope) de tus variables.

```html
<!-- Esto es un estado local -->
<div x-data="{ mensaje: 'Hola Cocina', isOpen: false }">
    <!-- El botón cambia la variable -->
    <button @click="isOpen = !isOpen">Cambiar Estado</button>
    
    <!-- El texto reacciona a la variable -->
    <p x-text="mensaje"></p>
    
    <!-- El div aparece/desaparece según la variable -->
    <div x-show="isOpen">Menú secreto</div>
</div>
```

### 📖 Concepto 2: x-for (Iterar listas)
Para pintar comandas, necesitas un bucle. En Alpine se usa la etiqueta `<template>`.

```html
<div x-data="{ pizzas: ['Margarita', 'Barbacoa', '4 Quesos'] }">
    <ul>
        <template x-for="pizza in pizzas" :key="pizza">
            <li x-text="pizza"></li>
        </template>
    </ul>
</div>
```
*Ojo:* Igual que en React necesitabas un prop `key` en los `.map()`, en Alpine usas `:key="id"` para que el framework sepa qué elemento mover si el array cambia.

### 🧪 Ejercicio
Crea un `x-data` con un array hardcodeado de 3 comandas falsas. Crea un botón que, al pulsarlo, haga un `.push()` de una nueva comanda al array. Si lo has hecho bien, verás aparecer la nueva comanda en la lista automáticamente.