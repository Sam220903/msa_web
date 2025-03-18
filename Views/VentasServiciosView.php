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
    <title>Informes ventas servicios</title>
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

<h2 class="text-white">Ventas de servicios</h2>
    <div class="container-info text-center">
        <h4 class="text-white">Filtros de búsqueda</h4>
        
        <form class="form-inline" action="VentasServiciosView.php" method="POST">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <label for="inputCompania">Compañia: </label>
                </div>
                <div class="col-auto">
                    <select class="form-control mb-2 mr-sm-2" id="inputCompania" name="inputCompania">
                        <?php 
                        $companias = getCompaniasServicios();
                        $companias = json_decode($companias);
                        ?>
                    
                        <option value=""></option>
                        <?php
                        foreach ($companias as $compania) {
                            echo '<option value="' . $compania->Compania . '">' . $compania->Compania . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="inputMonto">Monto recarga: </label>
                </div>
                <div class="col-auto">
                    <select class="form-control mb-2 mr-sm-2" id="inputMonto" name="inputMonto">
                        <?php
                        $precios = getPreciosServicios();
                        $precios = json_decode($precios);
                        ?>

                        <option value=""></option>
                        <?php
                        foreach ($precios as $precio) {
                            echo '<option value="' . $precio->Precio . '">' . $precio->Precio . '</option>';
                        }
                        ?>
                    </select>
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
        <div style="height: 300px; overflow-y: auto;">
            <?php

            if (isset($_POST['inputCompania'])) {
                $compania = $_POST['inputCompania'];
            } else {
                $compania = null;
            }
            if (isset($_POST['inputMonto'])) {
                $monto = $_POST['inputMonto'];
            } else {
                $monto = null;
            }
            if (isset($_POST['inputLapso'])) {
                $fecha = $_POST['inputLapso'];
            } else {
                $fecha = null;
            }
            
            $ventasServicios = getVentasServicios($compania, $monto, $fecha);
            $ventasServicios = json_decode($ventasServicios);

            if (count($ventasServicios) > 0) {
                echo '<table class="table table-dark table-striped  table-hover mt-4 mx-auto">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th scope="col">Compañia</th>';
                echo '<th scope="col">Monto de recarga</th>';
                echo '<th scope="col">Atendido por</th>';
                echo '<th scope="col">Fecha</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($ventasServicios as $venta) {
                    echo '<tr>';
                    echo '<td>' . $venta->Compania . '</td>';
                    echo '<td>' . $venta->Precio. '</td>';
                    echo '<td>' . $venta->Trabajador . '</td>';
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