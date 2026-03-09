<?php
/**
 * TUTORIAL: Alpine.store() - Arquitectura de Grado Producción
 * 
 * Estilo: Libro de Texto Interactivo
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tutorial Fase 3: Alpine.store() - El Singleton del KDS</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- 📦 CARGA DEL STORE MODULAR -->
    <script src="js/fase_3_store.js"></script>
    
    <style>
        :root { --primary: #38bdf8; --bg: #0f172a; --card: #1e293b; --text: #f1f5f9; }
        body { font-family: "Segoe UI", sans-serif; background: var(--bg); color: var(--text); padding: 2rem; line-height: 1.6; }
        .master-grid { display: grid; grid-template-columns: 400px 1fr; gap: 3rem; max-width: 1400px; margin: auto; }
        
        /* 📚 ESTILO LIBRO / TUTORIAL */
        .lesson-panel { position: sticky; top: 2rem; height: calc(100vh - 4rem); overflow-y: auto; padding-right: 1rem; }
        h1 { color: var(--primary); font-size: 1.8rem; border-bottom: 2px solid #334155; padding-bottom: 1rem; }
        .concept-box { background: rgba(56, 189, 248, 0.05); border-left: 4px solid var(--primary); padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; }
        
        /* 🚀 ESTILO KDS INTERACTIVO */
        .kds-preview { background: var(--card); border-radius: 16px; border: 1px solid #334155; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { background: #020617; padding: 1rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border: 1px solid #334155; }
        .kds-card { background: #334155; padding: 1rem; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; border-left: 4px solid #64748b; }
        .kds-card.urgente { border-left-color: #ef4444; }
        
        /* 🎨 DICCIONARIOS */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.85rem; }
        th { text-align: left; padding: 10px; border-bottom: 2px solid #334155; color: var(--primary); }
        td { padding: 10px; border-bottom: 1px solid #1e293b; }
        pre { background: #020617; padding: 1rem; border-radius: 8px; color: var(--primary); font-size: 0.8rem; overflow-x: auto; }
        .btn { background: var(--primary); color: #0f172a; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 700; transition: 0.2s; }
        .badge { background: #4f46e5; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; }
    </style>
</head>
<body>

    <div class="master-grid">
        
        <!-- 📚 PANEL DE LECCIÓN (Teoría y Análisis) -->
        <aside class="lesson-panel">
            <h1>Masterclass: Alpine.store()</h1>
            
            <div class="concept-box">
                <strong>Anclaje Real:</strong>
                <p>En el KDS real, no todo vive en un solo bloque. El Header necesita saber cuántas comandas hay, y el Tablero debe pintarlas. Sin <code>Alpine.store</code>, tendrías que pasar datos de padres a hijos (un infierno en Vanilla JS).</p>
            </div>

            <h3>Comparativa de Patrones</h3>
            <table>
                <tr>
                    <th>Concepto</th>
                    <th>Vanilla (Caos)</th>
                    <th>Alpine (Orden)</th>
                </tr>
                <tr>
                    <td><b>Estado</b></td>
                    <td><code>window.data = [...]</code></td>
                    <td><code>$store.kds</code></td>
                </tr>
                <tr>
                    <td><b>Evento</b></td>
                    <td><code>document.trigger('update')</code></td>
                    <td>Automático (Reactivo)</td>
                </tr>
                <tr>
                    <td><b>Cálculos</b></td>
                    <td><code>updateTotalCount()</code> manual</td>
                    <td><code>get total()</code> dinámico</td>
                </tr>
            </table>

            <div style="margin-top: 2rem;">
                <h3>Sintaxis Modular:</h3>
                <pre>
// js/kds_store.js
document.addEventListener('alpine:init', () => {
    Alpine.store('kds', {
        tickets: [],
        get total() { 
            return this.tickets.length 
        }
    });
});</pre>
            </div>
        </aside>

        <!-- 🚀 PANEL DE PREVIEW (Interactividad) -->
        <main>
            <div class="kds-preview" x-data>
                
                <!-- PIEZA 1: HEADER (Accede al store) -->
                <header class="header">
                    <div>
                        <h2 style="margin:0; font-size: 1rem;">TABLERO DE COCINA</h2>
                        <span class="badge" x-text="'FILTRO: ' + $store.kds.filtro.toUpperCase()"></span>
                    </div>
                    <div style="text-align: right;">
                        <small style="color: #94a3b8;">TICKETS PENDIENTES</small><br>
                        <strong style="font-size: 1.5rem; color: var(--primary);" x-text="$store.kds.totalPendientes"></strong>
                    </div>
                </header>

                <!-- PIEZA 2: CONTROLES -->
                <div style="display: flex; gap: 10px; margin-bottom: 2rem;">
                    <button class="btn" @click="$store.kds.crearTicket('Mesa ' + Math.floor(Math.random()*10), false)">+ Normal</button>
                    <button class="btn" style="background: #ef4444;" @click="$store.kds.crearTicket('Mesa VIP', true)">+ VIP</button>
                    
                    <select class="btn" style="background: transparent; color: white; border: 1px solid #334155;" x-model="$store.kds.filtro">
                        <option value="todos">Todos</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="preparando">En Preparación</option>
                    </select>
                </div>

                <!-- PIEZA 3: LISTADO -->
                <div style="min-height: 200px;">
                    <template x-for="t in $store.kds.listaFiltrada" :key="t.id">
                        <div class="kds-card" :class="t.urgente && 'urgente'" x-transition>
                            <div>
                                <strong x-text="t.mesa"></strong>
                                <div style="font-size: 0.8rem; color: #94a3b8;" x-text="t.estado.toUpperCase()"></div>
                            </div>
                            <button class="btn" style="padding: 4px 8px; font-size: 0.7rem;" @click="$store.kds.avanzarTicket(t.id)">SIGUIENTE</button>
                        </div>
                    </template>
                </div>

                <!-- VISOR DE ESTADO -->
                <div style="margin-top: 2rem; padding: 1rem; background: #020617; border-radius: 8px;">
                    <h4 style="margin:0 0 10px 0; color: #f472b6;">Estado Crudo del Store:</h4>
                    <pre x-text="JSON.stringify($store.kds, null, 2)"></pre>
                </div>

            </div>
        </main>

    </div>

</body>
</html>