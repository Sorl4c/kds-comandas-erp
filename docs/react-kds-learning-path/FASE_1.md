# FASE 1 — Fundamentos básicos
## React desde cero, aplicado a KitchenSync

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 2-4 horas  
> **Prerequisito:** Fase 0 completada al 100%

---

## 🧠 La idea central de React

Antes de tocar código, entiende esto:

En **HTML/JS tradicional** tú le dices al navegador *cómo* cambiar las cosas:
```js
// "Encuentra este elemento y cámbialo"
document.getElementById('mesa').textContent = 'Mesa 5'
```

En **React** tú describes *qué* quieres que se vea, dado unos datos:
```jsx
// "Si tengo este dato, pinta esto"
function MesaLabel({ nombre }) {
  return <span>{nombre}</span>
}
```

React se encarga de actualizar el DOM. Tú no tocas el DOM nunca directamente.

**¿Por qué esto importa?** Porque en un KDS con 20 tickets actualizándose cada segundo, gestionar el DOM manualmente sería un infierno. React lo hace eficientemente por ti.

---

## 📖 Concepto 1 — JSX

JSX parece HTML pero no lo es. Es una sintaxis especial que Vite transforma en JavaScript puro.

```jsx
// Esto que escribes tú:
const elemento = <h1 className="titulo">KitchenSync</h1>

// Se convierte en esto internamente:
const elemento = React.createElement('h1', { className: 'titulo' }, 'KitchenSync')
```

No necesitas memorizar la transformación, pero sí entender que **JSX es JavaScript disfrazado de HTML**. Por eso:

- Se usa `className` en lugar de `class` (porque `class` es palabra reservada en JS)
- Se usa `htmlFor` en lugar de `for`
- Las expresiones JS van entre llaves: `{variable}`, `{funcion()}`, `{condicion ? 'a' : 'b'}`
- Los atributos de evento van en camelCase: `onClick`, `onChange`, `onPointerDown`

**En nuestro proyecto lo verás en:**
```jsx
// En KanbanCard — className de Tailwind
<div className="p-3 mb-3 rounded-xl border">

// En Header — expresión JS dentro de JSX
<div>{timeString}</div>

// En KanbanColumn — evento
<button onClick={() => setActiveFilter('cocina')}>Cocina</button>
```

---

## 📖 Concepto 2 — Componentes

Un componente es simplemente **una función que devuelve JSX**.

```jsx
// Componente mínimo
function MesaBadge() {
  return <span className="bg-slate-900 px-2 py-1 rounded text-white">Mesa 7</span>
}
```

Reglas que debes conocer:
- El nombre siempre empieza con **mayúscula** (`MesaBadge`, no `mesaBadge`)
- Solo puede devolver **un elemento raíz** (si necesitas varios, usa un Fragment: `<>...</>`)
- Se usa como si fuera una etiqueta HTML: `<MesaBadge />`

**¿Por qué componentizar?** Porque en nuestro KDS tenemos cosas que se repiten — cada ticket de la columna Kanban es idéntico estructuralmente, solo cambian los datos. Un componente te permite definir esa estructura una vez y reutilizarla con datos diferentes.

---

## 📖 Concepto 3 — El árbol de componentes

Los componentes se anidan. Eso crea un árbol:

```
App
├── Header
└── main
    ├── KanbanBoard
    │   ├── KanbanColumn (Pendiente)
    │   │   ├── KanbanCard (Mesa 12 - Burger)
    │   │   └── KanbanCard (Mesa 4 - Pizza)
    │   └── KanbanColumn (Cocina)
    │       └── KanbanCard (Mesa 8 - Entrecot)
    └── MesasView
        ├── MesaCard (Mesa 12)
        └── MesaCard (Mesa 4)
```

Este árbol es importante porque define **de dónde vienen los datos** y **quién controla qué**. Lo exploraremos en profundidad en las Fases 3 y 4.

---

## 🧪 Mini-ejercicio práctico

**Objetivo:** Crear tu primer componente en este proyecto, desde cero, sin mirar el monolito.

Crea el fichero `src/components/Header/Header.jsx` con este contenido mínimo:

```jsx
function Header() {
  return (
    <header className="bg-slate-900 border-b border-slate-800 px-6 py-4">
      <h1 className="text-2xl font-black text-white">
        KITCHEN<span className="text-orange-500">SYNC</span>
      </h1>
    </header>
  )
}

export default Header
```

Luego en `src/App.jsx`, úsalo:

```jsx
import Header from './components/Header/Header'

function App() {
  return (
    <div className="h-screen bg-[#0B1120]">
      <Header />
    </div>
  )
}

export default App
```

Guarda y comprueba que lo ves en el navegador. Si aparece el header → has creado, exportado e importado tu primer componente.

**Preguntas para reflexionar:**
- ¿Por qué el `export default` va al final y no en la declaración de la función?
- ¿Qué pasaría si escribes `<header />` (minúscula) en lugar de `<Header />`?
- ¿Dónde ve React la diferencia entre un componente y una etiqueta HTML nativa?

---

## ✅ Checklist de esta fase

- [ ] Entiendo qué es JSX y por qué no es HTML puro
- [ ] Sé por qué usamos `className` en lugar de `class`
- [ ] Sé cómo poner una expresión JavaScript dentro de JSX (`{}`)
- [ ] He creado el componente `Header` desde cero
- [ ] Sé qué hace `export default` y cómo funciona el `import`
- [ ] He visto el componente renderizado en el navegador
- [ ] Entiendo qué es el árbol de componentes
- [ ] Puedo explicar la diferencia entre `<Header />` y `<header>`

---

## 🚦 ¿Puedo pasar a la Fase 2?

Sí, cuando puedas responder sin dudar:

- ¿Qué devuelve siempre un componente React?
- ¿Qué hace Vite con el JSX antes de que llegue al navegador?
- ¿Por qué los nombres de componentes empiezan con mayúscula?

→ **[Siguiente: FASE_2.md](./FASE_2.md)**  
← **[Anterior: FASE_0.md](./FASE_0.md)**
