<?php
require_once 'Connection.php';
$db = new Repository\Connection();
$connection = $db->connect();


function registrarVenta($productoID, $trabajador, $cantidad){
    global $connection;
    $cantidad = $connection->real_escape_string($cantidad);
    $productoID = $connection->real_escape_string($productoID);
    $trabajador = $connection->real_escape_string($trabajador);
    $query = "CALL msa_ventas ($productoID, $trabajador, $cantidad, NOW(), @respuesta)";
    $result = $connection->query($query);
    $query = "SELECT @respuesta as respuesta";
    $result = $connection->query($query);
    $respuesta = "";
    if ($result->num_rows > 0) {
        $respuesta = $result->fetch_assoc()['respuesta'];
    }
    return $respuesta;
}

function registrarVentaServicio($servicioID, $trabajador){
    global $connection;
    $servicioID = $connection->real_escape_string($servicioID);
    $trabajador = $connection->real_escape_string($trabajador);
    $query = "INSERT INTO msa_ventas_servicios VALUES ( NOW(), $trabajador, $servicioID)";
    $result = $connection->query($query);
    return $result;
}