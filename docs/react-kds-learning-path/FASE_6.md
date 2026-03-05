# FASE 6 — Datos mock y simulación API
## Preparar el proyecto para conectar con PHP cuando llegue el momento

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 3-5 horas  
> **Prerequisito:** Fase 5 completada

---

## 🧠 La idea central

Tu backend en PHP devolverá un JSON. React no sabe si los datos vienen de una API real o de un fichero mock — solo ve el array de objetos. Si estructuras bien el mock, el día que conectes la API real solo cambias una línea.

---

## 📖 La estructura mock definitiva

Todo vive en `src/data/mockData.js`. Nada de datos hardcodeados en componentes.

```js
// src/data/mockData.js

// Esto es lo que devolvería tu API PHP en /api/orders
export const INITIAL_ITEMS = [
  {
    id: 'i1',
    orderId: 'O-101',
    mesa: 'Mesa 12',
    producto: 'Burger Smash',
    station: 'cocina',
    estado: 'pendiente',
    estado_timestamp: Date.now() - 1000 * 60 * 2,
    estimated_time: 10,
    notes: ['Sin cebolla']
  },
  // ...
]

// Esto es configuración de la app, no datos de la API
export const WORKFLOW = {
  pendiente: (station) => station,
  barra: () => 'emplatado',
  cocina: () => 'emplatado',
  horno: () => 'emplatado',
  emplatado: () => 'listo'
}

export const STATION_COLORS = {
  barra: '#06b6d4',
  cocina: '#ef4444',
  horno: '#f97316'
}
```

---

## 📖 El simulador de pedidos

Un botón en el Header que genera un pedido aleatorio. Simula lo que haría el POS enviando datos a la API.

```jsx
// src/hooks/useOrderSimulator.js
import { INITIAL_ITEMS } from '../data/mockData'

function useOrderSimulator(setItems) {
  const addRandomOrder = () => {
    // Coger un item del mock como plantilla
    const template = INITIAL_ITEMS[Math.floor(Math.random() * INITIAL_ITEMS.length)]
    
    const newItem = {
      ...template,
      id: `sim-${Date.now()}`,
      orderId: `O-SIM-${Date.now()}`,
      estado: 'pendiente',
      estado_timestamp: Date.now(),
    }
    
    setItems(prev => [newItem, ...prev])
  }
  
  return { addRandomOrder }
}

export default useOrderSimulator
```

---

## 📖 Cómo sería con una API real (preview)

Cuando llegue el momento, el cambio es mínimo:

```jsx
// Ahora (mock)
const [items, setItems] = useState(INITIAL_ITEMS)

// Con API real — solo esto cambia
useEffect(() => {
  fetch('https://tu-api-php.com/api/orders')
    .then(res => res.json())
    .then(data => setItems(data))
}, [])
```

El resto del proyecto — componentes, handlers, UI — no cambia nada. Por eso vale la pena estructurar bien el mock desde el principio.

---

## 🧪 Mini-ejercicio

Implementa el simulador completo:
1. El hook `useOrderSimulator` en `src/hooks/`
2. Un botón en el `Header` que llame a `addRandomOrder`
3. El nuevo item debe aparecer en la columna "Pendiente" del Kanban

Si funciona, tienes el flujo completo de entrada de datos funcionando.

---

## ✅ Checklist de esta fase

- [ ] Todos los datos viven en `src/data/mockData.js`, ningún dato hardcodeado en componentes
- [ ] He implementado `useOrderSimulator`
- [ ] El botón del simulador añade items que aparecen en el Kanban
- [ ] Entiendo qué cambiaría si mañana conectara una API real
- [ ] `useMemo` de `groupedColumns` sigue funcionando con los nuevos items simulados

→ **[Siguiente: FASE_7.md](./FASE_7.md)**  
← **[Anterior: FASE_5.md](./FASE_5.md)**
