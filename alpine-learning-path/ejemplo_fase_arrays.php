<?php
/**
 * 📦 1. ARRAY ASOCIATIVO (El "Paquete" con etiquetas)
 * En lugar de posiciones 0, 1, 2... usamos nombres.
 */
$una_comanda = [
    "id" => 101,
    "mesa" => "Mesa 5",
    "pedido" => "Hamburguesa XL",
    "estado" => "cocina"
];

/**
 * 📚 2. ARRAY DE ARRAYS (La "Colección")
 * Es lo que nos devuelve la base de datos (fetchArray).
 * Es una lista donde cada elemento es un paquete (un array asociativo).
 */
$listado_comandas = [
    [
        "id" => 101,
        "mesa" => "Mesa 5",
        "pedido" => "Hamburguesa XL",
        "estado" => "cocina"
    ],
    [
        "id" => 102,
        "mesa" => "Barra 2",
        "pedido" => "Caña de lomo",
        "estado" => "pendiente"
    ]
];

/**
 * 🧬 3. EL TRADUCTOR (json_encode)
 * PHP 'Asociativo' -> se convierte en -> JS 'Objeto' { }
 * PHP 'Indexado'   -> se convierte en -> JS 'Array'  [ ]
 */
$json_para_alpine = json_encode($listado_comandas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clase de Arrays: PHP a Alpine</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #111827; color: #e5e7eb; padding: 40px; }
        .grid { display: gap; gap: 20px; max-width: 800px; margin: auto; }
        .bloque { background: #1f2937; padding: 20px; border-radius: 12px; border: 1px solid #374151; margin-bottom: 20px; }
        h3 { color: #10b981; margin-top: 0; }
        pre { background: #000; padding: 15px; border-radius: 8px; color: #34d399; overflow-x: auto; }
        .badge { background: #3b82f6; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
    </style>
</head>
<body>

    <div class="grid" x-data="{ 
        datos: <?php echo htmlspecialchars($json_para_alpine, ENT_QUOTES, 'UTF-8'); ?> 
    }">

        <!-- EXPLICACIÓN 1 -->
        <div class="bloque">
            <h3>🧠 Concepto: El Array Asociativo</h3>
            <p>En PHP lo definimos así:</p>
            <pre>
$comanda = [
  "mesa" => "Mesa 5", 
  "pedido" => "Hamburguesa"
];</pre>
            <p>Acceso en PHP: <code>$comanda['mesa']</code></p>
        </div>


        <!-- EXPLICACIÓN 2 -->
        <div class="bloque">
            <h3>🚀 Resultado en Alpine.js</h3>
            <p>Al pasar por <code>json_encode</code>, Alpine lo recibe como objetos:</p>
            
            <ul>
                <template x-for="item in datos">
                    <li style="margin-bottom: 10px;">
                        <span>En la </span>
                        <b x-text="item.mesa"></b>
                        <span> han pedido: </span>
                        <span class="badge" x-text="item.pedido"></span>
                        <br>
                        <small style="color: #9ca3af;">
                            Acceso en JS: <code x-text="'item.' + Object.keys(item)[1]"></code>
                        </small>
                    </li>
                </template>
            </ul>
        </div>


        <!-- VISOR TDAH -->
        <div class="bloque" style="border-color: #f59e0b;">
            <h3>🔍 Visor de Datos (La Verdad Desnuda)</h3>
            <p>Esto es lo que PHP le ha "inyectado" a Alpine:</p>
            <pre x-text="JSON.stringify(datos, null, 2)"></pre>
        </div>

    </div>

</body>
</html>
