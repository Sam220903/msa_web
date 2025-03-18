<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../");
    exit();
}

$esAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN';
if(!$esAdmin){
    header("Location: home.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes ventas productos</title>
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
include_once "../Repository/queries.php";
?>

<div class="container" style="margin-top: 100px;">
    <h2 class="text-white">Ventas de productos</h2>
    <div class="container-info text-center">
        <h4 class="text-white">Filtros de búsqueda</h4>
        
        <form class="form-inline" action="VentasProductosView.php" method="POST">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <label for="inputProducto">Producto: </label>
                </div>
                <div class="col-auto">
                    <select class="form-control mb-2 mr-sm-2" id="inputProducto" name="inputProducto">
                        <option value=""></option>
                        <?php
                        $productos = getNombresProductos();
                        $productos = json_decode($productos);
                        foreach ($productos as $producto) {
                            echo '<option value="' . $producto->Nombre_producto . '">' . $producto->Nombre_producto . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="inputCantidad">Cantidad: </label>
                </div>
                <div class="col-auto">
                    <input type="number" class="form-control mb-2 mr-sm-2" id="inputCantidad" name="cantidad" size="7">
                </div>
                <div class="col-auto">
                    <label for="inputLapso">Lapso: </label>
                </div>
                <div class="col-auto">
                    <select class="form-control mb-2 mr-sm-2" id="inputLapso" name="inputLapso">
                        <option value=""></option>
                        <option value="1">Hoy</option>
                        <option value="7">Última semana</option>
                        <option value="30">Últimos 30 días</option>
                        <option value="365">Último año</option>
                        <option value="0">Todos</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="submit" class="btn btn-primary" value="Buscar">
                </div>
            </div>
        </form>
    </div>
    <div class="container-info">
        <div style="height: 500px; overflow-y: auto;">
            <?php
            if(isset($_POST['inputProducto'])){
                $producto = $_POST['inputProducto'];
            } else {
                $producto = null;
            }
            if(isset($_POST['cantidad'])){
                $cantidad = $_POST['cantidad'];
            } else {
                $cantidad = null;
            }
            if(isset($_POST['inputLapso'])){
                $lapso = $_POST['inputLapso'];
            } else {
                $lapso = null;
            }


            $ventasProductos = getVentasProductos($producto, $cantidad, $lapso);
            $ventasProductos = json_decode($ventasProductos);

            if (count($ventasProductos) > 0) {
                echo '<table class="table table-dark table-striped  table-hover mt-4 mx-auto">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th scope="col">Producto vendido</th>';
                echo '<th scope="col">Atendido por</th>';
                echo '<th scope="col">Cantidad vendida</th>';
                echo '<th scope="col">Fecha</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($ventasProductos as $venta) {
                    echo '<tr>';
                    echo '<td>' . $venta->Producto . '</td>';
                    echo '<td>' . $venta->Trabajador . '</td>';
                    echo '<td>' . $venta->Cantidad . '</td>';
                    echo '<td>' . $venta->Fecha . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';

            } else {
                echo '<div class="alert alert-warning text-white bg-dark" role="alert">
                            Aun no hay ventas de productos registradas
                          </div>';
            }

            ?>
        </div>
    </div>
    
</div>
    
</body>
</html>