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
    <title>Detalles del trabajador</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include '../Libraries/header.php';
include_once '../Repository/trabajadoresRepository.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workerID = $_POST['workerID'];
    $operation = $_POST['operation'];
    $photoName = $_FILES['Foto']['name'];
    $photoTmp = $_FILES['Foto']['tmp_name'];
    $Name = $_POST['Name'];
    $LastName = $_POST['LastName'];
    if (isset($_POST['password']) && $_POST['password'] != '') $password = md5($_POST['password']);
    else $password = null;
    $role = $_POST['rol'];
    $number = $_POST['number'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $street = $_POST['street'];

    $extension = pathinfo($photoName, PATHINFO_EXTENSION);

    ?>
    <div class="container mt-4" style="padding: 100px">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-md">
                    <div class="card-header text-white text-center" style="background-color: #354656">
                        <h5 class="mb-0">Detalles del trabajador</h5>
                    </div>
                    <div class="card-body text-white justify-content-center text-center" style="background-color: #1d2e3d">
                        <?php
                        if((!isset($photoName) || $photoName == "") || (!isset($photoTmp) || $photoTmp == "")) {
                            echo '<img src="../'.getImgTrabajador($workerID).'" alt="" style="width: 150px;" class="mx-auto">';
                        } else {
                            echo '<img src="' . 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($photoTmp)) . '" alt="" style="width: 150px;" class="mx-auto">';
                        }
                        ?>
                        <p><strong>Nombre:</strong> <?php echo $Name; ?></p>
                        <p><strong>Apellido:</strong> <?php echo $LastName; ?></p>
                        <p></p><strong>Rol:</strong> <?php echo $role == 1 ? 'Administrador' : 'Trabajador'; ?></p>
                        <p><strong>Número telefónico:</strong> <?php echo $number; ?></p>
                        <p><strong>Estado:</strong> <?php echo $state; ?></p>
                        <p><strong>Ciudad:</strong> <?php echo $city; ?></p>
                        <p><strong>Calle y numero:</strong> <?php echo $street; ?></p>
                        <div class="text-center mt-3">
                            <a href="#" onclick="goBack2()" class="buttonMSA-form">Aceptar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['operation'])) $operation = $_GET['operation'];
    if(isset($_GET['workerID'])) $worker_delete = $_GET['workerID'];
    else $worker_delete = null;
}
?>

<?php
    include '../Libraries/JQueries.php';
    if($operation == 1){
        $directory = "../assets/trabajadores";
        $relDirectory = "assets/trabajadores";
        $dir = opendir($directory);
        $target_path = $relDirectory . '/' . $photoName;
        $path_absolute = $directory . '/' . $photoName;

        if(insertarTrabajador($Name, $LastName, $number, $state, $city, $street, $password, $target_path, $role)){
            if (move_uploaded_file($photoTmp, $path_absolute)) {
                $photo = $target_path;
            } else {
                $photo = null;
            }
        } else {
            echo "<script>Swal.fire({
                title: 'Error',
                text: 'Error al agregar trabajador',
                icon: 'error',
                confirmButtonText: 'OK'
            });</script>";
        }
        closedir($dir);
    } elseif ($operation == 2){
        if(!empty($photoTmp) && !empty($photoName)){
            $imagenActual = getImgTrabajador($workerID);
            if($imagenActual != null){
                unlink('../'.$imagenActual);
            }

            $directory = "../assets/trabajadores";
            $relDirectory = "assets/trabajadores";
            $dir = opendir($directory);
            $target_path = $relDirectory . '/' . $photoName;
            $path_absolute = $directory . '/' . $photoName;

            if(actualizarTrabajador($workerID, $Name, $LastName, $number, $state, $city, $street, $password, $target_path, $role)){
                if (!move_uploaded_file($photoTmp, $path_absolute)) {
                    echo "<script>Swal.fire({
                        title: 'Error',
                        text: 'Error al subir la imagen',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });</script>";
                }
            } else {
                echo "<script>Swal.fire({
                    title: 'Error',
                    text: 'Error al agregar trabajador',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });</script>";
            }

            closedir($dir);
        }else{
            if(!actualizarTrabajador($workerID, $Name, $LastName, $number, $state, $city, $street, $password, null, $role)){
                echo "<script>Swal.fire({
                    title: 'Error',
                    text: 'Error al agregar trabajador',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });</script>";
            }
        }
    } elseif ($operation == 3){
        // Código para hacer un borrado físico del trabajador

        /*
        $foto = getImgTrabajador($worker_delete);
        if ($foto != null) {
            unlink("../".$foto);
        }
        if (eliminarTrabajador($worker_delete)) {
            echo '<script>alert("Trabajador eliminado correctamente")</script>';
        } else {
            echo '<script>alert("Error al eliminar trabajador")</script>';
        }
        echo '<script>location.href = "../Views/TrabajadoresView.php";</script>';
        */

        // Código para hacer un borrado lógico del trabajador
        if(ocultarTrabajador($worker_delete)){
            echo '<script>
                  Swal.fire({
                      title: "Trabajador eliminado correctamente",
                      icon: "success",
                      confirmButtonText: "Aceptar"
                  }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "../Views/TrabajadoresView.php";
                    }
                  });
                  </script>';
        } else {
            echo '<script>
                  Swal.fire({
                      title: "Error al eliminar trabajador",
                      icon: "error",
                      confirmButtonText: "Aceptar"
                  }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "../Views/TrabajadoresView.php";
                    }
                  });
                  </script>';
        }
    }
?>
</body>
</html>