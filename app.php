<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<?php

require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/MetricaController.php';

$vista = $_GET['view'] ?? 'usuarios';

switch ($vista) {
    case 'formularios':
        include __DIR__ . '/views/formulario/listar.php';
        break;

    case 'metrica':
        $controller = new MetricaController();
        $controller->listar();
        break;

    case 'usuarios':
    default:
        $controller = new UsuarioController();
        $controller->listar();
        break;
}
