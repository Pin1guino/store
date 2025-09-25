<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION['cliente_id'];
$mensaje = "";

// Procesar compra final
if (isset($_POST['comprar'])) {
    // Pasar productos del carrito a tabla compra
    $sql_carrito = "SELECT id_produc, cantidad FROM carrito WHERE id_clie = ?";
    $stmt_carrito = $conn->prepare($sql_carrito);
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $res_carrito = $stmt_carrito->get_result();

    if ($res_carrito->num_rows > 0) {
        $conn->begin_transaction();
        try {
            while ($item = $res_carrito->fetch_assoc()) {
                $id_produc = $item['id_produc'];
                $cantidad = $item['cantidad'];
                // Insertar tantas veces como cantidad (o cambiar lógica si quieres)
                for ($i = 0; $i < $cantidad; $i++) {
                    $stmt_compra = $conn->prepare("INSERT INTO compra (id_clie, id_produc) VALUES (?, ?)");
                    $stmt_compra->bind_param("ii", $id_cliente, $id_produc);
                    $stmt_compra->execute();
                }
            }
            // Vaciar carrito
            $stmt_del = $conn->prepare("DELETE FROM carrito WHERE id_clie = ?");
            $stmt_del->bind_param("i", $id_cliente);
            $stmt_del->execute();

            $conn->commit();
            $mensaje = "Compra realizada con éxito.";
        } catch (Exception $e) {
            $conn->rollback();
            $mensaje = "Error al procesar la compra.";
        }
    } else {
        $mensaje = "El carrito está vacío.";
    }
}

// Obtener productos en carrito para mostrar
$sql = "SELECT c.id_produc, p.nombre, p.precio, p.imagen, c.cantidad
        FROM carrito c
        JOIN productos p ON c.id_produc = p.ID_produc
        WHERE c.id_clie = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Carrito de Compras - Tienda de Ropa</title>
<link rel="stylesheet" href="estilos.css" />
</head>
<body>
<header>
    <h1>Tienda de Ropa</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="ingresar.php">Ingresar Prenda</a>
        <a href="carrito.php" class="active">Carrito</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
</header>
<main>
    <h2>Tu Carrito</h2>
    <?php if ($mensaje) echo "<p class='mensaje'>$mensaje</p>"; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($fila = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="img/<?php echo htmlspecialchars($fila['imagen']); ?>" alt="<?php echo htmlspecialchars($fila['nombre']); ?>" style="width:50px; height:50px; object-fit:cover;"></td>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td>$<?php echo number_format($fila['precio'],2,',','.'); ?></td>
                    <td><?php echo intval($fila['cantidad']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <form method="POST" style="margin-top: 20px;">
            <button type="submit" name="comprar">Comprar Todo</button>
        </form>
    <?php else: ?>
        <p>Tu carrito está vacío.</p>
    <?php endif; ?>
</main>
</body>
</html>
