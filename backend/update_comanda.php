<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$db = new SQLite3('kds_database.db');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ids']) && isset($data['estado'])) {
    $estado = $data['estado'];
    $timestamp = time() * 1000;
    
    foreach ($data['ids'] as $id) {
        $stmt = $db->prepare("UPDATE comandas SET estado = ?, estado_timestamp = ? WHERE id = ?");
        $stmt->bindValue(1, $estado);
        $stmt->bindValue(2, $timestamp);
        $stmt->bindValue(3, $id);
        $stmt->execute();
    }
    
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>