<?php
include_once("includes/header.php");

if ($_SESSION['rol'] == 'admin') {
    echo '<h2>Bienvenido Admin</h2>';
}
?>

