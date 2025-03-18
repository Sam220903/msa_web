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
    <title>Detalles del proveedor</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include '../Libraries/header.php';
include_once '../Repository/proveedorRepository.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $supplierID = $_POST['supplierID'];
    $operation = $_POST['operation'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $time_supplies = $_POST['time_supplies'];
    ?>
    <div class="container mt-4" style="padding: 100px">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-md">
                    <div class="card-header text-white text-center" style="background-color: #354656">
                        <h5 class="mb-0">Detalles del proveedor</h5>
                    </div>
                    <div class="card-body text-white justify-content-center text-center" style="background-color: #1d2e3d">
                        <p><strong>Nombre:</strong> <?php echo $name; ?></p>
                        <p><strong>Teléfono:</strong> <?php echo $phone; ?></p>
                        <p><strong>Lapto de surtido:</strong> <?php echo $time_supplies; ?></p>
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
if($_SERVER['REQUEST_METHOD']==='GET'){
    if(isset($_GET['operation'])) $operation = $_GET['operation'];
    if(isset($_GET['supplierID'])) $supplier_delete = $_GET['supplierID'];
    else $supplier_delete = null;
}
?>

<?php
include '../Libraries/JQueries.php';
    if($operation == 1){
        if(!insertarProveedor($name, $phone, $time_supplies)){
            echo '<script>alert("Error al agregar proveedor")</script>';
        }
    } elseif ($operation == 2) {
        if(!actualizarProveedor($supplierID, $name, $phone, $time_supplies)){
            echo '<script>alert("Error al actualizar proveedor")</script>';
        }
    } elseif ($operation == 3){
        // Código para hacer un borrado físico del producto
        /*
        if(eliminarProveedor($supplier_delete)){
            echo '<script>alert("Proveedor eliminado correctamente")</script>';
        }else{
            echo '<script>alert("Error al eliminar proveedor")</script>';
        }
        echo '<script>location.href = "../Views/ProveedoresView.php";</script>';
         */

        // Código para hacer un borrado lógico del proveedor
        if(ocultarProveedor($supplier_delete)){
            echo '<script>
                  Swal.fire({
                      title: "Proveedor eliminado correctamente",
                      icon: "success",
                      confirmButtonText: "Aceptar"
                  }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "../Views/ProveedoresView.php";
                    }
                  });
                  </script>';
        }else{
            echo '<script>
                    Swal.fire({
                        title: "Error al eliminar proveedor",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = "../Views/ProveedoresView.php";
                        }
                    });
                    </script>';
        }
    }
?>
</body>
</html>