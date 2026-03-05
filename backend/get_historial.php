<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

try {
    $db = new SQLite3('kds_local.sqlite');
    
    // Set busy timeout in case db is locked
    $db->busyTimeout(5000);

    // Get the last 200 finished items
    $results = $db->query("SELECT * FROM comandas WHERE estado = 'listo' ORDER BY estado_timestamp DESC LIMIT 200");
    
    $items = [];
    if ($results) {
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $row['notes'] = json_decode($row['notes'], true);
            $items[] = $row;
        }
    }
    
    echo json_encode($items);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>