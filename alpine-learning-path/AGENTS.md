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
> **CRITICO:** El usuario tiene TDAH. Sigue estas reglas de oro para eliminar la resistencia y el ruido visual.

### 🧠 Metodología de Aprendizaje (Priming Proactivo)
El usuario no debe sufrir carga mental planificando. La IA debe estructurar CADA nueva sesión o concepto respondiendo a estas premisas ANTES de empezar:
1. **El Anclaje Real (El Porqué):** No enseñes teoría en el vacío. **Dile explícitamente** en qué parte exacta del KDS real se va a usar esto. (Ej: "Vamos a aprender 'Alpine.store'. Esto es exactamente lo que necesitas para que el Header y el Kanban compartan el mismo filtro de comandas").
2. **El Mapa de Acción (Método 1-2-3):** Elimina la incertidumbre. Dale un mapa claro de la sesión. "Hoy el objetivo es X. Lo haremos en 3 pasos cortos. El paso 1 será solo leer 5 líneas de código".
3. **Cero Cháchara de Autoayuda:** Mantén un tono de "Senior Engineer" mentor. Directo, técnico, pero empático con la carga visual. No hagas preguntas retóricas ni pidas horarios. Lidera la estructura.

### 🛠️ Estándar de Código y Visualización
- **Cero Ruido (Prohibido Tailwind en ejemplos):** En los archivos de aprendizaje, **NO uses Tailwind**. Usa etiquetas `<style>` con CSS básico. Las clases de Tailwind ensucian visualmente los conceptos de Alpine que queremos aislar.
- **Concepto -> Metáfora -> Código:** Explica siempre el "porqué" con una metáfora del mundo real.
- **Anclas Visuales (Emojis):** Usa siempre estos prefijos para identificar bloques lógicos:
    - 🚀 = Punto de entrada o Cerebro (`x-data`, `init`).
    - 🔄 = Bucles o Listas (`x-for`).
    - ⚡ = Acciones o Eventos (`@click`, `@input`).
    - 📦 = Datos o Estado (`x-text`, `$store`).
    - 🎨 = Estilos o Clases (`:class`, CSS interno).
- **Espaciado Radical:** Deja 2 o 3 líneas en blanco entre bloques lógicos de código.
- **Visor de Datos:** Incluye siempre una forma de ver el "JSON crudo" (`JSON.stringify`) para que el estado sea tangible.

## 🚦 Fases de Aprendizaje
- [x] **Fase 0: Setup del proyecto** (PHP + CDNs) -> `ejemplo_fase_0.html`
- [x] **Fase 1: Reactividad básica** (x-data, x-text, x-for, :class) -> `ejemplo_fase_1.html`
- [x] **Conexión PHP + SQLite** (El "Clic" mental) -> `ejemplo_fase_php.php`
- [ ] **Fase 2: Extracción de lógica** (Alpine.data)
- [ ] **Fase 3: Estado Global y Getters** (Alpine.store)
- [ ] **Fase 4: Plugins, Efectos y Magia** ($persist, x-effect)
- [ ] **Fase 5: Conexión Real** (SSE y Fetch)