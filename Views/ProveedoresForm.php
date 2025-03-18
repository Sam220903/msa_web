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
    include_once '../Repository/proveedorRepository.php';

    if(isset($_GET['operation'])) $op = $_GET['operation']; else $op = null;
    if(isset($_GET['supplierID']) && $op == 2){
        $supplierID = $_GET['supplierID'];
        $proveedor = getProveedorById($supplierID);
    } else {
        $supplierID = null;
    }

    echo '<meta charset="UTF-8">';
    if ($op == 1) echo '<title>Agregar Proveedor</title>';
    else if ($op == 2) echo '<title>Modificar Proveedor</title>';

    include '../Libraries/linkStyles.php';

    echo '<link rel="stylesheet" href="../Css/style.css">';
    ?>
</head>
<body>
<?php
include '../Libraries/header.php';
?>
<div class="container-fluid" style="padding: 100px">
    <form name="suppliers" action="../Controllers/ControlProveedores.php" method="POST" class="border rounded p-4 bg-custom mx-auto" style="max-width: 30%;">
        <?php
        if($op == 1) echo '<h2 class="text-center">Añadir Proveedor</h2>';
        else if ($op == 2) echo '<h2 class="text-center">Modificar Proveedor</h2>';
        ?>
        <div class="form-group">
            <label for="name" class="font-weight-normal">*Nombre</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="name" name="name" value="' . $proveedor['Nombre'] . '">';
            else
                echo '<input type="text" class="form-control" id="name" name="name">';
            ?>
        </div>
        <div class="form-group">
            <label for="phone" class="font-weight-normal">*Telefono:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="phone" name="phone" value="' . $proveedor['Telefono'] . '">';
            else
                echo '<input type="text" class="form-control" id="phone" name="phone">';
            ?>
        </div>
        <div class="form-group">
            <label for="time_supplies" class="font-weight-normal">*Lapso de surtido:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="time_supplies" name="time_supplies" value="' . $proveedor['Lapso_surte'] . '">';
            else
                echo '<input type="text" class="form-control" id="time_supplies" name="time_supplies">';
            ?>
        </div>
        <input type="hidden" name="operation" value="<?php echo $op; ?>">
        <input type="hidden" name="supplierID" value="<?php echo $supplierID; ?>">
        <div class="text-center mt-3">
            <?php
            echo '<button type="button" class="buttonMSA-form" formtarget="_blank" onclick="validate('.$op.')">';
                if($op == 1) echo 'Añadir';
                else if ($op == 2) echo 'Modificar';
            echo '</button>';
            ?>
        </div>
    </form>
</div>
<script>
    function validate(op){
        if(document.suppliers.name.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe ingresar un nombre para el proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.suppliers.name.focus()
            return 0
        }
        if(document.suppliers.phone.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe ingresar un número telefónico para el proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.suppliers.phone.focus()
            return 0
        }
        if(document.suppliers.time_supplies.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe ingresar un lapso para el proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.suppliers.time_supplies.focus()
            return 0
        }
        if(op == 1){
            Swal.fire({
                title: '¡Proveedor añadido!',
                text: 'El proveedor se ha añadido correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.suppliers.submit()
                }
            });
        } else if (op == 2){
            Swal.fire({
                title: '¡Proveedor modificado!',
                text: 'Los datos del proveedor se han modificado exitosamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {

                if (result.isConfirmed) {
                    document.suppliers.submit()
                }
            });
        }
        
    }

</script>
<?php
include '../Libraries/JQueries.php';
?>
</body>
</html>