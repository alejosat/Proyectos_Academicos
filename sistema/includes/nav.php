<nav>
	<ul>
		<li><a href="../index.php"><i class="fas fa-home"></i> Inicio</a></li>
		<?php
			if($_SESSION['rol'] == 1)
			{

		?>
		<li class="principal">
		
				<a href="#"><i class="fas fa-users"></i> Usuarios</a>
				<ul>
					<li><a href="registro_usuario.php">Nuevo Usuario</a></li>
					<li><a href="lista_usuarios.php">Lista de Usuarios</a></li>
				</ul>
				</li>
		<?php } ?>
				<li class="principal">
					<a href="#"><i class="fas fa-user"></i> Clientes</a>
					<ul>
						<li><a href="registro_cliente.php">Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php">Lista de Clientes</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><i class="fas fa-clipboard-list"></i> Productos</a>
					<ul>
					<?php if($_SESSION['rol'] == 1) { ?>
						<li><a href="registro_producto.php">Nuevo Producto</a></li>
					<?php  } ?>
						<li><a href="lista_producto.php">Lista de Productos</a></li>
					</ul>
				</li>
				<!-- <li class="principal">
					<a href="#"><i class="fas fa-receipt"></i> Facturas</a>
					<ul>
						<li><a href="#">Nuevo Factura</a></li>
						<li><a href="#">Facturas</a></li>
					</ul>
				</li> -->
			</ul>
		</nav>
			