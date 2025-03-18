<?php
require 'Connection.php';
$db = new Repository\Connection();
$connection = $db->connect();
function getRole($username)
{
    global $connection;
    $query = sprintf("SELECT r.rol 
            FROM msa_trabajadores u JOIN msa_roles r ON u.id_rol = r.id 
            WHERE u.Nombre LIKE '%s'", $connection->real_escape_string($username));
    return $connection->query($query);
}
function getLogin($user, $password)
{
    global $connection;
    $query = sprintf("SELECT * FROM msa_trabajadores WHERE Nombre LIKE '%s' AND Password = '%s'", $connection->real_escape_string($user), $connection->real_escape_string($password));
    return $connection->query($query);
}

function getPicTrabajador($password)
{
    global $connection;
    $query = sprintf("SELECT Foto FROM msa_trabajadores WHERE Password = '%s'", $connection->real_escape_string($password));
    return $connection->query($query);
}

function calcularTotalVentasProductos(){
    global $connection;
    $sql = "SELECT SUM(Cantidad) Total FROM msa_ventas_productos";
    $result = $connection->query($sql);
    $total = 0;
    if ($result->num_rows > 0) {
        $total = $result->fetch_assoc()['Total'];
    }
    return $total;
}

function calcularTotalVentasServicios(){
    global $connection;
    $sql = "SELECT COUNT(*) Total FROM msa_ventas_servicios";
    $result = $connection->query($sql);
    $total = 0;
    if ($result->num_rows > 0) {
        $total = $result->fetch_assoc()['Total'];
    }
    return $total;
}

function calcularTotalSurtidos(){
    global $connection;
    $sql = "SELECT sum(Cantidad_surtida) Total FROM msa_surtidos";
    $result = $connection->query($sql);
    $total = 0;
    if ($result->num_rows > 0) {
        $total = $result->fetch_assoc()['Total'];
    }
    return $total;
}

function getTop3Productos(){
    global $connection;
    $sql = "SELECT pdo.Nombre_producto Producto, SUM(vp.Cantidad) Cantidad, pdo.Imagen Imagen
            FROM msa_productos pdo JOIN msa_ventas_productos vp ON (pdo.ID = vp.MSA_PDO_ID) 
            JOIN msa_trabajadores tbr ON (tbr.ID = vp.MSA_TBR_ID)
            GROUP BY Producto,Imagen ORDER BY Cantidad DESC 
            LIMIT 3;";
    $result = $connection->query($sql);
    $top3 = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $top3[] = $row;
        }
    }
    return json_encode($top3);
}

function getPreciosPorProducto($id){
    global $connection;
    $sql = sprintf("SELECT pdo.Nombre_producto Producto, hp.Precio_publico Precio, hp.Fecha_inicio Fecha_inicio, hp.Fecha_fin Fecha_fin
            FROM msa_productos pdo JOIN msa_historial_precios hp ON (pdo.ID = hp.MSA_PDO_ID)
            WHERE hp.MSA_PDO_ID = %d", $id);
    $result = $connection->query($sql);
    $precios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $precios[] = $row;
        }
    }
    return json_encode($precios);
}

function getSurtidos($proveedor, $producto, $cantidad){
    global $connection;
    $sql = "SELECT pdo.Nombre_producto Producto, pvr.Nombre Proveedor, s.Cantidad_surtida Cantidad_surtida, s.Costo_unitario Costo_unitario, s.fecha as Fecha_Hora
            FROM msa_productos pdo JOIN msa_surtidos s ON (pdo.ID = s.MSA_PDO_ID) JOIN msa_proveedores pvr ON (pvr.ID = s.MSA_PVR_ID)";
    $isset_proveedor = $proveedor != "";
    $isset_producto = $producto != "";
    $isset_cantidad = $cantidad != "";
    if($isset_proveedor or $isset_producto or $isset_cantidad){
        $sql = $sql." WHERE ";
        if($isset_proveedor){
            $sql = $sql."pvr.Nombre = '".$proveedor."'";
            if($isset_producto or $isset_cantidad){
                $sql = $sql." AND ";
            }
        }
        if($isset_producto){
            $sql = $sql."pdo.Nombre_producto = '".$producto."'";
            if($isset_cantidad){
                $sql = $sql." AND ";
            }
        }
        if($isset_cantidad){
            $sql = $sql."s.Cantidad_surtida = '".$cantidad."'";
        }
    }

    $result = $connection->query($sql);
    $surtidos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $surtidos[] = $row;
        }
    }

    return json_encode($surtidos);
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

function getNombresProductos(){
    global $connection;
    $query = "SELECT ID, Nombre_producto FROM msa_productos";
    $result = $connection->query($query);
    $productos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
    return json_encode($productos);
}

function getNombresTrabajadores(){
    global $connection;
    $query = "SELECT ID, CONCAT(Nombre,' ',Apellido) Nombre FROM msa_trabajadores";
    $result = $connection->query($query);
    $trabajadores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $trabajadores[] = $row;
        }
    }
    return json_encode($trabajadores);
}

function getCompaniasServicios(){
    global $connection;
    $query = "SELECT DISTINCT Compania FROM msa_servicios";
    $result = $connection -> query($query);
    $servicios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
    }
    return json_encode($servicios);
}

