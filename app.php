<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<?php
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/MetricaController.php';
require_once __DIR__ . '/controllers/CalendarioController.php';

$vista = $_GET['view'] ?? 'usuarios';

switch ($vista) {
    case 'formularios':
        include __DIR__ . '/views/formulario/listar.php';
        break;
    case 'calendario':
        $controller = new CalendarioController();
        $controller->listar();
        break;
    case 'metrica':
        $controller = new MetricaController();
        $controller->listar();
        break;

    case 'usuarios':
    default:
        $controller = new UsuarioController();

        $accion = $_GET['action'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($accion === 'crear') {
                $controller->crear();
            } elseif ($accion === 'editar') {
                $controller->editar();
            } elseif ($accion === 'eliminar') {
                $controller->eliminar();
            } else {
                $controller->listar();
            }
        } else {
            $controller->listar();
        }
        break;
}
