<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Poliweb</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="CSS/estilos.css" rel="stylesheet" type="text/css"/>
</head>
    </head>
    <body class="bg-light">
        <div class="container mt-3">
            <nav class="navbar navbar-expand-lg bg-white rounded shadow-sm px-4">
                <div class="container-fluid">
                    <!-- Icono a la izquierda -->
                    <a class="navbar-brand" href="#">
                        <img src="recursos/icon.png" alt="Icono" width="40" height="40" class="d-inline-block align-text-top">
                    </a>

                    <!-- Botón a la derecha -->
                    <div class="d-flex ms-auto">
                        <a href="#" class="btn btn-primary">Iniciar Sesión</a>
                    </div>
                </div>
            </nav>
            
            <div class="row mt-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="seccion" style="background-image: url('recursos/examinadores.webp');">
                        <div class="overlay-text">EXAMINADORES</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="seccion" style="background-image: url('recursos/administradores.jpeg');">
                        <div class="overlay-text">ADMINISTRADORES</div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        // put your code here
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
