# PRD & Implementation Plan: KitchenSync KDS (Alpine.js + PHP Edition)

## 🎯 1. Objetivo del Proyecto
Replicar la funcionalidad, limpieza arquitectónica y experiencia de usuario (UX) del MVP actual desarrollado en React + Vite, utilizando exclusivamente **PHP puro** (para la estructuración de componentes) y **Alpine.js v3** (para la reactividad y el estado del cliente).

El objetivo principal es demostrar que, usando patrones avanzados de Alpine.js, se puede alcanzar una mantenibilidad y separación de responsabilidades ("React-like") sin necesidad de herramientas de compilación complejas (Node.js, Vite).

## 📚 2. Referencias Técnicas (Alpine.js Avanzado)
Cualquier desarrollador (o IA) que trabaje en este proyecto DEBE consultar esta documentación antes de implementar su respectiva fase:

*   **Estado Global:** [Alpine.store](https://alpinejs.dev/globals/alpine-store) - Reemplaza a `App.jsx` como única fuente de verdad (Source of Truth).
*   **Lógica de Componentes Aisalda:** [Alpine.data](https://alpinejs.dev/globals/alpine-data) - Reemplaza los componentes funcionales de React para encapsular lógica y variables locales.
*   **Estado Derivado (El reemplazo de useMemo):** [JavaScript Getters en Alpine](https://alpinejs.dev/essentials/state#getters) - Vital para la agrupación de comandas (`x2`, `x3`) sin afectar el rendimiento.
*   **Persistencia Local:** [Plugin Persist](https://alpinejs.dev/plugins/persist) - El reemplazo de nuestro `useEffect` con `localStorage`. Permite que variables como la vista activa sobrevivan al F5.
*   **Efectos Secundarios:** [x-effect](https://alpinejs.dev/directives/effect) - El equivalente exacto a `useEffect` para sincronizaciones (como el reloj del sistema).
*   **Portales (Opcional para Toasts):** [x-teleport](https://alpinejs.dev/directives/teleport) - Reemplaza la necesidad de anclar componentes flotantes (como notificaciones) en la raíz del documento.

---

## 📁 3. Estructura de Carpetas (Monorepo)

Se propone la siguiente arquitectura para mantener la higiene mental:

```text
comandas-kds/
├── backend/                  <-- API, SSE, Base de Datos (Compartido y funcional)
│   ├── kds_database.db
│   ├── kds_api.php
│   ├── update_comanda.php
│   └── pos_simulator.php
│
├── frontend-react/           <-- El MVP actual (referencia)
│
└── frontend-alpine/          <-- NUEVO PROYECTO
    ├── index.php             <-- Orquestador principal
    ├── css/
    │   └── tailwind.css      <-- Tailwind compilado (o CDN para desarrollo rápido)
    ├── js/
    │   ├── app.js            <-- Inicialización de Alpine
    │   ├── store.js          <-- Lógica global (SSE, fetch, estado)
    │   └── components/       <-- Archivos Alpine.data()
    │       ├── kanbanCard.js
    │       └── header.js
    └── components/           <-- Fragmentos PHP (Estructura DOM)
        ├── header.php
        ├── kanban_column.php
        ├── kanban_card.php
        └── toasts.php
```

---

## 🚀 4. Fases de Implementación

### FASE 0: Setup e Inicialización
**Objetivo:** Montar el esqueleto base y cargar las librerías necesarias.
1.  Crear la carpeta `frontend-alpine`.
2.  Crear `index.php` con la estructura HTML base.
3.  Cargar Tailwind CSS vía CDN.
4.  Cargar Alpine.js (Core + Persist Plugin) vía CDN. Asegurar que los scripts se cargan con `defer`.
5.  Crear los ficheros base de la carpeta `js/`.

### FASE 1: El Cerebro Global (`Alpine.store`)
**Objetivo:** Replicar el estado de `App.jsx` y la conexión al Backend.
1.  En `js/store.js`, definir `Alpine.store('kds', { ... })`.
2.  **Estado:** Incluir `items: []`, `activeView: Alpine.$persist('kanban')`, `activeFilter: Alpine.$persist('todo')`.
3.  **SSE:** Crear un método `init()` dentro del store que abra el `new EventSource()` apuntando a `../backend/kds_api.php`. Cuando reciba datos, actualizar `this.items`.
4.  **Acciones:** Implementar `advanceState(ids, newState)` y `goBackState(ids)` usando `fetch()` apuntando a `../backend/update_comanda.php` (lógica optimista + write-back).

### FASE 2: Componentización del Layout (PHP)
**Objetivo:** Crear el esqueleto visual usando `include` de PHP.
1.  En `index.php`, añadir `x-data` en el body para inicializar la app.
2.  Crear `components/header.php`. Leer variables globales usando `$store.kds.activeView`, etc.
3.  Crear el esqueleto de `components/kanban_column.php`. Usar un bucle estático o prepararlo para recibir datos de un `x-for`.
4.  Integrar el Header en `index.php` mediante `<?php include 'components/header.php'; ?>`.

### FASE 3: Lógica Derivada y Getters (El Reemplazo de `useMemo`)
**Objetivo:** Agrupar comandas iguales (x2, x3) dinámicamente.
1.  Dentro de `Alpine.store('kds')` o en un `Alpine.data` superior, crear un "Getter" (e.g., `get groupedColumns() { ... }`).
2.  Copiar la lógica de filtrado y mapeo de `App.jsx` que agrupa ítems por mesa, producto, estado y notas.
3.  El getter debe devolver un objeto con las columnas: `{ pendiente: [...], cocina: [...], ... }`.
4.  En el `index.php`, iterar sobre estas columnas pasando los datos a los fragmentos PHP.

### FASE 4: Interacciones Complejas y Temporizadores (`Alpine.data`)
**Objetivo:** El "Long Press" y los relojes de colores.
1.  Crear `js/components/kanbanCard.js`. Definir `Alpine.data('kanbanCard', (item) => ({ ... }))`.
2.  **Temporizador:** Implementar la lógica de cálculo de tiempo transcurrido, color de borde y texto. Usar `setInterval` en la inicialización del componente y limpiarlo al destruir (simulando el hook `useCurrentTime`).
3.  **Long Press:** Replicar la lógica de `pointerdown`, `pointerup` usando `setTimeout` y variables locales dentro del componente.
4.  Enlazar `components/kanban_card.php` con este dato usando `<div x-data="kanbanCard(item)">`.

### FASE 5: Toasts y Pulido Final
**Objetivo:** Sistema de notificaciones con capacidad de "Deshacer".
1.  Añadir un array `toasts: []` al store global.
2.  Modificar la función `advanceState` del store para que, si el estado es 'listo', añada un elemento a `toasts` y prepare un `setTimeout` para borrarlo en 5 segundos.
3.  Crear `components/toasts.php` usando `<template x-for="toast in $store.kds.toasts">`.
4.  Implementar la llamada a la función "Deshacer" (`undoToast(toastId)`) en el botón correspondiente.

---

## 📝 5. Reglas de Desarrollo (Guidelines)
1.  **Cero Lógica en HTML:** Prohibido usar expresiones ternarias largas o funciones complejas dentro de los atributos `x-` del HTML. Toda lógica debe vivir en los archivos `.js` (`store.js` o `Alpine.data`).
2.  **Unidad de Componentes:** Cada archivo `.php` en `components/` debe ser tonto visualmente y recibir su contexto o depender del `$store` global.
3.  **Comprobación Rigurosa:** Tras cada fase, se debe validar en el navegador que no hay errores de consola y que la funcionalidad es idéntica a la de React.