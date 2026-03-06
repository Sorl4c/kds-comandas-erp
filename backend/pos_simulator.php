<?php
$db = new SQLite3('kds_local.sqlite');

// Asegurar que la tabla existe
$db->exec("CREATE TABLE IF NOT EXISTS comandas (
    id TEXT PRIMARY KEY,
    orderId TEXT,
    mesa TEXT,
    producto TEXT,
    station TEXT,
    estado TEXT,
    estado_timestamp INTEGER,
    estimated_time INTEGER,
    notes TEXT
)");

$products = [
    ['n' => 'Burger Smash', 's' => 'cocina', 'e' => 10],
    ['n' => 'Patatas Bravas', 's' => 'cocina', 'e' => 8],
    ['n' => 'Ensalada César', 's' => 'barra', 'e' => 5],
    ['n' => 'Pizza Margarita', 's' => 'horno', 'e' => 12],
    ['n' => 'Tarta de Queso', 's' => 'barra', 'e' => 4],
    ['n' => 'Vino Tinto', 's' => 'barra', 'e' => 2]
];

$mesas = ['Mesa 01', 'Mesa 05', 'Terraza 02', 'Barra 01', 'Mesa 12'];

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add_random') {
        $mesa = $mesas[array_rand($mesas)];
        $orderId = 'O-' . rand(100, 999);
        $numItems = rand(1, 3);
        
        for ($i = 0; $i < $numItems; $i++) {
            $prod = $products[array_rand($products)];
            $id = uniqid('i');
            $timestamp = time() * 1000;
            $notes = rand(0, 1) > 0.8 ? json_encode(["Poco hecho"]) : json_encode([]);
            
            $stmt = $db->prepare("INSERT INTO comandas (id, orderId, mesa, producto, station, estado, estado_timestamp, estimated_time, notes) VALUES (?, ?, ?, ?, ?, 'pendiente', ?, ?, ?)");
            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $orderId);
            $stmt->bindValue(3, $mesa);
            $stmt->bindValue(4, $prod['n']);
            $stmt->bindValue(5, $prod['s']);
            $stmt->bindValue(6, $timestamp);
            $stmt->bindValue(7, $prod['e']);
            $stmt->bindValue(8, $notes);
            $stmt->execute();
        }
    } elseif ($_POST['action'] == 'add_complex') {
        // Escenario complejo: Una mesa con 2 comandas. Una parcialmente adelantada, platos duplicados con notas.
        $mesa = 'Mesa ' . rand(20, 50);
        
        // Comanda 1: Múltiples platos iguales, mezcla de estaciones, parcialmente en cocina
        $orderId1 = 'O-C1-' . rand(100, 999);
        $timestamp = time() * 1000 - 600000; // 10 mins ago
        
        $items1 = [
            ['p' => $products[0], 'estado' => 'cocina', 'qty' => 2, 'notes' => ['Sin cebolla']], // 2x Burger en cocina
            ['p' => $products[2], 'estado' => 'emplatado', 'qty' => 1, 'notes' => []],           // 1x Ensalada en emplatado
            ['p' => $products[5], 'estado' => 'listo', 'qty' => 1, 'notes' => []]                // 1x Vino ya listo
        ];
        
        foreach ($items1 as $it) {
            for ($i = 0; $i < $it['qty']; $i++) {
                $stmt = $db->prepare("INSERT INTO comandas (id, orderId, mesa, producto, station, estado, estado_timestamp, estimated_time, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, uniqid('i'));
                $stmt->bindValue(2, $orderId1);
                $stmt->bindValue(3, $mesa);
                $stmt->bindValue(4, $it['p']['n']);
                $stmt->bindValue(5, $it['p']['s']);
                $stmt->bindValue(6, $it['estado']);
                $stmt->bindValue(7, $timestamp);
                $stmt->bindValue(8, $it['p']['e']);
                $stmt->bindValue(9, json_encode($it['notes']));
                $stmt->execute();
            }
        }
        
        // Comanda 2: En la misma mesa, pero todo pendiente
        $orderId2 = 'O-C2-' . rand(100, 999);
        $timestamp2 = time() * 1000 - 120000; // 2 mins ago
        
        $items2 = [
            ['p' => $products[4], 'estado' => 'pendiente', 'qty' => 2, 'notes' => []] // 2x Tarta de Queso
        ];
        
        foreach ($items2 as $it) {
            for ($i = 0; $i < $it['qty']; $i++) {
                $stmt = $db->prepare("INSERT INTO comandas (id, orderId, mesa, producto, station, estado, estado_timestamp, estimated_time, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, uniqid('i'));
                $stmt->bindValue(2, $orderId2);
                $stmt->bindValue(3, $mesa);
                $stmt->bindValue(4, $it['p']['n']);
                $stmt->bindValue(5, $it['p']['s']);
                $stmt->bindValue(6, $it['estado']);
                $stmt->bindValue(7, $timestamp2);
                $stmt->bindValue(8, $it['p']['e']);
                $stmt->bindValue(9, json_encode($it['notes']));
                $stmt->execute();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS Simulator</title>
    <style>
        body { font-family: sans-serif; background: #1a202c; color: white; display: flex; flex-direction: column; gap: 20px; justify-content: center; align-items: center; height: 100vh; }
        button { padding: 20px 40px; font-size: 20px; background: #ed8936; color: white; border: none; border-radius: 10px; cursor: pointer; width: 350px; }
        button:hover { background: #dd6b20; }
        .btn-complex { background: #9f7aea; }
        .btn-complex:hover { background: #805ad5; }
    </style>
</head>
<body>
    <form method="POST">
        <input type="hidden" name="action" value="add_random">
        <button type="submit">🚀 LANZAR NUEVA COMANDA</button>
    </form>
    <form method="POST">
        <input type="hidden" name="action" value="add_complex">
        <button type="submit" class="btn-complex">🧪 ESCENARIO COMPLEJO</button>
    </form>
</body>
</html>