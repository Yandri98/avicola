<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.ID_PERMISO WHERE d.ID_EMPLEADO = '$id_user' AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idcliente = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
            $sql_update = mysqli_query($conexion, "UPDATE clientes SET NOMBRES_CLIENTE = '$nombre' ,
             APELLIDOS_CLIENTE = '$apellido', DIRECCION_CLIENTE = '$direccion', CORREO_CLIENTE = '$correo', TELEFONO_CLIENTE = '$telefono' WHERE ID_CLIENTE = $idcliente");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Cliente Actualizado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Cliente</div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: clientes.php");
}
$idcliente = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM clientes WHERE ID_CLIENTE = $idcliente");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: clientes.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['ID_CLIENTE'];
        $nombre = $data['NOMBRES_CLIENTE'];
        $apellido = $data['APELLIDOS_CLIENTE'];
        $direccion = $data['DIRECCION_CLIENTE'];
        $correo = $data['CORREO_CLIENTE'];
        $telefono = $data['TELEFONO_CLIENTE'];
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Cliente
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                        <div class="form-group">
                            <label for="nombre">Nombres</label>
                            <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" placeholder="Ingrese Apellidos" name="apellido" class="form-control" id="apellido" value="<?php echo $apellido; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Email</label>
                            <input type="email" placeholder="Ingrese Email" name="correo" class="form-control" id="correo" value="<?php echo $correo; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Telefono</label>
                            <input type="number" placeholder="Ingrese Telefono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Cliente</button>
                        <a href="clientes.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>