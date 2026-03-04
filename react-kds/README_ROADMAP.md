# README_ROADMAP.md — KitchenSync KDS
## Mi mapa de aprendizaje React

> Marca cada casilla cuando la domines de verdad, no cuando hayas copiado el código.  
> "Dominar" = puedo explicarlo con mis palabras y aplicarlo en otro contexto.

---

## ⚡ Checklist rápida de progreso

### 🔧 FASE 0 — Setup
- [ ] Tengo Node instalado y sé qué versión uso (`node -v`)
- [ ] Sé qué es Vite y por qué lo usamos en lugar de Create React App
- [ ] He creado el proyecto con `npm create vite@latest`
- [ ] He instalado dependencias con `npm install`
- [ ] He arrancado el servidor con `npm run dev` y he visto la app en el navegador
- [ ] He instalado Tailwind CSS y funciona
- [ ] He copiado el monolito en `App.monolith.jsx` como referencia
- [ ] Entiendo la estructura de carpetas que Vite genera

→ **[Ver FASE_0.md completa](./docs/fases/FASE_0.md)**

---

### 📦 FASE 1 — Fundamentos básicos
- [ ] Sé qué es JSX y por qué no es HTML
- [ ] Entiendo por qué React usa componentes (funciones que devuelven JSX)
- [ ] He creado mi primer componente desde cero (sin copiar)
- [ ] Sé la diferencia entre un componente y un elemento HTML
- [ ] Entiendo qué es el árbol de componentes (component tree)
- [ ] Sé por qué en JSX usamos `className` en lugar de `class`
- [ ] He visto en el navegador un componente que yo he creado

→ **[Ver FASE_1.md completa](./docs/fases/FASE_1.md)**

---

### 🧩 FASE 2 — Componentización real
- [ ] He extraído `Header` del monolito a su propio fichero
- [ ] Sé cómo importar y exportar componentes correctamente
- [ ] Entiendo la diferencia entre `export default` y `export named`
- [ ] He extraído `ToastContainer` del monolito
- [ ] He extraído `KanbanColumn` del monolito
- [ ] He extraído `KanbanCard` del monolito
- [ ] La app sigue funcionando exactamente igual que el monolito

→ **[Ver FASE_2.md completa](./docs/fases/FASE_2.md)**

---

### 🔗 FASE 3 — Props y composición
- [ ] Sé qué son las props y para qué sirven
- [ ] Entiendo que las props fluyen hacia abajo (padre → hijo), nunca al revés
- [ ] Sé cómo desestructurar props en el componente hijo
- [ ] Entiendo qué es `children` como prop especial
- [ ] He pasado datos reales de un componente padre a uno hijo
- [ ] Sé qué pasa si paso una prop que el hijo no usa
- [ ] He pasado una función como prop (callback) para comunicar eventos hacia arriba

→ **[Ver FASE_3.md completa](./docs/fases/FASE_3.md)**

---

### 🔄 FASE 4 — Estado con useState
- [ ] Entiendo por qué existe el estado (vs variables normales)
- [ ] Sé qué provoca un re-render y qué no
- [ ] He usado `useState` para controlar el filtro activo del header
- [ ] He usado `useState` para controlar la vista activa (kanban/mesas)
- [ ] He usado `useState` para la lista de items del KDS
- [ ] Entiendo por qué el estado vive en el componente más alto que lo necesita
- [ ] Sé que nunca muto el estado directamente (siempre con el setter)
- [ ] Entiendo la diferencia entre `setItems(newItems)` y `setItems(prev => ...)`

→ **[Ver FASE_4.md completa](./docs/fases/FASE_4.md)**

---

### 🪝 FASE 5 — Hooks y lógica reutilizable
- [ ] Entiendo qué es un custom hook y para qué sirve
- [ ] Sé por qué `useCurrentTime` es un hook y no una función normal
- [ ] He entendido `useEffect` como "sincronizar con el mundo exterior"
- [ ] Sé cuándo poner dependencias en el array de `useEffect`
- [ ] Sé qué es el cleanup de `useEffect` y por qué el timer lo necesita
- [ ] Entiendo la diferencia entre `useRef` y `useState`
- [ ] Sé por qué el long press usa `useRef` y no `useState`

→ **[Ver FASE_5.md completa](./docs/fases/FASE_5.md)**

---

### 📡 FASE 6 — Datos mock y simulación API
- [ ] Tengo todos los datos en `/src/data/mockData.js`
- [ ] Entiendo `useMemo` y cuándo tiene sentido usarlo
- [ ] He separado la lógica de agrupación del Kanban fuera del componente
- [ ] He implementado el simulador de pedidos (genera items aleatorios)
- [ ] El simulador usa el mismo flujo de estado que usaría una API real
- [ ] Sé cómo adaptaría esto a un fetch real cuando llegue el momento

→ **[Ver FASE_6.md completa](./docs/fases/FASE_6.md)**

---

### ✨ FASE 7 — Refactor y buenas prácticas
- [ ] Cada componente tiene una sola responsabilidad clara
- [ ] No hay lógica de negocio dentro de los componentes visuales
- [ ] Los nombres de componentes, props y funciones son descriptivos
- [ ] No hay `console.log` olvidados
- [ ] He revisado el código con criterio propio (no solo con IA)
- [ ] Puedo explicar cualquier parte del proyecto a otra persona
- [ ] El proyecto está en GitHub con commits descriptivos

→ **[Ver FASE_7.md completa](./docs/fases/FASE_7.md)**

---

## 🧠 Conceptos React dominados

Marca cuando puedas explicarlo sin mirar documentación:

**Fundamentos**
- [ ] JSX — qué es y cómo se transforma
- [ ] Componente funcional — qué recibe y qué devuelve
- [ ] Props — flujo unidireccional de datos
- [ ] Estado — cuándo existe, dónde vive, cómo cambia

**Hooks**
- [ ] `useState` — estado local de un componente
- [ ] `useEffect` — sincronizar efectos secundarios
- [ ] `useRef` — referencias mutables sin re-render
- [ ] `useMemo` — valores derivados costosos de calcular
- [ ] Custom hooks — extraer lógica reutilizable

**Patrones**
- [ ] Lifting state up — subir estado al ancestro común
- [ ] Callback props — comunicación hijo → padre
- [ ] Conditional rendering — renderizar según condición
- [ ] List rendering — `array.map()` con key
- [ ] Controlled components — inputs controlados por estado

---

## 📊 Mi progreso actual

```
Fase 0  [░░░░░░░░░░] 0%
Fase 1  [░░░░░░░░░░] 0%
Fase 2  [░░░░░░░░░░] 0%
Fase 3  [░░░░░░░░░░] 0%
Fase 4  [░░░░░░░░░░] 0%
Fase 5  [░░░░░░░░░░] 0%
Fase 6  [░░░░░░░░░░] 0%
Fase 7  [░░░░░░░░░░] 0%
```

> Actualiza las barras manualmente conforme avances.  
> Ejemplo: 3 de 8 casillas = `[███░░░░░░░] 37%`
