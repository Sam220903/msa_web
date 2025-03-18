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
    <title>Detalles del servicio</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include '../Libraries/header.php';
include_once '../Repository/serviciosRepository.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $serviceID = $_POST['serviceID'];
    $operation = $_POST['operation'];
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $company = $_POST['company'];
    $price = $_POST['price'];

    $extension = pathinfo($imageName, PATHINFO_EXTENSION);

    ?>
    <div class="container mt-4" style="padding: 100px">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-md">
                    <div class="card-header text-white text-center" style="background-color: #354656;">
                        <h5 class="mb-0">Detalles del Servicio</h5>
                    </div>
                    <div class="card-body text-white justify-content-center text-center" style="background-color: #1d2e3d;">
                        <?php
                        if((!isset($imageName) || $imageName == "") || (!isset($imageTmp) || $imageTmp == "")) {
                            echo '<img src="../'.getImgServicio($serviceID).'" alt="" style="width: 150px;" class="mx-auto">';
                        } else {
                            echo '<img src="' . 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($imageTmp)) . '" alt="" style="width: 150px;" class="mx-auto">';
                        }
                        ?>
                        <p><strong>Compañía: </strong><?php echo $company; ?></p>
                        <p><strong>Precio: </strong><?php echo $price; ?></p>
                        <div class="text-center mt-3">
                            <a href="#" onclick="goBack2()"  class="buttonMSA-form">Aceptar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
if($_SERVER['REQUEST_METHOD']==='GET'){
    if(isset($_GET['operation'])) $operation = $_GET['operation'];
    if(isset($_GET['serviceID'])) $service_delete = $_GET['serviceID'];
    else $service_delete = null;
}
?>

<?php
include '../Libraries/JQueries.php';
    if($operation == 1){
        $directory = '../assets/servicios';
        $relDirectory = 'assets/servicios';
        $dir = opendir($directory);
        $target_path = $relDirectory . '/' . $imageName;
        $path_absolute = $directory . '/' . $imageName;
        if (insertarServicio($company, $price, $target_path)) {
            move_uploaded_file($imageTmp, $path_absolute);
        } else {
            echo '<script>alert("Error al agregar servicio")</script>';
        }
        closedir($dir);
    } elseif ($operation == 2){
        if(!empty($imageName) && !empty($imageTmp)){
            $imagenActual = getImgServicio($serviceID);
            if ($imagenActual != null){
                unlink('../'.$imagenActual);
            }

            $directory = '../assets/servicios';
            $relDirectory = 'assets/servicios';
            $dir = opendir($directory);
            $target_path = $relDirectory . '/' . $imageName;
            $path_absolute = $directory . '/' . $imageName;
            if (actualizarServicio($serviceID, $company, $price, $target_path)) {
                move_uploaded_file($imageTmp, $path_absolute);
            } else {
                echo '<script>alert("Error al agregar servicio")</script>';
            }
            closedir($dir);
        } else {
            if (!actualizarServicio($serviceID, $company, $price, null)) {
                echo '<script>alert("Error al agregar servicio")</script>';
            }
        }
    } elseif ($operation == 3){
        /*
        // Código para hacer un borrado físico del producto
        $foto = getImgServicio($service_delete);
        if ($foto != null) {
            unlink("../".$foto);
        }
        if (eliminarServicio($service_delete)) {
            echo '<script>alert("Servicio eliminado correctamente")</script>';
        } else {
            echo '<script>alert("Error al eliminar servicio")</script>';
        }
        echo '<script>location.href = "../Views/ServiciosView.php";</script>';
         */

        // Código para hacer un borrado lógico del servicio
        if(ocultarServicio($service_delete)) {
            echo '<script>
                  Swal.fire({
                      title: "Servicio eliminado correctamente",
                      icon: "success",
                      confirmButtonText: "Aceptar"
                  }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "../Views/ServiciosView.php";
                    }
                  });
                  </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        title: "Error al eliminar servicio",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = "../Views/ServiciosView.php";
                        }
                    });
                  </script>';
        }
    }
?>
</body>
</html>