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
    <title>Trabajadores</title>
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
        include '../Repository/trabajadoresRepository.php';
        $trabajadores = getTrabajadores($_SESSION['password']);
        $trabajadores = json_decode($trabajadores);
        if (count($trabajadores) > 0) {
            foreach ($trabajadores as $trabajador) {
                echo '<div class="d-flex">'; // Envuelve la tarjeta en un div con d-flex
                echo '<div class="card profile-card m-3">';
                echo '<div class="card-img-block">';
                echo '<img class="card-img-top" src="../' . $trabajador->Foto . '" alt="Card image cap" style="width: 200px; height: 200px; margin: 0 auto; top: -20px; overflow: hidden;object-fit: cover;">';
                echo '</div>';
                echo '<div class="flex-grow-1 d-flex flex-column">'; // Envuelve el contenido de la tarjeta
                echo '<div class="card-body pt-0">';
                echo '<h5 class="card-title">' . $trabajador->Nombre . " " . $trabajador->Apellido . '</h5>';
                echo '<p class="card-text text-white">' . $trabajador->Telefono . '</p>';
                echo '<p class="card-text text-white">' . $trabajador->CalleyNum . '</p>';
                echo '<p class="card-text text-white">' . $trabajador->Ciudad . '</p>';
                echo '</div>';
                if ($esAdmin) {
                    echo '<div class="d-flex justify-content-between mt-auto">'; // mt-auto para empujar los botones hacia abajo
                    echo '<a href="TrabajadoresForm.php?operation=2&workerID='.$trabajador->ID.'" class="btn btn-primary">Modificar</a>';
                    echo '<a href="../Controllers/ControlTrabajador.php?operation=3&workerID=' . $trabajador->ID . '" class="btn btn-danger">Eliminar</a>';
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
        <a href="TrabajadoresForm.php?operation=1" class="buttonMSA">Agregar Trabajador</a>
    </div>
</div>
<?php
include '../Libraries/JQueries.php';
?>
</body>
</html>