# FASE 2 — Componentización real
## Desmontar el monolito pieza a pieza

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 3-5 horas  
> **Prerequisito:** Fase 1 completada — tienes `Header` funcionando

---

## 🧠 La idea central de esta fase

El monolito funciona. No lo rompas por romperlo.

El objetivo es entender **por qué** separamos componentes, no solo hacerlo mecánicamente. Cada extracción tiene una justificación. Si no puedes justificarla, no la hagas todavía.

**Criterios para extraer un componente:**
1. Se repite (KanbanCard se usa N veces)
2. Tiene una responsabilidad clara y acotada (Header solo muestra información de cabecera)
3. Es suficientemente complejo para que tener su propio fichero ayude a entenderlo

---

## 📖 Concepto 1 — Export/Import en detalle

Hay dos formas de exportar en JavaScript moderno:

### Export default
```jsx
// Solo puede haber uno por fichero
function KanbanCard() { ... }
export default KanbanCard

// Al importar, tú eliges el nombre
import KanbanCard from './KanbanCard'
import Card from './KanbanCard'       // también funciona, mismo componente
```

### Named export
```jsx
// Puede haber varios por fichero
export const WORKFLOW = { ... }
export const STATION_COLORS = { ... }
export function formatTime(ms) { ... }

// Al importar, el nombre debe coincidir exactamente
import { WORKFLOW, STATION_COLORS } from './mockData'
import { formatTime } from './timeHelpers'
```

**Regla en este proyecto:**
- Componentes → `export default`
- Datos, constantes y funciones utilitarias → named exports

---

## 📖 Concepto 2 — Estructura de carpetas por componente

Verás que usamos carpetas para componentes, no solo ficheros:

```
components/
├── Header/
│   └── Header.jsx        ← no Header/index.jsx todavía
├── Kanban/
│   ├── KanbanBoard.jsx
│   ├── KanbanColumn.jsx
│   └── KanbanCard.jsx
└── Mesas/
    └── MesasView.jsx
```

¿Por qué carpetas? Porque cuando un componente crezca, puede tener su propio CSS, sus propios tests, sus propios subcomponentes — todo agrupado. Es una convención que escala bien.

---

## 🛠️ Extracciones en orden

Haz estas extracciones en este orden exacto. No saltes.

### Extracción 1 — `mockData.js`

Antes de tocar componentes, saca los datos.

Crea `src/data/mockData.js` y mueve ahí:
- `INITIAL_ITEMS`
- `WORKFLOW`  
- `STATION_COLORS`

```js
// src/data/mockData.js
export const INITIAL_ITEMS = [ ... ]  // named export
export const WORKFLOW = { ... }       // named export
export const STATION_COLORS = { ... } // named export
```

En `App.jsx`, importa desde ahí:
```jsx
import { INITIAL_ITEMS, WORKFLOW, STATION_COLORS } from './data/mockData'
```

**Por qué primero esto:** Porque los datos son independientes de los componentes. Si los mueves antes, todos los componentes que los necesiten saben dónde buscarlos.

---

### Extracción 2 — `ToastContainer`

Es el más fácil de extraer porque es casi autónomo — recibe props y pinta toasts.

Crea `src/components/Toast/ToastContainer.jsx`.

Copia el componente del monolito. Añade los imports que necesita:
```jsx
import { CheckCircle, RotateCcw, X } from 'lucide-react'
```

Exporta al final:
```jsx
export default ToastContainer
```

En `App.jsx`, elimina la función `ToastContainer` y añade el import.

**Comprueba que la app sigue funcionando antes de continuar.**

---

### Extracción 3 — `KanbanCard`

El componente más complejo de los tres primeros porque tiene lógica de interacción (long press, timers visuales).

Crea `src/components/Kanban/KanbanCard.jsx`.

Imports que necesita:
```jsx
import { Clock, AlertCircle } from 'lucide-react'
import { STATION_COLORS } from '../../data/mockData'
```

Fíjate en la ruta relativa: dos niveles arriba (`../..`) para llegar a `src/`, luego `data/mockData`.

**Comprueba que la app sigue funcionando antes de continuar.**

---

### Extracción 4 — `KanbanColumn`

Crea `src/components/Kanban/KanbanColumn.jsx`.

Imports que necesita:
```jsx
import { CheckCircle } from 'lucide-react'
import KanbanCard from './KanbanCard'   // mismo nivel de carpeta
```

---

### Extracción 5 — `MesasView`

Crea `src/components/Mesas/MesasView.jsx`.

Imports que necesita:
```jsx
import { useMemo } from 'react'
import { Clock, CheckCircle, AlertCircle } from 'lucide-react'
```

---

### Extracción 6 — `Header`

Ya lo tienes de la Fase 1 pero era mínimo. Ahora copia el Header real del monolito con todos sus filtros y el toggle de vistas.

---

## 🧪 Mini-ejercicio práctico

Cuando hayas extraído todos los componentes, abre el monolito (`App.monolith.jsx`) y el `App.jsx` nuevo lado a lado.

Pregúntate:
- ¿Qué tiene el `App.jsx` nuevo que el monolito no tenía organizado?
- ¿Qué tendrías que cambiar si mañana el diseño del `KanbanCard` cambia completamente?
- En el monolito, ¿cuántas líneas tendrías que tocar? ¿Y en la versión modular?

---

## ✅ Checklist de esta fase

- [ ] He creado `src/data/mockData.js` con los tres exports
- [ ] He extraído `ToastContainer` a su propio fichero
- [ ] He extraído `KanbanCard` a su propio fichero
- [ ] He extraído `KanbanColumn` a su propio fichero
- [ ] He extraído `MesasView` a su propio fichero
- [ ] He extraído `Header` completo a su propio fichero
- [ ] La app funciona exactamente igual que antes de empezar esta fase
- [ ] Entiendo la diferencia entre `export default` y named exports
- [ ] Sé por qué las rutas de import son relativas (`./`, `../`)
- [ ] `App.jsx` ahora solo tiene: estado + handlers + estructura JSX principal

---

## 🚦 ¿Puedo pasar a la Fase 3?

Sí, cuando:
- Todas las casillas estén marcadas
- La app funcione sin errores en consola
- Puedas decirme qué hace cada fichero en una frase

→ **[Siguiente: FASE_3.md](./FASE_3.md)**  
← **[Anterior: FASE_1.md](./FASE_1.md)**
