<?php
/**
 * FASE 3: Alpine.store() - El Almacén Central del KDS
 * 
 * Objetivo: Aprender a compartir datos entre componentes que no están 
 * uno dentro del otro (ej: Header y Tablero).
 */

$comandas_iniciales = [
    ["id" => 1, "mesa" => "Terraza 1", "estado" => "pendiente", "items" => 3],
    ["id" => 2, "mesa" => "Mesa 4", "estado" => "preparando", "items" => 1],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fase 3: Alpine.store() - Estado Global</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: "Segoe UI", sans-serif; background: #0f172a; color: white; padding: 2rem; margin: 0; }
        
        /* 📦 ESTILOS DEL KDS */
        .header { 
            background: #1e293b; padding: 1.5rem; border-radius: 12px; 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 2rem; border-bottom: 3px solid #38bdf8;  
        }
        .grid { display: grid; grid-template-columns: 350px 1fr; gap: 2rem; max-width: 1400px; margin: auto; }
        .column { background: #1e293b; padding: 1.5rem; border-radius: 12px; border: 1px solid #334155; }
        
        .card { 
            background: #334155; padding: 1rem; margin: 1rem 0; border-radius: 8px; 
            border-left: 4px solid #38bdf8; display: flex; justify-content: space-between; align-items: center;
            transition: 0.3s;
        }
        .card.urgente { border-left-color: #ef4444; background: #450a0a; }

        .badge { background: #38bdf8; color: #0f172a; padding: 4px 12px; border-radius: 20px; font-weight: 800; font-size: 0.85rem; }
        .btn { 
            background: #38bdf8; color: #0f172a; border: none; padding: 10px 15px; border-radius: 6px; 
            cursor: pointer; font-weight: 700; width: 100%; transition: 0.2s;
        }
        .btn:hover { background: #7dd3fc; }
        .btn-filter { 
            background: transparent; border: 1px solid #334155; color: #94a3b8; 
            margin-bottom: 0.5rem; text-align: left; 
        }
        .btn-filter.active { border-color: #38bdf8; color: #38bdf8; background: rgba(56, 189, 248, 0.1); }

        /* 📚 ESTILOS DE LA LECCIÓN */
        .lesson-note { background: #1e1b4b; border-left: 4px solid #818cf8; padding: 1rem; margin: 1rem 0; border-radius: 8px; font-size: 0.9rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: 0.85rem; }
        th { text-align: left; color: #38bdf8; padding: 8px; border-bottom: 2px solid #334155; }
        td { padding: 8px; border-bottom: 1px solid #334155; }
        pre { background: #020617; padding: 1rem; border-radius: 8px; color: #38bdf8; font-size: 0.8rem; overflow-x: auto; }
    </style>
</head>
<body>

    <!-- 📦 COMPONENTE 1: EL HEADER (Accede al store) -->
    <header class="header" x-data>
        <div>
            <h1 style="margin:0; font-size: 1.2rem;">KDS Pro - Vista de Cocina</h1>
            <p style="margin:0; color: #94a3b8; font-size: 0.8rem;">
                Filtro Global: <span x-text="$store.kds.filtro.toUpperCase()" style="color: #38bdf8; font-weight: bold;"></span>
            </p>
        </div>
        <div style="text-align: right">
            <div style="font-size: 0.7rem; color: #94a3b8; margin-bottom: 4px;">TOTAL PENDIENTES</div>
            <span class="badge" x-text="$store.kds.conteoPendientes()"></span>
        </div>       <div style="text-align: right">
            <div style="font-size: 0.7rem; color: #94a3b8; margin-bottom: 4px;">TOTAL PENDIENTES</div>
            <span class="badge" x-text="$store.kds.conteoPendientes()"></span>
        </div>
    </header>

    <div class="grid">
        <!-- 📦 COMPONENTE 2: LATERAL DE CONTROL -->
        <aside class="column" x-data>
            <h3 style="margin-top:0; font-size: 0.9rem; color: #94a3b8;">FILTROS DEL SISTEMA</h3>
            
            <button class="btn btn-filter" :class="$store.kds.filtro === 'todos' && 'active'" @click="$store.kds.filtro = 'todos'">
                📂 Ver Todas las mesas
            </button>
            
            <button class="btn btn-filter" :class="$store.kds.filtro === 'pendiente' && 'active'" @click="$store.kds.filtro = 'pendiente'">
                ⏳ Solo Pendientes
            </button>

            <button class="btn btn-filter" :class="$store.kds.filtro === 'preparando' && 'active'" @click="$store.kds.filtro = 'preparando'">
                👨‍🍳 En Preparación
            </button>

            <hr style="border: 0; border-top: 1px solid #334155; margin: 1.5rem 0;">

            <button class="btn" @click="$store.kds.nuevaComanda()">
                🚀 Recibir Comanda POS
            </button>

            <div class="lesson-note">
                <strong>¿Por qué $store?</strong><br>
                Si usáramos <code>x-data</code>, el botón de "Recibir" no podría avisar al Header que hay una más. El Store es el "WhatsApp" donde todos se enteran de todo.
            </div>
        </aside>

        <!-- 📦 COMPONENTE 3: EL TABLERO -->
        <main class="column" x-data>
            <h3 style="margin-top:0; font-size: 0.9rem; color: #94a3b8;">
                COMANDAS EN PANTALLA (<span x-text="$store.kds.listaFiltrada().length"></span>)
            </h3>

            <div style="min-height: 200px;">
                <template x-for="c in $store.kds.listaFiltrada()" :key="c.id">
                    <div class="card" :class="c.items > 5 && 'urgente'" x-transition.duration.300ms>
                        <div>
                            <div style="font-weight: bold; font-size: 1.1rem;" x-text="c.mesa"></div>
                            <div style="font-size: 0.75rem; color: #94a3b8;" x-text="'Tickets: ' + c.items"></div>
                        </div>
                        <div>
                            <span :style="{ color: c.estado === 'pendiente' ? '#fbbf24' : '#10b981' }" 
                                  style="font-size: 0.8rem; font-weight: 800; margin-right: 1rem;"
                                  x-text="c.estado.toUpperCase()"></span>
                            <button class="btn" style="width: auto; padding: 5px 10px; font-size: 0.7rem;" 
                                    @click="$store.kds.cambiarEstado(c.id)">
                                SIGUIENTE PASO
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="$store.kds.listaFiltrada().length === 0" style="text-align:center; padding: 3rem; color: #475569;">
                    No hay comandas que coincidan con el filtro.
                </div>
            </div>

            <!-- EXPLICACIÓN TÉCNICA -->
            <div style="margin-top: 2rem; border-top: 1px solid #334155; padding-top: 1rem;">
                <h4>Diccionario de Traducción: Estado Global</h4>
                <table>
                    <tr>
                        <th>Concepto</th>
                        <th>Vanilla JS</th>
                        <th>React / Redux</th>
                        <th>Alpine.store()</th>
                    </tr>
                    <tr>
                        <td><b>Definición</b></td>
                        <td><code>window.state = {}</code></td>
                        <td><code>createSlice(...)</code></td>
                        <td><code>Alpine.store('kds', {...})</code></td>
                    </tr>
                    <tr>
                        <td><b>Consumo</b></td>
                        <td>Manual / Eventos</td>
                        <td><code>useSelector(...)</code></td>
                        <td><code>$store.kds.dato</code></td>
                    </tr>
                    <tr>
                        <td><b>Cálculos</b></td>
                        <td>Funciones sueltas</td>
                        <td><code>useMemo / Selectors</code></td>
                        <td><code>metodo() { return ... }</code></td>
                    </tr>
                </table>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // REGISTRO DEL STORE GLOBAL
            Alpine.store('kds', {
                lista: <?php echo json_encode($comandas_iniciales); ?>,
                filtro: 'todos',

                // --- ACCIONES ---
                nuevaComanda() {
                    this.lista.push({
                        id: Date.now(),
                        mesa: 'Mesa ' + (Math.floor(Math.random() * 20) + 1),
                        estado: 'pendiente',
                        items: Math.floor(Math.random() * 8) + 1
                    });
                },

                cambiarEstado(id) {
                    let c = this.lista.find(i => i.id === id);
                    if (c.estado === 'pendiente') c.estado = 'preparando';
                    else this.lista = this.lista.filter(i => i.id !== id);
                },

                // --- GETTERS (Cálculos reactivos) ---
                conteoPendientes() {
                    // Contamos cuántas están en estado 'pendiente'
                    return this.lista.filter(c => c.estado === 'pendiente').length;
                },

                    // --- GETTERS (Cálculos reactivos) ---
                conteoPendientes() {
                    // Contamos cuántas están en estado 'pendiente'
                    return this.lista.filter(c => c.estado === 'pendiente').length;
                },

                listaFiltrada() {
                    if (this.filtro === 'todos') return this.lista;
                    return this.lista.filter(c => c.estado === this.filtro);
                }
            });
        });
    </script>
</body>
</html>