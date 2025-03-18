<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Si no hay sesión activa, redirigir al usuario al inicio de sesión
    header("Location: ../");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Surtidos</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include_once "../Libraries/header.php";
include_once "../Repository/queries.php";
?>


<div class="container" style="margin-top: 100px">

    <h2 class="text-white">Surtidos</h2>
    <div class="container-info text-center">
        <h4 class="text-white">Filtros de búsqueda</h4>

        <form class="form-inline" action="SurtidosView.php" method="POST">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <label for="inputProducto">Proveedor: </label>
                </div>
                <div class="col-auto">
                    <select class="form-control mb-2 mr-sm-2" id="inputProveedor" name="inputProveedor">
                        <option value=""></option>
                        <?php
                        $proveedores = getNombresProveedores();
                        $proveedores = json_decode($proveedores);
                        foreach ($proveedores as $proveedor) {
                            echo '<option value="' . $proveedor->Nombre . '">' . $proveedor->Nombre. '</option>';
                        }
                        ?>
                    </select>
                </div>
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
                    <input type="submit" class="btn btn-primary" value="Buscar">
                </div>
            </div>
        </form>
    </div>

    <div class="container-info mb-4">

        <div style="height: 400px; overflow-y: auto;">
            <?php
            if(isset($_POST['inputProveedor'])){
                $proveedor = $_POST['inputProveedor'];
            } else {
                $proveedor = null;
            }
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

            $surtidos = getSurtidos($proveedor, $producto, $cantidad);
            $surtidos = json_decode($surtidos);
            if (count($surtidos) > 0) {
                //echo '<div class="row bg-trasparent my-4 p-3">';
                echo '<table class="table table-dark table-striped  table-hover mt-4 mx-auto">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Proveedor</th>';
                echo '<th>Producto</th>';
                echo '<th>Cantidad Surtida</th>';
                echo '<th>Costo Unitario</th>';
                echo '<th>Fecha / Hora</th>';

                /*
                if($_SESSION['role'] === 'ADMIN'){
                    echo '<th>Acciones</th>';
                }
                */
                foreach($surtidos as $surtido){
                    echo '<tr>';
                    echo '<td>'. htmlspecialchars($surtido->Proveedor) . '</td>';
                    echo '<td>'. htmlspecialchars($surtido->Producto) . '</td>';
                    echo '<td>'. htmlspecialchars($surtido->Cantidad_surtida) . '</td>';
                    echo '<td>'. htmlspecialchars($surtido->Costo_unitario) . '</td>';
                    echo '<td>'. htmlspecialchars($surtido->Fecha_Hora) . '</td>';
                    /*
                    if($_SESSION['role'] === 'ADMIN'){
                        echo'<td>';
                        echo'<a href="#" class="btn btn-primary">Modificar</a>';
                        echo ' ';
                        echo'<a href="#" class="btn btn-danger">Eliminar</a>';
                        echo '</td>';
                    }
                    */
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            }else{
                echo '<div class = "alert-warning bg-dark" role="alert">
                        No hay productos registrados
                        </div>';
            }
            ?>
        </div>
    </div>
    <div class="container-fluid d-flex justify-content-end mr-0">
        <a href="SurtidosForm.php" class="buttonMSA">Agregar Surtido</a>
    </div>
</body>
<?php
include '../Libraries/JQueries.php';
?>
</html>