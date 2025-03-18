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
    <title>Agregar Surtido</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
</head>
<body>
<?php
include '../Libraries/header.php';
include_once '../Repository/surtidosRepository.php'

?>
<div class="container-fluid" style="padding: 100px">
    <form name="assortments" action="../Controllers/ControlSurtido.php" method="POST" class="border rounded p-4 bg-custom mx-auto" style="max-width: 30%;">
        <h2 class="text-center">Añadir surtido</h2>
        <div class="form-group">
            <label for="productID" class="font-weight-normal">*Producto:</label>
            <select class="form-control" id="productID" name="productID">
                <?php
                $productos = getNombresProductos();
                $productos = json_decode($productos);
                if (count($productos) > 0) {
                    foreach ($productos as $producto) {
                        echo '<option value="'.$producto->ID.'">'.$producto->Nombre_producto.'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="supplierID" class="font-weight-normal">*Proveedor:</label>
            <select class="form-control" id="supplierID" name="supplierID">
                <?php
                $proveedores = getNombresProveedores();
                $proveedores = json_decode($proveedores);
                if (count($proveedores) > 0) {
                    foreach ($proveedores as $proveedor) {
                        echo '<option value="'.$proveedor->ID.'">'.$proveedor->Nombre.'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="supplied_quan" class="font-weight-normal">*Cantidad a surtir:</label>
            <input type="number" class="form-control" id="supplied_quan" name="supplied_quan">
        </div>
        <div class="form-group">
            <label for="unit_cost" class="font-weight-normal">*Costo unitario:</label>
            <input type="text" class="form-control" id="unit_cost" name="unit_cost">
        </div>
        <div class="text-center mt-3">
            <button type="submit" class="buttonMSA-form" onclick="return validate()">Añadir</button>
        </div>
    </form>
</div>
<script>
    function validate(){
        if(document.assortments.productID.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un ID de producto.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.productID.focus();
            return false;
        }
        if(document.assortments.supplierID.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un ID de proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.supplierID.focus();
            return false;
        }
        if(document.assortments.supplied_quan.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir una cantidad surtida.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.supplied_quan.focus();
            return false;
        } else if(isNaN(document.assortments.supplied_quan.value) || Number(document.assortments.supplied_quan.value) <= 0){
            Swal.fire({
                    title: 'Error',
                    text: 'La cantidad surtida debe ser un número entero mayor a 0.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.supplied_quan.focus();
            return false;
        }
        if(document.assortments.unit_cost.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un costo unitario.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.unit_cost.focus();
            return false;
        } else if(isNaN(document.assortments.unit_cost.value) || Number(document.assortments.unit_cost.value) <= 0){
            Swal.fire({
                    title: 'Error',
                    text: 'El costo unitario debe ser un número mayor a 0.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.unit_cost.focus();
            return false;
        }
        if(document.assortments.date.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir una fecha de surtido.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.date.focus();
            return false;
        }
        if(document.assortments.time.value.length==0){
            Swal.fire({
                    title: 'Error',
                    text: 'Debe de añadir un horario de surtido.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            document.assortments.time.focus();
            return false;
        }
        
        return true; // Si pasa todas las validaciones, devuelve true
        
    }
</script>

<?php
include '../Libraries/JQueries.php';
?>
</body>
</html>