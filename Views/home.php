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
    <title>Home</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
    <?php
    include '../Libraries/header.php';
    include_once "../Repository/queries.php";
    $soldProducts = calcularTotalVentasProductos();
    $soldServices = calcularTotalVentasServicios();
    $doneAssortments = calcularTotalSurtidos();
    $top3products = getTop3Productos();
    $top3products = json_decode($top3products);
    $productTop1 = $top3products[0];
    $productTop2 = $top3products[1];
    $productTop3 = $top3products[2];
    ?>
    <div class="d-flex align-items-center" style="margin-top: 100px">
        <h2 class="mx-auto mt-4" style="font-weight: 600;">Movimientos de la semana</h2>
    </div>
    <div class="card mx-auto col-8 mt-3 ">
        <div style="padding: 5px;">
            <div class="d-flex align-items-center justify-content-between" style="margin-top: 0; margin-bottom: 0;">
                <strong class="mr-3 align-left align-center" style="font-size: 100px; margin-left: 150px; margin-right:150px;"><?php echo $soldProducts ?></strong>
                <label style="font-size: 40px; font-weight: 600; max-width: 300px; word-wrap: break-word;">Productos vendidos</label>
            </div>
        </div>
    </div>
    <div class="col-8 mx-auto d-flex align-items-center justify-content-between">
        <div class="card mx-auto col-5 mt-3">
            <div class="d-flex align-items-center justify-content-between">
                <strong class="mr-3 align-left align-center" style="font-size: 50px; margin-left: 50px; margin-right: 50px;"><?php echo $soldServices ?></strong>
                <label style="font-size: 25px; font-weight: 600; max-width: 150px; word-wrap: break-word;">Servicios vendidos</label>
            </div>
        </div>
        <div class="card mx-auto col-5 mt-3">
            <div class="d-flex align-items-center justify-content-between">
                <strong class="mr-3 align-left align-center" style="font-size: 50px; margin-left: 50px; margin-right: 50px;"><?php echo $doneAssortments ?></strong>
                <label style="font-size: 25px; font-weight: 600; max-width: 150px; word-wrap: break-word;">Surtidos realizados</label>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center">
        <h2 class="mx-auto mt-4" style="font-weight: 600;">Productos más vendidos</h2>
    </div>
    <div class="card mx-auto col-8 mt-3">
        <div class="d-flex align-items-center justify-content-between" style="padding: 10px;">
            <div class="card col-3" style="background-color: #354656;">
                <div class="d-flex flex-column align-items-center justify-content-between mt-3 mb-3">
                    <img src="../<?php echo $productTop1->Imagen ?>" alt="Product top 1" style="max-width: 100%; max-height: 200px;">
                    <label style="font-size: 20px; max-width: 100px; word-wrap: break-word; text-align: center; margin-top: 10px;"><?php echo $productTop1->Producto ?></label>
                    <label style="font-size: 15px">Cantidad vendida: <?php echo $productTop1->Cantidad ?></label>
                </div>
            </div>
            <div class="card col-3" style="background-color: #354656;">
                <div class="d-flex flex-column align-items-center justify-content-between mt-3 mb-3">
                    <img src="../<?php echo $productTop2->Imagen ?>" alt="Product top 2" style="max-width: 100%; max-height: 200px;">
                    <label style="font-size: 20px; max-width: 100px; word-wrap: break-word; text-align: center; margin-top: 10px;"><?php echo $productTop2->Producto ?></label>
                    <label style="font-size: 15px">Cantidad vendida: <?php echo $productTop2->Cantidad ?></label>
                </div>
            </div>
            <div class="card col-3" style="background-color: #354656;">
                <div class="d-flex flex-column align-items-center justify-content-between mt-3 mb-3">
                    <img src="../<?php echo $productTop3->Imagen ?>" alt="Product top 3" style="max-width: 100%; max-height: 200px;">
                    <label style="font-size: 20px; max-width: 100px; word-wrap: break-word; text-align: center; margin-top: 10px;"><?php echo $productTop3->Producto ?></label>
                    <label style="font-size: 15px">Cantidad vendida: <?php echo $productTop3->Cantidad ?></label>
                </div>
            </div>
        </div>
    </div>
    <?php
    include '../Libraries/JQueries.php';
    ?>
</body>
</html>