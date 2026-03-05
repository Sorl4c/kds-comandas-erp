# FASE 3 — Props y composición
## Cómo fluyen los datos entre componentes

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 3-4 horas  
> **Prerequisito:** Fase 2 completada — todos los componentes extraídos

---

## 🧠 La idea central de esta fase

Las props son el sistema de comunicación de React.

**Regla fundamental:** Los datos fluyen hacia abajo. Los eventos suben hacia arriba.

```
App (tiene los datos)
 │
 │ props ↓ (datos hacia abajo)
 │
KanbanBoard
 │
 │ props ↓
 │
KanbanColumn
 │
 │ props ↓
 │
KanbanCard
 │
 │ callback ↑ (eventos hacia arriba)
 │
KanbanColumn → KanbanBoard → App (actualiza estado)
```

Nunca un hijo modifica directamente el estado del padre. Solo puede pedirle al padre que lo haga, a través de una función que el padre le pasó como prop.

---

## 📖 Concepto 1 — Qué son las props

Props (propiedades) son los argumentos que le pasas a un componente. Son de solo lectura — el componente hijo nunca debe modificarlas.

```jsx
// Padre: pasa props
<KanbanCard 
  mesa="Mesa 7"
  producto="Burger Smash"
  estimated_time={10}
/>

// Hijo: las recibe como objeto
function KanbanCard(props) {
  return <h3>{props.producto}</h3>
}

// Mejor: desestructuradas directamente
function KanbanCard({ mesa, producto, estimated_time }) {
  return (
    <div>
      <span>{mesa}</span>
      <h3>{producto}</h3>
    </div>
  )
}
```

---

## 📖 Concepto 2 — Props como funciones (callbacks)

Cuando un cocinero toca un ticket para avanzar su estado, ¿quién actualiza los datos? El `App.jsx`, porque ahí vive el estado. Pero quien detecta el tap es `KanbanCard`.

Solución: `App` le pasa a `KanbanCard` una función como prop. Cuando `KanbanCard` detecta el tap, llama a esa función.

```jsx
// En App.jsx
function handleAdvanceState(itemId, newState) {
  // actualiza el estado...
}

// App pasa la función como prop
<KanbanBoard onAdvance={handleAdvanceState} />

// KanbanBoard la pasa hacia abajo
<KanbanColumn onAdvance={onAdvance} />

// KanbanColumn la pasa al card
<KanbanCard onAdvance={onAdvance} />

// KanbanCard la llama cuando el usuario toca
<div onPointerUp={() => onAdvance(ids[0], nextState)}>
```

Esto se llama **prop drilling** — pasar props a través de varios niveles. Para este proyecto es perfectamente válido. Context y otros patrones avanzados vendrán después si los necesitamos.

---

## 📖 Concepto 3 — Tipos de props que verás en este proyecto

```jsx
// String
<Header turno="Mañana" />

// Number
<KanbanCard estimated_time={10} />

// Boolean (true implícito si no pones valor)
<KanbanCard isUrgent />           // equivale a isUrgent={true}
<KanbanCard isUrgent={false} />

// Array
<KanbanCard notes={['Sin cebolla', 'Salsa aparte']} />

// Objeto
<KanbanCard itemGroup={{ mesa: 'Mesa 7', producto: 'Burger' }} />

// Función
<KanbanCard onAdvance={handleAdvanceState} />

// currentTime (número, timestamp)
<KanbanCard currentTime={currentTime} />
```

---

## 🧪 Mini-ejercicio práctico

Abre `KanbanCard.jsx` y localiza todas las props que recibe.

1. Lista cada prop con su tipo (string, number, array, función...)
2. Para cada una, traza de dónde viene — ¿la genera `App`? ¿`KanbanBoard`? ¿`KanbanColumn`?
3. Identifica qué props son **datos** y cuáles son **callbacks** (funciones)

Luego haz lo mismo con `KanbanColumn` y `Header`.

Este ejercicio te da el mapa completo del flujo de datos del proyecto.

---

## ✅ Checklist de esta fase

- [ ] Entiendo qué son las props y que son de solo lectura
- [ ] Sé desestructurar props en el componente hijo
- [ ] Entiendo el flujo unidireccional: datos bajan, eventos suben
- [ ] Sé pasar una función como prop (callback)
- [ ] He trazado el flujo completo de datos en el KDS
- [ ] Entiendo qué es prop drilling y cuándo es aceptable
- [ ] Sé la diferencia entre una prop de datos y una prop de evento

---

## 🚦 ¿Puedo pasar a la Fase 4?

Pregunta clave: si mañana necesito mostrar el número de ítems urgentes en el Header, ¿de dónde vienen ese dato y cómo llega al Header?

Si puedes responder eso sin dudar → estás listo.

→ **[Siguiente: FASE_4.md](./FASE_4.md)**  
← **[Anterior: FASE_2.md](./FASE_2.md)**
