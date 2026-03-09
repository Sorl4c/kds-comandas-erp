# FASE 5: Conexión Real
## SSE, Fetch y el flujo de datos

En el KDS, no queremos que el cocinero tenga que refrescar la página para ver si hay pedidos nuevos. Queremos que los pedidos "empujen" la puerta y aparezcan solos.

---

### 1. El Walkie-Talkie (SSE - Server-Sent Events) 📻
**Concepto:** Una conexión de una sola vía (Servidor -> Cliente) que se mantiene abierta. El servidor envía datos cada vez que hay un cambio en la base de datos.

**Anclaje Real:** 
En el KDS, usamos `EventSource`. El servidor PHP (`kds_api.php`) mantiene la conexión abierta y, en cuanto entra una comanda en el POS, envía el JSON. Alpine lo recibe y actualiza el `store` automáticamente.

**Código (La esencia):**
```javascript
const sse = new EventSource('api.php');

sse.onmessage = (event) => {
    const nuevosDatos = JSON.parse(event.data);
    this.$store.kds.items = nuevosDatos; // Reactividad instantánea
};
```

---

### 2. La Llamada Telefónica (Fetch) 📞
**Concepto:** Una petición puntual. El cliente pregunta, el servidor responde y la conexión se cierra.

**Anclaje Real:**
Usamos `fetch` para **acciones**. Cuando el cocinero pulsa "Terminar Plato", Alpine hace un `fetch` (POST) al servidor para decirle "Oye, marca el ID #123 como LISTO".

**Código (La esencia):**
```javascript
async marcarListo(id) {
    await fetch('update.php', {
        method: 'POST',
        body: JSON.stringify({ id, estado: 'listo' })
    });
    // No necesitamos refrescar, el SSE nos enviará la confirmación
}
```

---

### 3. El Flujo de "Círculo Cerrado" 🔄
La arquitectura profesional que estamos siguiendo en el KDS es:
1.  **Acción:** El usuario pulsa un botón -> Se lanza un `fetch`.
2.  **Backend:** El PHP actualiza la base de datos SQLite.
3.  **Push:** El proceso SSE detecta el cambio en SQLite y envía los nuevos datos a TODOS los KDS conectados.
4.  **Update:** Alpine recibe los datos y la pantalla cambia.

---

### Diccionario de Traducción

| Técnica | Metáfora | Cuándo usarla |
| :--- | :--- | :--- |
| **SSE** | Walkie-Talkie | Recibir pedidos nuevos en tiempo real. |
| **Fetch** | Llamada telefónica | Enviar una orden (marcar plato, cambiar filtro). |
| **WebSockets** | Chat de WhatsApp | Si el cocinero tuviera que hablar con el camarero (bi-direccional). |

---

### Reto de esta fase
¿Por qué no usamos `setInterval` con un `fetch` cada 2 segundos?
**Respuesta:** Porque estaríamos saturando el servidor con preguntas innecesarias ("¿Hay algo?", "¿Y ahora?", "¿Y ahora?"). SSE es mucho más eficiente: el servidor solo habla cuando tiene algo que decir.
