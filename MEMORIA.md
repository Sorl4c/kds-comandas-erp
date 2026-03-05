# Memoria del Proyecto: KDS-Comandas-ERP

Este archivo sirve como registro de configuraciones críticas, lecciones aprendidas y recordatorios para el desarrollo del proyecto.

## 🛠️ Configuraciones del Entorno

### PHP (Backend)
- **Archivo de configuración:** `C:\xampp\php\php.ini`
- **Configuraciones de Error (Activadas para Desarrollo):**
    - `display_errors = On`: Muestra los errores directamente en la salida (pantalla/HTML).
    - `display_startup_errors = On`: Muestra errores que ocurren durante el inicio de PHP.
    - `error_reporting = E_ALL`: Reporta todos los errores, advertencias y notas de PHP.
    - `log_errors = On`: Registra los errores en un archivo de log.
- **Ubicación de Logs:** `C:\xampp\php\logs\php_error_log` (o accesible desde el botón "Logs" de Apache en el panel de XAMPP).
- **⚠️ Nota Importante:** Cualquier cambio en el `php.ini` requiere **reiniciar el servicio de Apache** desde el panel de XAMPP para que surta efecto.

## 💡 Lecciones Aprendidas

### Depuración de Errores Silenciosos
- **Problema:** Si un componente de la vista (como `servidos_view.php`) falla al cargar por un error de PHP (ej. archivo no encontrado), la consola de JavaScript no mostrará nada.
- **Solución:** 
    1. Revisar el código fuente de la página (`Ctrl + U`) para buscar advertencias de PHP inyectadas en el HTML.
    2. Revisar la pestaña "Elements" en las herramientas de desarrollador para ver si hay mensajes de error dentro del DOM.
    3. Consultar los logs de Apache (`error.log`) en XAMPP.

### Persistencia en Conexiones SSE (Server-Sent Events)
- El archivo `kds_api.php` utiliza un bucle infinito `while(true)`. 
- **Efecto:** Los cambios en el código de la API no se aplican inmediatamente porque el servidor mantiene la conexión "Keep-Alive" con el código antiguo en memoria.
- **Acción:** Siempre reiniciar Apache después de modificar el backend de la API si se usa SSE.
