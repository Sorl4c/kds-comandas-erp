<?php
/**
 * FASE 3: EL CONCEPTO DE GETTER AISLADO
 * Objetivo: Entender la diferencia entre una variable muerta, 
 * una función tradicional y un Getter (función viva).
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Masterclass: ¿Qué demonios es un Getter?</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: "Segoe UI", sans-serif; background: #0f172a; color: white; padding: 2rem; display: flex; flex-direction: column; gap: 2rem; align-items: center; }
        .box { background: #1e293b; padding: 2rem; border-radius: 12px; border: 1px solid #334155; width: 100%; max-width: 800px; }
        .btn { background: #38bdf8; color: #0f172a; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 1.1rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-top: 2rem; }
        .card { background: #334155; padding: 1.5rem; border-radius: 8px; text-align: center; }
        .valor { font-size: 2.5rem; font-weight: 900; margin: 1rem 0; color: #10b981; }
        .error { color: #ef4444; }
        pre { background: #020617; padding: 1rem; border-radius: 8px; color: #94a3b8; font-size: 0.9rem; text-align: left; }
        .highlight { color: #38bdf8; font-weight: bold; }
    </style>
</head>
<body x-data>

    <div style="text-align: center;">
        <h1 style="color: #38bdf8; margin-bottom: 0.5rem;">El Misterio de los Getters</h1>
        <p style="color: #94a3b8; font-size: 1.2rem;">Un getter es un <strong>Espejo Mágico</strong>. No tiene luz propia, refleja lo que tiene delante.</p>
    </div>

    <div class="box">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2>Array Real (La Verdad)</h2>
            <button class="btn" @click="$store.kds.añadir()">+ Añadir Ticket</button>
            <div style="margin-top: 1rem; font-size: 1.2rem;">
                Tickets actuales en el sistema: <strong x-text="$store.kds.tickets.length" style="color: #38bdf8; font-size: 1.5rem;"></strong>
            </div>
        </div>

        <div class="grid">
            <!-- CASO 1: Variable Estática -->
            <div class="card">
                <h3>1. Variable Normal</h3>
                <div class="valor error" x-text="$store.kds.variable_estatica"></div>
                <p style="font-size: 0.8rem; color: #cbd5e1;">Nació valiendo 2 y se quedó en 2. <strong>Está muerta.</strong> No se entera de que entraron más tickets.</p>
                <pre>variable: 2</pre>
                <code style="font-size: 0.8rem; color: #ef4444;">&lt;span x-text="variable"&gt;</code>
            </div>

            <!-- CASO 2: Función -->
            <div class="card">
                <h3>2. Función Normal</h3>
                <div class="valor" x-text="$store.kds.funcion_contar()"></div>
                <p style="font-size: 0.8rem; color: #cbd5e1;">Funciona bien, pero en el HTML tienes que ponerle los <strong>()</strong> porque le estás dando una orden: "¡Calcula!".</p>
                <pre>funcion() {
  return lista.length
}</pre>
                <code style="font-size: 0.8rem; color: #f59e0b;">&lt;span x-text="funcion<strong style="color:white">()</strong>"&gt;</code>
            </div>

            <!-- CASO 3: Getter -->
            <div class="card" style="border: 2px solid #38bdf8;">
                <h3>3. El Getter 👑</h3>
                <div class="valor" x-text="$store.kds.getter_contar"></div>
                <p style="font-size: 0.8rem; color: #cbd5e1;">Es igual que la función por dentro, pero en el HTML se lee como una variable limpia (sin paréntesis).</p>
                <pre><strong style="color: #38bdf8;">get</strong> contar() {
  return lista.length
}</pre>
                <code style="font-size: 0.8rem; color: #10b981;">&lt;span x-text="contar"&gt;</code>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('kds', {
                // El array original tiene 2 elementos al principio
                tickets: [ {id: 1}, {id: 2} ],

                añadir() {
                    this.tickets.push({ id: Date.now() });
                },

                // 1. VARIABLE: Se calcula una vez al nacer y NUNCA MÁS.
                variable_estatica: 2,

                // 2. FUNCIÓN: Bloque de código que tú ejecutas manualmente.
                funcion_contar() {
                    return this.tickets.length;
                },

                // 3. GETTER: Bloque de código que Alpine ejecuta automáticamente 
                // cuando alguien lo mira. ¡Fíjate en la palabra 'get'!
                get getter_contar() {
                    return this.tickets.length;
                }
            });
        });
    </script>
</body>
</html>