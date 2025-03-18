<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
    exit();
}
$esAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN';
if(!$esAdmin){
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proveedores</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include '../Libraries/header.php';
?>
<div class="container" style="padding: 100px">
    <div class="d-flex overflow-x-auto flex-nowrap p-5 text-white">
        <?php
        include '../Repository/proveedorRepository.php';
        $proveedores = getProveedores($_SESSION['password']);
        $proveedores = json_decode($proveedores);
        if (count($proveedores) > 0) {
            foreach ($proveedores as $proveedor) {
                echo '<div class="d-flex">'; // Envuelve la tarjeta en un div con d-flex
                echo '<div class="card profile-card m-3">';
                echo '<div class="d-flex justify-content-center">';
                echo '<img class="profile" src="../assets/proveedores/user.png" alt="Card image cap">';
                echo '</div>';
                echo '<div class="flex-grow-1 d-flex flex-column">'; // Envuelve el contenido de la tarjeta
                echo '<div class="card-body pt-0">';
                echo '<h5 class="card-title">' . $proveedor->Nombre . '</h5>';
                echo '<p class="card-text text-white">' . $proveedor->Telefono . '</p>';
                echo '<p class="card-text text-white info">' . $proveedor->Lapso_surte . '</p>';
                echo '</div>';
                if ($esAdmin) {
                    echo '<div class="d-flex justify-content-between mt-auto">'; // mt-auto para empujar los botones hacia abajo
                    echo '<a href="ProveedoresForm.php?operation=2&supplierID='.$proveedor->ID.'" class="btn btn-primary">Modificar</a>';
                    echo '<a href="../Controllers/ControlProveedores.php?operation=3&supplierID='.$proveedor->ID.'" class="btn btn-danger">Eliminar</a>';
                    echo '</div>';
                }
                echo '</div>'; // Cierra el div que envuelve el contenido
                echo '</div>'; // Cierra el div de la tarjeta
                echo '</div>'; // Cierra el div que envuelve la tarjeta con d-flex
            }
        }
        ?>
    </div>
    <div class="container-fluid d-flex justify-content-end mr-0 mt-3">
        <a href="ProveedoresForm.php?operation=1" class="buttonMSA">Agregar Proveedor</a>
    </div>
</div>
<?php
include '../Libraries/JQueries.php';
?>
</body>
</html>