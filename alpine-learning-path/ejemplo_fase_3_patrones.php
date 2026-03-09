<?php
/**
 * FASE 3.2: Patrones de Diseño en Alpine.js
 * 
 * Objetivo: Entender Alpine no como un "juguete", sino como una 
 * implementación de patrones clásicos (Singleton, Factory, Strategy).
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fase 3.2: Patrones de Diseño y Abstracción</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: "Segoe UI", sans-serif; background: #0f172a; color: white; padding: 2rem; margin: 0; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; max-width: 1400px; margin: auto; }
        .box { background: #1e293b; padding: 2rem; border-radius: 12px; border: 1px solid #334155; }
        
        .card { 
            background: #334155; padding: 1rem; margin: 10px 0; border-radius: 8px; 
            border-left: 5px solid #64748b; display: flex; justify-content: space-between;
        }
        .card-vip { border-left-color: #f59e0b; background: #451a03; }
        
        .btn { 
            background: #38bdf8; color: #0f172a; border: none; padding: 10px 15px; 
            border-radius: 6px; cursor: pointer; font-weight: 700; transition: 0.2s;
        }
        .btn-outline { background: transparent; border: 1px solid #38bdf8; color: #38bdf8; }
        .btn-active { background: #38bdf8; color: #0f172a; }

        table { width: 100%; border-collapse: collapse; margin-top: 2rem; font-size: 0.85rem; }
        th { text-align: left; color: #38bdf8; padding: 10px; border-bottom: 2px solid #334155; }
        td { padding: 10px; border-bottom: 1px solid #334155; vertical-align: top; }
        pre { background: #020617; padding: 1rem; border-radius: 8px; color: #38bdf8; font-size: 0.75rem; overflow-x: auto; }
        .pattern-tag { font-size: 0.7rem; background: #4f46e5; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; }
    </style>
</head>
<body>

    <header style="max-width: 1400px; margin: 0 auto 2rem auto;">
        <h1 style="color: #38bdf8;">Arquitectura KDS: Patrones de Diseño</h1>
        <p style="color: #94a3b8;">Mapeando conceptos de Ingeniería de Software a Alpine.js.</p>
    </header>

    <div class="grid">
        
        <!-- 📦 VISTA: EL TABLERO (Strategy & Factory) -->
        <div class="box" x-data>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin:0">Comandas Activas</h3>
                <div>
                    <span class="pattern-tag">Strategy</span>
                    <select class="btn btn-outline" style="padding: 5px;" x-model="$store.kds.estrategia">
                        <option value="recientes">Más Recientes</option>
                        <option value="prioridad">Por Prioridad (VIP)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <button class="btn" @click="$store.kds.crearTicket('Mesa 5', false)">+ Ticket Normal</button>
                <button class="btn" style="background: #f59e0b" @click="$store.kds.crearTicket('Mesa VIP', true)">+ Ticket VIP</button>
            </div>

            <div style="min-height: 200px;">
                <template x-for="t in $store.kds.listaProcesada" :key="t.id">
                    <div class="card" :class="t.esVip && 'card-vip'" x-transition>
                        <div>
                            <strong x-text="t.mesa"></strong>
                            <div style="font-size: 0.8rem; color: #94a3b8;" x-text="'ID: #' + t.id"></div>
                        </div>
                        <div style="text-align: right;">
                            <span class="pattern-tag" style="background: #10b981">Observer</span>
                            <div x-text="t.esVip ? '🔥 ALTA' : 'NORMAL'"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- 📦 EXPLICACIÓN: PATRONES APLICADOS -->
        <div class="box">
            <h3 style="margin-top:0">Análisis Arquitectónico</h3>
            
            <table>
                <tr>
                    <th>Patrón</th>
                    <th>Propósito en el KDS</th>
                    <th>Implementación Alpine</th>
                </tr>
                <tr>
                    <td><b>Singleton</b></td>
                    <td>Garantiza que solo exista un almacén de datos central.</td>
                    <td><code>Alpine.store('kds', {...})</code></td>
                </tr>
                <tr>
                    <td><b>Factory</b></td>
                    <td>Crea objetos "Ticket" consistentes con la misma estructura.</td>
                    <td>Método <code>crearTicket()</code> en el store.</td>
                </tr>
                <tr>
                    <td><b>Strategy</b></td>
                    <td>Cambia el algoritmo de ordenación sin tocar el HTML.</td>
                    <td>Getter <code>listaProcesada</code> basado en <code>this.estrategia</code>.</td>
                </tr>
                <tr>
                    <td><b>Observer</b></td>
                    <td>Notifica a la UI cuando los datos cambian.</td>
                    <td>Sistema de reactividad interno (Proxies).</td>
                </tr>
            </table>

            <div style="margin-top: 1.5rem;">
                <h4>Código Estratégico (Getter):</h4>
                <pre>
get listaProcesada() {
    const list = [...this.tickets];
    
    // ESTRATEGIA: Selección dinámica del algoritmo
    if (this.estrategia === 'recientes') {
        return list.reverse();
    }
    
    if (this.estrategia === 'prioridad') {
        return list.sort((a, b) => b.esVip - a.esVip);
    }
    
    return list;
}</pre>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            
            // PATRÓN: SINGLETON
            Alpine.store('kds', {
                tickets: [
                    { id: 101, mesa: 'Mesa 1', esVip: false },
                    { id: 102, mesa: 'Terraza 2', esVip: true }
                ],
                estrategia: 'recientes',

                // PATRÓN: FACTORY (Encapsula la creación del objeto)
                crearTicket(nombre, vip) {
                    const nuevoTicket = {
                        id: Date.now(),
                        mesa: nombre,
                        esVip: vip
                    };
                    this.tickets.push(nuevoTicket);
                },

                // PATRÓN: STRATEGY (Datos derivados mediante Getters)
                get listaProcesada() {
                    const copia = [...this.tickets];
                    
                    if (this.estrategia === 'recientes') {
                        return copia.reverse();
                    }
                    
                    if (this.estrategia === 'prioridad') {
                        // Ordena poniendo los VIPs arriba
                        return copia.sort((a, b) => (b.esVip === a.esVip) ? 0 : b.esVip ? 1 : -1);
                    }
                    
                    return copia;
                }
            });
        });
    </script>
</body>
</html>