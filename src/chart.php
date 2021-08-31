<?php
include("../conexion.php");
if ($_POST['action'] == 'sales') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT NOMBRE_PRODUCTO, CANTIDAD_PRODUCTO FROM productos WHERE CANTIDAD_PRODUCTO <= 10 ORDER BY CANTIDAD_PRODUCTO ASC LIMIT 10");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
if ($_POST['action'] == 'polarChart') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT p.ID_PRODUCTO, p.NOMBRE_PRODUCTO, d.ID_PRODUCTO, d.cantidad, SUM(d.cantidad) as total FROM productos p INNER JOIN detalle_venta d WHERE p.ID_PRODUCTO = d.ID_PRODUCTO group by d.ID_PRODUCTO ORDER BY d.cantidad DESC LIMIT 5;");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
//
?>
