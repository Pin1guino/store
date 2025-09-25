<?php
session_start();
include("conexion.php");

$mensaje = "";
if (isset($_POST['agregar_carrito'])) {
    $id_cliente = $_SESSION['cliente_id'] ?? null;
    $id_producto = $_POST['producto'];

    if ($id_cliente) {
        // Verificar si ya está en carrito, si sí, aumentar cantidad
        $sql_check = "SELECT cantidad FROM carrito WHERE id_clie = ? AND id_produc = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $id_cliente, $id_producto);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result();

        if ($res_check->num_rows > 0) {
            $row = $res_check->fetch_assoc();
            $nueva_cantidad = $row['cantidad'] + 1;
            $sql_update = "UPDATE carrito SET cantidad = ? WHERE id_clie = ? AND id_produc = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("iii", $nueva_cantidad, $id_cliente, $id_producto);
            $stmt_update->execute();
        } else {
            $sql_insert = "INSERT INTO carrito (id_clie, id_produc, cantidad) VALUES (?, ?, 1)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ii", $id_cliente, $id_producto);
            $stmt_insert->execute();
        }
        $mensaje = "Producto agregado al carrito.";
    } else {
        $mensaje = "Debes iniciar sesión para agregar productos.";
    }
}

function contarProductosCarrito() {
    include("conexion.php");
    if(isset($_SESSION['cliente_id'])) {
        $id_cliente = $_SESSION['cliente_id'];
        $sql = "SELECT SUM(cantidad) as total FROM carrito WHERE id_clie = $id_cliente";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
    return 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Comprar Prendas - Tienda de Ropa</title>
<link rel="stylesheet" href="estilos.css" />
<style>
.producto-carrusel {
    cursor: default;
}

.producto-carrusel form {
    margin-top: 10px;
}

.mensaje {
    text-align: center;
    font-weight: 600;
    color: green;
    margin: 15px 0;
}

/* Estilos del carrito */
.carrito-icono {
    cursor: pointer;
    position: relative;
    margin-left: 20px;
    font-size: 1.5rem;
    display: inline-block;
}

.contador-carrito {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 0.8rem;
}

.carrito-flotante {
    position: fixed;
    top: 80px;
    right: 20px;
    width: 300px;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    border-radius: 10px;
    padding: 15px;
    z-index: 1000;
    display: none;
}

.carrito-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.carrito-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-right: 10px;
    border-radius: 5px;
}

.carrito-total {
    font-weight: bold;
    text-align: right;
    margin-top: 10px;
}

.ver-carrito-btn {
    display: block;
    text-align: center;
    background: #27ae60;
    color: white;
    padding: 8px;
    border-radius: 5px;
    margin-top: 10px;
    text-decoration: none;
}

.btn-agregar-carrito {
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-agregar-carrito:hover {
    background-color: #219150;
}
</style>
</head>
<body>
    
<header>
    <h1>Tienda de Ropa</h1>
    <nav>
        <a href="index.php" class="active">Inicio</a>
        <a href="ingresar.php">Ingresar Prenda</a>
        <?php if (isset($_SESSION['cliente_id'])): ?>
            <div class="carrito-icono" onclick="toggleCarrito()">
                🛒 <span class="contador-carrito"><?php echo contarProductosCarrito(); ?></span>
            </div>
            <span style="margin-left: auto; color: #2ecc71; font-weight: bold;">
                ✔ Sesión iniciada como: <?php echo htmlspecialchars($_SESSION['cliente_nombre']); ?>
            </span>
            <a href="logout.php" style="margin-left: 15px;">Cerrar sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Crear cuenta</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <h2>Prendas Disponibles</h2>
    <?php if (isset($mensaje)) echo "<p class='mensaje'>$mensaje</p>"; ?>
    <div class="carrusel-container">
        <button class="btn-scroll left" id="btnIzquierda">❮</button>
        <div class="carrusel" id="carruselProductos">
            <?php
            $sql = "SELECT * FROM productos ORDER BY ID_produc DESC";
            $resultado = $conn->query($sql);
            while ($fila = $resultado->fetch_assoc()) {
                $id = $fila['ID_produc'];
                $nombre = htmlspecialchars($fila['nombre']);
                $precio = number_format($fila['precio'], 2, ',', '.');
                $categoria = htmlspecialchars($fila['categoria']);
                $stock = intval($fila['stock']);
                $imagen = htmlspecialchars($fila['imagen']);
                echo <<<HTML
                <article class="producto-carrusel">
                    <img src="img/$imagen" alt="$nombre" loading="lazy" />
                    <div class="info-producto">
                        <h3>$nombre</h3>
                        <p><strong>Precio:</strong> $$precio</p>
                        <p><strong>Categoría:</strong> $categoria</p>
                        <p><strong>Stock:</strong> $stock unidades</p>
                HTML;

                if (isset($_SESSION['cliente_id'])) {
                    echo <<<FORM
                        <form method="POST" action="index.php">
                            <input type="hidden" name="producto" value="$id" />
                            <button type="submit" name="agregar_carrito" class="btn-agregar-carrito" onclick="actualizarCarrito()">Agregar al carrito</button>
                        </form>
                    FORM;
                } else {
                    echo "<p style='color: gray;'>Iniciá sesión para comprar</p>";
                }

                echo "</div></article>";
            }
            ?>
        </div>
        <button class="btn-scroll right" id="btnDerecha">❯</button>
    </div>
</main>

<div class="carrito-flotante" id="carritoFlotante">
    <h3>Tu Carrito</h3>
    <div id="lista-carrito">
        <?php
        if(isset($_SESSION['cliente_id'])) {
            $id_cliente = $_SESSION['cliente_id'];
            $sql = "SELECT p.ID_produc, p.nombre, p.precio, p.imagen, c.cantidad 
                    FROM carrito c 
                    JOIN productos p ON c.id_produc = p.ID_produc 
                    WHERE c.id_clie = $id_cliente";
            $result = $conn->query($sql);
            
            if($result->num_rows > 0) {
                $total = 0;
                while($fila = $result->fetch_assoc()) {
                    $subtotal = $fila['precio'] * $fila['cantidad'];
                    $total += $subtotal;
                    echo '<div class="carrito-item">
                            <img src="img/'.htmlspecialchars($fila['imagen']).'" alt="'.htmlspecialchars($fila['nombre']).'">
                            <div>
                                <h4>'.htmlspecialchars($fila['nombre']).'</h4>
                                <p>$'.number_format($fila['precio'], 2, ',', '.').' × '.$fila['cantidad'].'</p>
                            </div>
                          </div>';
                }
                echo '<div class="carrito-total">Total: $'.number_format($total, 2, ',', '.').'</div>';
            } else {
                echo '<p>Tu carrito está vacío</p>';
            }
        }
        ?>
    </div>
    <a href="carrito.php" class="ver-carrito-btn">Ver Carrito Completo</a>
</div>

<script>
// Mostrar/ocultar carrito
function toggleCarrito() {
    const carrito = document.getElementById('carritoFlotante');
    carrito.style.display = carrito.style.display === 'block' ? 'none' : 'block';
    
    // Actualizar el carrito cada vez que se muestra
    if(carrito.style.display === 'block') {
        actualizarCarrito();
    }
}

// Cerrar carrito al hacer clic fuera
document.addEventListener('click', function(event) {
    const carrito = document.getElementById('carritoFlotante');
    const icono = document.querySelector('.carrito-icono');
    
    if (!carrito.contains(event.target) && !icono.contains(event.target)) {
        carrito.style.display = 'none';
    }
});

// Actualizar carrito dinámicamente
function actualizarCarrito() {
    fetch('actualizar_carrito.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('lista-carrito').innerHTML = data;
            actualizarContador();
        });
}

// Actualizar contador
function actualizarContador() {
    fetch('contar_carrito.php')
        .then(response => response.json())
        .then(data => {
            document.querySelector('.contador-carrito').textContent = data.total;
        });
}

// Carrusel de productos
const carrusel = document.getElementById('carruselProductos');
document.getElementById('btnIzquierda').onclick = () => carrusel.scrollBy({ left: -300, behavior: 'smooth' });
document.getElementById('btnDerecha').onclick = () => carrusel.scrollBy({ left: 300, behavior: 'smooth' });

// Actualizar carrito al agregar producto
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[action="index.php"]');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            setTimeout(() => {
                actualizarCarrito();
            }, 500);
        });
    });
});
</script>
</body>
</html>