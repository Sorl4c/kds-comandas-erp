<?php
/**
 * MASTERCLASS: La Evolución del Estado (Fases 1, 2 y 3)
 * 
 * Objetivo: Entender de un vistazo cuándo usar x-data en línea, 
 * cuándo extraer a Alpine.data(), y cuándo necesitas Alpine.store().
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Masterclass: Evolución del Estado (Fase 1 a 3)</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --bg: #0f172a; --panel: #1e293b; --text: #f1f5f9; --f1: #a855f7; --f2: #ec4899; --f3: #38bdf8; }
        body { font-family: "Segoe UI", sans-serif; background: var(--bg); color: var(--text); padding: 2rem; margin: 0; line-height: 1.6; }
        
        .header-title { text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #334155; padding-bottom: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; max-width: 1400px; margin: auto; }
        
        .box { background: var(--panel); border-radius: 12px; padding: 1.5rem; border: 1px solid #334155; display: flex; flex-direction: column; gap: 1rem; }
        .box-title { font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 1rem 0; display: flex; align-items: center; justify-content: space-between; }
        
        .badge { font-size: 0.7rem; padding: 3px 8px; border-radius: 4px; font-weight: bold; background: rgba(255,255,255,0.1); }
        .badge-f1 { background: var(--f1); color: white; }
        .badge-f2 { background: var(--f2); color: white; }
        .badge-f3 { background: var(--f3); color: #0f172a; }
        
        .code-block { background: #020617; padding: 1rem; border-radius: 8px; font-size: 0.8rem; overflow-x: auto; border: 1px solid #334155; margin-bottom: 1rem; }
        .btn { background: #475569; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        .btn:hover { filter: brightness(1.2); }
        
        .kds-mock-card { background: #334155; padding: 10px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #64748b; }
    </style>
</head>
<body>

    <div class="header-title">
        <h1 style="color: white; margin: 0 0 10px 0;">El Viaje Arquitectónico del KDS</h1>
        <p style="color: #94a3b8; font-size: 1.1rem; max-width: 800px; margin: auto;">
            Resumen visual de las Fases 1, 2 y 3. Desde un estado "sucio" en el HTML, hasta una factoría de componentes, y finalmente el anillo que los gobierna a todos (Store).
        </p>
    </div>

    <div class="grid-3">
        
        <!-- ========================================== -->
        <!-- FASE 1: X-DATA EN LÍNEA -->
        <!-- ========================================== -->
        <div class="box" style="border-color: var(--f1);">
            <h2 class="box-title" style="color: var(--f1);">
                Fase 1: En Línea <span class="badge badge-f1">Local Único</span>
            </h2>
            <p style="font-size: 0.85rem; color: #cbd5e1;">Todo vive dentro del atributo HTML. Ideal para cosas pequeñitas que no se comunican con nadie, como un desplegable o un botón de "ver más".</p>
            
            <div class="code-block" style="color: var(--f1);">
<pre style="margin:0;">&lt;div x-data="{ abierto: false }"&gt;
  &lt;button @click="abierto = !abierto"&gt;
  &lt;div x-show="abierto"&gt;...&lt;/div&gt;
&lt;/div&gt;</pre>
            </div>

            <div style="padding: 1rem; background: rgba(168, 85, 247, 0.05); border-radius: 8px; border: 1px dashed var(--f1);">
                <!-- EJEMPLO INTERACTIVO FASE 1 -->
                <div x-data="{ notasAbiertas: false }" class="kds-mock-card" style="border-color: var(--f1);">
                    <div style="display:flex; justify-content: space-between;">
                        <strong>Burger XL</strong>
                        <button class="btn" style="background: var(--f1); font-size: 0.7rem;" @click="notasAbiertas = !notasAbiertas">
                            <span x-text="notasAbiertas ? 'Ocultar' : 'Ver Notas'"></span>
                        </button>
                    </div>
                    <div x-show="notasAbiertas" x-collapse style="margin-top: 10px; font-size: 0.8rem; color: #fbbf24;">
                        ⚠️ Sin cebolla, extra queso.
                    </div>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 10px;">* El estado <code>notasAbiertas</code> nace y muere aquí. Nadie más lo conoce.</p>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FASE 2: ALPINE.DATA (FACTORÍA) -->
        <!-- ========================================== -->
        <div class="box" style="border-color: var(--f2);">
            <h2 class="box-title" style="color: var(--f2);">
                Fase 2: Componente <span class="badge badge-f2">Fábrica Reutilizable</span>
            </h2>
            <p style="font-size: 0.85rem; color: #cbd5e1;">La lógica crece y ensucia el HTML. La extraemos a JS (`Alpine.data`). Esto crea un "molde". Podemos crear varias instancias independientes.</p>

            <div class="code-block" style="color: var(--f2);">
<pre style="margin:0;">// JS
Alpine.data('Columna', (nombre) => ({
  nombre,
  items: [],
  add() { this.items.push(1) }
}))

// HTML
&lt;div x-data="Columna('Cocina')"&gt;
&lt;div x-data="Columna('Barra')"&gt;</pre>
            </div>

            <div style="display:flex; gap: 10px;">
                <!-- INSTANCIA 1 -->
                <div x-data="ColumnaKDS('Cocina')" style="flex:1; padding: 10px; background: rgba(236, 72, 153, 0.05); border-radius: 8px; border: 1px dashed var(--f2);">
                    <strong x-text="titulo" style="color: var(--f2);"></strong>
                    <div style="font-size: 1.5rem; font-weight: bold; margin: 10px 0;" x-text="tickets"></div>
                    <button class="btn" style="background: var(--f2); font-size: 0.7rem; width: 100%;" @click="sumar()">Añadir a Cocina</button>
                </div>
                
                <!-- INSTANCIA 2 -->
                <div x-data="ColumnaKDS('Barra')" style="flex:1; padding: 10px; background: rgba(236, 72, 153, 0.05); border-radius: 8px; border: 1px dashed var(--f2);">
                    <strong x-text="titulo" style="color: var(--f2);"></strong>
                    <div style="font-size: 1.5rem; font-weight: bold; margin: 10px 0;" x-text="tickets"></div>
                    <button class="btn" style="background: var(--f2); font-size: 0.7rem; width: 100%;" @click="sumar()">Añadir a Barra</button>
                </div>
            </div>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 10px;">* Misma lógica (el JS), pero los datos de "Cocina" no se mezclan con "Barra". Siguen siendo islas separadas.</p>
        </div>

        <!-- ========================================== -->
        <!-- FASE 3: ALPINE.STORE (EL ANILLO ÚNICO) -->
        <!-- ========================================== -->
        <div class="box" style="border-color: var(--f3);">
            <h2 class="box-title" style="color: var(--f3);">
                Fase 3: Store <span class="badge badge-f3">Estado Global Singleton</span>
            </h2>
            <p style="font-size: 0.85rem; color: #cbd5e1;">El Header necesita saber cuántos tickets hay en total. Como Cocina y Barra son islas, usamos el Store como base de datos central en la memoria.</p>

            <div class="code-block" style="color: var(--f3);">
<pre style="margin:0;">// JS
Alpine.store('kds', {
  totalGlobal: 0
})

// En CUALQUIER lugar del HTML:
&lt;span x-text="$store.kds.totalGlobal"&gt;</pre>
            </div>

            <div style="padding: 1rem; background: rgba(56, 189, 248, 0.05); border-radius: 8px; border: 1px dashed var(--f3);" x-data>
                <!-- HEADER SIMULADO -->
                <div style="background: #020617; padding: 10px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--f3);">
                    <strong>HEADER KDS</strong>
                    <div style="text-align: right;">
                        <div style="font-size: 0.6rem; color: var(--f3);">TOTAL RESTAURANTE</div>
                        <strong style="font-size: 1.2rem;" x-text="$store.kds.totalGlobal"></strong>
                    </div>
                </div>

                <div style="margin-top: 15px; display: flex; gap: 10px;">
                    <button class="btn" style="background: var(--f3); color: #0f172a; flex:1;" @click="$store.kds.totalGlobal++">
                        Simular Nueva Entrada POS
                    </button>
                    <button class="btn" style="background: #ef4444; flex:1;" @click="$store.kds.totalGlobal = 0">
                        Reset
                    </button>
                </div>
            </div>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 10px;">* El botón inyecta al Store. El Header lee del Store. Pueden estar a 1000 líneas de HTML de distancia, la comunicación es instantánea y reactiva.</p>
        </div>

    </div>

    <!-- SCRIPTS CON LA LÓGICA DE FASE 2 Y 3 -->
    <script>
        document.addEventListener('alpine:init', () => {
            
            // LÓGICA FASE 2: Molde reutilizable (Instancias Múltiples)
            Alpine.data('ColumnaKDS', (nombre) => ({
                titulo: nombre,
                tickets: 0,
                sumar() {
                    this.tickets++;
                }
            }));

            // LÓGICA FASE 3: Almacén Global (Instancia Única)
            Alpine.store('kds', {
                totalGlobal: 0
            });
            
        });
    </script>
</body>
</html>