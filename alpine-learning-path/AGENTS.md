# AGENTS.md — Alpine.js KDS Path
## Guía para IA y para mí mismo

> Este fichero define las reglas del juego para la versión Alpine.js.
> El objetivo es dominar Alpine v3 para crear aplicaciones complejas (SPA-like) sin herramientas de build, apoyándonos en PHP para la estructura.

---

## 🎯 Intención del proyecto
Reescribir el KDS (Kitchen Display System) utilizando **PHP + Alpine.js**, logrando la misma calidad arquitectónica que la versión React, pero maximizando la velocidad de despliegue y minimizando dependencias.

## 📐 Filosofía de trabajo
- **El Servidor manda:** PHP renderiza el cascarón (el layout, las columnas).
- **Alpine le da vida:** Alpine.js solo se encarga del estado del cliente (filtros, timers, SSE).
- **Cero Build Steps:** Nada de Node, NPM ni Vite. Solo ficheros y el navegador.
- **HTML Limpio:** Prohibido escribir lógica compleja en los atributos `x-`. Si ocupa más de una línea, va a un archivo JS usando `Alpine.data` o `Alpine.store`.

## 🤖 Instrucciones para la IA (Protocolo TDAH-Friendly)
> **CRITICO:** El usuario tiene TDAH. Sigue estas reglas de oro para que el código no sea una barrera visual.

- **Concepto -> Metáfora -> Código:** No lances código sin explicar el "porqué" con una metáfora del mundo real (ej. el cartón de huevos para los arrays).
- **Anclas Visuales (Emojis):** Usa siempre estos prefijos para identificar bloques lógicos:
    - 🚀 = Punto de entrada o Cerebro (`x-data`, `init`).
    - 🔄 = Bucles o Listas (`x-for`).
    - ⚡ = Acciones o Eventos (`@click`, `@input`).
    - 📦 = Datos o Estado (`x-text`, `$store`).
    - 🎨 = Estilos o Clases (`:class`, Tailwind).
- **Espaciado Radical:** Deja 2 o 3 líneas en blanco entre bloques lógicos de código. El aire visual ayuda a la concentración.
- **Enseña el "Alpine avanzado":** No te quedes en lo básico; usa `$store`, `Alpine.data`, `$persist` y `$watch`.
- **Visor de Datos:** En los ejemplos, incluye siempre una forma de ver el "JSON crudo" (usando `JSON.stringify`) para que el estado sea tangible.

## 🚦 Fases de Aprendizaje
- [x] **Fase 0: Setup del proyecto** (PHP + CDNs) -> `ejemplo_fase_0.html`
- [x] **Fase 1: Reactividad básica** (x-data, x-text, x-for, :class) -> `ejemplo_fase_1.html`
- [x] **Conexión PHP + SQLite** (El "Clic" mental) -> `ejemplo_fase_php.php`
- [ ] **Fase 2: Extracción de lógica** (Alpine.data)
- [ ] **Fase 3: Estado Global y Getters** (Alpine.store)
- [ ] **Fase 4: Plugins, Efectos y Magia** ($persist, x-effect)
- [ ] **Fase 5: Conexión Real** (SSE y Fetch)