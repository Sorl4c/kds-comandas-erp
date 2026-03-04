# README_ROADMAP.md — Alpine.js KDS
## Mi mapa de aprendizaje

> "React es para construir fábricas. Alpine es para transformar una cabaña de madera en una casa inteligente."

### 🔧 FASE 0 — Setup
- [ ] Entiendo cómo instalar Alpine.js y sus plugins vía CDN.
- [ ] Entiendo por qué el orden de carga (defer) es importante.
- [ ] He configurado un esqueleto base en PHP (index.php).

### 📦 FASE 1 — Reactividad Básica
- [ ] Sé inicializar un estado local con `x-data`.
- [ ] Sé cómo pintar variables en el HTML con `x-text`.
- [ ] Entiendo cómo atar clases dinámicas con `:class`.
- [ ] Entiendo cómo iterar arrays con `<template x-for>`.

### 🧩 FASE 2 — Extracción de Lógica (El equivalente a Componentes)
- [ ] Entiendo el problema del código espagueti en HTML.
- [ ] Sé crear un bloque de código reutilizable con `Alpine.data()`.
- [ ] He aislado la lógica del temporizador de la comanda en su propio script `.js`.
- [ ] He usado `x-init` para arrancar ciclos de vida (como setInterval).

### 🌍 FASE 3 — Estado Global (El "Redux" de Alpine)
- [ ] Sé inicializar un estado global con `Alpine.store('nombre', { ... })`.
- [ ] Entiendo cómo leer y escribir en el store desde cualquier parte del HTML con `$store`.
- [ ] **Avanzado:** He creado *Getters* en el store para derivar datos (ej: agrupar comandas iguales) sin afectar el rendimiento.

### ✨ FASE 4 — Efectos y Plugins
- [ ] He usado `@alpinejs/persist` para que la vista activa sobreviva al pulsar F5.
- [ ] Entiendo cómo usar `x-effect` para vigilar variables y disparar acciones (ej: logs o sonidos).
- [ ] Conozco las propiedades mágicas como `$el` (para tocar el DOM) y `$watch`.

### 📡 FASE 5 — Sincronización Real (SSE + Fetch)
- [ ] He implementado un `new EventSource` dentro de la inicialización de mi `$store`.
- [ ] He logrado que Alpine actualice su array local de ítems automáticamente al recibir un "grito" del backend.
- [ ] He creado métodos en el `$store` que lanzan `fetch()` POST al backend para cambiar estados reales.