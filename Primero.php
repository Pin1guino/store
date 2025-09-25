<?php
$db = new SQLite3('db.sqlite');

// Crear tabla si no existe
$db->exec("CREATE TABLE IF NOT EXISTS productos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT,
    categoria TEXT,
    precio REAL,
    stock INTEGER,
    stock_minimo INTEGER
)");

// Insertar producto (ejemplo)
$db->exec("INSERT INTO productos (nombre, categoria, precio, stock, stock_minimo)
           VALUES ('Alfajor', 'Golosinas', 150.00, 20, 5)");

// Listar productos
$result = $db->query("SELECT * FROM productos");
while ($row = $result->fetchArray()) {
    echo "{$row['nombre']} - Stock: {$row['stock']}<br>";
}
?>