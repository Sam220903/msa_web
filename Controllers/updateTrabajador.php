<?php
global $target_path;
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
include_once '../Repository/trabajadoresRepository.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['workerID'];
    $photoName = $_FILES['Foto']['name'];
    $photoTmp = $_FILES['Foto']['tmp_name'];
    $Name = $_POST['Name'];
    $LastName = $_POST['LastName'];
    $password = md5($_POST['password']);
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
                        <img style="border-radius: 20px; margin-bottom: 15px; max-height: 200px;" src="<?php echo 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($photoTmp)) ?>" alt="" style="width: 150px;" class="mx-auto">
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
?>

<?php
    include '../Libraries/JQueries.php';
    if(!empty($photoTmp) && !empty($photoName)){
        $imagenActual = getImgTrabajador($id);
        if($imagenActual != null){
            unlink('../'.$imagenActual);
        }

        $directory = "../assets/trabajadores";
        $relDirectory = "assets/trabajadores";
        $dir = opendir($directory);
        $target_path = $relDirectory . '/' . $photoName;
        $path_absolute = $directory . '/' . $photoName;

        if(actualizarTrabajador($id, $Name, $LastName, $number, $state, $city, $street, $password, $target_path, $role)){
            if (!move_uploaded_file($photoTmp, $path_absolute)) {
                echo '<script>alert("Error al subir la imagen")</script>';
            }
        } else {
            echo '<script>alert("Error al agregar trabajador")</script>';
        }

        closedir($dir); 
    }else{
        if(!actualizarTrabajador($id, $Name, $LastName, $number, $state, $city, $street, $password, $target_path, $role)){
            echo '<script>alert("Error al agregar trabajador")</script>';
        }
    }
?>
</body>
</html>