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

if (isset($_POST['action']) && $_POST['action'] == 'add_random') {
    $mesa = $mesas[array_rand($mesas)];
    $orderId = 'O-' . rand(100, 999);
    $numItems = rand(1, 3);
    
    for ($i = 0; $i < $numItems; $i++) {
        $prod = $products[array_rand($products)];
        $id = uniqid('i');
        $timestamp = time() * 1000;
        $notes = json_encode(rand(0, 1) > 0.8 ? ["Poco hecho", "Sin sal"][array_rand(["Poco hecho", "Sin sal"])] : []);
        
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
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS Simulator</title>
    <style>
        body { font-family: sans-serif; background: #1a202c; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; }
        button { padding: 20px 40px; font-size: 20px; background: #ed8936; color: white; border: none; border-radius: 10px; cursor: pointer; }
        button:hover { background: #dd6b20; }
    </style>
</head>
<body>
    <form method="POST">
        <input type="hidden" name="action" value="add_random">
        <button type="submit">🚀 LANZAR NUEVA COMANDA</button>
    </form>
</body>
</html>