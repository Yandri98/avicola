<?php include_once "includes/header.php";
require "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.ID_PERMISO WHERE d.ID_EMPLEADO = '$id_user' AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['usuario'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idUser = $_GET['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $sueldo = $_POST ['sueldo']; 
        $usuario = $_POST['usuario']; 
        $clave = ($_POST['clave']);
        
        $sql_update = mysqli_query($conexion, "UPDATE empleados SET  
         NOMBRE_EMPLEADO = '$nombre',APELLIDOS_EMPLEADO = '$apellido', 
          DIRECCION_EMPLEADO = '$direccion',
         TELEFONO_EMPLEADO = '$telefono',SUELDO_EMPLEADO='$sueldo',
         USUARIO = '$usuario', contrasena = '$clave' WHERE ID_EMPLEADO = $idUser");
        $alert = '<div class="alert alert-success" role="alert">Usuario Actualizado</div>';
    }
}

// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: usuarios.php");
}
$idusuario = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM empleados WHERE ID_EMPLEADO = $id_usuario");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: usuarios.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['ID_EMPLEADO'];
        $nombre = $data['NOMBRE_EMPLEADO'];
        $apellido = $data['APELLIDOS_EMPLEADO']; 
        $direccion = $data['DIRECCION_EMPLEADO'];
        $telefono = $data['TELEFONO_EMPLEADO'];
        $sueldo = $data ['SUELDO_EMPLEADO'];  
        $usuario = $data['USUARIO'];
        $clave = md5($data['contrasena']);
       
      
    }
}
?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Modificar Usuario
            </div>
            <div class="card-body">
                <form class="" action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <input type="hidden" name="id" value="<?php echo $idUser; ?>">
                    
                    <div class="form-group">
                        <label for="nombre">Nombres</label>
                        <input type="text" placeholder="Ingrese nombre" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre; ?>">

                    </div>
                    <div class="form-group">
                        <label for="correo">Apellidos</label>
                        <input type="text" placeholder="Ingrese apellido" class="form-control" name="apellido" id="apellido" value="<?php echo $apellido; ?>">

                    </div>
                   
                    <div class="form-group">
                        <label for="usuario">Dirección</label>
                        <input type="text" placeholder="Ingrese dirección" class="form-control" name="direccion" id="direccion" value="<?php echo $direccion; ?>">

                    </div>
                    <div class="form-group">
                        <label for="correo">Telefono</label>
                        <input type="number" placeholder="Ingrese telefono" class="form-control" name="telefono" id="telefono" value="<?php echo $telefono; ?>">

                    </div>
                    <div class="form-group">
                        <label for="correo">sueldo</label>
                        <input type="number" placeholder="Ingrese sueldo" class="form-control" name="sueldo" id="sueldo" value="<?php echo $sueldo; ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo">Usuario</label>
                        <input type="text" placeholder="Ingrese usuario" class="form-control" name="usuario" id="usuario" value="<?php echo $usuario; ?>">

                    </div>
                    <div class="form-group">
                        <label for="correo">Clave</label>
                        <input type="password" placeholder="Ingrese clave" class="form-control" name="clave" id="clave" value="<?php echo $clave; ?>">

                    </div>
                   
                    <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i></button>
                    <a href="usuarios.php" class="btn btn-danger">Atras</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
