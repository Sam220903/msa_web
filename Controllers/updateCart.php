<?php
session_start();

if(isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['type'])){
    $id = $_POST['id'];
    $quantity = (int) $_POST['quantity'];
    $type = $_POST['type'];

    if($type === 'product'){
        if($quantity > 0){
            $_SESSION['cart']['products'][$id] = $quantity;
        }else{
            unset($_SESSION['cart']['products'][$id]);
        }
    }elseif ($type === 'service') {
        if($quantity > 0){
            $_SESSION['cart']['services'][$id] = $quantity;
        }else{
            unset($_SESSION['cart']['services'][$id]);
        }
    }

    echo json_encode(['success' => true]);
}else {
    echo json_encode(['success' => false]);
}