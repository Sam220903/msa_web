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
if(!$esAdmin){
    header("Location: home.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historial de precios</title>
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
<div class="container" style="margin-top: 100px">
    <div class="container-info p-5 text-white justify-content-center">
        <?php
        include_once "../Repository/queries.php";
        if($_GET['id']) {
            $producto_id = $_GET['id'];
            // Obtén los precios filtrados por el ID del producto
            $precios = getPreciosPorProducto($producto_id);
            $precios = json_decode($precios);

            echo '<div class="row bg-trasparent p-3">';
            echo '<h2 class="text-center text-white">Historial de precios para:  '.$precios[0]->Producto .'</h2>';
            if (count($precios) > 0) {
                echo '<table class="table table-dark table-striped  table-hover mt-4 mx-auto">';
                echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">Producto</th>';
                echo '<th scope="col">Precio</th>';
                echo '<th scope="col">Fecha de inicio</th>';
                echo '<th scope="col">Fecha de fin</th>';
                echo '</tr>';
                echo '</thead>';
                foreach ($precios as $precio) {
                    echo '<tbody>';
                    echo '<tr>';
                    echo '<td>' . $precio->Producto . '</td>';
                    echo '<td>' . $precio->Precio . '</td>';
                    echo '<td>' . $precio->Fecha_inicio . '</td>';
                    echo '<td>' . $precio->Fecha_fin . '</td>';
                    echo '</tr>';
                    echo '</tbody>';
                }
                echo '</table>';
                echo '</div>';

            } else {
                echo '<div class="alert alert-warning text-white bg-dark" role="alert">
                            No hay precios registrados para este producto
                          </div>';
            }
        } else {
            echo '<div class="alert alert-danger text-white bg-dark" role="alert">
                            ID de producto no proporcionado
                          </div>';
        }
        ?>
    </div>
</div>
</body>
</html>