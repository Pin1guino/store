<?php
session_start();
include("conexion.php");

$response = ['total' => 0];

if(isset($_SESSION['cliente_id'])) {
    $id_cliente = $_SESSION['cliente_id'];
    $sql = "SELECT SUM(cantidad) as total FROM carrito WHERE id_clie = $id_cliente";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $response['total'] = $row['total'] ?? 0;
}

header('Content-Type: application/json');
echo json_encode($response);
?>