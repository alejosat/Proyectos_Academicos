<?php
    session_start();
    
    include "../conexion.php";

    if(!empty($_POST))
    {
        $alert = '';
        if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']))
        {
            $alert = '<p class="msg_error"> Todos los campos son obligatorios </p>';
        }
        else
        {
            $idcliente = $_POST['id'];
            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            
            $result = 0;

            if(is_numeric($cedula))
            {
                $query = mysqli_query($conection,"SELECT * FROM cliente WHERE (cedula = '$cedula' AND idcliente != $idcliente)");

                $result = mysqli_fetch_array($query);

            }
            
            if($result > 0)
            {
                $alert = '<p class = "msg_error">La cedula ya existe, ingrese otro.!!</p>';
            }
            else
            {   

                    $sql_update = mysqli_query($conection,"UPDATE cliente SET cedula = '$cedula', nombre = '$nombre',telefono = '$telefono', direccion = '$direccion' WHERE idcliente = $idcliente");        
        
                if($sql_update)
                {
                    $alert = '<p class="msg_save">Usuario Actualizado Correctamente..!!</p>';
                }
                else
                {
                    $alert = '<p class="msg_error">Error al Actualizar el cliente..!!</p>';
                }
            }

        }
    }
    //Mostrar datos
    if(empty($_REQUEST['id']))
    {
        header('Location: lista_clientes.php');
        mysqli_close($conection);
    }
    $idcliente = $_REQUEST['id'];
    $sql = mysqli_query($conection,"SELECT * FROM cliente  WHERE idcliente = $idcliente and estatus = 1");

    mysqli_close($conection);

    $result_sql = mysqli_num_rows($sql);
    if($result_sql == 0)
    {
        header('Location: lista_clientes.php');
    }
    else
    {
        while($data = mysqli_fetch_array($sql))
        {
            $idcliente = $data['idcliente'];
            $cedula = $data['cedula'];
            $nombre = $data['nombre'];
            $telefono = $data['telefono'];
            $direccion = $data['direccion'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Cliente</title>
</head>
<body>
	<?php include "includes/header.php";?>
	<section id="container">
		<div class="form_register">
            <h1>Actualizar Cliente</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $idcliente;?>">
                <label for="cedula">Numero Cedula</label>
                <input type="number" name="cedula" id="cedula" placeholder="Número de Cedula" value="<?php echo $cedula;?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre;?>">
                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Teléfono" value="<?php echo $telefono;?>">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección Completa" value="<?php echo $direccion;?>">
                
                <input type="submit" value="Actualizar Cliente" class="btn_save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php";?>
</body>
</html>