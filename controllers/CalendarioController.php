<?php
require_once __DIR__ . '/../models/Calendario.php';

class CalendarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Calendario();
    }

    public function listar() {
        $fecha = $_GET['fecha'] ?? date('Y-m-d'); // por defecto hoy

        // Si se envió formulario de reserva
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservar'])) {
            $usuarioCod = $_POST['usuario_cod'];
            $cip = $_POST['cip_militar'];
            $hora = $_POST['hora'];

            // Validar disponibilidad
            if ($this->modelo->validarDisponibilidad($usuarioCod, $fecha, $hora)) {
                $this->modelo->crearReserva($usuarioCod, $cip, $fecha, $hora);
                header("Location: app.php?view=calendario&fecha=$fecha&exito=1");
                exit();
            } else {
                header("Location: app.php?view=calendario&fecha=$fecha&error=1");
                exit();
            }
        }

        // Listar entrevistas del día
        $reservas = $this->modelo->listarReservasPorDia($fecha);

        include __DIR__ . '/../views/calendario/listar.php';
    }
}
