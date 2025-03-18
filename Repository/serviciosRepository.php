<?php
require_once 'Connection.php';
$db = new Repository\Connection();
$connection = $db->connect();

function getServicios()
{
    global $connection;
    $sql = "SELECT Imagen, Compania, Precio, ID FROM msa_servicios WHERE Visibilidad = 1";
    $result = $connection->query($sql);
    $servicios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
    }
    return json_encode($servicios);
}

function getServicioById($id)
{
    global $connection;
    $sql = "SELECT s.ID, s.Compania, s.Precio, s.Imagen
            FROM msa_servicios s
            WHERE s.ID = %d";

    $sql = sprintf($sql, $id);

    $result = $connection->query($sql);
    if ($result && $result->num_rows == 1) {
        return $result->fetch_assoc();
    }

    return null;
}


function insertarServicio($compania, $precio, $imagen){
    global $connection;
    $compania = $connection->real_escape_string($compania);
    $precio = $connection->real_escape_string($precio);
    $imagen = $connection->real_escape_string($imagen);
    $sql = "INSERT INTO msa_servicios (Compania, Precio, Imagen) 
            VALUES ('$compania', '$precio', '$imagen')";
    $result = $connection->query($sql);
    return $result;
}

function getImgServicio($id){
    global $connection;
    $sql = "SELECT Imagen FROM msa_servicios WHERE ID = %d";
    $sql = sprintf($sql, $id);
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Imagen'];
    }
    return null;
}

function eliminarServicio($id){
    global $connection;
    $sql = "DELETE FROM msa_servicios WHERE ID = %d";
    $sql = sprintf($sql, $id);
    $result = $connection->query($sql);
    return $result;
}

function actualizarServicio($id, $compania, $precio, $imagen){
    global $connection;
    $compania = $connection->real_escape_string($compania);
    $precio = $connection->real_escape_string($precio);
    if (!isset($imagen) or $imagen == '') $imagen = getImgServicio($id);
    else  $imagen = $connection->real_escape_string($imagen);

    $sql = "UPDATE msa_servicios SET Compania = '$compania', 
            Precio = '$precio', Imagen = '$imagen' 
            WHERE ID = %d";
    $sql = sprintf($sql, $id);
    $result = $connection->query($sql);
    return $result;
}

function ocultarServicio($id){
    global $connection;
    $id = $connection->real_escape_string($id);
    $query = "UPDATE msa_servicios SET Visibilidad = 0 WHERE ID = $id";
    $result = $connection->query($query);
    return $result;
}