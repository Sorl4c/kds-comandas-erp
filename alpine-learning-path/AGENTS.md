# AGENTS.md - Alpine.js KDS Path
## Guia para IA y para mi mismo

> Este fichero define las reglas del juego para la version Alpine.js.
> El objetivo es dominar Alpine v3 para crear aplicaciones complejas, SPA-like y sin build step, apoyandonos en PHP para la estructura.

---

## Intencion del proyecto
Reescribir el KDS (Kitchen Display System) usando `PHP + Alpine.js`, manteniendo una calidad arquitectonica comparable a la version React, pero con menos dependencias, menos friccion de despliegue y mayor velocidad de iteracion.

## Filosofia de trabajo
- `PHP` renderiza el esqueleto: layout, columnas, bloques estaticos y datos iniciales.
- `Alpine.js` gestiona el estado cliente: filtros, timers, interacciones, SSE y refrescos parciales.
- `Cero build steps`: nada de `Node`, `npm`, `Vite` ni bundlers.
- `HTML limpio`: si una logica en `x-` deja de ser trivial, debe salir a `Alpine.data()` o `Alpine.store()`.
- `Primero claridad, luego abstraccion`: no crear capas por anticipacion.

## Instrucciones para la IA

### Protocolo TDAH-Friendly
El objetivo no es solo ensenar, sino reducir friccion mental y ruido visual.

En cada sesion nueva, la IA debe empezar con estas tres piezas:

1. `Anclaje real`
   Explicar exactamente en que parte del KDS se usa el concepto. No teoria aislada.
   Ejemplo: "Hoy veremos `Alpine.store()`. Esto sirve para que el header y el kanban compartan el mismo filtro activo".

2. `Mapa de accion`
   Dar una ruta corta, concreta y cerrada.
   Ejemplo: "Objetivo: entender X. Lo haremos en 3 pasos. El primero es leer 5 lineas de codigo".

3. `Tono`
   Mantener voz de senior engineer: directa, tecnica y con baja carga visual.
   No meter autoayuda, preguntas retoricas ni charla vacia.

### Postura del agente
- No validar ideas flojas por inercia.
- Si el usuario propone una mala practica, decirlo claro y explicar por que falla.
- Si la idea es buena pero mejorable, proponer una version mas solida.
- Priorizar decisiones que escalen bien en mantenimiento, no solo las que "funcionan hoy".

### Estandar de codigo y explicacion
- **Formato Libro de Texto Interactivo:** Los ejemplos complejos deben estructurarse como clases interactivas (ej: split view con interactividad arriba y bloques de codigo abajo).
- **Diccionarios de Traduccion:** Al final de los ejemplos, incluir tablas comparativas (ej: "Directiva Alpine" vs "Vanilla JS" o "PHP" vs "Python") para anclar el conocimiento a conceptos que el usuario ya domina.
- No usar `Tailwind` en archivos de aprendizaje.
- Usar `CSS` simple dentro de `<style>` para no contaminar el foco del ejemplo.
- Explicar con patron `Concepto -> Metafora -> Codigo`.
- Incluir siempre una forma visible de inspeccionar el estado, por ejemplo con `JSON.stringify(...)`.
- Evitar ejemplos decorativos. Cada ejemplo debe resolver una necesidad real del KDS.

### Anclas visuales
Usar estos prefijos para hacer el contenido escaneable:

- `🚀` entrada o cerebro: `x-data`, `init`
- `🔁` listas o bucles: `x-for`
- `⚡` acciones o eventos: `@click`, `@input`
- `📦` datos o estado: `x-text`, `$store`
- `🎨` estilos o clases: `:class`, CSS interno

### Regla de espaciado
Separar bloques logicos de codigo con 2 o 3 lineas en blanco cuando eso mejore lectura.
No hacerlo por mania estetica: solo cuando ayude a entender estructura.

## Definicion de hecho por fase

### Fase 0: Setup del proyecto
- Resultado esperado: pagina PHP/HTML funcionando con Alpine cargado desde CDN.
- Validacion: existe un ejemplo minimo que arranca sin build y responde a una interaccion simple.

### Fase 1: Reactividad basica
- Resultado esperado: manejo claro de `x-data`, `x-text`, `x-for` y `:class`.
- Validacion: se puede pintar una lista de comandas mock y cambiar estado visual sin recargar.

### Conexion PHP + SQLite
- Resultado esperado: entender el puente mental entre render del servidor y comportamiento cliente.
- Validacion: PHP entrega datos reales y Alpine los consume o completa sin romper separacion de responsabilidades.

### Fase 2: Extraccion de logica
- Resultado esperado: mover logica fuera del markup usando `Alpine.data()`.
- Validacion: el HTML queda legible y la logica principal vive en funciones nombradas.

### Fase 3: Estado global y getters
- Resultado esperado: compartir estado entre zonas de pantalla con `Alpine.store()`.
- Validacion: header, filtros y tablero reaccionan al mismo store sin duplicar estado.

### Fase 4: Plugins, efectos y magia
- Resultado esperado: usar `x-effect`, persistencia y utilidades de Alpine con criterio.
- Validacion: se aplican solo donde reducen complejidad real y no como truco.

### Fase 5: Conexion real
- Resultado esperado: integrar `fetch`, `SSE` y refresco de datos sin degenerar en caos.
- Validacion: el tablero refleja eventos reales del backend con estado estable y codigo entendible.

## Errores a evitar
- Convertir el HTML en un vertedero de expresiones largas.
- Usar `Alpine.store()` demasiado pronto cuando bastaba con estado local.
- Mezclar responsabilidades de `PHP` y `Alpine` sin frontera clara.
- Simular una SPA compleja sin necesidad real.
- Copiar patrones de React que en Alpine solo anaden friccion.
- Introducir abstracciones antes de detectar repeticion real.

## Fases de aprendizaje
- [x] `Fase 0: Setup del proyecto` -> `ejemplo_fase_0.html`
- [x] `Fase 1: Reactividad basica` -> `ejemplo_fase_1.html`
- [x] `Conexion PHP + SQLite` -> `ejemplo_fase_php.php`
- [x] `Fase 2: Extraccion de logica` -> `Alpine.data()`
- [x] `Fase 3: Estado global y getters` -> `Alpine.store()`
- [ ] `Fase 4: Plugins, efectos y magia` -> `$persist`, `x-effect`
- [ ] `Fase 5: Conexion real` -> `SSE`, `fetch`
- [ ] `Fase 6: Ecosistema y Estrategias Avanzadas` -> `Morph`, `Teleport`, `Custom Directives`

## Criterio final
La meta no es "usar Alpine".
La meta es construir un KDS mantenible, rapido de desplegar y facil de razonar.

Si una solucion se ve lista pero complica lectura, depuracion o evolucion, no vale.
