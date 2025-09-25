<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO clientes (Nombre, Dni, tel, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $nombre, $dni, $tel, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php?registrado=1");
        exit();
    } else {
        $error = "Error al crear la cuenta. ¿Email o DNI ya están en uso?";
    }
}
?>
<div class="container">


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="auth.css" />

</head>
<body>
<h2>Crear Cuenta</h2>
<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required><br>
    <input type="number" name="dni" placeholder="DNI" required><br>
    <input type="number" name="tel" placeholder="Teléfono" required><br>
    <input type="email" name="email" placeholder="Correo electrónico" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button type="submit">Registrarme</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<a href="login.php">¿Ya tenés cuenta? Iniciar sesión</a>
</body>
</html>
</div>