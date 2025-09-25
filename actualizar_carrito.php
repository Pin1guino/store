<?php
session_start();
include("conexion.php");

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
} else {
    echo '<p>Inicia sesión para ver tu carrito</p>';
}
?>