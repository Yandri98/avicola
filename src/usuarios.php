<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.ID_PERMISO WHERE d.ID_EMPLEADO = '$id_user' AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['usuario']) || empty($_POST['clave'])) {
        $alert = '<div class="alert alert-danger" role="alert">
        Todo los campos son obligatorios
        </div>';
    } else {
        $idUser = $_POST['id_user'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $sueldo = $_POST ['sueldo']; 
        $user = $_POST['usuario']; 
        $clave = ($_POST['clave']);
        $rol = $_POST['rol'];
        
        $query = mysqli_query($conexion, "SELECT * FROM empleados where ID_EMPLEADO = '$idUser'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                        El usuario ya existe
                    </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO empleados(ID_EMPLEADO, ID_TIPOEMPLEADO, ID_EMPRESA, NOMBRE_EMPLEADO, APELLIDOS_EMPLEADO, DIRECCION_EMPLEADO, TELEFONO_EMPLEADO, SUELDO_EMPLEADO, USUARIO, contrasena, estado) values ('$idUser','$rol','1','$nombre','$apellido', '$direccion','$telefono','$sueldo','$user', '$clave' ,'1')");
            if ($query_insert) {
                $alert = '<div class="alert alert-primary" role="alert">
                            Usuario registrado
                        </div>';
                header("Location: usuarios.php");
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                        Error al registrar
                    </div>';
            }
        }
    }
}
?>
<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#nuevo_usuario"><i class="fas fa-plus"></i></button>
<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Usuario</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">ID</label>
                        <input type="number" class="form-control" placeholder="Ingrese ID" name="id_user" id="id_user">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombres</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Apellidos</label>
                        <input type="text" class="form-control" placeholder="Ingrese Apellido" name="apellido" id="apellido">
                    </div>
                    
                    <div class="form-group">
                        <label for="correo">Direccion</label>
                        <input type="text" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion">
                    </div>
                    <div class="form-group">
                        <label for="correo">Telefono</label>
                        <input type="text" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono">
                    </div>
                    <div class="form-group">
                        <label for="correo">Sueldo</label>
                        <input type="number" class="form-control" placeholder="Ingrese Sueldo" name="sueldo" id="sueldo">
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario" id="usuario">
                    </div>
                    
                    <div class="form-group">
                        <label for="clave">Contraseña</label>
                        <input type="password" class="form-control" placeholder="Ingrese Contraseña" name="clave" id="clave">
                    </div>
                    <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" id="rol" class="form-control">
                        <?php
                        $query_rol = mysqli_query($conexion, " select * from tipo_empleado");
                        mysqli_close($conexion);
                        $resultado_rol = mysqli_num_rows($query_rol);
                        if ($resultado_rol > 0) {
                            while ($rol = mysqli_fetch_array($query_rol)) {
                        ?>
                                <option value="<?php echo $rol["ID_TIPOEMPLEADO"]; ?>"><?php echo $rol["DESCRIPCION_TIPOEMPLEADO"] ?></option>
                        <?php

                            }
                        }

                        ?>
                    </select></div>
                    <input type="submit" value="Registrar" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
				<th>NOMBRE</th>
				<th>USUARIO</th>
				<th>DIRECCIÓN</th>
				<th>TELEFONO</th>
				<th>SUELDO</th>
				<th>TIPO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php ?>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT e.*, t.DESCRIPCION_TIPOEMPLEADO FROM empleados e INNER JOIN tipo_empleado t on e.ID_TIPOEMPLEADO = t.ID_TIPOEMPLEADO ORDER BY estado DESC;");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                   
            ?>
                    <tr>
                              <td><?php echo $data['ID_EMPLEADO']; ?></td>
                                <td><?php echo $data['NOMBRE_EMPLEADO']; ?></td>
								 <td><?php echo $data['USUARIO']; ?></td>
								 <td><?php echo $data['DIRECCION_EMPLEADO']; ?></td>
								 <td><?php echo $data['TELEFONO_EMPLEADO']; ?></td>
								 <td><?php echo $data['SUELDO_EMPLEADO']; ?></td>
								 <td><?php echo $data['DESCRIPCION_TIPOEMPLEADO']; ?></td>
                        
                        <td>
                            <?php  ?>
                                <a href="rol.php?id=<?php echo $data['ID_EMPLEADO']; ?>" class="btn btn-warning"><i class='fas fa-key'></i></a>
                                <a href="editar_usuario.php?id=<?php echo $data['ID_EMPLEADO']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_usuario.php?id=<?php echo $data['ID_EMPLEADO']; ?>" method="post" class="confirmar d-inline">
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
<?php include_once "includes/footer.php"; ?>