<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    public function listar()
    {
        $ruta = __DIR__ . '/../storage/usuarios.json';

        // EDITAR USUARIO
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarEdicion'])) {
            $data = $_POST;
            $usuarios = json_decode(file_get_contents($ruta), true);
            $errores = [];

            // Validaciones
            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $data['nombre'])) {
                $errores[] = "El nombre solo debe contener letras.";
            }
            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $data['apellido'])) {
                $errores[] = "El apellido solo debe contener letras.";
            }
            foreach ($usuarios as $u) {
                if ($u['usuario'] === $data['usuario'] && $u['cod'] !== $data['cod']) {
                    $errores[] = "El nombre de usuario ya está en uso.";
                    break;
                }
            }
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $data['contra'])) {
                $errores[] = "La contraseña debe tener al menos 8 caracteres, con letras y números.";
            }

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header('Location: app.php?view=usuarios');
                exit();
            }

            foreach ($usuarios as &$usuario) {
                if ($usuario['cod'] === $data['cod']) {
                    $usuario['nombre'] = $data['nombre'];
                    $usuario['apellido'] = $data['apellido'];
                    $usuario['usuario'] = $data['usuario'];
                    $usuario['contra'] = $data['contra'];
                    break;
                }
            }
            unset($usuario);

            file_put_contents($ruta, json_encode($usuarios, JSON_PRETTY_PRINT));

            header('Location: app.php?view=usuarios');
            exit();
        }

        // --- ELIMINAR USUARIO
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            $cod = $_POST['eliminar'];
            $usuarios = json_decode(file_get_contents($ruta), true);

            $usuarios = array_filter($usuarios, function ($u) use ($cod) {
                return $u['cod'] !== $cod;
            });

            file_put_contents($ruta, json_encode(array_values($usuarios), JSON_PRETTY_PRINT));

            header('Location: app.php?view=usuarios');
            exit();
        }
        // --- REGISTRAR NUEVO USUARIO
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
            $data = $_POST;
            $usuarios = json_decode(file_get_contents($ruta), true);
            $errores = [];

            // Validaciones
            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $data['nombre'])) {
                $errores[] = "El nombre solo debe contener letras.";
            }
            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $data['apellido'])) {
                $errores[] = "El apellido solo debe contener letras.";
            }
            foreach ($usuarios as $u) {
                if ($u['usuario'] === $data['usuario']) {
                    $errores[] = "El nombre de usuario ya está en uso.";
                    break;
                }
            }
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $data['contra'])) {
                $errores[] = "La contraseña debe tener al menos 8 caracteres, con letras y números.";
            }

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header('Location: app.php?view=usuarios');
                exit();
            }

            // Generar código único incremental con formato U###
            if (count($usuarios) === 0) {
                $nuevoCod = 'U001';
            } else {
                // codigo para tener el codigo ordenado
                usort($usuarios, function ($a, $b) {
                    return strcmp($a['cod'], $b['cod']);
                });
                $ultimoUsuario = end($usuarios);
                $ultimoCod = $ultimoUsuario['cod'];

                $num = (int) substr($ultimoCod, 1);
                $num++;

                $nuevoCod = 'U' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }

            $nuevoUsuario = [
                'cod' => $nuevoCod,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'usuario' => $data['usuario'],
                'contra' => $data['contra'],
            ];

            $usuarios[] = $nuevoUsuario;

            file_put_contents($ruta, json_encode($usuarios, JSON_PRETTY_PRINT));

            header('Location: app.php?view=usuarios');
            exit();
        }

        //PAGINACIÓN
        $usuarios = Usuario::all();
        $query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : null;

        if ($query) {
            $usuarios = array_filter($usuarios, function ($u) use ($query) {
                return str_contains(strtolower($u['cod']), $query) || str_contains(strtolower($u['nombre']), $query) || str_contains(strtolower($u['apellido']), $query) || str_contains(strtolower($u['usuario']), $query);
            });
        }

        $porPagina = 6;
        $totalUsuarios = count($usuarios);
        $totalPaginas = ceil($totalUsuarios / $porPagina);
        $paginaActual = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $inicio = ($paginaActual - 1) * $porPagina;
        $usuariosPaginados = array_slice($usuarios, $inicio, $porPagina);

        include __DIR__ . '/../views/usuario/listar.php';
    }
}
