<?php
/**
 * 🛠️ DATOS REALES (PHP)
 * Imaginamos que vienen de SQLite.
 */
$listado = [
    ["producto" => "Pizza", "mesa" => "Mesa 1"],
    ["producto" => "Pasta", "mesa" => "Mesa 5"]
];
$json_comandas = json_encode($listado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fase 2: Cerebro Externo (Alpine.data)</title>
    
    <!-- 📦 LIBRERÍA Y CEREBRO -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="js/fase_2.js"></script>

    <style>
        body { font-family: sans-serif; background: #0f172a; color: white; padding: 2rem; }
        .tarjeta { background: #1e293b; padding: 2rem; border-radius: 12px; max-width: 500px; margin: auto; }
        .contador { background: #4f46e5; padding: 2px 8px; border-radius: 99px; font-size: 0.8rem; }
        button { width: 100%; padding: 12px; background: #334155; color: white; border: none; border-radius: 8px; cursor: pointer; margin-top: 1rem; }
    </style>
</head>
<body>

    <!-- 🚀 USAMOS EL CEREBRO REGISTRADO EN EL JS -->
    <!-- Pasamos los datos de PHP como argumento a KdsApp -->
    <div class="tarjeta" 
         x-data="KdsApp(<?php echo htmlspecialchars($json_comandas, ENT_QUOTES, 'UTF-8'); ?>)">

        <h2 style="color: #34d399; margin-top: 0;">
            KDS Limpio 
            <span class="contador" x-text="totalComandas"></span>
        </h2>

        <!-- 🔄 EL BUCLE -->
        <ul>
            <template x-for="item in comandas">
                <li style="margin-bottom: 8px;">
                    <span x-text="item.producto"></span>
                    <small x-text="'(' + item.mesa + ')'" style="color: #94a3b8;"></small>
                </li>
            </template>
        </ul>

        <!-- ⚡ ACCIÓN (Lógica está en el JS) -->
        <button @click="toggleJson()">
            <span x-text="mostrarJson ? '🙈 Ocultar' : '👁️ Ver JSON'"></span>
        </button>

        <!-- 📦 VISOR JSON -->
        <pre x-show="mostrarJson" 
             x-text="JSON.stringify(comandas, null, 2)" 
             style="background: black; padding: 10px; font-size: 0.7rem; color: #34d399;"></pre>

    </div>

</body>
</html>
