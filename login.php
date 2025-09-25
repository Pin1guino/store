<?php
session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $clave = $_POST['password'];

    $stmt = $conn->prepare("SELECT ID_Clie, Nombre, password FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $cliente = $resultado->fetch_assoc();

        if (password_verify($clave, $cliente['password'])) {
            $_SESSION['cliente_id'] = $cliente['ID_Clie'];
            $_SESSION['cliente_nombre'] = $cliente['Nombre'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Email no registrado.";
    }
}
?>

<div class="container">
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="auth.css" />

    
</head>
<body>
<h2>Iniciar Sesión</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Correo electrónico" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button type="submit">Entrar</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<a href="registro.php">¿No tenés cuenta? Registrate</a>
</body>
</html>
</div>