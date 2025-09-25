<?php include("conexion.php"); ?>

<?php
// Procesar envío del formulario
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];

    $stmt = $conn->prepare("INSERT INTO productos (nombre, categoria, stock, precio, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssids", $nombre, $categoria, $stock, $precio, $imagen);

    if ($stmt->execute()) {
        $mensaje = '<div class="mensaje exito">Prenda ingresada correctamente.</div>';
    } else {
        $mensaje = '<div class="mensaje error">Error al guardar la prenda.</div>';
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingresar Prenda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        body {
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #e74c3c;
            padding: 20px 40px;
            color: white;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-weight: 900;
            font-size: 1.8rem;
            letter-spacing: 1px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-weight: 600;
            padding: 8px 15px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #c0392b;
        }

        nav a.active {
            background-color: white;
            color: #e74c3c;
            box-shadow: 0 2px 5px rgb(0 0 0 / 0.15);
        }

        .form-ingresar {
            background-color: #ffffff;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-ingresar h2 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.8rem;
            color: #e74c3c;
        }

        .form-ingresar label {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .form-ingresar input,
        .form-ingresar select {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            width: 100%;
            transition: border-color 0.3s;
        }

        .form-ingresar input:focus,
        .form-ingresar select:focus {
            border-color: #e74c3c;
            outline: none;
        }

        .form-ingresar button {
            padding: 12px;
            background-color: #e74c3c;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-ingresar button:hover {
            background-color: #c0392b;
        }

        .mensaje {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .mensaje.exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .cuadros-prendas {
            max-width: 1000px;
            margin: 60px auto;
            padding: 20px;
        }

        .cuadros-prendas h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #e74c3c;
        }

        .grid-prendas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        .tarjeta-prenda {
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
            padding: 15px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .tarjeta-prenda:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        .mini-imagen {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin: 0 auto 10px;
            display: block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .tarjeta-prenda h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: #333;
        }

        .tarjeta-prenda p {
            font-size: 0.9rem;
            margin: 4px 0;
            color: #555;
        }

        .no-prendas {
            text-align: center;
            font-size: 1.1rem;
            color: #888;
        }
    </style>
</head>
<body>
    <header>
        <h1>Tienda de Ropa</h1>
        <nav>
            <a href="index.php">Comprar</a>
            <a href="ingresar.php" class="active">Ingresar Prenda</a>
        </nav>
    </header>

    <form class="form-ingresar" method="POST" action="">
        <h2>Ingresar Nueva Prenda</h2>

        <?php if (isset($mensaje)) echo $mensaje; ?>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="categoria">Categoría:</label>
        <select name="categoria" id="categoria" required>
            <option value="">Seleccionar</option>
            <option value="remera">Remera</option>
            <option value="camisa">Camisa</option>
            <option value="campera">Campera</option>
            <option value="buzo">Buzo</option>
        </select>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" required>

        <label for="imagen">Nombre de imagen (carpeta <code>img/</code>):</label>
        <input type="text" name="imagen" id="imagen" value="default.jpg" required>

        <button type="submit" name="guardar">Guardar Prenda</button>
    </form>

    <div class="cuadros-prendas">
        <h2>Prendas Ingresadas</h2>
        <div class="grid-prendas">
            <?php
            $resultado = $conn->query("SELECT * FROM productos ORDER BY ID_produc DESC");

            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $img = htmlspecialchars($fila['imagen']);
                    $nom = htmlspecialchars($fila['nombre']);
                    $cat = htmlspecialchars($fila['categoria']);
                    $stk = (int)$fila['stock'];
                    $pre = number_format($fila['precio'], 2, ',', '.');

                    echo <<<HTML
                    <div class="tarjeta-prenda">
                        <img src="img/$img" alt="$nom" class="mini-imagen">
                        <div class="info">
                            <h3>$nom</h3>
                            <p><strong>Precio:</strong> \$$pre</p>
                            <p><strong>Categoría:</strong> $cat</p>
                            <p><strong>Stock:</strong> $stk</p>
                        </div>
                    </div>
                    HTML;
                }
            } else {
                echo "<p class='no-prendas'>No hay prendas ingresadas aún.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
