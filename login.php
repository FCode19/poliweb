<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Poliweb</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tu archivo CSS personalizado -->
    <link rel="stylesheet" href="CSS/Login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo o título -->
            <div class="text-center mb-3">
                <i class="bi bi-shield-lock" style="font-size: 2rem; color: #123d12;"></i>
                <h2 class="mt-2" style="color: #123d12;">POLIWEB</h2>
            </div>

            <!-- Mensaje de bienvenida -->
            <p class="text-center text-muted mb-4" style="font-size: 0.95rem;">
                Ingresa tus credenciales para acceder a la plataforma.
            </p>

            <!-- Formulario -->
            <form action="controllers/validarLogin.php" method="post" autocomplete="off">
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña" required>
                </div>

                <button type="submit" class="btn btn-custom w-100 mt-4">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar
                </button>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger mt-3 text-center">
                        Usuario o contraseña incorrectos.
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
<div class="overlay"></div>

</html>
