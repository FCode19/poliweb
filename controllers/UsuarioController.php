<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    public function listar()
    {
        // Módulo para usar en enlaces de paginación
        $modulo = 'usuarios';

        $usuarios = Usuario::all();

        // Búsqueda
        $query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
        if (!empty($query)) {
            $usuarios = array_filter($usuarios, function ($u) use ($query) {
                return str_contains(strtolower($u['cod']), $query) || str_contains(strtolower($u['nombre']), $query) || str_contains(strtolower($u['apellido']), $query) || str_contains(strtolower($u['usuario']), $query);
            });
        }

        $porPagina = 6;
        $totalUsuarios = count($usuarios);
        $totalPaginas = max(1, ceil($totalUsuarios / $porPagina));
        $paginaActual = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        if ($paginaActual > $totalPaginas) {
            $paginaActual = $totalPaginas;
        }

        $inicio = ($paginaActual - 1) * $porPagina;
        $usuariosPaginados = array_slice($usuarios, $inicio, $porPagina);

        require __DIR__ . '/../views/usuario/listar.php';
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'cod' => Usuario::generarCodigoNuevo(),
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'usuario' => $_POST['usuario'],
                'contra' => $_POST['contra'],
            ];

            Usuario::insertar($datos);

            $pagina = $_POST['pagina'] ?? 1;

            header("Location: app.php?view=usuarios&page=$pagina");
            exit();
        }
    }

    public function editar()
    {
        if (isset($_POST['cod'], $_POST['nombre'], $_POST['apellido'], $_POST['usuario'], $_POST['contra'])) {
            Usuario::actualizar([
                'cod' => $_POST['cod'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'usuario' => $_POST['usuario'],
                'contra' => $_POST['contra'],
            ]);

            $pagina = $_POST['pagina'] ?? 1;

            header("Location: app.php?view=usuarios&page=$pagina");
            exit();
        }
    }

    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cod'])) {
            $cod = $_POST['cod'];
            Usuario::eliminar($cod);

            $pagina = $_POST['pagina'] ?? 1;

            header("Location: app.php?view=usuarios&page=$pagina");
            exit();
        }
    }
}
