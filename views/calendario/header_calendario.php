<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Métricas | EPE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .titulo {
            text-align: center;
            margin-top: 20px;
            color: #182917;
        }
        td {
            vertical-align: top;
            height: 120px;
            padding: 8px;
        }
        td.cal-dia {
            height: 150px;
            vertical-align: top;
            overflow-y: auto;
            padding: 5px;
            position: relative;
        }

        .cal-dia > strong {
            position: absolute;
            top: 4px;
            left: 6px;
            font-size: 0.85rem;
            color: #495057;
        }

        .cal-dia > div {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include_once "components/sidebar.php"; ?>

        <div class="flex-grow-1 p-4">
