<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Si no hay sesión activa, redirigir al usuario al inicio de sesión
    header("Location: ../");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">git 
    <title>Ventas</title>
    <?php
    include '../Libraries/linkStyles.php';
    ?>
    <link rel="stylesheet" href="../Css/style.css">
</head>
<body>
<?php
include '../Libraries/header.php';
?>
<div class="container my-5" style="padding: 100px">
    <h1 class="text-center mb-4">Carrito de Compras</h1>
    <div class="row">
        <div class="col-md-8">

            <h2>Productos</h2>
            <?php
            include_once "../Repository/productosRepository.php";
            if(isset($_SESSION['cart']['products']) && count($_SESSION['cart']) > 0){
                foreach($_SESSION['cart']['products'] as $product_id => $quantity){
                    $minusBtnId = "minus-product-$product_id";
                    $plusBtnId = "plus-product-$product_id";
                    $inputId = "quantity-product-$product_id";
                    $price = "product-price-$product_id";
                    $producto = getProductoById($product_id);
                    if($producto){
                        //$product = getProductoById($product_id);
                        echo '<div class="card mb-4" id="product-card-' . $product_id . '">';
                        echo '<div class="card-body">';
                        echo '<div class="row">';
                        echo '<div class="col-md-3"><img src="../' . $producto['Imagen'] . '" class="img-fluid" alt="' . $producto['Nombre_producto'] . '"></div>';
                        echo '<div class="col-md-9">';
                        echo '<h5 class="card-title">' . $producto['Nombre_producto'] . '</h5>';
                        echo '<p class="card-text">Descripción del producto</p>';
                        echo '<p class="card-text" id="'.$price.'">' . $producto['Precio_publico'] . '</p>';
                        echo '<div class="input-group mb-3">';
                        echo '<div class="input-group-prepend">';
                        echo '<button class="btn btn-outline-secondary" type="button" id="' . $minusBtnId . '">-</button>';
                        echo '</div>';
                        echo '<input type="text" class="form-control text-center" id="' . $inputId . '" value="' . $quantity . '">';
                        echo '<div class="input-group-append">';
                        echo '<button class="btn btn-outline-secondary" type="button" id="' . $plusBtnId . '">+</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                }
            }else{
                echo '<p>No hay productos en el carrito</p>';
            }
            ?>
        </div>
        <div class="col-md-4">
            <h2>Servicios</h2>
            <?php
            include_once "../Repository/serviciosRepository.php";
            if(isset($_SESSION['cart']['services']) && count ($_SESSION['cart']) > 0){
                foreach($_SESSION['cart']['services'] as $service_id => $quantity){
                    $minusBtnId = "minus-service-$service_id";
                    $plusBtnId = "plus-service-$service_id";
                    $inputId = "quantity-service-$service_id";
                    $price = "service-price-$service_id";
                    $servicio = getServicioById($service_id);
                    if($servicio){
                        echo '<div class="card mb-4" id="service-card-' . $service_id . '">';
                        echo '<div class="card-body text-white">';
                        echo '<div class="row">';
                        echo '<div class="col-md-3"><img src="../' . $servicio['Imagen'] . '" class="img-fluid" alt="' . $servicio['Compania'] . '"></div>';
                        echo '<div class="col-md-8">';
                        echo '<h5 class="card-title">' . $servicio['Compania'] .'</h5>';
                        echo '<p class="card-text" id="'.$price.'">' . $servicio['Precio'] .'</p>';
                        echo '<div class="input-group mb-3">';
                        echo '<div class="input-group-prepend">';
                        echo '<button class="btn btn-outline-secondary" type="button" id="' . $minusBtnId . '">-</button>';
                        echo '</div>';
                        echo '<input type="text" class="form-control text-center" id="' . $inputId . '" value="' . $quantity . '">';
                        echo '<div class="input-group-append">';
                        echo '<button class="btn btn-outline-secondary" type="button" id="' . $plusBtnId . '">+</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            }
            ?>            
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h3>
            <div id="totalPrice">Total: $0</div>
            <h3>
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary btn-lg btn-block" onclick="validate()" id="realizarCompra">Realizar Compra</button>
        </div>
    </div>
</div>

<?php
include '../Libraries/JQueries.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            if (event.target.tagName === 'BUTTON') {
                const parts = event.target.id.split('-');
                const action = parts[0];
                const type = parts[1];  // "product" or "service"
                const id = parts[2];
                const quantityInput = document.getElementById('quantity-' + type + '-' + id);

                if (quantityInput) {
                    let newQuantity = parseInt(quantityInput.value);
                    if (action === 'plus') {
                        newQuantity++;
                    } else if (action === 'minus') {
                        newQuantity = Math.max(newQuantity - 1, 0); // Prevents negative quantities
                    }
                    quantityInput.value = newQuantity;
                    updateCart(id, newQuantity, type); // Updates the cart in the backend
                    updateTotalPrice(); // Recalculates the total price
                }
            }
                document.querySelectorAll('[id^="quantity-product-"], [id^="quantity-service-"]').forEach(input => {
                    input.addEventListener('input', function() {
                        const parts = this.id.split('-');
                        const type = parts[1];
                        const id = parts[2];
                        const quantity = parseInt(this.value);
                        updateCart(id, quantity, type);
                        updateTotalPrice();
                    });
                });
        });
    });

    function updateCart(id, quantity, type) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../Controllers/updateCart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status == 200) {
                console.log('Cart updated');
                var response = JSON.parse(this.responseText);
                if(response.success && quantity === 0) {
                    hideCartItem(id, type);
                }
                updateTotalPrice();
            }
        };
        xhr.send('id=' + encodeURIComponent(id) + '&quantity=' + encodeURIComponent(quantity) + '&type=' + encodeURIComponent(type));
    }

    function hideCartItem(id, type){
        var cardId = type + '-card-' + id;
        var cardElement = document.getElementById(cardId);
        if (cardElement) {
            cardElement.style.display = 'none';
        }
    }

    function updateTotalPrice() {
    let total = 0;
    document.querySelectorAll('[id^="quantity-product-"], [id^="quantity-service-"]').forEach(input => {
        const id = input.id.split('-')[2];
        const type = input.id.split('-')[1];
        const quantity = parseInt(input.value);
        const priceElementId = type === 'product' ? 'product-price-' + id : 'service-price-' + id;
        const price = parseFloat(document.getElementById(priceElementId).textContent);
        total += price * quantity;
    });
    document.getElementById('totalPrice').textContent = 'Total: $' + total.toFixed(2);
    }
    function updateTotalPriceOnLoad() {
        let total = 0;

        // Verificar si hay productos en el carrito y sumar sus precios
        if (typeof <?php echo json_encode($_SESSION['cart']['products']); ?> !== 'undefined') {
            const products = <?php echo json_encode($_SESSION['cart']['products']); ?>;
            for (const [product_id, quantity] of Object.entries(products)) {
                const priceElementId = 'product-price-' + product_id;
                const price = parseFloat(document.getElementById(priceElementId)?.textContent || '0');
                total += price * quantity;
            }
        }

        // Verificar si hay servicios en el carrito y sumar sus precios
        if (typeof <?php echo json_encode($_SESSION['cart']['services']); ?> !== 'undefined') {
            const services = <?php echo json_encode($_SESSION['cart']['services']); ?>;
            for (const [service_id, quantity] of Object.entries(services)) {
                const priceElementId = 'service-price-' + service_id;
                const price = parseFloat(document.getElementById(priceElementId)?.textContent || '0');
                total += price * quantity;
            }
        }

        // Actualizar el precio total en el elemento correspondiente
        document.getElementById('totalPrice').textContent = 'Total: $' + total.toFixed(2);
    }
    window.addEventListener('DOMContentLoaded', updateTotalPriceOnLoad);
