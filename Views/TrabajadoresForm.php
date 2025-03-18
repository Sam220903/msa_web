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
    include_once '../Repository/trabajadoresRepository.php';

    if(isset($_GET['operation'])) $op = $_GET['operation']; else $op = null;
    if(isset($_GET['workerID']) && $op == 2){
        $workerID = $_GET['workerID'];
        $trabajador = getTrabajadorById($workerID);
    } else {
        $workerID = null;
    }

    echo '<meta charset="UTF-8">';
    if ($op == 1) echo '<title>Agregar Trabajador</title>';
    else if ($op == 2) echo '<title>Modificar Trabajador</title>';

    include '../Libraries/linkStyles.php';
    ?>

</head>
<body>
<?php
include '../Libraries/header.php';
?>
<div class= "container-fluid" style="padding: 100px">
    <form name="trabajadores" method="POST" action="../Controllers/ControlTrabajador.php" class="border rounded p-4 bg-custom mx-auto" style="max-width: 30%" enctype="multipart/form-data" >
        <?php
        if($op == 1) echo '<h2 class="text-center">Añadir Trabajador</h2>';
        else if ($op == 2) echo '<h2 class="text-center">Modificar Trabajador</h2>';
        ?>

        <div class="form-group d-flex justify-content-center">
            <?php
            if($op == 2){
                echo '<img src="../' . $trabajador['Foto'] . '" alt="" style="width: 150px;">';
            }
            ?>
        </div>

        <div class="form-group">
            <?php
            if($op == 2){
                echo '<label for="Foto" class="font-weight-normal" style="color: white;">Foto:</label>';
                echo '<input type="file" class="form-control" id="Foto" name="Foto" data-toggle="tooltip" data-placement="right" title="Modificar la imagen no es obligatorio para aplicar cambios">';
            }
            else{
                echo '<label for="Foto" class="font-weight-normal" style="color: white;">*Foto:</label>';
                echo '<input type="file" class="form-control" id="Foto" name="Foto" >';
            }
            ?>
        </div>
        <div class="form-group">
            <label for="Name" class="font-weight-normal" style="color: white;">*Nombre:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="Name" name="Name" value="' . $trabajador['Nombre'] . '">';
            else
                echo '<input type="text" class="form-control" id="Name" name="Name">';
            ?>
        </div>
        <div class="form-group">
            <label for="LastName" class="font-weight-normal" style="color: white;">*Apellido:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="LastName" name="LastName" value="' . $trabajador['Apellido'] . '">';
            else
                echo '<input type="text" class="form-control" id="LastName" name="LastName">';
            ?>
        </div>
        <div class="form-group">
            <?php
            if($op == 2){
                echo '<label for="password" class="font-weight-normal" style="color: white;">Contraseña:</label>';
                echo '<input type="password" class="form-control" id="password" name="password" data-toggle="tooltip" data-placement="right" title="Modificar la contraseña no es obligatorio para aplicar cambios">';
            }
            else{
                echo '<label for="password" class="font-weight-normal" style="color: white;">*Contraseña:</label>';
                echo '<input type="password" class="form-control" id="password" name="password">';
            }
            ?>
        </div>
        <div class="form-group">
            <label for="rol" class="font-weight-normal" style="color: white;">*Rol:</label>
            <?php
            if($op == 2)
                if ($trabajador['id_rol'] == 1)
                    echo '<select class="form-control" id="rol" name="rol">
                        <option value="1" selected>Administrador</option>
                        <option value="2">Trabajador</option>
                    </select>';
                else
                    echo '<select class="form-control" id="rol" name="rol">
                        <option value="1">Administrador</option>
                        <option value="2" selected>Trabajador</option>
                    </select>';
            else
                echo '<select class="form-control" id="rol" name="rol">
                        <option value="1">Administrador</option>
                        <option value="2">Trabajador</option>
                    </select>';
            ?>
        </div>
        <div class="form-group">
            <label for="number" class="font-weight-normal" style="color: white;">*Numero telefonico:</label>
            <?php
            if($op == 2)
                echo '<input type="number" class="form-control" id="number" name="number" value="' . $trabajador['Telefono'] . '">';
            else
                echo '<input type="number" class="form-control" id="number" name="number">';
            ?>
        </div>
        <div class="form-group">
            <label for="state" class="font-weight-normal" style="color: white;">*Estado:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="state" name="state" value="' . $trabajador['Estado'] . '">';
            else
                echo '<input type="text" class="form-control" id="state" name="state">';
            ?>
        </div>
        <div class="form-group">
            <label for="city" class="font-weight-normal" style="color: white;">*Ciudad:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="city" name="city" value="' . $trabajador['Ciudad'] . '">';
            else
                echo '<input type="text" class="form-control" id="city" name="city">';
            ?>
        </div>
        <div class="form-group">
            <label for="street" class="font-weight-normal" style="color: white;">*Calle y número:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="street" name="street" value="' . $trabajador['CalleyNum'] . '">';
            else
                echo '<input type="text" class="form-control" id="street" name="street">';
            ?>
        </div>
        <input type="hidden" name="operation" value="<?php echo $op; ?>">
        <input type="hidden" name="workerID" value="<?php echo $workerID; ?>">
        <div class="text-center mt-3">
            <?php
            echo '<button type="button" class="buttonMSA-form" formtarget="_blank" onclick="validate('.$op.')">';
                if($op == 1) echo 'Añadir';
                else if($op == 2) echo 'Modificar';

                echo '</button>';
            ?>
        </div>
    </form>
</div>

<script>
    function validate(op){
        if (op == 1){
            if (document.trabajadores.Foto.value.length === 0){
                Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir una foto.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                document.trabajadores.Foto.focus()
                return 0
            }
        }
        if(document.trabajadores.Name.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un nombre',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.trabajadores.Name.focus()
            return 0
        }
        if(document.trabajadores.LastName.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un apellido.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.trabajadores.LastName.focus()
            return 0
        }
        if(op == 1){
            if(document.trabajadores.password.value.length === 0){
                Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir una contraseña.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                document.trabajadores.password.focus()
                return 0
            }
        }
        if(document.trabajadores.number.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un número de teléfono.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.trabajadores.number.focus()
            return 0
        }
        if(document.trabajadores.state.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un estado.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.trabajadores.state.focus()
            return 0
        }
        if(document.trabajadores.city.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir una ciudad.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.trabajadores.city.focus()
            return 0
        }
        if(document.trabajadores.street.value.length === 0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir una calle y número.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.trabajadores.street.focus()
            return 0
        }
        if(op == 1){
            Swal.fire({
                title: '¡Trabajador añadido!',
                text: 'El trabajador se ha añadido correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.trabajadores.submit()
                }
            });
        } else if (op == 2){
            Swal.fire({
                title: '¡Datos de trabajador modificados!',
                text: 'Los datos del trabajador se han modificado exitosamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.trabajadores.submit()
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