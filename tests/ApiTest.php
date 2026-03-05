<?php
// Script de prueba para las operaciones básicas de la base de datos
echo "Iniciando pruebas...\n";
echo "--------------------\n";

$test_db_file = 'test_kds.sqlite';
if (file_exists($test_db_file)) {
    unlink($test_db_file);
}

try {
    $db = new SQLite3($test_db_file);
    
    // Test 1: Crear tabla
    $query = "CREATE TABLE IF NOT EXISTS comandas (
        id TEXT PRIMARY KEY,
        orderId TEXT,
        mesa TEXT,
        producto TEXT,
        station TEXT,
        estado TEXT,
        estado_timestamp INTEGER,
        estimated_time INTEGER,
        notes TEXT
    )";
    $db->exec($query);
    echo "✔ Test 1: Tabla creada exitosamente.\n";

    // Test 2: Insertar comanda
    $id = 'test-id-123';
    $timestamp = time() * 1000;
    
    $stmt = $db->prepare("INSERT INTO comandas (id, orderId, mesa, producto, station, estado, estado_timestamp, estimated_time, notes) VALUES (?, ?, ?, ?, ?, 'pendiente', ?, ?, ?)");
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, 'O-TEST');
    $stmt->bindValue(3, 'Mesa Test');
    $stmt->bindValue(4, 'Burger Test');
    $stmt->bindValue(5, 'cocina');
    $stmt->bindValue(6, $timestamp);
    $stmt->bindValue(7, 10);
    $stmt->bindValue(8, json_encode(['Sin cebolla']));
    $result = $stmt->execute();
    
    if ($result) {
        echo "✔ Test 2: Comanda insertada exitosamente.\n";
    } else {
        echo "✖ Test 2 Falló: No se pudo insertar la comanda.\n";
    }

    // Test 3: Actualizar comanda a 'cocina'
    $new_timestamp = time() * 1000;
    $stmt_update = $db->prepare("UPDATE comandas SET estado = ?, estado_timestamp = ? WHERE id = ?");
    $stmt_update->bindValue(1, 'cocina');
    $stmt_update->bindValue(2, $new_timestamp);
    $stmt_update->bindValue(3, $id);
    $result_update = $stmt_update->execute();

    // Verificamos el cambio
    $res = $db->querySingle("SELECT estado FROM comandas WHERE id = 'test-id-123'", true);
    
    if ($res['estado'] === 'cocina') {
        echo "✔ Test 3: Estado de comanda actualizado exitosamente.\n";
    } else {
        echo "✖ Test 3 Falló: Estado esperado 'cocina', obtenido '" . $res['estado'] . "'.\n";
    }

    // Limpiar BD de prueba
    $db->close();
    unlink($test_db_file);
    echo "--------------------\n";
    echo "✔ Todas las pruebas finalizaron y el entorno se limpió correctamente.\n";

} catch (Exception $e) {
    echo "✖ Error durante las pruebas: " . $e->getMessage() . "\n";
}
?>