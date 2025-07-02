<?php
require_once __DIR__ . '/../config/conexion.php';

if (!isset($_GET['num'])) {
    exit("Solicitud inválida");
}

$num = $_GET['num'];
$pdo = conectar();

// Buscar archivo por número de entrevista
$stmt = $pdo->prepare("SELECT file_entrevista FROM entrevista WHERE num_entrevista = ?");
$stmt->execute([$num]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || empty($row['file_entrevista'])) {
    exit("Archivo no encontrado.");
}

$contenido = $row['file_entrevista'];

// Puedes modificar este nombre o tipo si lo deseas más específico
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=entrevista_$num.pdf"); // o .docx según corresponda
header('Content-Length: ' . strlen($contenido));
echo $contenido;
exit;
