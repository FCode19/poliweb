<?php

session_start();

$archivo = __DIR__ . '/../storage/login.json';

if (!file_exists($archivo)) {
    die('Archivo de login no encontrado');
}

$contenido = file_get_contents($archivo);
$credenciales = json_decode($contenido, true);

if (isset($_POST['usuario'], $_POST['pass'])) {
    $usuarioIngresado = $_POST['usuario'];
    $passIngresado = $_POST['pass'];

    if ($usuarioIngresado === $credenciales['usuario'] && $passIngresado === $credenciales['pass']) {
        $_SESSION['usuario'] = $usuarioIngresado;
        header('Location: ../app.php');
        exit;
    }
}

header('Location: ../login.php?error=1');
exit;
