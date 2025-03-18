<?php
require 'Connection.php';
$db = new Repository\Connection();
$connection = $db->connect();

function getProductos()
{
    global $connection;
    $sql = "SELECT p.ID, p.Imagen, p.Nombre_producto, p.Contenido, h.Precio_publico, p.Cantidad_disponible
            FROM msa_productos p 
            JOIN msa_historial_precios h ON p.ID = h.MSA_PDO_ID
            WHERE h.FECHA_FIN IS NULL AND p.Visibilidad = 1";
    $result = $connection->query($sql);
    $productos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
    return json_encode($productos);

}

function getProductoById($id)
{
    global $connection;
    $sql = "SELECT p.ID, p.Imagen, p.Nombre_producto, p.Contenido, p.Tipo, p.Presentacion, p.Marca, p.Fecha_caducidad, h.Precio_publico
            FROM msa_productos p
            JOIN msa_historial_precios h ON p.ID = h.MSA_PDO_ID
            WHERE p.ID = %d AND h.FECHA_FIN IS NULL";

    $sql = sprintf($sql, $id);

    $result = $connection->query($sql);
    if ($result && $result->num_rows == 1) {
        return $result->fetch_assoc();
    }

    return null;
}



function insertarProducto($nombre, $tipo, $presentacion, $contenido, $marca, $expiracion, $imagen){
    global $connection;
    $nombre = $connection->real_escape_string($nombre);
    $tipo = $connection->real_escape_string($tipo);
    $presentacion = $connection->real_escape_string($presentacion);
    $contenido = $connection->real_escape_string($contenido);
    $marca = $connection->real_escape_string($marca);
    $expiracion = $connection->real_escape_string($expiracion);
    $imagen = $connection->real_escape_string($imagen);

    $query = "INSERT INTO msa_productos (Nombre_producto, Tipo, Presentacion, Contenido, Marca, Fecha_caducidad, Imagen) 
              VALUES ('$nombre','$tipo','$presentacion','$contenido','$marca','$expiracion','$imagen')";
    return $connection->query($query);
}

function getIDUltimoRegistro(){
    global $connection;
    $query = "SELECT MAX(ID) ID FROM msa_productos";
    $result = $connection->query($query);
    $id = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['ID'];
    }
    return $id;
}

function insertarPrecio($precio, $producto){
    global $connection;
    $precio = $connection->real_escape_string($precio);
    $producto = $connection->real_escape_string($producto);

    $query = "INSERT INTO msa_historial_precios (Precio_publico, MSA_PDO_ID) 
              VALUES ('$precio','$producto')";
    return $connection->query($query);
}

function eliminarProducto($id){
    eliminarPrecios($id);
    global $connection;
    $id = $connection->real_escape_string($id);

    $query = "DELETE FROM msa_productos WHERE ID = $id";
    return $connection->query($query);
}

function eliminarPrecios($id){
    global $connection;
    $id = $connection->real_escape_string($id);

    $query = "DELETE FROM msa_historial_precios WHERE MSA_PDO_ID = $id";
    $result = $connection->query($query);
    
    return $result;
}

function getImgProducto($id){
    global $connection;
    $id = $connection->real_escape_string($id);

    $query = "SELECT Imagen FROM msa_productos WHERE ID = $id";
    $result = $connection->query($query);
    $foto = null;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $foto = $row['Imagen'];
    }
    return $foto;
}

function actualizarProducto($id, $nombre, $tipo, $presentacion, $contenido, $marca, $expiracion, $imagen){
    global $connection;
    $id = $connection->real_escape_string($id);
    $nombre = $connection->real_escape_string($nombre);
    $tipo = $connection->real_escape_string($tipo);
    $presentacion = $connection->real_escape_string($presentacion);
    $contenido = $connection->real_escape_string($contenido);
    $marca = $connection->real_escape_string($marca);
    $expiracion = $connection->real_escape_string($expiracion);
    
    if (!isset($imagen) or $imagen == "") $imagen = getImgProducto($id);
    else $imagen = $connection->real_escape_string($imagen);

    $query = "UPDATE msa_productos SET Nombre_producto = '$nombre', Tipo = '$tipo', 
              Presentacion = '$presentacion', Contenido = '$contenido', 
              Marca = '$marca', Fecha_caducidad = '$expiracion', Imagen = '$imagen' 
              WHERE ID = $id";

    $result = $connection->query($query);
    return $result;
}

function actualizarPrecio($precio, $producto){
    global $connection;
    $id = $connection->real_escape_string($producto);

    $query = "UPDATE msa_historial_precios SET Fecha_fin = NOW() WHERE MSA_PDO_ID = $id AND Fecha_fin IS NULL";
    $resultUpdate = $connection->query($query);

    if ($resultUpdate){
        $query = "INSERT INTO msa_historial_precios (Precio_publico, MSA_PDO_ID) 
                  VALUES ('$precio','$producto')";
        $resultInsert = $connection->query($query);
    }
    return $resultUpdate && $resultInsert;
}

function getPrecioActual($id){
    global $connection;
    $id = $connection->real_escape_string($id);

    $query = "SELECT Precio_publico FROM msa_historial_precios WHERE MSA_PDO_ID = $id AND Fecha_fin IS NULL";
    $result = $connection->query($query);
    $precio = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $precio = $row['Precio_publico'];
    }
    return $precio;
}

function ocultarProducto($id){
    global $connection;
    $id = $connection->real_escape_string($id);

    $query = "UPDATE msa_productos SET Visibilidad = 0 WHERE ID = $id";
    return $connection->query($query);
}


