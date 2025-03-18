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
    <title>Detalles del producto</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include '../Libraries/header.php';
include_once '../Repository/productosRepository.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['productID'];
    $operation = $_POST['operation'];
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $presentation = $_POST['presentation'];
    $content = $_POST['content'];
    $brand = $_POST['brand'];
    $expiration = $_POST['expiration'];

    $extension = pathinfo($imageName, PATHINFO_EXTENSION);



    ?>
    <div class="container mt-4" style="padding: 100px">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-md">
                    <div class="card-header text-white text-center" style="background-color: #354656">
                        <h5 class="mb-0">Detalles del producto</h5>
                    </div>
                    <div class="card-body text-white justify-content-center text-center" style="background-color: #1d2e3d">
                        <?php
                        if((!isset($imageName) || $imageName == "") || (!isset($imageTmp) || $imageTmp == "")) {
                            echo '<img src="../'.getImgProducto($productID).'" alt="" style="width: 150px;" class="mx-auto">';
                        } else {
                            echo '<img src="' . 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($imageTmp)) . '" alt="" style="width: 150px;" class="mx-auto">';
                        }
                        ?>
                        <p><strong>Nombre:</strong> <?php echo $productName; ?></p>
                        <p><strong>Tipo:</strong> <?php echo $type; ?></p>
                        <p><strong>Presentación:</strong> <?php echo $presentation; ?></p>
                        <p><strong>Contenido:</strong> <?php echo $content; ?></p>
                        <p><strong>Marca:</strong> <?php echo $brand; ?></p>
                        <p><strong>Fecha de Caducidad:</strong> <?php echo $expiration; ?></p>
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
    if(isset($_GET['productID'])) $product_delete = $_GET['productID'];
    else $product_delete = null;
}
?>

<?php
include '../Libraries/JQueries.php';
if($operation == 1){
    $directory = "../assets/productos";
    $relDirectory = "assets/productos";
    $dir = opendir($directory);
    $target_path = $relDirectory . "/" .$imageName;
    $path_absolute = $directory . "/" . $imageName;
    if(insertarProducto($productName, $type, $presentation, $content, $brand, $expiration, $target_path)){
        $product_id = getIDUltimoRegistro();
        if(!insertarPrecio($price, $product_id)){
            echo "<script>alert('Error al agregar el precio del producto')</script>";
        }
        move_uploaded_file($imageTmp, $path_absolute);
    } else {
        echo "<script>alert('Error al agregar el producto')</script>";
    }
    closedir($dir);
} elseif ($operation == 2){
    if (!empty($imageTmp) && !empty($imageName)){
        $imagenActual = getImgProducto($productID);
        if ($imagenActual != null){
            unlink('../'.$imagenActual);
        }

        $directory = "../assets/productos";
        $relDirectory = "assets/productos";
        $dir = opendir($directory);
        $target_path = $relDirectory . "/" .$imageName;
        $path_absolute = $directory . "/" . $imageName;
        if(actualizarProducto($productID, $productName, $type, $presentation, $content, $brand, $expiration, $target_path)){
            if ($price != getPrecioActual($productID)){
                if(!actualizarPrecio($price, $productID)){
                    echo "<script>alert('Error al agregar el precio del producto')</script>";
                }
            }
            move_uploaded_file($imageTmp, $path_absolute);
        } else {
            echo "<script>alert('Error al agregar el producto')</script>";
        }
        closedir($dir);
    } else {
        if (actualizarProducto($productID, $productName, $type, $presentation, $content, $brand, $expiration, null)){
            if ($price != getPrecioActual($productID)){
                if(!actualizarPrecio($price, $productID)){
                    echo "<script>alert('Error al agregar el precio del producto')</script>";
                }
            }
        } else {
            echo "<script>alert('Error al agregar el producto')</script>";
        }
    }
} elseif ($operation == 3){
    // Código para hacer un borrado físico del producto

    /*
     $foto = getImgProducto($product_delete);
    if ($foto != null) {
        unlink("../".$foto);
    }
    if (eliminarProducto($product_delete)) {
        echo '<script>alert("Producto eliminado correctamente")</script>';
    } else {
        echo '<script>alert("Error al eliminar producto")</script>';
    }
     * */

    // Código para hacer un borrado lógico del producto
    if (ocultarProducto($product_delete)){
        echo '<script>
                Swal.fire({
                    title: "Producto eliminado correctamente",
                    icon: "success",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "../Views/ProductosView.php";
                    }
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    title: "Error al eliminar producto",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "../Views/ProductosView.php";
                    }
                });
              </script>';
    }

}
?>
</body>
</html>