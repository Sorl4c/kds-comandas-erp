# FASE 0 — Setup del proyecto
## Antes de escribir una sola línea de React

> **Estado:** ⬜ Pendiente  
> **Tiempo estimado:** 30-60 minutos  
> **Prerequisito:** Node.js instalado (`node -v` debe devolver algo)

---

## 🧠 Qué aprenderás en esta fase

No vas a tocar React todavía. Esta fase es sobre entender el entorno en el que vas a trabajar. Muchos problemas futuros vienen de no entender bien esto.

---

## 📖 Conceptos clave

### ¿Qué es Vite y por qué no Create React App?

**Create React App (CRA)** fue durante años la forma oficial de crear proyectos React. El problema es que es lento — tanto al arrancar como al recargar. Está prácticamente abandonado.

**Vite** es la herramienta actual. Arranca en menos de 1 segundo, recarga el navegador instantáneamente cuando cambias código, y genera builds de producción más ligeros. Todos los proyectos React nuevos hoy usan Vite.

Lo que hace Vite por ti:
- Convierte tu JSX en JavaScript que el navegador entiende
- Convierte tus imports de módulos en algo que el navegador puede cargar
- Recarga el navegador automáticamente cuando guardas un fichero (HMR)

### ¿Qué es npm?

Node Package Manager. Gestiona las librerías externas que usa tu proyecto. Cuando instalas React, Vite o Tailwind, npm los descarga y los guarda en `node_modules/`.

`package.json` es el fichero que lista qué dependencias tiene tu proyecto y qué comandos puedes ejecutar.

---

## 🛠️ Comandos paso a paso

### Paso 1 — Crear el proyecto

```bash
npm create vite@latest kitchensync-kds -- --template react
```

Esto hace:
- Descarga la plantilla de Vite para React
- Crea una carpeta `kitchensync-kds/` con la estructura base
- No instala nada todavía (eso es el paso 2)

Cuando te pregunte, elige: `React` → `JavaScript` (no TypeScript)

### Paso 2 — Entrar en la carpeta e instalar dependencias

```bash
cd kitchensync-kds
npm install
```

`npm install` lee el `package.json` y descarga todo en `node_modules/`. Esta carpeta nunca va a Git (está en `.gitignore` automáticamente).

### Paso 3 — Instalar Tailwind CSS

```bash
npm install -D tailwindcss @tailwindcss/vite
```

Luego edita `vite.config.js` para que quede así:

```js
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    react(),
    tailwindcss(),
  ],
})
```

Y en `src/index.css`, reemplaza todo el contenido con:

```css
@import "tailwindcss";
```

### Paso 4 — Instalar Lucide React (los iconos del proyecto)

```bash
npm install lucide-react
```

### Paso 5 — Arrancar el servidor de desarrollo

```bash
npm run dev
```

Abre el navegador en `http://localhost:5173`. Deberías ver la pantalla de bienvenida de Vite + React.

### Paso 6 — Copiar el monolito como referencia

Copia el fichero monolítico original en:

```
src/App.monolith.jsx
```

**No lo borres nunca.** Es tu referencia. Cuando no sepas cómo funcionaba algo, lo buscas ahí.

---

## 📁 Estructura que debe quedar

```
kitchensync-kds/
├── public/
├── src/
│   ├── App.monolith.jsx    ← el monolito original (referencia)
│   ├── App.jsx             ← aquí empezaremos a trabajar
│   ├── main.jsx            ← punto de entrada (no tocamos esto)
│   └── index.css           ← solo la línea de Tailwind
├── index.html
├── package.json
├── vite.config.js
└── docs/
    └── fases/              ← estas guías
```

---

## 🧪 Mini-ejercicio

Antes de pasar a la Fase 1, haz esto sin ayuda:

1. Para el servidor (`Ctrl+C`)
2. Árrancalo otra vez (`npm run dev`)
3. Abre `src/App.jsx` y cambia el texto que aparece en pantalla por cualquier cosa
4. Guarda el fichero y comprueba que el navegador se actualiza solo

Si el navegador se actualiza automáticamente al guardar → **HMR funciona**. Estás listo.

---

## ✅ Checklist de esta fase

- [ ] `node -v` me devuelve una versión (18+ recomendado)
- [ ] He creado el proyecto con Vite sin errores
- [ ] He instalado las dependencias (`npm install`)
- [ ] He instalado y configurado Tailwind CSS
- [ ] He instalado Lucide React
- [ ] `npm run dev` arranca sin errores
- [ ] He visto la app en `http://localhost:5173`
- [ ] He guardado el monolito en `App.monolith.jsx`
- [ ] He modificado algo en `App.jsx` y he visto el cambio en vivo
- [ ] Sé explicar qué hace cada comando que he ejecutado

---

## 🚦 ¿Puedo pasar a la Fase 1?

Sí, cuando todas las casillas estén marcadas Y puedas responder:

- ¿Qué diferencia hay entre `npm install` y `npm run dev`?
- ¿Dónde está el punto de entrada de la aplicación React?
- ¿Por qué `node_modules` no va a Git?

→ **[Siguiente: FASE_1.md](./FASE_1.md)**
