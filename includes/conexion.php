<?php
// config.php

// Datos de conexión
$host     = 'localhost';
$dbname   = 'tienda_deportes';
$username = 'root';
$password = '';
$charset  = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Opciones de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores como excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch asociativo por defecto
    PDO::ATTR_EMULATE_PREPARES   => false,                  // usar prepares nativos
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // echo "Conexión correcta"; // opcional para pruebas
} catch (PDOException $e) {
    // En producción mejor loguear el error en lugar de mostrarlo
    die('Error de conexión: ' . $e->getMessage());
}
