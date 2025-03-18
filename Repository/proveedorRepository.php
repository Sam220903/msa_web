<?php
require 'Connection.php';
$db = new Repository\Connection();
$connection = $db->connect();

function getProveedores($adminPass){
    global $connection;
    $query = sprintf("SELECT * FROM msa_trabajadores WHERE Password = '%s'", $connection->real_escape_string($adminPass));
    $result = $connection->query($query);
    if ($result->num_rows > 0) {
        $query = "SELECT * FROM msa_proveedores WHERE Visibilidad = 1";
        $result = $connection->query($query);
        $proveedores = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $proveedores[] = $row;
            }
        }
        return json_encode($proveedores);
    }
    return null;
}


function insertarProveedor($nombre, $telefono, $lapso_surtido){
    global $connection;
    $nombre = $connection->real_escape_string($nombre);
    $telefono = $connection->real_escape_string($telefono);
    $lapso_surtido = $connection->real_escape_string($lapso_surtido);
    $query = "INSERT INTO msa_proveedores (Nombre, Telefono, Lapso_surte) 
              VALUES ('$nombre','$telefono','$lapso_surtido')";

    $result = $connection->query($query);
    return $result;
}

function eliminarProveedor($id){
    global $connection;
    $id = $connection->real_escape_string($id);
    $query = "DELETE FROM msa_proveedores WHERE ID = $id";
    $result = $connection->query($query);
    return $result;
}

function getProveedorByID($id){
    global $connection;
    $id = $connection->real_escape_string($id);
    $query = "SELECT * FROM msa_proveedores WHERE ID = $id";
    $result = $connection->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function actualizarProveedor($id, $nombre, $telefono, $lapso_surtido){
    global $connection;
    $id = $connection->real_escape_string($id);
    $nombre = $connection->real_escape_string($nombre);
    $telefono = $connection->real_escape_string($telefono);
    $lapso_surtido = $connection->real_escape_string($lapso_surtido);
    $query = "UPDATE msa_proveedores SET Nombre = '$nombre', 
              Telefono = '$telefono', Lapso_surte = '$lapso_surtido' 
              WHERE ID = $id";
    $result = $connection->query($query);
    return $result;
}

function ocultarProveedor($id){
    global $connection;
    $id = $connection->real_escape_string($id);
    $query = "UPDATE msa_proveedores SET Visibilidad = 0 WHERE ID = $id";
    $result = $connection->query($query);
    return $result;
}