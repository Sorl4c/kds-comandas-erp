# FASE 4 — Estado con useState
## El corazón de React

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 4-6 horas  
> **Prerequisito:** Fase 3 completada

---

## 🧠 La idea central

¿Por qué no usamos variables normales para los datos?

```jsx
// Esto NO funciona — React no sabe que cambió
let items = INITIAL_ITEMS
function handleBump(id) {
  items = items.filter(i => i.id !== id)  // React no re-renderiza
}

// Esto SÍ funciona — React detecta el cambio y actualiza la UI
const [items, setItems] = useState(INITIAL_ITEMS)
function handleBump(id) {
  setItems(prev => prev.filter(i => i.id !== id))  // React re-renderiza
}
```

El estado es la **única fuente de verdad** de tu UI. Cuando el estado cambia, React actualiza el DOM automáticamente.

---

## 📖 Reglas del estado

**1. Nunca mutes el estado directamente**
```jsx
// ❌ MAL
items.push(nuevoItem)
setItems(items)

// ✅ BIEN
setItems(prev => [...prev, nuevoItem])
```

**2. Usa la forma funcional cuando el nuevo estado depende del anterior**
```jsx
// ❌ Puede tener bugs en actualizaciones rápidas
setItems(items.map(i => i.id === id ? {...i, estado: 'listo'} : i))

// ✅ Siempre correcto
setItems(prev => prev.map(i => i.id === id ? {...i, estado: 'listo'} : i))
```

**3. El estado vive en el componente más alto que lo necesita**

En nuestro KDS, `items` vive en `App` porque tanto `KanbanBoard` como `MesasView` lo necesitan. Si solo lo necesitara `KanbanBoard`, viviría ahí.

---

## 📖 El estado en nuestro App.jsx

```jsx
// Los cuatro estados de App.jsx y por qué cada uno está ahí

const [items, setItems] = useState(INITIAL_ITEMS)
// → Lo necesitan KanbanBoard Y MesasView → vive arriba en App

const [activeView, setActiveView] = useState('kanban')
// → Lo necesitan Header (para el toggle) y main (para decidir qué renderizar)

const [activeFilter, setActiveFilter] = useState('todo')  
// → Lo necesitan Header (para los botones) y KanbanBoard (para filtrar)

const [toasts, setToasts] = useState([])
// → Solo lo necesita ToastContainer, pero los handlers están en App
// → (podría moverse en el futuro, pero por ahora está bien aquí)
```

---

## 🧪 Mini-ejercicio

Sin mirar el monolito, implementa desde cero la lógica de `handleAdvanceState`.

Pistas:
- Recibe `itemId` y `newState`
- Usa `setItems` con la forma funcional
- Si `newState === 'listo'`, también debe añadir un toast
- El toast tiene: id único, itemId, mesa, producto, prevState, prevTimestamp

---

## ✅ Checklist de esta fase

- [ ] Entiendo por qué las variables normales no funcionan en React
- [ ] Sé qué provoca un re-render (cambio de estado o props)
- [ ] Nunca muto el estado directamente
- [ ] Uso la forma funcional `prev =>` cuando el nuevo estado depende del anterior
- [ ] Entiendo por qué cada estado vive donde vive en App.jsx
- [ ] He implementado al menos un handler desde cero

→ **[Siguiente: FASE_5.md](./FASE_5.md)**  
← **[Anterior: FASE_3.md](./FASE_3.md)**
