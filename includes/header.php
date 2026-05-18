<?php
require_once __DIR__ . '/configuracion.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>resources/estilos.css">
    <title>Document</title>
</head>

<body>

    <header>
        <!-- <input type="checkbox" id="hamburguesa">-->
        <img src="<?= BASE_URL ?>static/img/logo.png" alt="" width="180">

        <!--<label for="hamburguesa" id="icono">-->
        <!--<i class="fa fa-bars"></i>-->
        <!-- </label>-->

        <nav>
            <ul class="menu">
                <li><a href="<?= BASE_URL ?>index.php">Inicio</a></li>
                <li><a href="<?= BASE_URL ?>acerca_de.php">Acerca de</a></li>
                <li><a href="<?= BASE_URL ?>productos.php">Productos</a></li>
                <li><a href="<?= BASE_URL ?>contacto.php">Contacto</a></li>

                <?php
                if (isset($_SESSION['rol'])) {
                    echo '<li><a href="logout.php">Logout</a></li>';
                } else {
                    echo '<li><a href="login.php">Login</a></li>';
                }
                ?>
                <?php
                if ($_SESSION['rol'] == 'admin') {
                    echo '<li><a href="admin/productos_admin.php">Panel Admin</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>