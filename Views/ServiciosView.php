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
    <title>Servicios</title>
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
    include_once "../Repository/serviciosRepository.php";
    $servicios = getServicios();
    $servicios = json_decode($servicios);
    if (count($servicios) > 0) {
        foreach ($servicios as $servicio) {
            echo '<div class="producto">';
            echo '<div class="d-block" style="height: 200px; margin-bottom: 50px">';
            echo '<img class="img-fluid" src="../' . $servicio->Imagen . '" alt="' . $servicio->Compania . ' " style="height:80%;">';
            echo '</div>';
            echo '<h3>' . $servicio->Compania . '</h3>';
            echo '<p>Precio: $' . $servicio->Precio . '</p>';

            // Agregar botones de modificar y borrar solo si el usuario es administrador
            if ($esAdmin) {
                echo '<div class="d-flex justify-content-between">';
                echo '<li class="btn btn-primary nav-item dropdown mx-2"> ' .
                    '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Control</a>' .
                    '<ul class="dropdown-menu">' .
                    '<li><a class="dropdown-item" href="ServiciosForm.php?operation=2&serviceID='.$servicio->ID.'">Modificar</a></li>' .
                    '<li><a class="dropdown-item" href="../Controllers/ControlServicio.php?operation=3&serviceID='.$servicio->ID.'">Borrar</a></li>' .
                    '</ul></li>';
                //echo '<a href="#" class="btn btn-secondary mr-2">Historial de precios</a>';
            } else {
                echo '<div class="d-flex justify-content-end">';
            }
            //echo '<a class="btn btn-success" onclick="incrementCartCount()">Agregar al carrito</a>';
            echo '<a class="btn btn-success" onclick="addToCart(' . $servicio->ID . ')">Agregar al carrito</a>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
    <div class="container-fluid d-flex justify-content-end mr-0">
        <a href="ServiciosForm.php?operation=1" class="buttonMSA">Agregar servicio</a>
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
    function addToCart(serviceId){
        fetch('../Controllers/add_to_cart.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'service_id=' + serviceId
        })
        .then(response => response.json())
        .then(data =>{
            if(data.success){
                incrementCartCount();
            }else{
                alert('Error al añadir servicio al carrito.');
            }
        })
        .catch(error => {
            console.error('Error:',error);
        });
    }
</script>
</body>
</html>