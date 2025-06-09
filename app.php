<?php
require_once __DIR__ . '/controllers/UsuarioController.php';
$vista = $_GET['view'] ?? 'usuarios';
switch ($vista) {
  case 'formularios':
    include __DIR__ . '/views/formulario/listar.php';
    break;
  case 'usuarios':
  default:
    $controller = new UsuarioController();
    $controller->listar();
    break;
}