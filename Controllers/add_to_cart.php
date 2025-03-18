<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = ['products' => [], 'services' => []];
}

// Check if the product ID is sent to the script
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart']['products'][$product_id])) {
        $_SESSION['cart']['products'][$product_id]++; // Increment if already in the cart
    } else {
        $_SESSION['cart']['products'][$product_id] = 1; // Add new item to the cart
    }

    echo json_encode(['success' => true]);
}elseif (isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart']['services'][$service_id])) {
        $_SESSION['cart']['services'][$service_id]++; // Increment if already in the cart
    } else {
        $_SESSION['cart']['services'][$service_id] = 1; // Add new item to the cart
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
