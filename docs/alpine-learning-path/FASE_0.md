# FASE 0 — Setup (La Vía PHP)
## De vuelta a las raíces

> **Estado:** ⬜ Pendiente

### 🧠 Qué aprenderás en esta fase
A diferencia de React, donde necesitas un servidor intermedio (Vite) para traducir el código antes de que el navegador lo entienda, Alpine.js vive nativamente en el navegador. Volvemos a la filosofía de soltar archivos en una carpeta y que funcionen.

### 📖 Concepto 1: La carga de Scripts (CDN)
No hay `npm install`. Para usar Alpine avanzado, necesitas cargar el "Core" (el núcleo) y los "Plugins" (superpoderes extra).

**Regla de Oro de Alpine:** Los plugins deben cargarse ANTES que el Core. Y el Core DEBE usar el atributo `defer`.

```html
<!-- index.php -->
<head>
    <!-- 1. Tailwind (CDN para desarrollo, CLI en producción) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- 2. Plugins de Alpine (Ej: Persist para localStorage) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    
    <!-- 3. Core de Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- 4. Tus propios scripts (la lógica de tu app) -->
    <script defer src="js/app.js"></script>
</head>
```

### 📖 Concepto 2: Estructura PHP en lugar de Componentes
En React tenías `<Header />`. En este paradigma, usarás PHP para trocear tu HTML.

```php
<!-- index.php -->
<body class="bg-gray-900">
    <?php include 'components/header.php'; ?>
    
    <main>
        <?php include 'components/kanban_board.php'; ?>
    </main>
</body>
```

### 🧪 Ejercicio
Crea un `index.php` básico que cargue los scripts de la forma correcta. Verifica en la pestaña "Red" (Network) de las herramientas de desarrollo de Chrome que los scripts de Alpine se están descargando correctamente.