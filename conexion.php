<?php
$host = "localhost";
$user = "root";
$password = ""; // En XAMPP normalmente no hay contraseña
$db = "tienda_de_ropa"; // Nombre de tu base de datos

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    
}
?>
