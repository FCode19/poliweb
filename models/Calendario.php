<?php
require_once __DIR__ . '/../config/conexion.php';

class Calendario {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar();
    }
    
    public function listarReservasPorDia($fecha) {
        $stmt = $this->pdo->prepare("
        SELECT a.*, 
               u.nombre AS nombre_usuario, 
               CONCAT(m.nombre, ' ', m.apellido) AS nombre_militar
        FROM agenda_entrevista a
        INNER JOIN usuarios u ON a.usuario_cod = u.cod
        INNER JOIN militares m ON a.cip_militar = m.cip
        WHERE a.fecha = ?
        ORDER BY a.hora
    ");
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validarDisponibilidad($usuarioCod, $fecha, $hora) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS total
            FROM agenda_entrevista
            WHERE usuario_cod = ? AND fecha = ?
        ");
        $stmt->execute([$usuarioCod, $fecha]);
        $total = $stmt->fetchColumn();

        if ($total >= 3) {
            return false;
        }

        $horaInicio = new DateTime($hora);
        $horaFin = clone $horaInicio;
        $horaFin->modify('+1 hour 20 minutes');

        $stmt2 = $this->pdo->prepare("
            SELECT hora FROM agenda_entrevista
            WHERE usuario_cod = ? AND fecha = ?
        ");
        $stmt2->execute([$usuarioCod, $fecha]);
        $horarios = $stmt2->fetchAll(PDO::FETCH_COLUMN);

        foreach ($horarios as $h) {
            $actualInicio = new DateTime($h);
            $actualFin = clone $actualInicio;
            $actualFin->modify('+1 hour 20 minutes');

            if (
                ($horaInicio < $actualFin) && ($horaFin > $actualInicio)
            ) {
                return false;
            }
        }

        return true;
    }

    public function crearReserva($usuarioCod, $cip, $fecha, $hora) {
        $stmt = $this->pdo->prepare("
            INSERT INTO agenda_entrevista (usuario_cod, cip_militar, fecha, hora)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$usuarioCod, $cip, $fecha, $hora]);
    }
}
