<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/estilos.css">
    <title>Document</title>
</head>
<body>
    
<header>
   <!-- <input type="checkbox" id="hamburguesa">-->
    <img src="static/img/logo.png" alt="" width="180">

    <!--<label for="hamburguesa" id="icono">-->
        <!--<i class="fa fa-bars"></i>-->
   <!-- </label>-->

    <nav>
        <ul class="menu">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="Acerca_de.php">Acerca de</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="contacto.php">Contacto</a></li>

            <?php
                if(isset($_SESSION['rol'])){
                    echo '<li><a href="logout.php">Logout</a></li>';
                }else{
                    echo '<li><a href="login.php">Login</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>