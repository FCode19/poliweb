<?php $titulo = 'Listado de Usuarios'; ?>
<?php require_once __DIR__ . '/header_usuario.php'; ?>
<?php include __DIR__ . '/../../components/sidebar.php'; ?>

<div class="p-4 w-100">

    <div class="contenedor-principal">
        <h1 class="h4 mb-4">Usuarios</h1>
        <div class="d-flex justify-content-between align-items-center mb-3 gap-3">

            <!-- Filtro de busqueda -->
            <form class="d-flex flex-grow-1 gap-2" role="search" method="GET" action="app.php">
                <input type="hidden" name="view" value="usuarios">
                <input class="form-control input-busqueda" type="search" name="q" placeholder="Buscar usuario..."
                    aria-label="Buscar" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <!-- Boton de busqueda -->
                <button class="btn btn-buscar" type="submit">Buscar</button>
            </form>
            <!-- Boton de nuevo usuario -->
            <button type="button" class="btn-nuevo-usuario text-white px-4 py-2 border-0" data-bs-toggle="modal"
                data-bs-target="#modalUsuario">
                Nuevo Usuario
            </button>

        </div>

        <!-- Tabla de usuario -->
        <div class="flex-grow-1 mb-3 overflow-auto">
            <div class="table-responsive tabla-contenedor h-100">
                <table class="table mb-0 custom-table align-middle tabla-espaciada">
                    <thead class="bg-warning-subtle text-secondary">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuariosPaginados as $u): ?>
                        <tr>
                            <td><?= $u['cod'] ?></td>
                            <td><?= $u['nombre'] ?></td>
                            <td><?= $u['apellido'] ?></td>
                            <td><?= $u['usuario'] ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Boton de editar usuario -->
                                    <a href="#" class="btn-editar text-decoration-none" title="Editar"
                                        data-bs-toggle="modal" data-bs-target="#modalUsuario" data-modo="editar"
                                        data-cod="<?= $u['cod'] ?>" data-nombre="<?= $u['nombre'] ?>"
                                        data-apellido="<?= $u['apellido'] ?>" data-usuario="<?= $u['usuario'] ?>"
                                        data-contra="<?= $u['contra'] ?>">
                                        <i data-lucide="pencil"></i>
                                    </a>

                                    <!-- Boton de eliminar usuario -->
                                    <button type="button" class="btn-eliminar" title="Eliminar" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar" data-cod="<?= $u['cod'] ?>"
                                        data-nombre="<?= $u['nombre'] ?>">
                                        <i data-lucide="trash-2"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginacion -->
        <div class="pt-3">
            <?php
            $modulo = 'usuarios';
            $query = $_GET['q'] ?? null;
            include __DIR__ . '/../../components/paginacion.php';
            ?>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/footer_usuario.php'; ?>
