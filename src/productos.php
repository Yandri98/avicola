 <?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.ID_PERMISO WHERE d.ID_EMPLEADO = '$id_user' AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
    if (!empty($_POST)) {
		$codigo = $_POST['codigo'];
        $lote = $_POST['lote'];
        $producto = $_POST['producto'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];  
        $peso = $_POST['peso'];
        $usuario_id = $_SESSION['idUser'];
        $alert = "";
        if (empty($codigo) || empty($producto) || empty($precio) || $precio <  0 || empty($cantidad) || $cantidad < 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM productos WHERE ID_PRODUCTO = '$codigo'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        El código ya existe
                    </div>';
            } else {
				$query_insert = mysqli_query($conexion,"INSERT INTO productos(ID_PRODUCTO,ID_TIPOLOTE,NOMBRE_PRODUCTO,CANTIDAD_PRODUCTO,PRECIO_PRODUCTO, KG_PRODUCTO, estado) values ('$codigo', '$lote','$producto','$cantidad','$precio','$peso' ,'1')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Producto Registrado
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
                }
            }
        }
    }
    ?>
 <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button>
 <?php echo isset($alert) ? $alert : ''; ?>
 <div class="table-responsive">
     <table class="table table-striped table-bordered" id="tbl">
         <thead class="thead-dark">
             <tr>
                 <th>#</th>
                 <th>Producto</th>
                 <th>Precio</th>
                 <th>Stock</th>
                 <th>Peso KG</th>
                 
                 <th></th>
             </tr>
         </thead>
         <tbody>
             <?php
                include "../conexion.php";

                $query = mysqli_query($conexion, "SELECT * FROM productos");
                $result = mysqli_num_rows($query);
                if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
                       
                ?>
                     <tr>
                         <td><?php echo $data['ID_PRODUCTO']; ?></td>
                         <td><?php echo $data['NOMBRE_PRODUCTO']; ?></td>
                         <td><?php echo $data['PRECIO_PRODUCTO']; ?></td>
                         <td><?php echo $data['CANTIDAD_PRODUCTO']; ?></td>
                         <td><?php echo $data['KG_PRODUCTO']; ?></td>
                         
                         <td>
                             <?php ?>
                                 <a href="agregar_producto.php?id=<?php echo $data['ID_PRODUCTO']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>

                                 <a href="editar_producto.php?id=<?php echo $data['ID_PRODUCTO']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_producto.php?id=<?php echo $data['ID_PRODUCTO']; ?>" method="post" class="confirmar d-inline">
                                     <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                 </form>
                             
                         </td>
                     </tr>
             <?php }
                } ?>
         </tbody>

     </table>
 </div>
 <div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Nuevo Producto</h5>
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                         <label for="codigo">ID</label>
                         <input type="number" placeholder="Ingrese ID" name="codigo" id="codigo" class="form-control">
                     </div>
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
                         <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="cantidad">Cantidad</label>
                         <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                     </div>
                     <div class="form-group">
                         <label for="precio">Precio</label>
                         <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                     </div>
                     <div class="form-group">
                         <label for="precio">Peso KG</label>
                         <input type="number" placeholder="Ingrese el peso" class="form-control" name="peso" id="peso">
                     </div>
                     <input type="submit" value="Guardar Producto" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php include_once "includes/footer.php"; ?>
