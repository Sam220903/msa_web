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
    <?php
    include_once '../Repository/serviciosRepository.php';

    if(isset($_GET['operation'])) $op = $_GET['operation']; else $op = null;
    if(isset($_GET['serviceID']) && $op == 2){
        $serviceID = $_GET['serviceID'];
        $servicio = getServicioById($serviceID);
    } else {
        $serviceID = null;
    }

    echo '<meta charset="UTF-8">';
    if ($op == 1) echo '<title>Agregar Servicio</title>';
    else if ($op == 2) echo '<title>Modificar Servicio</title>';

    include '../Libraries/linkStyles.php';

    echo '<link rel="stylesheet" href="../Css/style.css">';

    ?>
    <meta charset="UTF-8">
    <title>Agregar Servicio</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
</head>
<body>
<?php
include '../Libraries/header.php';
?>
<div class="container-fluid" style="padding: 100px">
    <form name="services" method="POST" action="../Controllers/ControlServicio.php" class="border rounded p-4 bg-custom mx-auto" style="max-width: 30%;" enctype="multipart/form-data">
        <?php
        if($op == 1) echo '<h2 class="text-center">Añadir Servicio</h2>';
        else if ($op == 2) echo '<h2 class="text-center">Modificar Servicio</h2>';
        ?>
        <div class="form-group d-flex justify-content-center">
            <?php
            if($op == 2){
                echo '<img src="../' . $servicio['Imagen'] . '" alt="" style="width: 300px;">';
            }
            ?>
        </div>

        <div class="form-group">
            <?php
            if($op == 2){
                echo '<label for="image" class="font-weight-normal" style="color: white;">Foto:</label>';
                echo '<input type="file" class="form-control" id="image" name="image" data-toggle="tooltip" data-placement="right" title="Modificar la imagen no es obligatorio para aplicar cambios">';
            }
            else{
                echo '<label for="image" class="font-weight-normal" style="color: white;">*Foto:</label>';
                echo '<input type="file" class="form-control" id="image" name="image" >';
            }
            ?>
        </div>
        <div class="form-group">
            <label for="company" class="font-weight-normal">*Compañia:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="company" name="company" value="' . $servicio['Compania'] . '">';
            else
                echo '<input type="text" class="form-control" id="company" name="company">';
            ?>
        </div>
        <div class="form-group">
            <label for="price" class="font-weight-normal">*Precio:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="price" name="price" value="' . $servicio['Precio'] . '">';
            else
                echo '<input type="text" class="form-control" id="price" name="price">';
            ?>
        </div>
        <input type="hidden" name="operation" value="<?php echo $op; ?>">
        <input type="hidden" name="serviceID" value="<?php echo $serviceID; ?>">
        <div class="text-center mt-3">
            <?php
            echo '<button type="button" class="buttonMSA-form" formtarget="_blank" onclick="validateServices('.$op.')">';
                if($op == 1) echo 'Añadir';
                else if($op == 2) echo 'Modificar';
            echo '</button>';
            ?>
        </div>
    </form>
</div>

<script>
    function validateServices(op){
        if(document.services.company.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe ingresar una compañia.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            return 0
        }
        if(document.services.price.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe ingresar un precio.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.services.price.focus()
            return 0
        } else if(isNaN(document.services.price.value)){
            Swal.fire({
                    title: 'Error',
                    text: 'El precio tiene que ser un número.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.services.price.focus()
            return 0
        } else if(document.services.price.value < 0){
            Swal.fire({
                    title: 'Error',
                    text: 'El precio no puede ser negativo.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.services.price.focus()
            return 0
        }
        if(op==1){
            Swal.fire({
                title: '¡Servicio añadido!',
                text: 'El servicio se ha añadido correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.services.submit()
                }
            });
        } else if(op==2){
            Swal.fire({
                title: '¡Servicio modificado!',
                text: 'Los datos del servicio se han modificado exitosamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.services.submit()
                }
            });
        }
        
    }
</script>
<?php
include '../Libraries/JQueries.php';
?>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();

    });
</script>
</body>
</html>