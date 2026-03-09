<?php
/**
 * FASE 3.1: Getters - Propiedades Calculadas
 * 
 * Objetivo: Evitar estados inconsistentes derivando datos 
 * en lugar de almacenarlos manualmente.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fase 3.1: Getters en Alpine.store</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: "Segoe UI", sans-serif; background: #0f172a; color: white; padding: 2rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; max-width: 1200px; margin: auto; }
        .box { background: #1e293b; padding: 1.5rem; border-radius: 12px; border: 1px solid #334155; }
        .card { background: #334155; padding: 10px; margin: 10px 0; border-radius: 6px; display: flex; justify-content: space-between; }
        .badge { padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 0.8rem; }
        .badge-red { background: #ef4444; color: white; }
        .badge-blue { background: #38bdf8; color: #0f172a; }
        .btn { background: #38bdf8; color: #0f172a; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        pre { background: #020617; padding: 1rem; border-radius: 8px; color: #38bdf8; font-size: 0.8rem; }
    </style>
</head>
<body>

    <header style="text-align: center; margin-bottom: 2rem;">
        <h1>Fase 3.1: El poder de los Getters</h1>
        <p style="color: #94a3b8;">No almacenes lo que puedes calcular.</p>
    </header>

    <div class="grid" x-data>
        <!-- 📦 PANEL DE INTERACCIÓN -->
        <div class="box">
            <h3 style="margin-top:0">Listado de Comandas</h3>
            
            <div style="display: flex; gap: 10px; margin-bottom: 1rem;">
                <button class="btn" @click="$store.monitor.add(3)">+ Plato Normal</button>
                <button class="btn" style="background: #f472b6" @click="$store.monitor.add(8)">+ Plato Crítico</button>
            </div>

            <template x-for="(c, index) in $store.monitor.pedidos" :key="index">
                <div class="card">
                    <span>Ticket #<span x-text="index + 1"></span></span>
                    <span class="badge" :class="c.cantidad > 5 ? 'badge-red' : 'badge-blue'" 
                          x-text="c.cantidad + ' platos'"></span>
                </div>
            </template>
        </div>

        <!-- 📦 PANEL DE ESTADO (GETTERS) -->
        <div class="box">
            <h3 style="margin-top:0">Resumen Reactivo (Getters)</h3>
            
            <div style="margin-bottom: 1.5rem;">
                <p>Total Tickets: <strong x-text="$store.monitor.total"></strong></p>
                <p>Urgentes ( > 5 platos): <strong style="color: #ef4444" x-text="$store.monitor.urgentes"></strong></p>
                <p>Promedio Platos/Ticket: <strong x-text="$store.monitor.promedio"></strong></p>
            </div>

            <div style="border-top: 1px solid #334155; padding-top: 1rem;">
                <h4>Código del Store:</h4>
                <pre>
Alpine.store('monitor', {
    pedidos: [],
    
    // El 'get' hace la magia:
    get total() { 
        return this.pedidos.length 
    },
    
    get urgentes() {
        return this.pedidos.filter(p => p.cantidad > 5).length
    },

    get promedio() {
        if (this.total === 0) return 0;
        let sum = this.pedidos.reduce((a, b) => a + b.cantidad, 0);
        return (sum / this.total).toFixed(1);
    }
})</pre>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('monitor', {
                pedidos: [
                    { cantidad: 2 },
                    { cantidad: 7 }
                ],

                add(n) {
                    this.pedidos.push({ cantidad: n });
                },

                // --- GETTERS ---
                get total() {
                    return this.pedidos.length;
                },

                get urgentes() {
                    return this.pedidos.filter(p => p.cantidad > 5).length;
                },

                get promedio() {
                    if (this.total === 0) return 0;
                    let sum = this.pedidos.reduce((a, b) => a + b.cantidad, 0);
                    return (sum / this.total).toFixed(1);
                }
            });
        });
    </script>
</body>
</html>