<?php
/**
 * 1. EL SERVIDOR (PHP)
 * Simulamos lo que llega desde SQLite.
 */
$comandas_iniciales = [
    ["id" => 1, "mesa" => "Mesa 1", "plato" => "Burger XL"],
    ["id" => 2, "mesa" => "Barra", "plato" => "Nachos"],
];

$json_datos = json_encode($comandas_iniciales);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Sesion 2: Datos, Modulos y Reactividad</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #0f172a;
            color: #fff;
            padding: 2rem;
            margin: 0;
        }

        /* 🎭 CLASES DE TRANSICIÓN PERSONALIZADAS */
        .tarjeta-entra {
            transition: all 0.5s ease-out;
        }
        .tarjeta-entra-inicio {
            opacity: 0;
            transform: translateX(50px); /* Viene de la derecha */
        }
        .tarjeta-entra-fin {
            opacity: 1;
            transform: translateX(0);
        }

        .tarjeta-sale {
            transition: all 0.4s ease-in;
        }
        .tarjeta-sale-inicio {
            opacity: 1;
            transform: scale(1);
        }
        .tarjeta-sale-fin {
            opacity: 0;
            transform: scale(0.5); /* Se encoge al centro */
        }

        .header-title {
            text-align: center;
            margin-bottom: 0.75rem;
            color: #38bdf8;
        }

        .header-subtitle {
            text-align: center;
            color: #94a3b8;
            max-width: 900px;
            margin: 0 auto 2rem auto;
            line-height: 1.6;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 1400px;
            margin: auto;
        }

        .box {
            background: #1e293b;
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid #334155;
        }

        .lesson-panel {
            max-width: 1400px;
            margin: 0 auto 2rem auto;
            background: #1e293b;
            border-radius: 12px;
            border: 1px solid #334155;
        }

        .lesson-panel summary {
            padding: 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: #f472b6;
            cursor: pointer;
            list-style: none;
        }

        .lesson-body {
            padding: 0 1.5rem 1.5rem 1.5rem;
            color: #e2e8f0;
            line-height: 1.65;
        }

        .lesson-section {
            margin-top: 1.5rem;
        }

        .lesson-note {
            background: rgba(56, 189, 248, 0.08);
            border-left: 4px solid #38bdf8;
            padding: 0.9rem 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .kds-card {
            background: #334155;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #10b981;
            gap: 1rem;
        }

        .btn {
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 6px;
            border: none;
            font-weight: 700;
            background: #3b82f6;
            color: #fff;
            transition: 0.2s;
        }

        .btn:hover {
            background: #2563eb;
        }

        .btn-del {
            background: #ef4444;
            font-size: 0.8rem;
        }

        .badge {
            background: #4f46e5;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .code-block {
            background: #020617;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 15px;
            border: 1px solid #334155;
        }

        .code-header {
            background: #0f172a;
            padding: 8px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            border-bottom: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        pre {
            margin: 0;
            padding: 15px;
            font-size: 0.82rem;
            color: #e2e8f0;
            overflow-x: auto;
            font-family: Consolas, monospace;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        th {
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #334155;
            color: #38bdf8;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #1e293b;
            vertical-align: top;
        }

        .mini-block {
            margin-top: 20px;
            padding: 1.5rem;
        }

        .question-list {
            margin: 0;
            padding-left: 1.2rem;
            color: #cbd5e1;
        }

        .question-list li + li {
            margin-top: 0.55rem;
        }

        @media (max-width: 960px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <h1 class="header-title">Masterclass: Fase 2 de Alpine</h1>
    <p class="header-subtitle">
        Objetivo real de esta clase: entender por que mover la logica a <code>Alpine.data()</code>
        hace que el KDS sea mas legible, mas mantenible y menos fragil que dejar todo dentro del HTML.
    </p>

    <details class="lesson-panel">
        <summary>Desplegar clase teorica: conceptos, dudas y trampas reales</summary>
        <div class="lesson-body">
            <div class="lesson-section">
                <h3>1. Que significa "registrar un componente"</h3>
                <p>
                    Escribir toda la logica en <code>x-data="{ ... }"</code> sirve para ejemplos pequenos.
                    En cuanto el componente crece, el HTML deja de ser una vista y se convierte en una mezcla
                    de estructura y logica. Registrar un componente con <code>Alpine.data()</code> es separar la receta
                    de la mesa donde se sirve.
                </p>
                <div class="lesson-note">
                    Idea clave: <code>Alpine.data('ModuloKDS', (...args) => ({ ... }))</code> no crea una instancia
                    en ese momento. Registra una fabrica. La instancia nace cuando el HTML ejecuta
                    <code>x-data="ModuloKDS(...)"</code>.
                </div>
                <table>
                    <tr style="background: rgba(0,0,0,0.2);">
                        <th>Accion</th>
                        <th>En Python</th>
                        <th>En Alpine.js</th>
                    </tr>
                    <tr>
                        <td><b>Definir el molde</b></td>
                        <td><code>class Comanda:</code></td>
                        <td><code>Alpine.data('ModuloKDS', (...args) => ({ ... }))</code></td>
                    </tr>
                    <tr style="background: rgba(0,0,0,0.2);">
                        <td><b>Crear una instancia</b></td>
                        <td><code>c1 = Comanda()</code></td>
                        <td><code>x-data="ModuloKDS(datosIniciales)"</code></td>
                    </tr>
                    <tr>
                        <td><b>Usar el estado</b></td>
                        <td><code>c1.plato = "Pizza"</code></td>
                        <td><code>this.comandas.push(...)</code></td>
                    </tr>
                </table>
            </div>

            <div class="lesson-section">
                <h3>2. Reactividad: por que Alpine te ahorra trabajo real</h3>
                <p>
                    Con JavaScript puro acabas buscando nodos, creando elementos y sincronizando el DOM manualmente.
                    En un KDS eso escala mal. Alpine te deja declarar la relacion entre datos y vista. Cambias el array,
                    y Alpine vuelve a pintar lo que corresponda.
                </p>
                <div class="lesson-note">
                    Traduccion mental util: en Vanilla JS describes pasos del DOM; en Alpine describes estado y reglas de render.
                </div>
            </div>

            <div class="lesson-section">
                <h3>3. Arrays, objetos y el peligro del hueco</h3>
                <p>
                    El backend y el frontend se entienden gracias a <code>json_encode()</code>, pero hay una trampa comun.
                </p>
                <ul>
                    <li><b>Array indexado:</b> <code>['A', 'B']</code> en PHP se convierte en <code>["A", "B"]</code> en JSON/JS.</li>
                    <li><b>Array asociativo:</b> <code>['id' => 1]</code> en PHP se convierte en <code>{"id": 1}</code> en JSON/JS.</li>
                    <li><b>Peligro real:</b> si las claves numericas dejan de ser secuenciales, <code>json_encode()</code> ya no genera un array JSON, sino un objeto JSON. Eso rompe expectativas en <code>x-for</code>.</li>
                </ul>
                <div class="lesson-note">
                    Solucion practica: si borraste elementos y vas a reenviar la lista desde PHP, usa <code>array_values($lista)</code>
                    antes de convertirla a JSON.
                </div>
            </div>

            <div class="lesson-section">
                <h3>4. Diccionario rapido de directivas</h3>
                <table>
                    <tr style="background: rgba(0,0,0,0.2);">
                        <th>Metafora</th>
                        <th>Alpine.js</th>
                        <th>Vanilla JS</th>
                    </tr>
                    <tr>
                        <td><b>El cerebro</b></td>
                        <td><code>x-data="{ count: 0 }"</code></td>
                        <td><code>let state = { count: 0 }</code></td>
                    </tr>
                    <tr style="background: rgba(0,0,0,0.2);">
                        <td><b>El altavoz</b></td>
                        <td><code>x-text="count"</code></td>
                        <td><code>el.innerText = state.count</code></td>
                    </tr>
                    <tr>
                        <td><b>La fotocopiadora</b></td>
                        <td><code>x-for="item in lista"</code></td>
                        <td><code>lista.forEach(...)</code></td>
                    </tr>
                    <tr style="background: rgba(0,0,0,0.2);">
                        <td><b>La oreja</b></td>
                        <td><code>@click="sumar()"</code></td>
                        <td><code>el.addEventListener('click', ...)</code></td>
                    </tr>
                    <tr>
                        <td><b>El interruptor</b></td>
                        <td><code>x-show="abierto"</code></td>
                        <td><code>el.style.display = abierto ? '' : 'none'</code></td>
                    </tr>
                </table>
            </div>

            <div class="lesson-section">
                <h3>5. Que gana y que pierde esta arquitectura</h3>
                <table>
                    <tr style="background: rgba(0,0,0,0.2);">
                        <th>Pros</th>
                        <th>Contras</th>
                    </tr>
                    <tr>
                        <td>
                            HTML mas limpio.<br>
                            Logica reutilizable.<br>
                            Cero build step.<br>
                            Mejor punto medio entre PHP tradicional y JS reactivo.
                        </td>
                        <td>
                            Hay que saltar entre HTML y JS.<br>
                            Extraer demasiado pronto puede ser sobreingenieria.<br>
                            Si no nombras bien los metodos, pierdes claridad.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </details>

    <div class="grid">
        <div class="box" x-data="ModuloKDS(<?php echo htmlspecialchars($json_datos, ENT_QUOTES, 'UTF-8'); ?>)">
            <h2 style="margin-top: 0; color: #10b981;">
                Simulador KDS
                <span class="badge" x-text="comandas.length + ' pendientes'"></span>
            </h2>

            <p style="color: #cbd5e1; line-height: 1.6;">
                Esta demo responde a la duda central de la fase 2: el HTML ya no decide como crear o borrar comandas.
                Solo invoca metodos del modulo.
            </p>

            <button class="btn" style="width: 100%; margin-bottom: 20px;" @click="nuevaComanda()">
                + Entra comanda aleatoria
            </button>

            <div style="min-height: 100px;">
                <template x-for="item in comandas" :key="item.id">
                    <div class="kds-card" 
                         x-transition:enter.duration.500ms.scale.0
                         x-transition:leave.duration.400ms.scale.0.opacity.0>
                        <div>
                            <strong x-text="item.mesa" style="color: #38bdf8;"></strong><br>
                            <span x-text="item.plato"></span>
                        </div>
                        <button class="btn btn-del" @click="marcarListo(item.id)">Entregar</button>
                    </div>
                </template>

                <div x-show="comandas.length === 0" 
                     x-transition.duration.800ms
                     style="text-align: center; color: #64748b; padding: 20px;">
                    No hay comandas pendientes. Buen trabajo.
                </div>
            </div>

            <button class="btn" 
                    style="background: #475569; margin-top: 20px; font-size: 0.8rem;"
                    @click="mostrarJson = !mostrarJson">
                <span x-text="mostrarJson ? 'Ocultar JSON' : 'Ver JSON'"></span>
            </button>

            <div class="code-block" 
                 x-show="mostrarJson"
                 x-transition:enter.duration.400ms.origin.top
                 x-transition:leave.duration.200ms
                 style="border-color: #f59e0b;">
                <div class="code-header" style="color: #f59e0b;">
                    <span>Visor de estado</span>
                    <span>JSON real del array</span>
                </div>
                <pre x-text="JSON.stringify(comandas, null, 2)"></pre>
            </div>
        </div>

        <div>
            <div class="code-block" style="margin-top: 0;">
                <div class="code-header">
                    <span>JS real ejecutado por Alpine.data()</span>
                    <span>app.js conceptual</span>
                </div>
                <pre>document.addEventListener('alpine:init', () => {
    Alpine.data('ModuloKDS', (datosIniciales) => ({
        comandas: datosIniciales,
        mostrarJson: false, // <-- Nuevo estado para el visor

        nuevaComanda() {
            this.comandas.push({
                id: Date.now(),
                mesa: 'Mesa ' + Math.floor(Math.random() * 10 + 1),
                plato: 'Bravas Especiales'
            });
        },

        marcarListo(idABorrar) {
            this.comandas = this.comandas.filter(
                (comanda) => comanda.id !== idABorrar
            );
        }
    }));
});</pre>
            </div>

            <div class="box mini-block">
                <h3 style="margin-top: 0; color: #f472b6;">Dudas resueltas en esta sesion</h3>
                <ul class="question-list">
                    <li><code>json_encode()</code> es el puente entre PHP y Alpine.</li>
                    <li><code>Alpine.data()</code> registra una fabrica, no una instancia unica global.</li>
                    <li><code>x-transition</code> añade animaciones sin CSS externo.</li>
                    <li><code>x-data="ModuloKDS(...)"</code> ejecuta esa fabrica y crea el estado del bloque.</li>
                    <li>Modificar <code>this.comandas</code> hace que Alpine recalcule la vista.</li>
                </ul>
            </div>

            <div class="box mini-block">
                <h3 style="margin-top: 0; color: #a7f3d0;">Diccionario rapido de arquitectura</h3>
                <table>
                    <tr>
                        <th>Concepto</th>
                        <th>Vanilla o React</th>
                        <th>Python</th>
                        <th>Alpine.js</th>
                    </tr>
                    <tr>
                        <td><b>Definir logica</b></td>
                        <td><code>class KDS {}</code></td>
                        <td><code>class KDS:</code></td>
                        <td><code>Alpine.data('KDS', ...)</code></td>
                    </tr>
                    <tr>
                        <td><b>Crear una instancia</b></td>
                        <td><code>new KDS()</code></td>
                        <td><code>KDS()</code></td>
                        <td><code>x-data="KDS(...)"</code></td>
                    </tr>
                    <tr>
                        <td><b>Animar cambios</b></td>
                        <td>CSS Transitions / Framer</td>
                        <td>-</td>
                        <td><code>x-transition</code></td>
                    </tr>
                    <tr>
                        <td><b>Reflejar cambios</b></td>
                        <td><code>render()</code> manual o framework</td>
                        <td>-</td>
                        <td><code>x-text</code>, <code>x-show</code>, <code>:class</code></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ModuloKDS', (datosIniciales) => ({
                comandas: datosIniciales,
                mostrarJson: false,

                nuevaComanda() {
                    this.comandas.push({
                        id: Date.now(),
                        mesa: 'Mesa ' + Math.floor(Math.random() * 10 + 1),
                        plato: 'Bravas Especiales'
                    });
                },

                marcarListo(idABorrar) {
                    this.comandas = this.comandas.filter(
                        (comanda) => comanda.id !== idABorrar
                    );
                }
            }));
        });
    </script>
</body>
</html>
