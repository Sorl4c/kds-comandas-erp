<?php
/**
 * FASE 3 (FINAL): Arquitectura de Alto Nivel
 * Conceptos: Scope, Encapsulación, Getters/Setters y Patrón Facade.
 * Paradigmas: State-Driven UI, UI-First Development, SRP.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Masterclass Definitiva: Arquitectura KDS</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --bg: #0f172a; --panel: #1e293b; --text: #f1f5f9; --primary: #38bdf8; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --purple: #a855f7; }
        body { font-family: "Segoe UI", sans-serif; background: var(--bg); color: var(--text); padding: 2rem; margin: 0; line-height: 1.6; }
        
        .header-title { text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #334155; padding-bottom: 1rem; }
        
        .theory-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; max-width: 1400px; margin: 0 auto 3rem auto; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; max-width: 1400px; margin: auto; }
        
        .box { background: var(--panel); border-radius: 12px; padding: 1.5rem; border: 1px solid #334155; display: flex; flex-direction: column; gap: 1rem; }
        .box-title { font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 1rem 0; display: flex; align-items: center; justify-content: space-between; }
        
        /* Badges de conceptos */
        .badge { font-size: 0.7rem; padding: 3px 8px; border-radius: 4px; font-weight: bold; background: rgba(255,255,255,0.1); }
        .badge-facade { background: #8b5cf6; color: white; }
        .badge-encap { background: #ef4444; color: white; }
        .badge-setter { background: #10b981; color: white; }
        
        /* Botones y UI */
        .btn { background: var(--primary); color: #0f172a; border: none; padding: 12px; border-radius: 6px; font-weight: 900; cursor: pointer; transition: 0.2s; width: 100%; font-size: 1rem; }
        .btn:hover { filter: brightness(1.2); transform: translateY(-2px); }
        
        .toggle-container { display: flex; align-items: center; justify-content: space-between; background: #020617; padding: 10px; border-radius: 6px; }
        
        /* Consolas (Engine Room) */
        .console { background: #020617; font-family: monospace; font-size: 0.85rem; padding: 1rem; border-radius: 8px; flex-1; overflow-y: auto; color: #a5b4fc; border: 1px solid #312e81; height: 250px; }
        .log-entry { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4px 0; }
        .log-time { color: #64748b; margin-right: 8px; }
        
        code { background: #020617; padding: 2px 6px; border-radius: 4px; color: var(--primary); font-size: 0.9em; }
        pre { background: #020617; padding: 1rem; border-radius: 8px; font-size: 0.8rem; overflow-x: auto; color: #38bdf8; border: 1px solid #1e293b; margin: 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.85rem; }
        th, td { padding: 8px; border-bottom: 1px solid #334155; text-align: left; vertical-align: top; }
        th { color: var(--primary); }
    </style>
</head>
<body>

    <div class="header-title">
        <h1 style="color: var(--primary); margin: 0 0 10px 0;">Arquitectura KDS: Teoría y Práctica</h1>
        <p style="color: #94a3b8; font-size: 1.1rem; max-width: 900px; margin: auto;">
            El puente entre pensar como un "picador de código" y pensar como un <strong>Arquitecto de Software</strong>.
        </p>
    </div>

    <!-- ========================================== -->
    <!-- SECCIÓN TEÓRICA -->
    <!-- ========================================== -->
    <div class="theory-grid">
        <!-- BLOQUE 1: PARADIGMAS -->
        <div class="box" style="border-top: 4px solid var(--purple);">
            <h2 class="box-title" style="color: var(--purple);">El Momento "Aha!": Paradigmas</h2>
            
            <div style="margin-bottom: 1rem;">
                <strong style="color: white;">1. State-Driven UI (Interfaz guiada por el Estado)</strong>
                <p style="font-size: 0.85rem; color: #cbd5e1; margin-top: 5px;">
                    El HTML es un esclavo ciego de tus datos. No le dices al HTML "ocúltate", le dices a los datos <code>urgente = true</code> y el HTML, que está escuchando, reacciona solo.
                </p>
                <table>
                    <tr>
                        <th>Imperativa (Vanilla/jQuery)</th>
                        <th>Declarativa (Alpine/React)</th>
                    </tr>
                    <tr>
                        <td>"Ve a este div y ponlo rojo" (Das órdenes paso a paso)</td>
                        <td>"El color depende de X. Si X cambia, cambia el color" (Estableces reglas)</td>
                    </tr>
                </table>
            </div>

            <div>
                <strong style="color: white;">2. UI-First Development (Mock-First)</strong>
                <p style="font-size: 0.85rem; color: #cbd5e1; margin-top: 5px;">
                    Construyes primero la UI y la interacción con un estado falso local. Una vez que la UX es perfecta, conectas el Facade al Backend (fetch). El HTML ni se entera de que ahora usa internet.
                </p>
            </div>
        </div>

        <!-- BLOQUE 2: CONVENCIONES DE CÓDIGO -->
        <div class="box" style="border-top: 4px solid var(--warning);">
            <h2 class="box-title" style="color: var(--warning);">El Jefe vs Los Especialistas (SRP vs Facade)</h2>
            <p style="font-size: 0.85rem; color: #cbd5e1; margin-bottom: 1rem;">
                ¿Cómo hacer que una función haga 4 cosas sin violar el Principio de Responsabilidad Única (SRP)? El Facade <strong>orquesta</strong> (El Director), las sub-funciones <strong>ejecutan</strong> (Los Músicos).
            </p>

            <div style="display: flex; gap: 1rem;">
                <div style="flex: 1; background: rgba(56, 189, 248, 0.1); padding: 1rem; border-radius: 8px;">
                    <strong style="color: var(--primary); font-size: 0.9rem;">El Director (Público)</strong>
                    <ul style="font-size: 0.8rem; padding-left: 1rem; margin-top: 5px; color: #cbd5e1;">
                        <li>Habla el lenguaje del negocio.</li>
                        <li>Nomenclatura: Verbo + Sujeto.</li>
                        <li>Ej: ✅ <code>entregarPlato()</code></li>
                        <li>Ej: ❌ <code>updateDBAndPrint()</code></li>
                    </ul>
                </div>
                <div style="flex: 1; background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 8px;">
                    <strong style="color: var(--danger); font-size: 0.9rem;">Los Músicos (Privado)</strong>
                    <ul style="font-size: 0.8rem; padding-left: 1rem; margin-top: 5px; color: #cbd5e1;">
                        <li>Hacen UNA sola cosa (SRP).</li>
                        <li>Nomenclatura: <code>_</code> + Verbo técnico + Objetivo.</li>
                        <li>Ej: ✅ <code>_guardarEnBD(id)</code></li>
                        <li>Ej: ✅ <code>_notificarCamarero()</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECCIÓN PRÁCTICA (INTERACTIVA) -->
    <!-- ========================================== -->
    <div class="header-title" style="border: none; margin-bottom: 1rem;">
        <h2 style="color: white; margin: 0;">Práctica: La Anatomía Perfecta en Acción</h2>
    </div>

    <div class="grid-3" x-data>
        
        <!-- 1. LA VISTA (El HTML Tonto) -->
        <div class="box" style="border-color: var(--primary);">
            <h2 class="box-title" style="color: var(--primary);">
                1. El HTML "Tonto" <span class="badge badge-facade">UI Limpia</span>
            </h2>
            <p style="font-size: 0.85rem; color: #cbd5e1;">Solo da órdenes de negocio o lee datos. No sabe de impresoras ni de bases de datos.</p>
            
            <div style="margin-top: 1rem; padding: 1rem; background: rgba(56, 189, 248, 0.05); border-radius: 8px; border-left: 4px solid var(--primary);">
                <p style="margin: 0 0 10px 0; font-size: 0.9rem;">Orden de Alto Nivel:</p>
                <button class="btn" @click="$store.motorKDS.entregarPlato('MESA 5')">
                    🚀 ENTREGAR MESA 5
                </button>
                <code style="display: block; margin-top: 10px; text-align: center;">@click="entregarPlato('MESA 5')"</code>
            </div>

            <div style="margin-top: 1rem; padding: 1rem; background: rgba(16, 185, 129, 0.05); border-radius: 8px; border-left: 4px solid var(--success);">
                <p style="margin: 0 0 10px 0; font-size: 0.9rem;">Aduana (Setter):</p>
                <div class="toggle-container">
                    <span style="font-size: 0.9rem;">Conexión a BD:</span>
                    <select x-model="$store.motorKDS.conexionRed" style="background: #1e293b; color: white; border: 1px solid #475569; padding: 5px; border-radius: 4px;">
                        <option value="online">Online</option>
                        <option value="offline">Offline (Error)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. EL STORE (La Fachada y Aduana) -->
        <div class="box" style="border-color: var(--success);">
            <h2 class="box-title" style="color: var(--success);">
                2. El Director <span class="badge badge-setter">Facade</span>
            </h2>
            <p style="font-size: 0.85rem; color: #cbd5e1;">Lee como un libro. Intercepta, orquesta y delega. Si hay 20 líneas de <code>if/else</code> técnicos aquí, está mal.</p>

<pre>
<span style="color: #64748b;">// 🏛️ LA FACHADA (Pública)</span>
entregarPlato(idTicket) {
  
  if(!this._esTicketValido(idTicket)) return;
  
  <span style="color: #64748b;">// Delega a los músicos (SRP)</span>
  this._marcarCompletadoLocal(idTicket);
  this._notificarAlCamarero(idTicket);
  
  let ok = this._guardarEnBD(idTicket);
  if(!ok) this._activarAlertaLocal();
}
</pre>
        </div>

        <!-- 3. EL MOTOR (Encapsulación) -->
        <div class="box" style="border-color: var(--danger);">
            <h2 class="box-title" style="color: var(--danger);">
                3. Los Músicos <span class="badge badge-encap">Privados SRP</span>
            </h2>
            <p style="font-size: 0.85rem; color: #cbd5e1;">Las funciones <code>_privadas</code>. Cada una hace una sola cosa técnica. Protegidas por encapsulación.</p>

            <div class="console" id="log-console">
                <template x-for="(log, i) in $store.motorKDS.historialLogs" :key="i">
                    <div class="log-entry">
                        <span class="log-time" x-text="log.time"></span>
                        <span x-text="log.msg" :style="log.color ? 'color:'+log.color : ''"></span>
                    </div>
                </template>
                <div x-show="$store.motorKDS.historialLogs.length === 0" style="color: #475569; text-align: center; margin-top: 2rem;">Consola del sistema...</div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            
            Alpine.store('motorKDS', {
                
                // ==========================================
                // 🔒 LA BÓVEDA (Variables Privadas)
                // ==========================================
                _logs: [],
                _estadoRedSecreto: 'online',

                // ==========================================
                // 🛂 LA ADUANA (Getters y Setters)
                // ==========================================
                get historialLogs() { return this._logs; },

                set conexionRed(nuevoEstado) {
                    this._escribirLog(`⚙️ SETTER INTERCEPTÓ: Red -> [${nuevoEstado.toUpperCase()}]`, '#f59e0b');
                    this._estadoRedSecreto = nuevoEstado;
                },
                get conexionRed() { return this._estadoRedSecreto; },

                // ==========================================
                // 🏛️ PATRÓN FACADE (Público, leguaje de negocio)
                // ==========================================
                entregarPlato(nombreMesa) {
                    this._escribirLog(`--- 🚀 ORDEN RECIBIDA: Entregar ${nombreMesa} ---`, '#38bdf8');
                    
                    if (!this._esTicketValido(nombreMesa)) return;

                    this._marcarCompletadoLocal(nombreMesa);
                    this._notificarAlCamarero(nombreMesa);
                    
                    let guardado = this._guardarEnBD(nombreMesa);
                    if (!guardado) {
                        this._activarAlertaLocal(nombreMesa);
                    } else {
                        this._escribirLog(`✅ ÉXITO: Flujo completado.`, '#10b981');
                    }
                },

                // ==========================================
                // ⚙️ LOS ESPECIALISTAS (Privados, SRP estricto)
                // ==========================================
                _esTicketValido(mesa) {
                    this._escribirLog(`🔍 (1/4) Validando existencia en memoria...`);
                    return true;
                },

                _marcarCompletadoLocal(mesa) {
                    this._escribirLog(`📝 (2/4) Actualizando DOM de forma optimista (State-Driven)...`);
                },

                _notificarAlCamarero(mesa) {
                    this._escribirLog(`🔔 (3/4) Disparando Socket/Sonido al reloj del camarero...`);
                },

                _guardarEnBD(mesa) {
                    this._escribirLog(`💾 (4/4) Intentando FETCH a update_comanda.php...`);
                    if (this._estadoRedSecreto === 'offline') {
                        this._escribirLog(`❌ ERROR BD: Sin conexión. Fallo en el paso 4.`, '#ef4444');
                        return false;
                    }
                    return true;
                },

                _activarAlertaLocal(mesa) {
                    this._escribirLog(`🚨 REVERSIÓN: Deshaciendo paso 2 y guardando en caché.`, '#ef4444');
                },

                // Helper para la consola
                _escribirLog(mensaje, color = null) {
                    const ahora = new Date();
                    const time = `[${ahora.getHours().toString().padStart(2,'0')}:${ahora.getMinutes().toString().padStart(2,'0')}:${ahora.getSeconds().toString().padStart(2,'0')}]`;
                    this._logs.push({ time, msg: mensaje, color });
                    setTimeout(() => {
                        const el = document.getElementById('log-console');
                        if(el) el.scrollTop = el.scrollHeight;
                    }, 50);
                }
            });
        });
    </script>
</body>
</html>