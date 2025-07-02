<?php
require_once __DIR__ . '/../config/conexion.php';
$pdo = conectar();

// Obtener mes y año desde GET o usar los actuales
$mesActual = isset($_GET['mes']) ? str_pad($_GET['mes'], 2, '0', STR_PAD_LEFT) : date('m');
$anioActual = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$primerDiaMes = "$anioActual-$mesActual-01";
$diaInicioSemana = date('N', strtotime($primerDiaMes)); // 1 = lunes
$numeroDiasMes = date('t', strtotime($primerDiaMes));

// Consultar entrevistas
$stmt = $pdo->prepare("
    SELECT 
        a.id, a.fecha, a.hora, a.estado,
        u.nombre AS nombre_usuario,
        m.nombre AS nombre_militar,
        m.apellido AS apellido_militar
    FROM agenda_entrevista a
    JOIN usuarios u ON a.usuario_cod = u.cod
    JOIN militares m ON a.cip_militar = m.cip
    WHERE MONTH(a.fecha) = ? AND YEAR(a.fecha) = ?
");
$stmt->execute([$mesActual, $anioActual]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por día
$eventosPorDia = [];
foreach ($reservas as $r) {
    $dia = date('j', strtotime($r['fecha']));
    $eventosPorDia[$dia][] = $r;
}

// Retornar
return [
    'anio' => $anioActual,
    'mes' => (int)$mesActual,
    'primerDiaSemana' => $diaInicioSemana,
    'diasMes' => $numeroDiasMes,
    'eventos' => $eventosPorDia
];
