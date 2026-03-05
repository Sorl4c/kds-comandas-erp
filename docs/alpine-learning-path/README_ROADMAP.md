# README_ROADMAP.md — Alpine.js KDS
## Mi mapa de aprendizaje

> "React es para construir fábricas. Alpine es para transformar una cabaña de madera en una casa inteligente."

### 🔧 FASE 0 — Setup
- [x] Entiendo cómo instalar Alpine.js y sus plugins vía CDN.
- [x] Entiendo por qué el orden de carga (defer) es importante.
- [x] He configurado un esqueleto base en PHP (index.php).

### 📦 FASE 1 — Reactividad Básica
- [x] Sé inicializar un estado local con `x-data`.
- [x] Sé cómo pintar variables en el HTML con `x-text`.
- [x] Entiendo cómo atar clases dinámicas con `:class`.
- [x] Entiendo cómo iterar arrays con `<template x-for>`.

### 🧩 FASE 2 — Extracción de Lógica (El equivalente a Componentes)
- [x] Entiendo el problema del código espagueti en HTML.
- [x] Sé crear un bloque de código reutilizable con `Alpine.data()`.
- [x] He aislado la lógica del temporizador de la comanda en su propio script `.js`.
- [x] He usado `x-init` para arrancar ciclos de vida (como setInterval).

### 🌍 FASE 3 — Estado Global (El "Redux" de Alpine)
- [x] Sé inicializar un estado global con `Alpine.store('nombre', { ... })`.
- [x] Entiendo cómo leer y escribir en el store desde cualquier parte del HTML con `$store`.
- [x] **Avanzado:** He creado *Getters* en el store para derivar datos (ej: agrupar comandas iguales) sin afectar el rendimiento.

### ✨ FASE 4 — Efectos y Plugins
- [x] He usado `@alpinejs/persist` para que la vista activa sobreviva al pulsar F5.
- [x] Entiendo cómo usar `x-effect` para vigilar variables y disparar acciones (ej: logs o sonidos).
- [x] Conozco las propiedades mágicas como `$el` (para tocar el DOM) y `$watch`.

### 📡 FASE 5 — Sincronización Real (SSE + Fetch)
- [x] He implementado un `new EventSource` dentro de la inicialización de mi `$store`.
- [x] He logrado que Alpine actualice su array local de ítems automáticamente al recibir un "grito" del backend.
- [x] He creado métodos en el `$store` que lanzan `fetch()` POST al backend para cambiar estados reales.