# AGENTS.md — KitchenSync KDS
## Guía para IA y para mí mismo

> Este fichero define las reglas del juego.  
> Cualquier IA que trabaje en este proyecto debe leerlo antes de generar código.  
> Yo mismo debo releerlo cuando me pierda o quiera saltarme pasos.

---

## 🎯 Intención del proyecto

**KitchenSync** es un KDS (Kitchen Display System) para cocina de restaurante.  
Muestra pedidos en tiempo real, organizados por estación (cocina, horno, barra), con estados por ítem y temporizadores visuales.

**Este proyecto tiene DOS propósitos simultáneos:**
1. Ser un producto real y funcional que pueda usar una empresa
2. Ser mi campo de entrenamiento para aprender React de verdad

Ambos propósitos son igual de importantes. No sacrifico aprendizaje por velocidad.

---

## 📐 Filosofía de trabajo

### Modularización progresiva
Partimos de un fichero monolítico funcional (`App.monolith.jsx`).  
Lo iremos desmontando pieza a pieza, fase a fase.  
**Nunca rompemos lo que funciona sin entender por qué funcionaba.**

### Secuencialidad estricta
Cada fase desbloquea la siguiente.  
No salto a la Fase 3 si no tengo marcadas todas las casillas de la Fase 2.  
Si tengo dudas de una fase anterior, vuelvo. No pasa nada.

### Entender antes de avanzar
Si la IA genera código que no entiendo → lo pregunto antes de copiarlo.  
El objetivo no es tener el código. Es entender el código.

---

## 🤖 Instrucciones para la IA

Cuando trabajes en este proyecto:

- **Explica siempre el porqué**, no solo el cómo
- **Aplica los conceptos a este proyecto**, no a ejemplos abstractos tipo "contador"
- **Señala en qué fase estamos** antes de generar código
- **No introduzcas conceptos de fases posteriores** aunque creas que es mejor
- **Si ves un error conceptual mío**, corrígelo con explicación, no solo con código
- **Genera código modular desde el primer momento** — nada de ficheros monolíticos nuevos
- **Usa siempre Tailwind CSS** para estilos — ya está configurado en este proyecto
- **Los datos son siempre mock** — no hay backend real todavía. Los datos viven en `/src/data/mockData.js`

### ❌ Lo que NO debes hacer (todavía)
- No uses `useContext` ni `createContext`
- No uses `useReducer`
- No uses `React.memo` ni optimizaciones prematuras
- No instales librerías externas sin justificación clara
- No uses `TypeScript` — trabajamos en JavaScript plano
- No introduzcas React Query, Zustand ni ningún gestor de estado externo
- No hagas fetching real a APIs — mock data únicamente por ahora
- No uses `Next.js` — esto es React puro con Vite

---

## 📁 Estructura de ficheros objetivo

```
src/
├── data/
│   └── mockData.js          ← Todos los datos falsos viven aquí
├── utils/
│   ├── timeHelpers.js       ← Funciones de tiempo (formatear, calcular elapsed)
│   └── workflowHelpers.js   ← Lógica de estados y transiciones
├── hooks/
│   └── useCurrentTime.js    ← Custom hook para el timer global
├── components/
│   ├── Header/
│   │   └── Header.jsx
│   ├── Toast/
│   │   └── ToastContainer.jsx
│   ├── Kanban/
│   │   ├── KanbanBoard.jsx  ← Orquesta las columnas + lógica de agrupación
│   │   ├── KanbanColumn.jsx
│   │   └── KanbanCard.jsx
│   └── Mesas/
│       └── MesasView.jsx
└── App.jsx                  ← Solo estado global + handlers. Nada más.
```

---

## 🚦 Estado actual del proyecto

| Fase | Nombre | Estado |
|------|--------|--------|
| 0 | Setup del proyecto | ⬜ Pendiente |
| 1 | Fundamentos básicos | ⬜ Pendiente |
| 2 | Componentización real | ⬜ Pendiente |
| 3 | Props y composición | ⬜ Pendiente |
| 4 | Estado (useState) | ⬜ Pendiente |
| 5 | Hooks y lógica reutilizable | ⬜ Pendiente |
| 6 | Datos mock y simulación API | ⬜ Pendiente |
| 7 | Refactor y buenas prácticas | ⬜ Pendiente |

> Actualiza este estado manualmente cuando completes cada fase.  
> ⬜ Pendiente → 🔄 En progreso → ✅ Completada

---

## 📎 Ficheros relacionados

- [`README_ROADMAP.md`](./README_ROADMAP.md) — Checklist general de progreso
- [`docs/fases/FASE_0.md`](./docs/fases/FASE_0.md) — Setup del proyecto
- [`docs/fases/FASE_1.md`](./docs/fases/FASE_1.md) — Fundamentos básicos
- [`docs/fases/FASE_2.md`](./docs/fases/FASE_2.md) — Componentización real
- [`docs/fases/FASE_3.md`](./docs/fases/FASE_3.md) — Props y composición
- [`docs/fases/FASE_4.md`](./docs/fases/FASE_4.md) — Estado con useState
- [`docs/fases/FASE_5.md`](./docs/fases/FASE_5.md) — Hooks y lógica reutilizable
- [`docs/fases/FASE_6.md`](./docs/fases/FASE_6.md) — Datos mock y simulación API
- [`docs/fases/FASE_7.md`](./docs/fases/FASE_7.md) — Refactor y buenas prácticas
