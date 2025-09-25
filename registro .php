<?php
include 'db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT)

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $clave);
    $stmt->execute();

    echo "Usuario registrado correctamente. <a href='login.php'>Iniciar sesión</a>";
}
?>

<!-- HTML -->
<form method="POST">
  <input type="text" name="nombre" placeholder="Nombre" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="clave" placeholder="Contraseña" required><br>
  <button type="submit">Registrarse</button>
</form>