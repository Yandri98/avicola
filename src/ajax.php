<?php
require_once "../conexion.php";
session_start();
if (isset($_GET['q'])) {
    $datos = array();
    $nombre = $_GET['q'];
    $cliente = mysqli_query($conexion, "SELECT * FROM clientes WHERE NOMBRES_CLIENTE LIKE '%$nombre%' AND estado = 1");
    while ($row = mysqli_fetch_assoc($cliente)) {
       
        $data['id'] = $row['ID_CLIENTE'];
        $data['label'] = $row['NOMBRES_CLIENTE'];
        $data['apellido'] = $row['APELLIDOS_CLIENTE'];
        $data['direccion'] = $row['DIRECCION_CLIENTE'];
        $data['telefono'] = $row['TELEFONO_CLIENTE'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['pro'])) {
    $datos = array();
    $nombre = $_GET['pro'];
    $producto = mysqli_query($conexion, "SELECT * FROM productos WHERE ID_PRODUCTO LIKE '%" . $nombre . "%' OR NOMBRE_PRODUCTO LIKE '%" . $nombre . "%' AND estado = 1");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['ID_PRODUCTO'];
        $data['label'] = $row['NOMBRE_PRODUCTO'] . ' - ' .$row['ID_PRODUCTO'];
        $data['value'] = $row['NOMBRE_PRODUCTO'];
        // $data['precio'] = $row['PRECIO_PRODUCTO'];
        $data['precio'] = $row['PRECIO_PRODUCTO'];
        $data['existencia'] = $row['CANTIDAD_PRODUCTO'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['detalle'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, p.ID_PRODUCTO, p.NOMBRE_PRODUCTO FROM detalle_temp d INNER JOIN productos p ON d.ID_PRODUCTO = p.ID_PRODUCTO WHERE d.ID_EMPLEADO = $id");
    $sumar = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE ID_EMPLEADO = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['descripcion'] = $row['NOMBRE_PRODUCTO'];
        $data['cantidad'] = $row['cantidad'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = number_format($row['precio_venta'] * $row['cantidad'], 2, '.', ',');
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['delete_detalle'])) {
    $id_detalle = $_GET['id'];
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id = $id_detalle");
    $datos = mysqli_fetch_assoc($verificar);
    if ($datos['cantidad'] > 1) {
        $cantidad = $datos['cantidad'] - 1;
        $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = $cantidad WHERE id = $id_detalle");
        if ($query) {
            $msg = "restado";
        } else {
            $msg = "Error";
        }
    }else{
        $query = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id = $id_detalle");
        if ($query) {
            $msg = "ok";
        } else {
            $msg = "Error";
        }
    }
    echo $msg;
    die();
} else if (isset($_GET['procesarVenta'])) {
    $id_cliente = $_GET['id'];
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE ID_EMPLEADO = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    $insertar = mysqli_query($conexion, "INSERT INTO venta(ID_CLIENTE, total, ID_EMPLEADO) VALUES ($id_cliente, '$total', $id_user)");
    if ($insertar) {
        $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS total FROM venta");
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['total'];
        $consultaDetalle = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE ID_EMPLEADO = $id_user");
        while ($row = mysqli_fetch_assoc($consultaDetalle)) {
            $id_producto = $row['ID_PRODUCTO'];
            $cantidad = $row['cantidad'];
            $precio = $row['precio_venta'];
            $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_venta(ID_PRODUCTO, ID_VENTA, cantidad, precio) VALUES ($id_producto, $ultimoId, $cantidad, '$precio')");
            $stockActual = mysqli_query($conexion, "SELECT * FROM productos WHERE ID_PRODUCTO = $id_producto");
            $stockNuevo = mysqli_fetch_assoc($stockActual);
            $stockTotal = $stockNuevo['CANTIDAD_PRODUCTO'] - $cantidad;
            $stock = mysqli_query($conexion, "UPDATE productos SET CANTIDAD_PRODUCTO = $stockTotal WHERE ID_PRODUCTO = $id_producto");
        } 
        if ($insertarDet) {
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE ID_EMPLEADO = $id_user");
            $msg = array('ID_CLIENTE' => $id_cliente, 'ID_VENTA' => $ultimoId);
        } 
    }else{
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
}
if (isset($_POST['action'])) {
    $id = $_POST['id'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $id_user = $_SESSION['idUser'];
    $total = $precio * $cant;
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE ID_PRODUCTO = $id AND ID_EMPLEADO = $id_user");
    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + 1;
        $total_precio = $cantidad * $total;
        $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = $cantidad, total = '$total_precio' WHERE ID_PRODUCTO = $id AND ID_EMPLEADO = $id_user");
        if ($query) {
            $msg = "actualizado";
        } else {
            $msg = "Error al ingresar";
        }
    }else{
        $query = mysqli_query($conexion, "INSERT INTO detalle_temp(ID_EMPLEADO, ID_PRODUCTO, cantidad, precio_venta, total) VALUES ($id_user, $id, $cant, '$precio', $total)");
        if ($query) {
            $msg = "registrado";
        }else{
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
}
if (isset($_POST['cambio'])) {
    if (empty($_POST['actual']) || empty($_POST['nueva'])) {
        $msg = 'Los campos estan vacios';
    } else {
        $id = $_SESSION['idUser'];
        $actual = md5($_POST['actual']);
        $nueva = md5($_POST['nueva']);
        $consulta = mysqli_query($conexion, "SELECT * FROM empleados WHERE contrasena = '$actual' AND ID_EMPLEADO = $id");
        $result = mysqli_num_rows($consulta);
        if ($result == 1) {
            $query = mysqli_query($conexion, "UPDATE empleados SET contrasena = '$nueva' WHERE ID_EMPLEADO = $id");
            if ($query) {
                $msg = 'ok';
            }else{
                $msg = 'error';
            }
        } else {
            $msg = 'dif';
        }
        
    }
    echo $msg;
    die();
    
}