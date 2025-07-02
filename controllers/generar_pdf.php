<?php
require_once __DIR__ . '/../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$nombre = $_GET['nombre'] ?? '';
$cip = $_GET['cip'] ?? '';
$cat_especifico = $_GET['cat_especifico'] ?? 0;
$cat_rutinario = $_GET['cat_rutinario'] ?? 0;
$cat_vinculos = $_GET['cat_vinculos'] ?? 0;
$situacion = $_GET['situacion'] ?? '';
$comentario = $_GET['comentario'] ?? 'Sin comentarios';
$fecha = $_GET['fecha'] ?? date('Y-m-d');

date_default_timezone_set('America/Lima');

$fechaObj = new DateTime();
$fecha = $fechaObj->format('Y-m-d');

$dia = $fechaObj->format('d');
$mes_num = (int)$fechaObj->format('m');
$anio = $fechaObj->format('Y');
$meses = [
    1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
    5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
    9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
];
$mes = $meses[$mes_num];
$fecha_literal = "$dia de $mes de $anio";

require_once __DIR__ . '/../config/conexion.php';
$pdo = conectar();
$stmt = $pdo->prepare("SELECT grado, dependencia FROM militares WHERE cip = ?");
$stmt->execute([$cip]);
$militar = $stmt->fetch(PDO::FETCH_ASSOC);
$grado = $militar['grado'] ?? '---';
$dependencia = $militar['dependencia'] ?? '---';

$template = file_get_contents(__DIR__ . '/../recursos/plantilla_poliweb.html');

$buscar = ['<nombre>', '<cip>', '<cat_especifico>', '<cat_rutinario>', '<cat_vinculos>', '<situacion>', '<comentario>', '<dia>', '<mes>', '<anio>', '<grado>', '<dependencia>', '<fecha>', '<fecha_literal>'];
$reemplazar = [$nombre, $cip, $cat_especifico, $cat_rutinario, $cat_vinculos, $situacion, $comentario, $dia, $mes, $anio, $grado, $dependencia, $fecha, $fecha_literal];

$html = str_replace($buscar, $reemplazar, $template);

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("informe_$cip.pdf", ["Attachment" => false]);