</script>

<script>
    function validate() {
        Swal.fire({
            title: 'Confirmar Compra',
            text: '¿Estás seguro de que deseas realizar la compra?',
            showCancelButton: true,
            confirmButtonText: 'Sí, realizar compra',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../Controllers/VentaController.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    console.log(this.responseText);
                    if (this.status == 200) {
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            // Limpiar el carrito después de una compra exitosa
                            clearCart();
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo realizar la compra.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                };

                // Enviar los datos del carrito al servidor
                var cartData = {
                    products: {},
                    services: {}
                };

                document.querySelectorAll('[id^="quantity-product-"]').forEach(input => {
                    const id = input.id.split('-')[2];
                    const quantity = parseInt(input.value);
                    cartData.products[id] = quantity;
                });

                document.querySelectorAll('[id^="quantity-service-"]').forEach(input => {
                    const id = input.id.split('-')[2];
                    const quantity = parseInt(input.value);
                    cartData.services[id] = quantity;
                });
                console.log(cartData);
                xhr.send('cart=' + encodeURIComponent(JSON.stringify(cartData)));
            }
        });
    }

    function clearCart() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../Controllers/clearCart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status == 200) {
                var response = JSON.parse(this.responseText);
                if (response.success) {
                    document.querySelectorAll('.card').forEach(card => {
                        card.style.display = 'none';
                    });
                    document.getElementById('totalPrice').textContent = 'Total: $0.00';

                    Swal.fire({
                        title: 'Exitoso',
                        text: 'La compra se ha realizado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo limpiar el carrito.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        };
        xhr.send();
    }

</script>
</body>
</html>