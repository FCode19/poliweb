<?php
require_once __DIR__ . '/../../config/conexion.php';

$pdo = conectar();

// Obtener usuarios
$stmtUsuarios = $pdo->query("SELECT cod, nombre, apellido FROM usuarios");
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Obtener militares
$stmtMilitares = $pdo->query("SELECT cip, nombre, apellido FROM militares");
$militares = $stmtMilitares->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once "views/calendario/header_calendario.php"; ?>

<div class="container mt-4">
    <h3>Reserva de Entrevistas</h3>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'maximo'): ?>
        <div class="alert alert-danger">Este usuario ya tiene 3 entrevistas programadas ese día.</div>
    <?php elseif (isset($_GET['success'])): ?>
        <div class="alert alert-success">Reserva registrada correctamente.</div>
    <?php endif; ?>

    <form action="controllers/guardar_reserva.php" method="POST" class="border p-4 rounded shadow-sm">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="hora" class="form-label">Hora:</label>
                <input type="time" name="hora" class="form-control" step="3600" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="cod" class="form-label">Usuario evaluador:</label>
                <select name="cod" class="form-select" required>
                    <option value="">Seleccione un usuario</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['cod'] ?>">
                            <?= $u['nombre'] . ' ' . $u['apellido'] ?> (<?= $u['cod'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="cip" class="form-label">Militar examinado:</label>
                <select name="cip" class="form-select" required>
                    <option value="">Seleccione un militar</option>
                    <?php foreach ($militares as $m): ?>
                        <option value="<?= $m['cip'] ?>">
                            <?= $m['nombre'] . ' ' . $m['apellido'] ?> (<?= $m['cip'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" name="guardar" class="btn btn-primary">Reservar Entrevista</button>
        </div>
    </form>
</div>

<!-- Siguiente -->
<?php
$datosCalendario = include __DIR__ . '/../../controllers/eventos_calendario.php';

// Obtener mes y año actuales
$mes = (int)$datosCalendario['mes'];
$anio = (int)$datosCalendario['anio'];

// Calcular anterior y siguiente
$mesAnterior = $mes - 1;
$anioAnterior = $anio;
if ($mesAnterior < 1) {
    $mesAnterior = 12;
    $anioAnterior--;
}

$mesSiguiente = $mes + 1;
$anioSiguiente = $anio;
if ($mesSiguiente > 12) {
    $mesSiguiente = 1;
    $anioSiguiente++;
}

$estado = $e['estado'] ?? 'pendiente';
$colorFondo = match ($estado) {
    'cancelada' => 'bg-danger-subtle',
    'completada' => 'bg-success-subtle',
    default => 'bg-light',
};

// Nombre del mes
setlocale(LC_TIME, 'es_ES.UTF-8');
$nombreMes = strftime('%B', strtotime("$anio-$mes-01"));
?>

<div class="container mt-5">
    <h4 class="mb-3 d-flex justify-content-between align-items-center">
        <a href="app.php?view=calendario&mes=<?= $mesAnterior ?>&anio=<?= $anioAnterior ?>" class="btn btn-outline-primary btn-sm">&laquo; Anterior</a>
        <?= ucfirst($nombreMes) . " $anio" ?>
        <a href="app.php?view=calendario&mes=<?= $mesSiguiente ?>&anio=<?= $anioSiguiente ?>" class="btn btn-outline-primary btn-sm">Siguiente &raquo;</a>
    </h4>

    <table class="table table-bordered text-center mt-3">
        <thead class="table-dark">
            <tr>
                <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dia = 1;
            for ($semana = 0; $semana < 6; $semana++):
                echo "<tr>";
                for ($d = 1; $d <= 7; $d++):
                    if ($semana === 0 && $d < $datosCalendario['primerDiaSemana']) {
                        echo "<td></td>";
                    } elseif ($dia > $datosCalendario['diasMes']) {
                        echo "<td></td>";
                    } else {
                        echo "<td class='cal-dia'><strong>$dia</strong>";

                        if (isset($datosCalendario['eventos'][$dia])) {
                            foreach ($datosCalendario['eventos'][$dia] as $e) {
                                $estado = $e['estado'] ?? 'pendiente';
                                $colorFondo = match ($estado) {
                                    'cancelada' => 'bg-danger-subtle',
                                    'completada' => 'bg-success-subtle',
                                    default => 'bg-light',
                                };

                                echo "<div class='border mt-1 p-1 rounded small $colorFondo'>";
                                echo "<span class='fw-bold text-primary'>{$e['hora']}</span><br>";
                                echo "Militar: {$e['nombre_militar']} {$e['apellido_militar']}<br>";
                                echo "<span class='text-muted'>Eval: {$e['nombre_usuario']}</span><br>";
                                echo "<button 
                                        class='btn btn-sm btn-outline-secondary mt-1' 
                                        data-bs-toggle='modal' 
                                        data-bs-target='#modalEstado'
                                        data-id='{$e['id']}'
                                        data-estado='{$estado}'>
                                        Cambiar estado
                                      </button>";
                                echo "</div>";
                            }

                        }

                        echo "</td>";
                        $dia++;
                    }
                endfor;
                echo "</tr>";
                if ($dia > $datosCalendario['diasMes']) break;
            endfor;
            ?>
        </tbody>
    </table>
</div>
<!-- modal estado -->
<div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="controllers/actualizar_estado.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEstadoLabel">Cambiar Estado de Entrevista</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="estado-id">
        <label for="estado-select" class="form-label">Nuevo Estado:</label>
        <select name="estado" id="estado-select" class="form-select" required>
            <option value="pendiente">Pendiente</option>
            <option value="completada">Completada</option>
            <option value="cancelada">Cancelada</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalEstado');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const estado = button.getAttribute('data-estado');

        document.getElementById('estado-id').value = id;
        document.getElementById('estado-select').value = estado;
    });
});
</script>

<?php include_once "views/calendario/footer_calendario.php"; ?>
