<?php
session_start();
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Si no hay sesión activa, redirigir al usuario al inicio de sesión
    header("Location: ../");
    exit();
}

// Verificar el rol del usuario
$esAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<?php
include '../Libraries/JQueries.php';
?>
<body>
<?php
include '../Libraries/header.php';
?>
<div class="container-info d-flex p-5 text-white" id="productos">
    <?php
    include_once "../Repository/productosRepository.php";
    $productos = getProductos();
    $productos = json_decode($productos);
    if (count($productos) > 0) {
        foreach ($productos as $producto) {
            echo '<div class="producto">';
            echo '<img src="../' . $producto->Imagen . '" alt="' . $producto->Nombre_producto . ' " style="max-width: 300px; max-height: 300px;">';
            echo '<h3>' . $producto->Nombre_producto . '</h3>';
            echo '<p>Contenido: ' . $producto->Contenido . '</p>';
            echo '<p>Precio: $' . $producto->Precio_publico . '</p>';
            echo '<p>Cantidad disponible: '.$producto->Cantidad_disponible.'</p>';

            // Agregar botones de modificar y borrar solo si el usuario es administrador
            if ($esAdmin) {
                echo '<div class="d-flex justify-content-between">';
                echo '<li class="btn btn-primary nav-item dropdown mx-2"> ' .
                    '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Control</a>' .
                    '<ul class="dropdown-menu">' .
                    '<li><a class="dropdown-item" href="ProductoForm.php?operation=2&productID='.$producto->ID.'">Modificar</a></li>' .
                    '<li><a class="dropdown-item" href="../Controllers/ControlProducto.php?operation=3&productID='.$producto->ID.'">Borrar</a></li>' .
                    '</ul></li>';
                echo '<a href="PreciosView.php?id=' . $producto->ID . '" class="btn btn-secondary">Historial de precios</a>';
            } else {
                echo '<div class="d-flex justify-content-end">';
            }
            echo '<a class="btn btn-success" onclick="addToCart('. $producto->ID . ')">Agregar al carrito</a>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
    <div class="container-fluid d-flex justify-content-end mr-0">
        <a href="ProductoForm.php?operation=1" class="buttonMSA">Agregar producto</a>
    </div>
</div>

<script>
    function incrementCartCount() {
        let cartCount = parseInt(document.getElementById('cart-count').textContent);
        cartCount++;
        document.getElementById('cart-count').textContent = cartCount;
    }
</script>

<script>
    function addToCart(productId) {
        fetch('../Controllers/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    incrementCartCount();
                } else {
                    alert('Error al añadir producto al carrito.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>

</body>
</html>