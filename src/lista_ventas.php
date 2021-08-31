<?php
include_once "includes/header.php";
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.ID_PERMISO WHERE d.ID_EMPLEADO = '$id_user' AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
$query = mysqli_query($conexion, "SELECT  C.NOMBRES_CLIENTE, C.ID_CLIENTE,C.APELLIDOS_CLIENTE,V.*
FROM venta V INNER JOIN clientes C ON V.ID_CLIENTE = C.ID_CLIENTE 
 ORDER BY id DESC");
?>
<table class="table table-light" id="tbl">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Fecha</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['NOMBRES_CLIENTE']; ?></td>
                <td><?php echo $row['total']; ?></td>
                <td><?php echo $row['fecha']; ?></td>
                <td>
                    <a href="pdf/generar.php?cl=<?php echo $row['ID_CLIENTE'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php include_once "includes/footer.php"; ?>