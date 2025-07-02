<?php
require_once __DIR__ . '/../config/conexion.php';

header('Content-Type: application/json');

$sql = "SELECT e.*, m.nombre, m.apellido 
        FROM entrevistas e 
        INNER JOIN militares m ON e.militar_id = m.id";
$stmt = $pdo->query($sql);

$eventos = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = [
        'title' => $row['nombre'] . ' ' . $row['apellido'],
        'start' => $row['fecha'] . 'T' . $row['hora_inicio'],
        'end'   => $row['fecha'] . 'T' . $row['hora_fin']
    ];
}

echo json_encode($eventos);
