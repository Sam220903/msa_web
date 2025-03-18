<?php
session_start();

//limiar carrito
if(isset($_SESSION['cart'])) {
    // mostrar mensaje con los productos eliminados
    $_SESSION['cart']['products'] = [];
    $_SESSION['cart']['services'] = [];
    echo json_encode(['success' => true, 'message' => 'Cart cleared']);
}else {
    echo json_encode(['success' => false, 'message' => 'Cart not found']);
}
?>