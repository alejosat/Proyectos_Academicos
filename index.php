<?php

    $alert = '';
    session_start();
    if(!empty($_SESSION['active']))
    {
        header('location: sistema/');
    }
    else
    {
    if(!empty($_POST))
    {
       if(empty($_POST['usuario']) || empty($_POST['clave']))
       {
            $alert = 'Ingrese su usuario y su clave';
       }
       else
       {
           require_once "conexion.php";

           $user = mysqli_real_escape_string($conection,$_POST['usuario']);
           $pass = md5(mysqli_real_escape_string($conection,$_POST['clave']));

           $query = mysqli_query($conection,"SELECT * FROM usuario WHERE username = '$user' AND password = '$pass'");
           mysqli_close($conection);
           $result = mysqli_num_rows($query);

           if($result > 0)
           {
               $data = mysqli_fetch_array($query);
               $_SESSION['active'] = true;
               $_SESSION['idUser'] = $data['id'];
               $_SESSION['nombre'] = $data['nombre'];
               $_SESSION['email'] = $data['email'];
               $_SESSION['user'] = $data['username'];
               $_SESSION['rol'] = $data['idrol'];

               header('location: sistema/');
           }else
           {
               $alert = 'El usuario o la clave son incorrectos';
               session_destroy();
               
           }
       }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_login.css">
    <title>Login | Panaderia PanMello</title>
</head>
<body>
    <div id="container">
        <center>
        <form action="" method="post">
        <h3>Iniciar Sesion</h3>
        <img src="img/login.png" alt="Login">
        <br><br>
        <input type="text" name="usuario" placeholder="Usuario">
        <input type="password" name="clave" placeholder="Contraseña">
        <div class="alert" ><?php echo isset($alert)? $alert: "";?></div>
        <br>
        <input type="submit" value="INGRESAR">
        </form>
        </center>
    </div>
</body>
</html>