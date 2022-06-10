<?php
    session_start();
    include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Busqueda de Productos|PanMello</title>
</head>
<body>
	<?php include "includes/header.php";?>
	<section id="container">
		<?php 
            $busqueda = '';
            if(empty($_REQUEST['busqueda']))
            {
                header("location: lista_producto.php");
            }
            if(!empty($_REQUEST['busqueda']))
            {
                $busqueda = strtolower($_REQUEST['busqueda']);
                $where = "(idproducto LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%') AND 
                estatus = 1";
            }
        ?>
        <h1>Busqueda de Productos</h1>
		<a href="registro_producto.php" class="btn_new">Crear Producto</a>

       <!-- Buscador -->

        <form action="buscar_productos.php" method = "get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" value="Buscar" class="btn_search">
        </form> 
        <table>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>
                <?php

                //Paginador
                  $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM producto WHERE $where");
                  $result_register = mysqli_fetch_array($sql_register);
                  $total_registro = $result_register['total_registro'];

                  $por_pagina =5;

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

                  $query = mysqli_query($conection, "SELECT * FROM producto WHERE $where ORDER BY idproducto DESC LIMIT $desde,$por_pagina");
                  
                  mysqli_close($conection);

                  $result = mysqli_num_rows($query);
                  if($result > 0)
                  {
                      while($data = mysqli_fetch_array($query))
                      {
                        if($data['foto'] != 'img_producto.png')
                        {
                            $foto = 'img/uploads/'.$data['foto'];
                        }
                        else
                        {
                            $foto = 'img/'.$data['foto'];
                        }
                ?>
                <tr class="row<?php echo $data["idproducto"]; ?>">
                    <td><?php echo $data["idproducto"]; ?></td>
                    <td><?php echo $data["descripcion"]; ?></td>
                    <td class="celPrecio"><?php echo $data["precio"]; ?></td>
                    <td class="celExistencia"><?php echo $data["existencia"]; ?></td>
                    <td class="img_producto"><img src="<?php echo $foto; ?>" alt="<?php echo $data["descripcion"]; ?>"></td>

                    <?php if($_SESSION['rol'] == 1) {?>
                    <td>
                        <a class= "link_add add_product" product="<?php echo $data['idproducto'];?>" href="#">Agregar</a>
                        |
                        <a class= "link_edit" href="editar_producto.php?id=<?php echo $data['idproducto'];?>">Editar</a>
                        |
                        <a class="link_delete del_product" href="#" product="<?php echo $data['idproducto'];?>">Eliminar</a>
                    </td>
                    <?php } ?>
                </tr>
        <?php
                }
                
            }
        ?>
        </table>
        <div class="paginador">

            <?php
                if($pagina != 1)
                {
            ?>
                <a href="?pagina=<?php echo 1; ?>">|<</a>
                <a href="?pagina=<?php echo $pagina - 1; ?>"><<</a>
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
                    echo '<a href="?pagina='.$i.'">'.$i.'</a>';
                    }
                }

                if($pagina != $total_paginas)
                {
            ?>         
                <a href="?pagina=<?php echo $pagina + 1;?>">>></a>
                <a href="?pagina=<?php echo $total_paginas?>">>|</a>
            <?php  } ?>
        </div>
	</section>
	<?php include "includes/footer.php";?>
</body>
</html>