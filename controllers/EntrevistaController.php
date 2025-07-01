<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_entrevista'])) {
    require_once __DIR__ . '/../config/conexion.php';

    $pdo = conectar();

    $stmt = $pdo->prepare("INSERT INTO entrevista (
        num_entrevista, cod, cip, fecha,
        cat_especifico, cat_rutinario, cat_vinculos_externos,
        file_entrevista, comentarios
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $fileData = null;
    if (isset($_FILES['file_entrevista']) && $_FILES['file_entrevista']['error'] === 0) {
        $fileTmp = $_FILES['file_entrevista']['tmp_name'];
        $fileType = mime_content_type($fileTmp);
        if ($fileType === 'application/pdf') {
            $fileData = file_get_contents($fileTmp);
        } else {
            header('Location: ../app.php?view=metrica&error=archivo');
            exit();
        }
    }

    $stmt->execute([
        $_POST['num_entrevista'],
        $_POST['cod'],
        $_POST['cip'],
        $_POST['fecha'],
        $_POST['cat_especifico'],
        $_POST['cat_rutinario'],
        $_POST['cat_vinculos_externos'],
        $fileData,
        $_POST['comentarios']
    ]);

    header('Location: ../app.php?view=metrica');
    exit();
}
