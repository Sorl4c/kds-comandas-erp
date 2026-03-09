<?php
/**
 * MASTERCLASS AVANZADA: Getters, Setters y Encapsulación
 * 
 * Extrapolación a conceptos de Programación Orientada a Objetos (POO) 
 * y Patrones Estructurales.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Masterclass Avanzada: Getters y Setters</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --primary: #38bdf8; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; }
        body { font-family: "Segoe UI", sans-serif; background: var(--bg); color: #f1f5f9; padding: 2rem; margin: 0; line-height: 1.6; }
        h1, h2, h3 { margin-top: 0; }
        
        .header { text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #334155; padding-bottom: 2rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; max-width: 1400px; margin: auto; }
        
        .panel { background: var(--card); border: 1px solid #334155; border-radius: 12px; padding: 2rem; position: relative; }
        .panel-title { color: var(--primary); font-size: 1.2rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; }
        
        .tag { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; background: rgba(255,255,255,0.1); }
        .tag.oop { background: #6366f1; color: white; }
        .tag.pattern { background: #8b5cf6; color: white; }
        
        .internal-state { background: #020617; border: 2px dashed #475569; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; font-family: monospace; }
        
        input, select { background: #0f172a; border: 1px solid #475569; color: white; padding: 10px; border-radius: 6px; width: 100%; box-sizing: border-box; font-size: 1rem; }
        input:focus { outline: 2px solid var(--primary); }
        
        .log-list { list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: #94a3b8; }
        .log-list li { border-left: 2px solid var(--primary); padding-left: 10px; margin-bottom: 5px; }

        pre { background: #020617; padding: 1rem; border-radius: 8px; font-size: 0.85rem; color: #38bdf8; overflow-x: auto; border: 1px solid #1e293b; }
        .comment { color: #64748b; }
    </style>
</head>
<body>

    <header class="header">
        <h1 style="color: var(--primary); font-size: 2.5rem; margin-bottom: 1rem;">La Magia de la Interceptación</h1>
        <p style="font-size: 1.2rem; color: #94a3b8; max-width: 800px; margin: auto;">
            En JavaScript moderno y Alpine.js, los <strong>Getters (get)</strong> y <strong>Setters (set)</strong> no son solo variables. Son <em>Aduanas</em>. Permiten ocultar la complejidad interna y exponer una interfaz limpia.
        </p>
    </header>

    <div class="grid" x-data="ModeloComanda()">
        
        <!-- COLUMNA IZQUIERDA: Teoría y Estado Interno -->
        <div>
            <div class="panel" style="margin-bottom: 2rem;">
                <h2 class="panel-title">Conceptos Arquitectónicos</h2>
                <div style="margin-bottom: 1rem;">
                    <span class="tag oop">POO: Encapsulación</span>
                    <p style="font-size: 0.95rem; color: #cbd5e1;">Ocultamos las variables reales (ej: <code>_segundos</code>) y solo permitimos interactuar con ellas a través de "puertas controladas" (ej: <code>horas</code>). Si la regla de negocio cambia, solo tocas la puerta, no todo el HTML.</p>
                </div>
                <div>
                    <span class="tag pattern">Patrón: Proxy / Facade</span>
                    <p style="font-size: 0.95rem; color: #cbd5e1;">El HTML interactúa con lo que parece una variable simple (<code>comanda.estado = 'listo'</code>). Pero por debajo, esa asignación es interceptada por un método que puede validar, registrar logs o formatear datos antes de guardar.</p>
                </div>
            </div>

            <div class="panel" style="border-color: #ef4444;">
                <h2 class="panel-title" style="color: #ef4444;">📦 LA CAJA FUERTE (Estado Privado)</h2>
                <p style="font-size: 0.85rem; color: #94a3b8;">Por convención en POO, empezamos estas variables con <code>_</code> para indicar que "nadie desde fuera debería tocarlas directamente".</p>
                
                <div class="internal-state">
                    <div>_estado_interno: <span x-text="'&quot;' + _estado_interno + '&quot;'" style="color: var(--warning)"></span></div>
                    <div>_tiempo_segundos: <span x-text="_tiempo_segundos" style="color: var(--primary)"></span></div>
                    <div style="margin-top: 10px;">_historial_logs:</div>
                    <ul class="log-list" style="margin-top: 5px;">
                        <template x-for="(log, i) in _historial_logs" :key="i">
                            <li x-text="log"></li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Interfaz Pública e Interacción -->
        <div>
            <div class="panel" style="border-color: var(--success); margin-bottom: 2rem;">
                <h2 class="panel-title" style="color: var(--success);">🛂 LA ADUANA (Getters y Setters)</h2>
                <p style="font-size: 0.85rem; color: #94a3b8;">Interactúa con estos controles. El HTML usa <code>x-model</code> sobre propiedades "falsas". Los Setters interceptan el valor, lo transforman y lo guardan en la Caja Fuerte.</p>

                <!-- EJEMPLO 1: Transformación de Datos -->
                <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(56, 189, 248, 0.05); border-radius: 8px;">
                    <h3>1. Conversión al Vuelo (Facade)</h3>
                    <p style="font-size: 0.85rem;">La BD guarda <strong>segundos</strong>. El chef escribe <strong>minutos</strong>. El HTML no hace ninguna multiplicación, se encarga la capa de datos.</p>
                    
                    <label style="display:block; margin-bottom: 5px; font-size: 0.9rem;">Tiempo Estimado (Minutos):</label>
                    <input type="number" x-model="tiempoEnMinutos" min="0" step="1">
                    
                    <div style="margin-top: 10px; font-size: 0.9rem;">
                        <strong>El Getter devuelve horas:</strong> <span x-text="tiempoEnHorasHumanas" style="color: var(--primary); font-weight: bold;"></span>
                    </div>
                </div>

                <!-- EJEMPLO 2: Side-Effects y Validación -->
                <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(245, 158, 11, 0.05); border-radius: 8px;">
                    <h3>2. Interceptación (Mutador Activo)</h3>
                    <p style="font-size: 0.85rem;">Cuando cambias el estado, el <code>set</code> no solo cambia el string. Valida reglas de negocio y escribe en el historial automáticamente.</p>
                    
                    <label style="display:block; margin-bottom: 5px; font-size: 0.9rem;">Cambiar Estado del Ticket:</label>
                    <select x-model="estadoControlado">
                        <option value="pendiente">Pendiente</option>
                        <option value="preparando">Preparando</option>
                        <option value="entregado">Entregado (Bloquea cambios)</option>
                    </select>
                </div>
            </div>

            <!-- CÓDIGO FUENTE -->
            <div class="panel">
                <h2 class="panel-title" style="font-size: 0.9rem;">Código del Componente:</h2>
                <pre>
Alpine.data('ModeloComanda', () => ({
    <span class="comment">// 1. ESTADO PRIVADO (Encapsulado)</span>
    _estado_interno: 'pendiente',
    _tiempo_segundos: 1800, <span class="comment">// 30 min por defecto</span>
    _historial_logs: ['Ticket creado'],

    <span class="comment">// 2. GETTERS (Transforman los datos para la Vista)</span>
    get tiempoEnMinutos() {
        return this._tiempo_segundos / 60;
    },

    get tiempoEnHorasHumanas() {
        let h = Math.floor(this.tiempoEnMinutos / 60);
        let m = this.tiempoEnMinutos % 60;
        return `${h}h ${m}m`;
    },

    get estadoControlado() {
        return this._estado_interno;
    },

    <span class="comment">// 3. SETTERS (Interceptan la entrada de la Vista)</span>
    set tiempoEnMinutos(nuevoValorMinutos) {
        <span class="comment">// Recibe minutos de la UI -> Guarda segundos en la BD</span>
        this._tiempo_segundos = nuevoValorMinutos * 60;
    },

    set estadoControlado(nuevoEstado) {
        <span class="comment">// Regla de Negocio: Validar antes de mutar</span>
        if (this._estado_interno === 'entregado') {
            alert('❌ Regla de negocio: Un pedido entregado no puede retroceder.');
            return; 
        }

        <span class="comment">// Efecto secundario (Side-effect): Escribir log automáticamente</span>
        this._historial_logs.push(`Estado cambió a: ${nuevoEstado}`);
        this._estado_interno = nuevoEstado;
    }
}))</pre>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ModeloComanda', () => ({
                // 1. ESTADO PRIVADO (Encapsulado)
                _estado_interno: 'pendiente',
                _tiempo_segundos: 1800, // 30 min por defecto
                _historial_logs: ['Ticket inicializado a las ' + new Date().toLocaleTimeString()],

                // 2. GETTERS (Lectura transformada)
                get tiempoEnMinutos() {
                    return this._tiempo_segundos / 60;
                },

                get tiempoEnHorasHumanas() {
                    let h = Math.floor(this.tiempoEnMinutos / 60);
                    let m = Math.floor(this.tiempoEnMinutos % 60);
                    return `${h}h ${m}m`;
                },

                get estadoControlado() {
                    return this._estado_interno;
                },

                // 3. SETTERS (Escritura interceptada)
                set tiempoEnMinutos(nuevoValorMinutos) {
                    // Recibe minutos del input -> Guarda segundos internos
                    let val = parseFloat(nuevoValorMinutos);
                    if(!isNaN(val) && val >= 0) {
                        this._tiempo_segundos = val * 60;
                    }
                },

                set estadoControlado(nuevoEstado) {
                    // Validar antes de mutar (Encapsulación / Protección)
                    if (this._estado_interno === 'entregado') {
                        alert('❌ ERROR DE NEGOCIO: Un ticket entregado no puede modificarse.');
                        
                        // Forzamos que el select vuelva a mostrar "entregado" 
                        // disparando un evento nativo o usando nextTick en Alpine avanzado.
                        // En x-model bidireccional puro, al rechazar el cambio, la vista se desincroniza temporalmente
                        // sin Alpine.$nextTick, pero como el getter no cambia, Alpine re-evaluará.
                        return;
                    }

                    // Mutación controlada + Efecto Secundario
                    this._estado_interno = nuevoEstado;
                    this._historial_logs.push(`Modificado a [${nuevoEstado}] a las ${new Date().toLocaleTimeString()}`);
                }
            }));
        });
    </script>
</body>
</html>