<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';

if (isset($_POST['usuario'], $_POST['pass'])) {
    $usuarioIngresado = trim($_POST['usuario']);
    $passIngresado = trim($_POST['pass']);

    $pdo = conectar();
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
    $stmt->execute(['usuario' => $usuarioIngresado]);
    $usuario = $stmt->fetch();

    if ($usuario && $usuario['contra'] === $passIngresado) {
        $_SESSION['usuario'] = $usuario['usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['cod'] = $usuario['cod'];

        header('Location: ../app.php');
        exit();
    }
}

header('Location: ../login.php?error=1');
exit();
