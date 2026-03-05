<?php
/**
 * 🛠️ CONEXIÓN A BASE DE DATOS (PHP)
 */
$db = new SQLite3('../backend/kds_local.sqlite');

/**
 * 📝 CONSULTA (SQL)
 */
$query = "SELECT producto, estado FROM comandas WHERE estado != 'listo' LIMIT 10";
$results = $db->query($query);

/**
 * 🍱 PREPARACIÓN (Array PHP)
 */
$lista_comandas = [];
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $lista_comandas[] = $row;
}

/**
 * 🧬 TRADUCCIÓN (JSON)
 */
$json_comandas = json_encode($lista_comandas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fase PHP + Alpine (Conexión Real)</title>
    
    <!-- 📦 LIBRERÍA -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: sans-serif; background: #0f172a; color: white; padding: 2rem; }
        .tarjeta { background: #1e293b; padding: 2rem; border-radius: 12px; max-width: 500px; margin: auto; }
        
        ul { list-style: none; padding: 0; }
        
        li { 
            background: #334155; 
            margin: 10px 0; 
            padding: 1rem; 
            border-radius: 8px; 
            display: flex; 
            justify-content: space-between;
        }

        .badge { 
            font-size: 0.7rem; 
            padding: 4px 8px; 
            border-radius: 4px; 
            text-transform: uppercase;
            font-weight: bold;
        }

        .badge-pendiente { background: #64748b; }
        .badge-horno { background: #f59e0b; }
        .badge-cocina { background: #ef4444; }

        /* Estilo para el visor de JSON */
        .visor-json {
            background: #000;
            color: #34d399;
            padding: 1rem;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.85rem;
            margin-top: 1rem;
            white-space: pre-wrap;
            border: 1px solid #334155;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 1rem;
        }

        button:hover { background: #4338ca; }
    </style>
</head>
<body>


    <div class="tarjeta" 
         x-data="{ 
            /* 🚀 EL PUENTE (PHP -> Alpine) */
            comandas: <?php echo htmlspecialchars($json_comandas, ENT_QUOTES, 'UTF-8'); ?>,
            
            /* 👁️ VISIBILIDAD DEL JSON */
            mostrarJson: false
         }"
    >

        <h2 style="color: #34d399; margin-top: 0;">Datos Reales (SQLite)</h2>


        <!-- 🔄 EL BUCLE (Pintando el HTML) -->
        <ul>
            <template x-for="item in comandas">
                <li>
                    <span x-text="item.producto"></span>
                    <span class="badge" :class="'badge-' + item.estado" x-text="item.estado"></span>
                </li>
            </template>
        </ul>



        <!-- ⚡ ACCIÓN: VER EL JSON CRUDO -->
        <button @click="mostrarJson = !mostrarJson">
            <span 
            x-text=
                "mostrarJson ? '🙈 Ocultar JSON Crudo' : 
            '👁️ Ver JSON Crudo (PHP Output)'"></span>
        </button>



        <!-- 📦 EL VISOR (Solo se ve si mostrarJson es true) -->
        <!-- JSON.stringify(comandas, null, 2) formatea el JSON para que sea legible -->
        <div class="visor-json" x-show="mostrarJson" x-cloak>
            <p style="color: #94a3b8; margin-top: 0; font-size: 0.7rem;">// Este es el array que PHP ha inyectado en Alpine:</p>
            <pre x-text="JSON.stringify(comandas, null, 3)"></pre>
        </div>


    </div>


</body>
</html>