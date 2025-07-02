<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/conexion.php';
    $pdo = conectar();

    $id = $_POST['id'];
    $estado = $_POST['estado'];

    $stmt = $pdo->prepare("UPDATE agenda_entrevista SET estado = ? WHERE id = ?");
    $stmt->execute([$estado, $id]);

    header('Location: ../app.php?view=calendario&success=estado');
    exit();
}
?>
