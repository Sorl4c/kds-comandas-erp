# FASE 7 — Refactor y buenas prácticas
## El proyecto terminado, entendido al 100%

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 3-5 horas  
> **Prerequisito:** Fases 0-6 completadas

---

## 🧠 La idea central

Refactorizar no es reescribir. Es mejorar la claridad y mantenibilidad sin cambiar el comportamiento. Si la app funciona igual antes y después → refactor correcto.

---

## 📖 Checklist de calidad — revisa cada fichero

### App.jsx
- [ ] Solo tiene estado global y handlers — nada de JSX complejo
- [ ] Cada handler hace una sola cosa
- [ ] Los nombres describen la intención (`handleAdvanceState`, no `handleClick`)

### Componentes
- [ ] Cada componente tiene una responsabilidad clara
- [ ] No hay lógica de negocio dentro de componentes visuales
- [ ] Los props están bien nombrados y son los mínimos necesarios

### Datos y utilidades
- [ ] `mockData.js` solo tiene datos, no lógica
- [ ] `timeHelpers.js` tiene las funciones de tiempo extraídas del componente
- [ ] `workflowHelpers.js` tiene la lógica de transición de estados

---

## 📖 Extracción pendiente — utilidades

Hay lógica que todavía vive dentro de los componentes y debería estar en utilidades:

```js
// src/utils/timeHelpers.js
export function formatElapsed(ms) {
  const minutes = Math.floor(ms / 60000)
  const seconds = Math.floor((ms % 60000) / 1000)
  return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
}

export function getTimeColor(elapsedMinutes, estimatedTime) {
  if (elapsedMinutes >= estimatedTime) return 'text-red-400 font-bold animate-pulse'
  if (elapsedMinutes >= estimatedTime * 0.75) return 'text-amber-400 font-bold'
  return 'text-slate-300'
}

export function getTimeBorderColor(elapsedMinutes, estimatedTime) {
  if (elapsedMinutes >= estimatedTime) return 'bg-red-900/40 border-red-500/50'
  if (elapsedMinutes >= estimatedTime * 0.75) return 'bg-amber-900/30 border-amber-500/50'
  return 'bg-slate-700/80 border-slate-600'
}
```

```js
// src/utils/workflowHelpers.js
import { WORKFLOW } from '../data/mockData'

export function getNextState(estado, station) {
  return WORKFLOW[estado] ? WORKFLOW[estado](station) : null
}

export function getPrevState(estado, station) {
  if (estado === 'emplatado') return station
  if (estado === station) return 'pendiente'
  return null
}
```

---

## 📖 El proyecto en GitHub

```bash
git init
git add .
git commit -m "feat: KDS MVP inicial con Kanban y vista de mesas"
```

Convención de commits que escala bien:
- `feat:` nueva funcionalidad
- `fix:` corrección de bug
- `refactor:` mejora de código sin cambio de comportamiento
- `docs:` cambios en documentación

---

## 🧪 Mini-ejercicio final

Sin ayuda de IA, intenta añadir esta feature:

**Un contador en el header que muestre cuántos items llevan más de 10 minutos en el mismo estado.**

Para hacerlo necesitas:
1. Computar el valor desde `items` y `currentTime`
2. Decidir dónde vive ese cálculo
3. Pasarlo como prop al Header
4. Renderizarlo con el color adecuado

Si puedes hacerlo solo → has completado el aprendizaje de este proyecto.

---

## ✅ Checklist final del proyecto

- [ ] `src/utils/timeHelpers.js` existe y está siendo usado
- [ ] `src/utils/workflowHelpers.js` existe y está siendo usado
- [ ] No hay `console.log` olvidados
- [ ] Cada componente tiene una sola responsabilidad
- [ ] La estructura de carpetas coincide con la definida en `AGENTS.md`
- [ ] El proyecto está en GitHub
- [ ] Puedo explicar cualquier parte del código a otra persona
- [ ] He añadido la feature del contador sin ayuda de IA

---

## 🏁 Has terminado el MVP educativo

Lo que sabes ahora:
- Crear y estructurar un proyecto React con Vite
- Componentizar de forma progresiva y justificada
- Gestionar estado con `useState` y entender su flujo
- Usar `useEffect`, `useRef`, `useMemo` y custom hooks
- Separar datos, lógica y presentación
- Preparar el proyecto para conectar con una API real

El siguiente paso natural es conectar la API PHP real. Cuando llegue ese momento, crea una `FASE_8.md` y documenta el proceso.

← **[Anterior: FASE_6.md](./FASE_6.md)**  
← **[Volver al ROADMAP](../../README_ROADMAP.md)**
