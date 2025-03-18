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
    <title>Reportes</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
    <?php
    include '../Libraries/header.php';
    include_once "../Repository/queries.php";
    ?>

    <div class="container mt-5 pt-5">
        <h1 class="text-center mb-4">Reportes</h1>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-ventas-tab" data-bs-toggle="pill" data-bs-target="#pills-ventas" type="button" role="tab" aria-controls="pills-ventas" aria-selected="true">Ganancias por producto</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-producto-tab" data-bs-toggle="pill" data-bs-target="#pills-producto" type="button" role="tab" aria-controls="pills-producto" aria-selected="false">Ganancias por trabajador</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-servicio-tab" data-bs-toggle="pill" data-bs-target="#pills-servicio" type="button" role="tab" aria-controls="pills-servicio" aria-selected="false">Productos próximos a caducar</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active card p-4" id="pills-ventas" role="tabpanel" aria-labelledby="pills-ventas-tab">
                <h3 class="mb-3">Ganancias por producto</h3>
                <p>Detalles del reporte generado:</p>
                <?php 
                    $productos =getVentasPorProducto();
                    $productos = json_decode($productos);
                    if(count($productos)>0){
                        echo '<table class="table table-dark table-striped  table-hover mt-4 mx-auto">';
                        echo '<thead class="thead-dark">';
                        echo '<tr>';
                        echo '<th scope="col">Producto</th>';
                        echo '<th scope="col">Cantidad vendida</th>';
                        echo '<th scope="col">Ganancias</th>';
                        echo '<th scope="col">Tipo</th>';
                        echo '<th scope="col">Presentación</th>';
                        echo '<th scope="col">Contenido</th>';
                        echo '<th scope="col">Marca</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach($productos as $producto){
                            echo '<tr>';
                            echo '<td>'.$producto->Producto.'</td>';
                            echo '<td>'.$producto->Cantidad.'</td>';
                            echo '<td>'.$producto->Ganancias.'</td>';
                            echo '<td>'.$producto->Tipo.'</td>';
                            echo '<td>'.$producto->Presentacion.'</td>';
                            echo '<td>'.$producto->Contenido.'</td>';
                            echo '<td>'.$producto->Marca.'</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>'; 
                    } else {
                        echo '<div class="alert alert-warning text-white bg-dark" role="alert">
                            No hay registros para mostrar
                          </div>';
                    }
                ?>
            </div>
            <div class="tab-pane fade card p-4" id="pills-producto" role="tabpanel" aria-labelledby="pills-producto-tab">
                <h3 class="mb-3">Ganancias por trabajador</h3>
                <p>Detalles del reporte generado:</p>
                <?php
                    $trabajadores = getVentasPorTrabajador();
                    $trabajadores = json_decode($trabajadores);
                    if(count($trabajadores)>0){
                        echo '<table class="table table-dark table-striped  table-hover mt-4 mx-auto">';
                        echo '<thead class="thead-dark">';
                        echo '<tr>';
                        echo '<th scope="col">Trabajador</th>';
                        echo '<th scope="col">Cantidad vendida</th>';
                        echo '<th scope="col">Ganancias</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach($trabajadores as $trabajador){
                            echo '<tr>';
                            echo '<td>'.$trabajador->Trabajador.'</td>';
                            echo '<td>'.$trabajador->Cantidad.'</td>';
                            echo '<td>'.$trabajador->Ganancias.'</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>'; 
                    } else {
                        echo '<div class="alert alert-warning text-white bg-dark" role="alert">
                            No hay registros para mostrar
                          </div>';
                    }
                ?>

            </div>
            <div class="tab-pane fade card p-4" id="pills-servicio" role="tabpanel" aria-labelledby="pills-servicio-tab">
                <h3 class="mb-3">Productos próximos a caducar</h3>
                <p>Detalles del reporte generado:</p>
                <?php
                    $productos = getProductsCloseExpiry();
                    $productos = json_decode($productos);
                    if(count($productos)>0){
                        echo '<table class="table table-dark table-striped  table-hover mt-4 mx-auto">';
                        echo '<thead class="thead-dark">';
                        echo '<tr>';
                        echo '<th scope="col">Producto</th>';
                        echo '<th scope="col">Fecha de caducidad</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        $hoy = new DateTime();
                        $proximaSemana = $hoy->modify('+7 days');

                        foreach($productos as $producto) {
                            $fechaCaducidad = new DateTime($producto->Fecha_caducidad);
                            $clase = '';
                            $diasRestantes = $hoy->diff($fechaCaducidad)->days;
                            if ($fechaCaducidad >= $hoy && $fechaCaducidad <= $proximaSemana) {
                                $clase = 'proxima-caducidad';
                            }
                            echo '<tr class="' . $clase . '">';
                            echo '<td>' . $producto->Producto . '</td>';
                            echo '<td><span data-toggle="tooltip" title="Faltan ' . $diasRestantes . ' días">' . date('d/m/Y', strtotime($producto->Fecha_caducidad)) . '</span></td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<div class="alert alert-warning text-white bg-dark" role="alert">
                            No hay registros para mostrar
                          </div>';
                    }
                ?>
            </div>
        </div>
    </div>

    <?php
    include '../Libraries/JQueries.php';
    ?>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>