function getPreciosServicios(){
    global $connection;
    $query = "SELECT DISTINCT Precio FROM msa_servicios";
    $result = $connection -> query($query);
    if($result -> num_rows > 0){
        while ($row = $result->fetch_assoc()){
            $servicios[] = $row;
        }
    }
    return json_encode($servicios);
}
function getVentasProductos($producto, $cantidad, $fecha) {
    global $connection;
    $query = 'SELECT p.Nombre_producto Producto, CONCAT(t.Nombre," ", t.Apellido) Trabajador, vp.Fecha, vp.Cantidad
              FROM msa_ventas_productos vp 
              JOIN msa_trabajadores t ON t.ID = vp.MSA_TBR_ID 
              JOIN msa_productos p ON p.ID = vp.MSA_PDO_ID';

    $isset_producto = !empty($producto);
    $isset_cantidad = !empty($cantidad);
    $isset_fecha = !empty($fecha) && $fecha != 0;
    $fecha_actual = date("Y-m-d");

    if ($isset_producto || $isset_cantidad || $isset_fecha) {
        $query .= ' WHERE ';
        if ($isset_producto) {
            $query .= "p.Nombre_producto = '" . $producto . "'";
            if ($isset_cantidad || $isset_fecha) {
                $query .= " AND ";
            }
        }
        if ($isset_cantidad) {
            $query .= "vp.Cantidad = '" . $cantidad . "'";
            if ($isset_fecha) {
                $query .= " AND ";
            }
        }
        if ($isset_fecha) {
            switch ($fecha) {
                case 1:
                    $query .= "vp.Fecha LIKE '" . $fecha_actual . "%'";
                    break;
                case 7:
                    $query .= "vp.Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()";
                    break;
                case 30:
                    $query .= "vp.Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()";
                    break;
                case 365:
                    $query .= "vp.Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 365 DAY) AND NOW()";
                    break;
            }
        }
    }
    $result = $connection->query($query);
    $ventas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ventas[] = $row;
        }
    }
    return json_encode($ventas);
}

function getVentasServicios($compania, $monto, $fecha) {
    global $connection;
    $query = 'SELECT s.Compania, s.Precio, CONCAT(t.Nombre," ", t.Apellido) Trabajador, vs.Fecha
              FROM msa_ventas_servicios vs 
              JOIN msa_trabajadores t ON t.ID = vs.MSA_TBR_ID 
              JOIN msa_servicios s ON s.ID = vs.MSA_SVO_ID';

    $isset_compania = !empty($compania);
    $isset_monto = !empty($monto);
    $isset_fecha = !empty($fecha) && $fecha != 0;
    $fecha_actual = date("Y-m-d");

    if ($isset_compania || $isset_monto || $isset_fecha) {
        $query .= ' WHERE ';
        if ($isset_compania) {
            $query .= "s.Compania = '" . $compania . "'";
            if ($isset_monto || $isset_fecha) {
                $query .= " AND ";
            }
        }
        if ($isset_monto) {
            $query .= "s.Precio = '" . $monto . "'";
            if ($isset_fecha) {
                $query .= " AND ";
            }
        }
        if ($isset_fecha) {
            switch ($fecha) {
                case 1:
                    $query .= "vs.Fecha LIKE '" . $fecha_actual . "%'";
                    break;
                case 7:
                    $query .= "vs.Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()";
                    break;
                case 30:
                    $query .= "vs.Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()";
                    break;
                case 365:
                    $query .= "vs.Fecha BETWEEN DATE_SUB(NOW(), INTERVAL 365 DAY) AND NOW()";
                    break;
            }
        }
    }

    $result = $connection->query($query);
    $ventas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ventas[] = $row;
        }
    }
    return json_encode($ventas);
}

function getVentasPorProducto(){
    global $connection;
    $sql = "SELECT pdo.Nombre_producto Producto, SUM(vp.Cantidad) Cantidad, hp.Precio_publico*SUM(vp.Cantidad) Ganancias, pdo.Tipo, pdo.Presentacion, pdo.Contenido, pdo.Marca
            FROM msa_productos pdo JOIN msa_ventas_productos vp ON (pdo.ID = vp.MSA_PDO_ID)
            JOIN msa_historial_precios hp ON (pdo.ID = hp.MSA_PDO_ID)
            WHERE hp.Fecha_fin IS NULL GROUP BY Producto ORDER BY Cantidad DESC;";
    $result = $connection->query($sql);
    $productos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
    return json_encode($productos);
}

function getVentasPorTrabajador(){
    global $connection;
    $sql = "SELECT CONCAT(tbr.Nombre,' ',tbr.Apellido) Trabajador, SUM(c.Cantidad) Cantidad, SUM(c.Ganancias) Ganancias
            FROM msa_trabajadores tbr JOIN 
            (SELECT tbr.ID ID, CONCAT(tbr.Nombre,' ',tbr.Apellido) Trabajador, pdo.Nombre_producto Producto, SUM(vp.Cantidad) Cantidad, SUM(vp.Cantidad)*hp.Precio_publico Ganancias
             FROM msa_trabajadores tbr JOIN msa_ventas_productos vp ON(tbr.ID = vp.MSA_TBR_ID)
             JOIN msa_productos pdo ON (pdo.ID = vp.MSA_PDO_ID)
             JOIN msa_historial_precios hp ON (hp.MSA_PDO_ID = pdo.ID)
             WHERE hp.Fecha_fin IS NULL
             GROUP BY Nombre, Producto) c ON (tbr.ID = c.ID)
            GROUP BY Trabajador ORDER BY Ganancias DESC;";
    $result = $connection->query($sql);
    $trabajadores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $trabajadores[] = $row;
        }
    }
    return json_encode($trabajadores);
    
}

function getProductsCloseExpiry()
{
    global $connection;
    $sql = "SELECT p.Nombre_producto Producto, p.Fecha_caducidad Fecha_caducidad
            FROM msa_productos p
            WHERE p.Fecha_caducidad <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY p.Fecha_caducidad ASC ";
    $result = $connection->query($sql);
    $productos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
    return json_encode($productos);
}