<?php
session_start();
if (!isset($_SESSION['user'])) {
    // Si no hay sesión activa, redirigir al usuario al inicio de sesión
    header("Location: ../");
    exit();
} else if (!isset($_POST['cart'])) {
    // Si no se recibieron datos del carrito, redirigir al usuario a la página de productos
    header("Location: ../Views/ProductosView.php");
    exit();
}

include '../Repository/VentasRepository.php';

// Obtener los datos del carrito del cuerpo de la solicitud
$cartData = json_decode($_POST['cart'], true);

// Inicializar arrays para almacenar los datos separados
$productIds = [];
$productQuantities = [];
$serviceIds = [];
$serviceQuantities = [];

// Separar los productos
foreach ($cartData['products'] as $id => $quantity) {
    $productIds[] = $id;
    $productQuantities[] = $quantity;
}

// Separar los servicios
foreach ($cartData['services'] as $id => $quantity) {
    $serviceIds[] = $id;
    $serviceQuantities[] = $quantity;
}

// Realizar las operaciones necesarias con los datos
for ($i = 0; $i < count($productIds); $i++) {
    $result = registrarVenta($productIds[$i], $_SESSION['id'], $productQuantities[$i]);
    if ($result == 0){
        // Enviar una respuesta de error al cliente
        echo json_encode(['success' => false]);
        exit();
    }
}

for ($i = 0; $i < count($serviceIds); $i++) {
    $result = registrarVentaServicio($serviceIds[$i], $_SESSION['id']);
}

// Enviar una respuesta de éxito al cliente
echo json_encode(['success' => true]);