<?php
session_start();
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Iniciar sesión - Poliweb</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light d-flex justify-content-center align-items-center vh-100">

        <div class="card p-4 shadow" style="min-width: 350px;">
            <h4 class="mb-3 text-center">Iniciar sesión</h4>
            <form action="controllers/validarLogin.php" method="post">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="pass" name="pass" required>
                </div>
                <button type="submit" class="btn btn-primary">Ingresar</button>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger mt-3">
                        Usuario o contraseña incorrectos.
                    </div>
                <?php endif; ?>
            </form>

        </div>

    </body>
</html>
