<?php
	session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Sistema PanMello</title>
</head>
<body>
	<?php include "includes/header.php";?>
	<section id="container">
		<h1>Bienvenido al sistema</h1>
			<br><br>
			<h2>Usuario: <?php echo $_SESSION['user'].'-'.$_SESSION['rol'];?></h2>
			<br>
			<h2>EMAIL:<?php echo $_SESSION['email']; ?></h2>
	</section>
	<?php include "includes/footer.php";?>
</body>
</html>