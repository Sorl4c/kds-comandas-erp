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

## 🤖 Instrucciones para la IA
- Explica siempre el porqué, no solo el cómo.
- Enseña el "Alpine avanzado", no el Alpine de "añadir una clase al hacer clic".
- Usa `$store`, `Alpine.data`, `$persist` y `$watch`.
- Los datos y la conexión real SSE son la prioridad.

## 🚦 Fases de Aprendizaje
- [ ] Fase 0: Setup del proyecto (PHP + CDNs)
- [ ] Fase 1: Reactividad básica (x-data, x-text)
- [ ] Fase 2: Extracción de lógica (Alpine.data)
- [ ] Fase 3: Estado Global y Getters (Alpine.store)
- [ ] Fase 4: Plugins, Efectos y Magia ($persist, x-effect)
- [ ] Fase 5: Conexión Real (SSE y Fetch)