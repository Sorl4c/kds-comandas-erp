# KitchenSync KDS: De React a Alpine.js (Arquitectura y Reactividad)

Este proyecto es un **MVP (Producto Mínimo Viable)** de un sistema de pantallas de cocina (**Kitchen Display System**) profesional. Lo que comenzó como una exploración técnica en React + Vite terminó evolucionando hacia una solución optimizada en Alpine.js + PHP, demostrando que se puede obtener una experiencia de usuario de alto nivel ("React-like") manteniendo la simplicidad de un stack tradicional.

## ⏱️ El Desafío de las 3 Horas
Este proyecto completo, desde la conceptualización hasta la migración final, se ha desarrollado en aproximadamente **3 horas de trabajo intensivo** (repartidas entre mañana y tarde). 

El objetivo no era solo "hacer que funcione", sino realizar un ejercicio de **Ingeniería Inversa y Migración Arquitectónica**:
1. **Fase Inicial (React):** Capturar la lógica compleja de estados, timers y UI.
2. **Fase de Migración (Alpine.js):** Replicar exactamente esa reactividad pero eliminando la dependencia de Node.js y procesos de compilación, integrándolo nativamente con un backend PHP/SQLite.

## 🚀 Características Técnicas

### 🧠 El Cerebro: Alpine.store
A diferencia de las webs tradicionales con PHP, este KDS utiliza un **Almacén Global Reactivo** (`Alpine.store`). Esto permite que:
- Los datos fluyan instantáneamente entre el Header, el Kanban y la Vista de Mesas.
- Exista una **Fuente Única de Verdad (Single Source of Truth)** similar a Redux o Zustand.

### 📡 Sincronización en Tiempo Real (SSE)
Implementamos una conexión vía **Server-Sent Events (SSE)** con el backend:
- **Resiliencia:** Reconexión automática en caso de caída del servidor.
- **Estado de Conexión:** Indicador visual en tiempo real del estado "En Línea".
- **Optimistic UI:** La interfaz cambia al instante cuando el usuario pulsa un botón, y el servidor se sincroniza en segundo plano.

### ⚡ UX Avanzada
- **Long Press (Pulsación Larga):** Implementado manualmente en Alpine para retroceder estados con feedback táctil (vibración).
- **Temporizadores Dinámicos:** Los tickets cambian de color (Verde -> Ámbar -> Rojo) automáticamente según el tiempo transcurrido vs el tiempo estimado de preparación.
- **Agrupación Inteligente:** El sistema detecta productos idénticos en el mismo pedido y los agrupa visualmente (x2, x3) para ahorrar espacio en cocina.

## 🛠️ Stack Tecnológico
- **Backend:** PHP puro + SQLite (Base de datos ligera y rápida).
- **Frontend A (Referencia):** React + Vite + Tailwind.
- **Frontend B (Producción):** Alpine.js v3 + Tailwind CSS (CDN) + Lucide Icons.

## 📂 Estructura del Proyecto
- `/backend`: API SSE y lógica de actualización de base de datos.
- `/frontend-alpine`: La aplicación final, organizada en fragmentos PHP y lógica JS desacoplada.
- `/poc-react`: La prueba de concepto inicial que sirvió de base lógica.

## 🏁 Conclusión
Este proyecto demuestra que la **reactividad moderna** no es exclusiva de los frameworks pesados. Con la dirección técnica adecuada y una IA como copiloto, es posible construir herramientas profesionales, robustas y escalables integradas en ecosistemas de servidores clásicos en tiempo récord.

---
**Desarrollado como MVP para integración futura con sistemas ERP reales.**
