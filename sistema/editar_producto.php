<?php
    session_start();

    if($_SESSION['rol'] != 1)
    {
        header("location: ./");
    }
    
    include "../conexion.php";

    if(!empty($_POST))
    {
        $alert = '';
        if(empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove']))
        {
            $alert = '<p class="msg_error"> Todos los campos son obligatorios </p>';
        }
        else
        {
            $codproducto = $_POST['id'];
            $producto = $_POST['producto'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $imgProducto = $_POST['foto_actual'];
            $imgRemove = $_POST['foto_remove'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];
            
            $upd = '';

            if($nombre_foto != '')
            {
                $destino = 'img/uploads/';
                $img_nombre = 'img_'.md5(date('d-m-y H:m:s'));
                $imgProducto = $img_nombre.'.jpg';
                $src = $destino.$imgProducto;
            }
            else
            {
                if($_POST['foto_actual'] != $_POST['foto_remove'])
                {
                    $imgProducto = 'img_producto.png';
                }
            }

                $query_update = mysqli_query($conection,"UPDATE producto SET descripcion = '$producto',precio = '$precio',existencia = '$cantidad', foto='$imgProducto' WHERE idproducto = '$codproducto'");

                if($query_update)
                {
                    if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual']) != ($_POST['foto_remove']))
                    {
                        unlink('img/uploads/'.$_POST['foto_actual']);
                    }

                    if($nombre_foto != '')
                    {
                       move_uploaded_file($url_temp,$src); 
                    }
                    $alert = '<p class="msg_save">Producto Actualizado correctamente..!!</p>';
                }
                else
                {
                    $alert = '<p class="msg_error">Error al Actualizar el Producto..!!</p>';
                }
            }    
        }      
        
        
        //validar producto
        if(empty($_REQUEST['id']))
        {
            header("location: lista_producto.php");
        }
        else
        {
            $id_producto = $_REQUEST['id'];
            if(!is_numeric($id_producto))
            {
                header("location: lista_producto.php");
            }

            $query_producto = mysqli_query($conection, "SELECT * FROM producto WHERE idproducto = $id_producto AND estatus = 1" );
            mysqli_close($conection);

            $result_producto = mysqli_num_rows($query_producto);

            $foto = '';
            $classRemove = 'notBlock';

            if($result_producto > 0)
            {
                $data_producto = mysqli_fetch_assoc($query_producto);

                if($data_producto['foto'] !== 'img_producto.png')
                {
                   $classRemove = '';
                   $foto = '<img id="img" src="img/uploads/'.$data_producto['foto'].'" alt="Producto">';
                }
            }
            else
            {
                header("location: lista_producto.php");
            }
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Producto</title>
</head>
<body>
	<?php include "includes/header.php";?>
	<section id="container">
		<div class="form_register">
            <h1>Actualizar Producto</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>

            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $data_producto['idproducto']; ?>">
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">
                <label for="producto">Producto</label>
                <input type="text" name="producto" id="producto" placeholder="Nombre del producto" value="<?php echo $data_producto['descripcion']?>">

                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del producto" value="<?php echo $data_producto['precio']?>">

                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad del producto" value="<?php echo $data_producto['existencia']?>">
                
                <div class="photo">
                    <label for="foto">Foto</label>
                    <div class="prevPhoto">
                    <span class="delPhoto <?php echo $classRemove; ?>">X</span>
                    <label for="foto"></label>
                    <?php echo $foto; ?>
                    </div>
                    <div class="upimg">
                    <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>
                
                <input type="submit" value="Actualizar Producto" class="btn_save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php";?>
</body>
</html>