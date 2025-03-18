<?php
require_once 'Connection.php';
$db = new Repository\Connection();
$connection = $db->connect();


function insertarSurtido($cantidad, $costo, $producto, $proveedor){
    global $connection;
    $cantidad = $connection->real_escape_string($cantidad);
    $costo = $connection->real_escape_string($costo);
    $producto = $connection->real_escape_string($producto);
    $proveedor = $connection->real_escape_string($proveedor);
    $query = "CALL msa_surtidos (NOW(),'$cantidad','$costo','$producto','$proveedor',@respuesta)";
    $result = $connection->query($query);
    
    $query = "SELECT @respuesta as respuesta";
    $result = $connection->query($query);
    $respuesta = 0;
    if ($result->num_rows > 0) {
        $respuesta = $result->fetch_assoc()['respuesta'];
    }
    return $respuesta;
}

function getNombresProductos(){
    global $connection;
    $query = "SELECT ID, Nombre_producto FROM msa_productos WHERE Visibilidad = 1";
    $result = $connection->query($query);
    $productos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
    return json_encode($productos);
}
function getNombresProveedores(){
    global $connection;
    $query = "SELECT ID, Nombre FROM msa_proveedores";
    $result = $connection->query($query);
    $proveedores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = $row;
        }
    }
    return json_encode($proveedores);
}

