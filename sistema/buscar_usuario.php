<?php
    session_start();
    if($_SESSION['rol'] != 1)
    {
        header("location: ./");
    }
    include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Buscar Usuarios | PanMello</title>
</head>
<body>
	<?php include "includes/header.php";?>
	<section id="container">
		
        <?php

            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda))
            {
                header("location: lista_usuarios.php");
                mysqli_close($conection);
            }
        ?>
        <h1>Busqueda de Usuarios</h1>
		<a href="registro_usuario.php" class="btn_new">Crear Usuario</a>

       <!-- Buscador -->

        <form action="buscar_usuario.php" method = "get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
                <?php

                //Paginador
                  $rol = "";
                  if($busqueda == 'administrador')
                  {
                      $rol = "OR idrol LIKE '%1%'";
                  }else if($busqueda == 'supervisor')
                  {
                      $rol = "OR idrol LIKE '%2%'";   
                  }

                  $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM usuario 
                  WHERE(id like '%$busqueda%' OR 
                        nombre like '%$busqueda%' OR 
                        email like '%$busqueda%' OR 
                        username like '%$busqueda%' 
                        $rol) 
                        AND estatus = 1");

                  $result_register = mysqli_fetch_array($sql_register);
                  $total_registro = $result_register['total_registro'];

                  $por_pagina = 5;

                  if(empty($_GET['pagina']))
                  {
                      $pagina = 1;
                  }
                  else
                  {
                      $pagina = $_GET['pagina'];
                  }

                  $desde = ($pagina-1) * $por_pagina;
                  $total_paginas = ceil($total_registro / $por_pagina);

                  $query = mysqli_query($conection, "SELECT u.id, u.nombre, u.email, u.username, r.rol FROM usuario u INNER JOIN rol r ON u.idrol = r.idrol WHERE 
                  (u.id like '%$busqueda%' OR 
                  u.nombre like '%$busqueda%' OR 
                  u.email like '%$busqueda%' OR 
                  u.username like '%$busqueda%' OR 
                  r.rol like '%$busqueda%') 
                  AND 
                  estatus = 1 ORDER BY u.id ASC LIMIT $desde,$por_pagina");
                  
                  mysqli_close($conection);
                  $result = mysqli_num_rows($query);
                  if($result > 0)
                  {
                      while($data = mysqli_fetch_array($query))
                      {
                ?>
                <tr>
                    <td><?php echo $data["id"]; ?></td>
                    <td><?php echo $data["nombre"]; ?></td>
                    <td><?php echo $data["email"]; ?></td>
                    <td><?php echo $data["username"]; ?></td>
                    <td><?php echo $data["rol"]; ?></td>
                    <td>
                        <a class= "link_edit" href="editar_usuario.php?id=<?php echo $data["id"]; ?>">Editar</a>
                        <?php if($data["id"] != 5) 
                        {
                        ?>
                        <a class="link_delete" href="eliminar_confirmar_usuario.php?id=<?php echo $data["id"]; ?>">Eliminar</a>
                        <?php } ?>
                    </td>
                </tr>
        <?php
                }
                
            }
        ?>
        </table>

            <?php
                if($total_registro != 0)
                {
            ?>

        <div class="paginador">
            <?php
                if($pagina != 1)
                {
            ?>
                <a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda;?>">|<</a>
                <a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda;?>"><<</a>
            <?php
                }
                for($i=1; $i <= $total_paginas; $i++)
                {
                    if($i == $pagina)
                    {
                        echo '<a class = "pageSelected">'.$i.'</a>'; 
                    }
                    else
                    {
                    echo '<a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a>';
                    }
                }

                if($pagina != $total_paginas)
                {
            ?>         
                <a href="?pagina=<?php echo $pagina + 1;?>&busqueda=<?php echo $busqueda;?>">>></a>
                <a href="?pagina=<?php echo $total_paginas?>&busqueda=<?php echo $busqueda;?>">>|</a>
            <?php  } ?>
        </div>
            <?php } ?>
	</section>
	<?php include "includes/footer.php";?>
</body>
</html>