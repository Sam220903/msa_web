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
    include_once '../Repository/productosRepository.php';

    if(isset($_GET['operation'])) $op = $_GET['operation']; else $op = null;
    if(isset($_GET['productID']) && $op == 2){
        $productID = $_GET['productID'];
        $producto = getProductoById($productID);
    } else {
        $productID = null;
    }

    echo '<meta charset="UTF-8">';
    if ($op == 1) echo '<title>Agregar Producto</title>';
    else if ($op == 2) echo '<title>Modificar Producto</title>';

    include '../Libraries/linkStyles.php';

    echo '<link rel="stylesheet" href="../Css/style.css">';
    ?>
</head>
<body>
<?php
include '../Libraries/header.php';
?>
<div class= "container-fluid" style="padding: 100px">
    <form name="products" method="POST" action="../Controllers/ControlProducto.php" class="border rounded p-4 bg-custom mx-auto" style="max-width: 30%" enctype="multipart/form-data">
        <?php
        if($op == 1) echo '<h2 class="text-center">Añadir Producto</h2>';
        else if ($op == 2) echo '<h2 class="text-center">Modificar Producto</h2>';
        ?>
        <div class="form-group d-flex justify-content-center">
            <?php
            if($op == 2){
                echo '<img src="../' . $producto['Imagen'] . '" alt="" style="width: 150px;">';
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
            <label for="productName" class="font-weight-normal">*Nombre:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="productName" name="productName" value="' . $producto['Nombre_producto'] . '">';
            else
                echo '<input type="text" class="form-control" id="productName" name="productName">';
            ?>
        </div>
        <div class="form-group">
            <label for="type" class="font-weight-normal">*Tipo:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="type" name="type" value="' . $producto['Tipo'] . '">';
            else
                echo '<input type="text" class="form-control" id="type" name="type">';
            ?>
        </div>

        <div class="form-group">
            <label for="presentation" class="font-weight-normal">*Presentación:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="presentation" name="presentation" value="' . $producto['Presentacion'] . '">';
            else
                echo '<input type="text" class="form-control" id="presentation" name="presentation">';
            ?>
        </div>
        <div class="form-group">
            <label for="content" class="font-weight-normal">*Contenido:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="content" name="content" value="' . $producto['Contenido'] . '">';
            else
                echo '<input type="text" class="form-control" id="content" name="content">';
            ?>
        </div>
        <div class="form-group">
            <label for="brand" class="font-weight-normal">*Marca:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="brand" name="brand" value="' . $producto['Marca'] . '">';
            else
                echo '<input type="text" class="form-control" id="brand" name="brand">';
            ?>
        </div>
        <div class="form-group">
            <label for="price" class="font-weight-normal">*Precio:</label>
            <?php
            if($op == 2)
                echo '<input type="text" class="form-control" id="price" name="price" value="' . $producto['Precio_publico'] . '">';
            else
                echo '<input type="text" class="form-control" id="price" name="price">';
            ?>
        </div>
        <div class="form-group">
            <label for="expiration" class="font-weight-normal">*Fecha de Caducidad:</label>
            <?php
            if($op == 2){
                $expiracion = date('Y-m-d', strtotime($producto['Fecha_caducidad']));
                echo '<input type="date" class="form-control" id="expiration" name="expiration" value="' . $expiracion . '">';
            }
            else
                echo '<input type="date" class="form-control" id="expiration" name="expiration">';
            ?>
        </div>
        <input type="hidden" name="productID" value="<?php echo $productID; ?>">
        <input type="hidden" name="operation" value=<?php echo $op?>>
        <div class="text-center mt-3">
            <?php
            echo '<button type="button" class="buttonMSA-form" formtarget="_blank" onclick="validate('.$op.')" id="enviarproducto">';

                if($op == 1) echo 'Añadir';
                else if ($op == 2) echo 'Modificar';

            echo '</button>';
            ?>
        </div>
    </form>
</div>

<script>
    function validate(op){
        if(document.products.productName.value.length === 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar un nombre para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            document.products.productName.focus()
            return 0
        }
        if(document.products.type.value.length === 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar un tipo para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.type.focus()
            return 0
        }
        if(document.products.presentation.value.length === 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar una presentación para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.presentation.focus()
            return 0
        }
        if(document.products.content.value.length === 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar el contenido para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.content.focus()
            return 0
        }
        if(document.products.brand.value.length === 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar una marca para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.brand.focus()
            return 0
        }
        if(document.products.price.value.length === 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar un precio para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.price.focus()
            return 0
        } else if(isNaN(document.products.price.value)){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar un número para el precio.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.price.focus()
            return 0
        } else if(Number(document.products.price.value) <= 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar un número mayor a 0 para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.price.focus()
            return 0
        }
        if(document.products.expiration.value.length === 0){
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar una fecha de caducidad para el producto.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            document.products.expiration.focus()
            return 0
        }
        if (op == 1){
            Swal.fire({
                title: '¡Producto añadido!',
                text: 'El producto se ha añadido correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.products.submit();
                }
            });
        } else if (op == 2){
            Swal.fire({
                title: '¡Producto modificado!',
                text: 'El producto ha sido modificado exitosamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.products.submit();
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