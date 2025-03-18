<?php
require 'Connection.php';
$db = new Repository\Connection();
$connection = $db->connect();

function getTrabajadores($adminPass){
    global $connection;
    $query = sprintf("SELECT * FROM msa_trabajadores WHERE Password = '%s'", $connection->real_escape_string($adminPass));
    $result = $connection->query($query);
    if ($result->num_rows > 0) {
        $query = "SELECT * FROM msa_trabajadores WHERE Visibilidad = 1";
        $result = $connection->query($query);
        $trabajadores = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trabajadores[] = $row;
            }
        }
        return json_encode($trabajadores);
    }
    return null;
}

function insertarTrabajador($nombre, $apellidos, $telefono, $estado, $ciudad, $calleynum, $password, $foto, $rol){
    global $connection;
    // Escapar los valores para prevenir inyección SQL
    $nombre = $connection->real_escape_string($nombre);
    $apellidos = $connection->real_escape_string($apellidos);
    $telefono = $connection->real_escape_string($telefono);
    $estado = $connection->real_escape_string($estado);
    $ciudad = $connection->real_escape_string($ciudad);
    $calleynum = $connection->real_escape_string($calleynum);
    $password = $connection->real_escape_string($password);
    $foto = $connection->real_escape_string($foto);
    $rol = $connection->real_escape_string($rol);

    $query = "INSERT INTO msa_trabajadores (Nombre, Apellido, Telefono, Estado, Ciudad, CalleyNum, Password, Foto, id_rol) 
                VALUES ('$nombre','$apellidos','$telefono','$estado','$ciudad','$calleynum','$password','$foto','$rol')";
    $result = $connection->query($query);

    return $result;
}

function eliminarTrabajador($id){
    global $connection;
    $query = "DELETE FROM msa_trabajadores WHERE id = $id";
    $result = $connection->query($query);
    return $result;

}

function getImgTrabajador($id){
    global $connection;
    $query = "SELECT Foto FROM msa_trabajadores WHERE id = $id";
    $result = $connection->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Foto'];
    }
    return null;
}

function getTrabajadorById($id){
    global $connection;
    $query = "SELECT * FROM msa_trabajadores WHERE id = $id";
    $result = $connection->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function actualizarTrabajador($id, $nombre, $apellidos, $telefono, $estado, $ciudad, $calleynum, $password, $foto, $rol){
    global $connection;
    $nombre = $connection->real_escape_string($nombre);
    $apellidos = $connection->real_escape_string($apellidos);
    $telefono = $connection->real_escape_string($telefono);
    $estado = $connection->real_escape_string($estado);
    $ciudad = $connection->real_escape_string($ciudad);
    $calleynum = $connection->real_escape_string($calleynum);
    $rol = $connection->real_escape_string($rol);
    if (!isset($foto) || $foto == '') $foto = getImgTrabajador($id);
    else $foto = $connection->real_escape_string($foto);
    if (!isset($password) || $password == ''){
        $updatePass = '';
    } else {
        $password = $connection->real_escape_string($password);
        $updatePass = "Password = '$password',";
    }
    $query = "UPDATE msa_trabajadores SET Nombre = '$nombre', 
              Apellido = '$apellidos', Telefono = '$telefono', 
              Estado = '$estado', Ciudad = '$ciudad', 
              CalleyNum = '$calleynum', ".$updatePass." 
              Foto = '$foto', id_rol = '$rol' 
              WHERE id = $id";
    $result = $connection->query($query);
    return $result;
}

function ocultarTrabajador($id){
    global $connection;
    $query = "UPDATE msa_trabajadores SET Visibilidad = 0 WHERE id = $id";
    $result = $connection->query($query);
    return $result;
}
