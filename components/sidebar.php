<div class="text-white p-3 vh-100 d-flex flex-column" style="width: 250px; background-color: #182917;">
    <div class="text-center mb-4">
        <div class="d-flex justify-content-center align-items-center"
            style="width: 140px; height: 100px; margin: 0 auto; background-color: #182917;">
            <img src="recursos/EPE_logo.png" alt="Logo EPE" style="max-height: 90px; max-width: 100%;">
        </div>
    </div>

    <small class="text-uppercase text-white-50">Gestión</small>
    <hr class="my-1 border-secondary">
    <ul class="nav flex-column mb-4">
        <li class="nav-item">
            <a href="app.php?view=usuarios"
                class="nav-link text-white px-0 <?= ($_GET['view'] ?? '') === 'usuarios' ? 'fw-bold' : '' ?>">
                Usuarios
            </a>
        </li>
    </ul>

    <small class="text-uppercase text-white-50">Estadisticas</small>
    <hr class="my-1 border-secondary">
    <ul class="nav flex-column mb-4">
        <li class="nav-item">
            <a href="app.php?view=metrica"
                class="nav-link text-white px-0 <?= ($_GET['view'] ?? '') === 'metrica' ? 'fw-bold' : '' ?>">
                Métricas
            </a>
        </li>
    </ul>


    <div class="mt-auto">
        <a href="logout.php" class="nav-link text-white px-0">Salir</a>
    </div>
</div>
