<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include '../Libraries/header.php';
include_once '../Repository/surtidosRepository.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $productID = $_POST['productID'];
    $supplierID = $_POST['supplierID'];
    $supplied_quan = $_POST['supplied_quan'];
    $unit_cost = $_POST['unit_cost'];
    ?>
    <div class="container mt-4" style="padding: 100px">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-md">
                    <div class="card-header text-white text-center" style="background-color: #354656">
                        <h5 class="mb-0">Detalles del surtido</h5>
                    </div>
                    <div class="card-body text-white justify-content-center text-center" style="background-color: #1d2e3d">
                        <p><strong>ID de producto:</strong> <?php echo $productID; ?></p>
                        <p><strong>ID de proveedor:</strong> <?php echo $supplierID; ?></p>
                        <p><strong>Cantidad surtida:</strong> <?php echo $supplied_quan; ?></p>
                        <p><strong>Costo unitario:</strong> <?php echo $unit_cost; ?></p>
                        <div class="text-center mt-3">
                            <a href="#" onclick="goBack2()" class="btn btn-success">Aceptar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php
include '../Libraries/JQueries.php';
if (insertarSurtido($supplied_quan, $unit_cost, $productID, $supplierID)) {
    echo "<script>Swal.fire({
        title: '¡Surtido añadido!',
        text: 'El surtido se ha añadido correctamente.',
        icon: 'success',
        confirmButtonText: 'OK'
    });</script>";
} else {
    echo "<script>Swal.fire({
        title: '¡Error al añadir surtido!',
        text: 'El surtido no se ha añadido correctamente.',
        icon: 'error',
        confirmButtonText: 'OK'
    });</script>";
}
?>

</body>
</html>