<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "lote";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.ID_PERMISO WHERE d.ID_EMPLEADO = '$id_user' AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}
if (!empty($_POST)) {
  $alert = "";
  if ("") {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $codproducto = $_GET['id'];
    $nombre_lote = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $criadero = $_POST['criadero'];  
    $query_update = mysqli_query($conexion, "UPDATE tipo_lote SET DESCRIPCION_TIPOLOTE = '$nombre_lote', CANTIDAD_TIPOLOTE = '$cantidad',
    CRIADERO_TIPOLOTE = '$criadero' 
    WHERE ID_TIPOLOTE = $codproducto");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Lote Modificado
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: productos.php");
} else {
  $id_producto = $_REQUEST['id'];
  if (!is_numeric($id_producto)) {
    header("Location: productos.php");
  }
  $query_producto = mysqli_query($conexion, "SELECT * FROM tipo_lote WHERE ID_TIPOLOTE = $id_producto");
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto > 0) {
    $data_producto = mysqli_fetch_assoc($query_producto);
  } else {
    header("Location: productos.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar producto
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          

          <div class="form-group">
            <label for="producto">Lote</label>
            <input type="text" class="form-control" placeholder="Ingrese nombre del Lote" name="descripcion" id="descripcion" value="<?php echo $data_producto['DESCRIPCION_TIPOLOTE']; ?>">

          </div>

          <div class="form-group">
            <label for="producto">Cantidad Lote</label>
            <input type="number" class="form-control" placeholder="Ingrese cantidad del lote" name="cantidad" id="cantidad" value="<?php echo $data_producto['CANTIDAD_TIPOLOTE']; ?>">

          </div>
          <div class="form-group">
            <label for="precio">Criadero Lote</label>
            <input type="text" placeholder="Ingrese Criadero" class="form-control" name="criadero" id="criadero" value="<?php echo $data_producto['CRIADERO_TIPOLOTE']; ?>">

          </div>
          <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          <a href="lote.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>