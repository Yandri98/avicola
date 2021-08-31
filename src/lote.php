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
        $nombre_lote = $_POST['descripcion'];
        $cantidad = $_POST['cantidad'];
        $criadero = $_POST['criadero'];  
      
        $usuario_id = $_SESSION['idUser'];
        $alert = "";
        if ($codigo == '' ) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM tipo_lote WHERE ID_TIPOLOTE = '$codigo'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        El código ya existe
                    </div>';
            } else {
				$query_insert = mysqli_query($conexion,"INSERT INTO tipo_lote (ID_TIPOLOTE,DESCRIPCION_TIPOLOTE,CANTIDAD_TIPOLOTE,CRIADERO_TIPOLOTE) values ('$codigo', '$nombre_lote','$cantidad','$criadero')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Lote Registrado
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el Lote
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
                 <th>Lote</th>
                 <th>Cantidad Lote</th>
                 <th>Criadero</th>
                
                 
                 <th></th>
             </tr>
         </thead>
         <tbody>
             <?php
                include "../conexion.php";

                $query = mysqli_query($conexion, "SELECT * FROM tipo_lote");
                $result = mysqli_num_rows($query);
                if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
                       
                ?>
                     <tr>
                         <td><?php echo $data['ID_TIPOLOTE']; ?></td>
                         <td><?php echo $data['DESCRIPCION_TIPOLOTE']; ?></td>
                         <td><?php echo $data['CANTIDAD_TIPOLOTE']; ?></td>
                         <td><?php echo $data['CRIADERO_TIPOLOTE']; ?></td>
                         
                         
                         <td>
                             <?php ?>
                                 <a href="agregar_producto.php?id=<?php echo $data['ID_TIPOLOTE']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>

                                 <a href="editarLote.php?id=<?php echo $data['ID_TIPOLOTE']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                 <form action="eliminar_producto.php?id=<?php echo $data['ID_TIPOLOTE']; ?>" method="post" class="confirmar d-inline">
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
                         <label for="producto">Lote</label>
                         <input type="text" placeholder="Ingrese nombre del producto" name="descripcion" id="descripcion" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="cantidad">Cantidad Lote</label>
                         <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                     </div>
                     <div class="form-group">
                         <label for="cantidad">Criadero Lote</label>
                         <input type="text" placeholder="Ingrese cantidad" class="form-control" name="criadero" id="criadero">
                     </div>
                    
                     <input type="submit" value="Guardar Producto" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php include_once "includes/footer.php"; ?>
