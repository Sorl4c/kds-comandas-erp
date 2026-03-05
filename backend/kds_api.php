<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('Access-Control-Allow-Origin: *');

// Desactivar límite de tiempo de ejecución
set_time_limit(0);

// Limpiar y desactivar cualquier buffer de salida previo
while (ob_get_level()) ob_end_clean();

$db = new SQLite3('kds_local.sqlite');

// Función para obtener todas las comandas activas
function getActiveComandas($db) {
    // Obtenemos las que no están listas, o las listas en los últimos 5 minutos
    $results = $db->query("SELECT * FROM comandas WHERE estado != 'listo' OR (strftime('%s','now')*1000 - estado_timestamp < 300000)");
    $items = [];
    if ($results) {
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $row['notes'] = json_decode($row['notes'], true);
            $items[] = $row;
        }
    }
    return $items;
}

$last_hash = '';
$last_heartbeat = time();

while (true) {
    $current_items = getActiveComandas($db);
    $current_hash = md5(json_encode($current_items));

    // Si hay cambios, enviamos los datos
    if ($current_hash !== $last_hash) {
        echo "data: " . json_encode($current_items) . "\n\n";
        flush();
        $last_hash = $current_hash;
        $last_heartbeat = time();
    } 
    // Si no hay cambios, enviamos un "latido" cada 10 seg para mantener la conexión
    else if (time() - $last_heartbeat > 10) {
        echo ": heartbeat\n\n";
        flush();
        $last_heartbeat = time();
    }

    // Esperar 1 segundo antes de volver a mirar la base de datos
    sleep(1);
}
?>