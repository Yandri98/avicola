<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
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
    $producto = $_POST['producto'];
    $lote = $_POST['lote'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];  
    $peso = $_POST['peso'];
    $query_update = mysqli_query($conexion, "UPDATE productos SET ID_TIPOLOTE = '$lote', NOMBRE_PRODUCTO = '$producto', CANTIDAD_PRODUCTO = '$cantidad', PRECIO_PRODUCTO = '$precio', 
    KG_PRODUCTO = '$peso' WHERE ID_PRODUCTO = $codproducto");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Producto Modificado
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
  $query_producto = mysqli_query($conexion, "SELECT * FROM productos WHERE ID_PRODUCTO = $id_producto");
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
                    <label>Lote</label>
                    <select name="lote" id="lote" class="form-control">
                        <?php
                        $query_rol = mysqli_query($conexion, " select * from tipo_lote");
                        mysqli_close($conexion);
                        $resultado_rol = mysqli_num_rows($query_rol);
                        if ($resultado_rol > 0) {
                            while ($rol = mysqli_fetch_array($query_rol)) {
                        ?>
                                <option value="<?php echo $rol["ID_TIPOLOTE"]; ?>"><?php echo $rol["DESCRIPCION_TIPOLOTE"] ?></option>
                        <?php

                            }
                        }

                        ?>
                    </select></div>

          <div class="form-group">
            <label for="producto">Producto</label>
            <input type="text" class="form-control" placeholder="Ingrese nombre del producto" name="producto" id="producto" value="<?php echo $data_producto['NOMBRE_PRODUCTO']; ?>">

          </div>

          <div class="form-group">
            <label for="producto">Cantidad</label>
            <input type="number" class="form-control" placeholder="Ingrese cantidad del producto" name="cantidad" id="cantidad" value="<?php echo $data_producto['CANTIDAD_PRODUCTO']; ?>">

          </div>
          <div class="form-group">
            <label for="precio">Precio</label>
            <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio" value="<?php echo $data_producto['PRECIO_PRODUCTO']; ?>">

          </div>
          <div class="form-group">
            <label for="precio">Peso KG</label>
            <input type="number" placeholder="Ingrese peso" class="form-control" name="peso" id="peso" value="<?php echo $data_producto['KG_PRODUCTO']; ?>">

          </div>
          <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          <a href="productos.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>