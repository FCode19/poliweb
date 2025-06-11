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
    <body>
        <div class="container mt-3">
            <nav class="navbar navbar-expand-lg bg-white rounded shadow-sm px-4">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="recursos/EPE_logo.png" alt="Icono" width="40" height="40" class="d-inline-block align-text-top">
                    </a>
                    <a class="navbar-brand" href="#">
                        <img src="recursos/icon.png" alt="Icono" width="40" height="40" class="d-inline-block align-text-top">
                    </a>
                    <div class="mx-auto text-center">
                        <span class="navbar-text fw-bold">SISTEMA DE GESTIÓN DE RESULTADOS DE PRUEBAS POLIGRÁFICAS</span>
                    </div>
                    <div class="d-flex ms-auto">
                        <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
                    </div>
                </div>
            </nav>
        </div>
        <br>
        <div class="container-fluid p-0 m-0">
            <div class="row g-0" style="height: 80vh;">
                <div class="col-md-6">
                    <div class="seccion h-100" style="background-image: url('recursos/examinadores.webp');">
                        <div class="overlay-text">EXAMINADORES</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="seccion h-100" style="background-image: url('recursos/administradores.jpeg');">
                        <div class="overlay-text">ADMINISTRADORES</div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <div class="container">
                <p class="mb-0">© 2025 Ejercito del Perú. Todos los derechos reservados.</p>
            </div>
        </footer>

        <?php
        // put your code here
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
