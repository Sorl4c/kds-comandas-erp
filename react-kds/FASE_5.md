# FASE 5 — Hooks y lógica reutilizable
## useEffect, useRef, useMemo y custom hooks

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 5-8 horas  
> **Prerequisito:** Fase 4 completada

---

## 🧠 La idea central

Los hooks son funciones especiales de React que te permiten "enganchar" funcionalidades del ciclo de vida de React dentro de componentes funcionales.

Regla de oro: los hooks solo se pueden llamar en el nivel superior de un componente o de otro hook. Nunca dentro de condicionales, bucles o funciones anidadas.

---

## 📖 useEffect — sincronizar con el mundo exterior

`useEffect` no es "código que corre cuando algo cambia". Es **sincronización**.

```jsx
// Sincronizar un timer con el componente
useEffect(() => {
  // Setup: esto corre cuando el componente aparece
  const timer = setInterval(() => {
    setCurrentTime(Date.now())
  }, 1000)

  // Cleanup: esto corre cuando el componente desaparece
  // Sin esto, el timer seguiría corriendo aunque el componente no exista
  return () => clearInterval(timer)

}, []) // Array vacío = solo corre una vez al montar
```

**En nuestro proyecto:** `useCurrentTime` usa exactamente este patrón. El timer se inicia cuando el componente aparece y se limpia cuando desaparece. Sin el cleanup, tendríamos memory leaks.

---

## 📖 useRef — referencias sin re-render

`useRef` guarda un valor que **persiste entre renders** pero cuyo cambio **no provoca re-render**.

```jsx
// En KanbanCard — el long press
const holdTimeout = useRef(null)      // guarda el ID del timeout
const hasLongPressed = useRef(false)  // guarda si ya se ejecutó el long press

// ¿Por qué useRef y no useState aquí?
// Porque no queremos re-renderizar cuando cambia hasLongPressed
// Solo necesitamos leer su valor en el momento del pointerUp
```

Si usaras `useState` para `hasLongPressed`, cada vez que cambiara provocaría un re-render, lo que podría interferir con la animación de la barra de progreso.

---

## 📖 useMemo — valores derivados costosos

`useMemo` memoriza el resultado de una computación y solo lo recalcula cuando sus dependencias cambian.

```jsx
// En App.jsx — la agrupación del Kanban
const groupedColumns = useMemo(() => {
  // Esta computación recorre todos los items, los filtra, los agrupa...
  // Es relativamente costosa si hay 50+ items
  let filtered = items.filter(i => i.estado !== 'listo')
  // ...más lógica...
  return { pendiente: [...], cocina: [...], horno: [...] }

}, [items, activeFilter])
// Solo recalcula si `items` o `activeFilter` cambian
// En cada re-render que NO cambie ninguno de los dos, devuelve el resultado anterior
```

**Cuándo usarlo:** Cuando la computación es cara Y depende de estado que no cambia en cada render. No lo pongas en todas partes — tiene su propio coste.

---

## 📖 Custom hooks — extraer lógica reutilizable

Un custom hook es una función que empieza con `use` y puede llamar a otros hooks.

```jsx
// src/hooks/useCurrentTime.js
import { useState, useEffect } from 'react'

function useCurrentTime(updateIntervalMs = 1000) {
  const [currentTime, setCurrentTime] = useState(Date.now())
  
  useEffect(() => {
    const timer = setInterval(() => setCurrentTime(Date.now()), updateIntervalMs)
    return () => clearInterval(timer)
  }, [updateIntervalMs])
  
  return currentTime
}

export default useCurrentTime

// Uso en cualquier componente:
const currentTime = useCurrentTime(1000)
```

**Por qué es un hook y no una función normal:** Porque usa `useState` y `useEffect` internamente. Eso solo está permitido en hooks (que empiezan con `use`) o en componentes directamente.

---

## 🧪 Mini-ejercicio

Crea un custom hook `useOrderSimulator` que cada 15 segundos añada un item aleatorio al array de items.

Pistas:
- Recibe `setItems` como parámetro
- Usa `useEffect` con `setInterval`
- Genera un item aleatorio desde `INITIAL_ITEMS` como plantilla
- Dale un ID único con `Date.now()`
- Devuelve una función `startSimulator` y `stopSimulator`

---

## ✅ Checklist de esta fase

- [ ] Entiendo `useEffect` como sincronización, no como "evento de cambio"
- [ ] Sé cuándo necesita cleanup un `useEffect` (cuando crea algo que persiste)
- [ ] Entiendo la diferencia entre `useRef` y `useState`
- [ ] Sé cuándo `useMemo` tiene sentido y cuándo es prematuro
- [ ] He extraído `useCurrentTime` a su propio fichero
- [ ] Entiendo por qué un custom hook empieza con `use`
- [ ] He creado al menos un custom hook propio

→ **[Siguiente: FASE_6.md](./FASE_6.md)**  
← **[Anterior: FASE_4.md](./FASE_4.md)**
