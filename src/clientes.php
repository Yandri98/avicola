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
        $alert = '<div class="alert alert-danger" role="alert">
                                    Todo los campos son obligatorio
                                </div>';
    } else {
        $id_cliente = $_POST['id_cliente'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $usuario_id = $_SESSION['idUser'];


        $result = 0;
        $query = mysqli_query($conexion, "SELECT * FROM clientes WHERE ID_CLIENTE = '$id_cliente'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                                    El cliente ya existe
                                </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO clientes (ID_CLIENTE,NOMBRES_CLIENTE,APELLIDOS_CLIENTE,
            DIRECCION_CLIENTE,CORREO_CLIENTE, TELEFONO_CLIENTE,estado) values ('$id_cliente','$nombre', '$apellido', '$direccion', '$correo','$telefono','1')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                                    Cliente registrado
                                </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                                    Error al registrar
                            </div>';
            }
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_cliente"><i class="fas fa-plus"></i></button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                             <th>#</th>
             
							<th>NOMBRE</th>
							<th>APELLIDO</th>
							<th>DIRECCIÓN</th>
							<th>EMAIL</th>
							<th>TELEFONO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM clientes");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    
            ?>
                    <tr>
                                    <td><?php echo $data['ID_CLIENTE']; ?></td>
									<td><?php echo $data['NOMBRES_CLIENTE']; ?></td>
									<td><?php echo $data['APELLIDOS_CLIENTE']; ?></td>
									<td><?php echo $data['DIRECCION_CLIENTE']; ?></td>
									<td><?php echo $data['CORREO_CLIENTE']; ?></td>
									<td><?php echo $data['TELEFONO_CLIENTE']; ?></td>
                        
                        <td>
                            <?php  ?>
                                <a href="editar_cliente.php?id=<?php echo $data['ID_CLIENTE']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_cliente.php?id=<?php echo $data['ID_CLIENTE']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php  ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>
<div id="nuevo_cliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Cliente</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                <div class="form-group">
                        <label for="id_cliente">ID</label>
                        <input type="number" placeholder="Ingrese Id" name="id_cliente" id="id_cliente" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" placeholder="Ingrese Apellido" name="apellido" id="apellido" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Direccion</label>
                        <input type="text" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" placeholder="Ingrese Correo" name="correo" id="correo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Telefono</label>
                        <input type="number" placeholder="Ingrese Telefono" name="telefono" id="telefono" class="form-control">
                    </div>
                    <input type="submit" value="Guardar Cliente" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>