<?php
function conectar() {
    $host = '127.0.0.1';       // Puedes usar 'localhost' también
    $db   = 'poliweb';         // Asegúrate de que esta base de datos exista en phpMyAdmin
    $user = 'root';            // Usuario por defecto en XAMPP
    $pass = '';                // Contraseña vacía por defecto en XAMPP
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
        exit;
    }
}
