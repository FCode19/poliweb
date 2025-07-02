<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    require_once __DIR__ . '/../config/conexion.php';

    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $usuario_cod = $_POST['cod'];
    $cip = $_POST['cip'];

    $pdo = conectar();

    // Validar si el usuario ya tiene 3 entrevistas ese día
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM agenda_entrevista WHERE usuario_cod = ? AND fecha = ?");
    $stmt->execute([$usuario_cod, $fecha]);
    $cantidad = $stmt->fetchColumn();

    if ($cantidad >= 3) {
        header("Location: ../app.php?view=calendario&error=maximo");
        exit();
    }

    // Insertar nueva reserva
    $stmt = $pdo->prepare("INSERT INTO agenda_entrevista (usuario_cod, cip_militar, fecha, hora) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario_cod, $cip, $fecha, $hora]);

    header("Location: ../app.php?view=calendario&success=1");
    exit();
} else {
    header("Location: ../app.php?view=calendario&error=acceso");
    exit();
}
