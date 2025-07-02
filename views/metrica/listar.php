<?php include_once "views/metrica/header_metrica.php"; ?>

<div class="container mt-4">
    <div class="mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEntrevista">
            Subir Entrevista
        </button>
    </div>
    <h2 class="mb-4">Resultados poligráficos</h2>
    <div class="table-responsive mb-5">
        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th># Entrevista</th>
                    <th>Usuario</th>
                    <th>CIP Militar</th>
                    <th>Fecha</th>
                    <th>Cat. Específico</th>
                    <th>Cat. Rutinario</th>
                    <th>Cat. Vínculos Externos</th>
                    <th>Comentarios</th>
                    <th>Archivo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entrevistas as $e): ?>
                    <tr>
                        <td><?= $e['num_entrevista'] ?></td>
                        <td><?= $e['nombre_completo'] ?></td>
                        <td><?= $e['cip'] ?></td>
                        <td><?= $e['fecha'] ?></td>
                        <td><?= $e['cat_especifico'] ?? 0 ?></td>
                        <td><?= $e['cat_rutinario'] ?? 0 ?></td>
                        <td><?= $e['cat_vinculos_externos'] ?? 0 ?></td>
                        <td><?= $e['comentarios'] ?? '-' ?></td>
                        <td>
                            <?php if (!empty($e['file_entrevista'])): ?>
                                <a href="controllers/descargar_archivo.php?num=<?= $e['num_entrevista'] ?>" class="btn btn-sm btn-success">Descargar</a>
                            <?php else: ?>
                                <span class="text-muted">Sin contenido</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="mb-4">Métricas por Militar</h2>

    <div class="mb-3">
        <label for="usuarioSelect" class="form-label">Seleccionar Militares:</label>
        <select id="usuarioSelect" class="form-select" multiple style="max-width: 400px;">
            <?php foreach ($usuariosDisponibles as $cip => $nombreCompleto): ?>
                <option value="<?= $cip ?>"><?= $nombreCompleto ?></option>
            <?php endforeach; ?>
        </select>
        <div class="form-text">Mantén Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples militares.</div>
    </div>

    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Militar</th>
                    <th>Específico</th>
                    <th>Rutinario</th>
                    <th>Vínculos Externos</th>
                    <th>Situación</th>
                    <th>Resultados</th>
                </tr>
            </thead>
            <tbody id="tablaEntrevistas">
                <?php foreach ($entrevistas as $e): ?>
                    <tr>
                        <td><?= $e['nombre_completo'] ?></td>
                        <td><?= $e['cat_especifico'] ?></td>
                        <td><?= $e['cat_rutinario'] ?></td>
                        <td><?= $e['cat_vinculos_externos'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function generarPDF(nombre, cip, cat_especifico, cat_rutinario, cat_vinculos, situacion, fecha) {
            const comentario = prompt("¿Desea agregar un comentario para este informe? (Opcional):", "");
            const comentarioFinal = comentario && comentario.trim() !== "" ? comentario : "Sin comentarios";
            const params = new URLSearchParams({
                nombre,
                cip,
                cat_especifico,
                cat_rutinario,
                cat_vinculos,
                situacion,
                fecha,
                comentario: comentarioFinal
            });
            const url = 'controllers/generar_pdf.php?' + params.toString();
            window.open(url, '_blank');
        }
    </script>
    <div class="row mb-5">
        <div class="col-md-6">
            <canvas id="graficoBarras" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="graficoPie" height="200"></canvas>
        </div>
    </div>
</div>
<?php
foreach ($entrevistas as &$e) {
    unset($e['file_entrevista']);
}
unset($e);
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const entrevistas = <?= json_encode($entrevistas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK) ?>;
        const colores = ['#4e79a7', '#f28e2b', '#e15759'];

        const nombresMilitares = {};
        entrevistas.forEach(e => {
            nombresMilitares[e.cip] = `${e.cip} - ${e.nombre_completo}`;
        });

        const selectUsuarios = document.getElementById('usuarioSelect');
        const tablaBody = document.getElementById('tablaEntrevistas');
        let chartBar = null;
        let chartPie = null;

        function obtenerSeleccionados() {
            return [...selectUsuarios.selectedOptions].map(opt => opt.value);
        }

        function filtrarPorCip(cips) {
            return entrevistas.filter(e => cips.includes(e.cip.toString()));
        }

        function actualizarTabla(data) {
            tablaBody.innerHTML = '';
            data.forEach(e => {
                const promedio = (e.cat_especifico + e.cat_rutinario + e.cat_vinculos_externos) / 3;
                let situacionTexto = '';
                if (promedio <= 5) {
                    situacionTexto = 'Examinación urgente';
                } else if (promedio <= 8) {
                    situacionTexto = 'Acudir pronto';
                } else {
                    situacionTexto = 'Esperar próximo examen';
                }

                const situacionHTML = `<span style="color:${promedio <= 5 ? 'red' : promedio <= 8 ? 'orange' : 'green'}; font-weight:bold;">${situacionTexto}</span>`;

                tablaBody.innerHTML += `
                    <tr>
                        <td>${e.nombre_completo}</td>
                        <td>${e.cat_especifico}</td>
                        <td>${e.cat_rutinario}</td>
                        <td>${e.cat_vinculos_externos}</td>
                        <td>${situacionHTML}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="generarPDF(
                                '${e.nombre_completo}',
                                '${e.cip}',
                                ${e.cat_especifico},
                                ${e.cat_rutinario},
                                ${e.cat_vinculos_externos},
                                '${situacionTexto}',
                                '${e.fecha}'
                            )">Generar</button>
                        </td>
                    </tr>
                `;
            });
        }


        function actualizarGraficos(cips) {
            const datos = filtrarPorCip(cips);
            const agrupado = {};
            cips.forEach(cip => {
                agrupado[cip] = datos.filter(e => e.cip == cip);
            });

            const categorias = ['cat_especifico', 'cat_rutinario', 'cat_vinculos_externos'];
            const datasets = categorias.map((cat, i) => ({
                label: cat.replace('cat_', '').replace('_', ' ').toUpperCase(),
                data: cips.map(cip =>
                    agrupado[cip].reduce((acc, e) => acc + parseFloat(e[cat]), 0)
                ),
                backgroundColor: colores[i]
            }));

            const promedios = categorias.map(cat => {
                const valores = datos.map(e => parseFloat(e[cat]));
                const total = valores.reduce((a, b) => a + b, 0);
                return valores.length ? (total / valores.length).toFixed(2) : 0;
            });

            if (chartBar) chartBar.destroy();
            if (chartPie) chartPie.destroy();

            chartBar = new Chart(document.getElementById('graficoBarras'), {
                type: 'bar',
                data: {
                    labels: cips.map(cip => nombresMilitares[cip]),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Comparación por Categoría'
                        }
                    }
                }
            });

            chartPie = new Chart(document.getElementById('graficoPie'), {
                type: 'pie',
                data: {
                    labels: ['Específico', 'Rutinario', 'Vínculos Externos'],
                    datasets: [{
                        data: promedios,
                        backgroundColor: colores
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Promedio de Categorías (Gráfico Pie)'
                        }
                    }
                }
            });

            actualizarTabla(datos);
        }

        const todosCips = [...new Set(entrevistas.map(e => e.cip.toString()))];
        actualizarGraficos(todosCips);

        selectUsuarios.addEventListener('change', () => {
            const seleccionados = obtenerSeleccionados();
            actualizarGraficos(seleccionados.length ? seleccionados : todosCips);
        });
    });
</script>
<div class="modal fade" id="modalEntrevista" tabindex="-1" aria-labelledby="modalEntrevistaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="controllers/EntrevistaController.php" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEntrevistaLabel">Registrar Nueva Entrevista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label for="num_entrevista" class="form-label"># Entrevista</label>
                    <input type="number" class="form-control" name="num_entrevista" required>
                </div>
                <div class="col-md-4">
                    <label for="cod" class="form-label">Usuario (Examinador)</label>
                    <select class="form-select" name="cod" required>
                        <option value="" disabled selected>Seleccione...</option>
                        <?php
                        require_once __DIR__ . '/../../config/conexion.php';
                        $pdo = conectar();
                        $stmt = $pdo->query("SELECT cod, nombre, apellido FROM usuarios");
                        while ($u = $stmt->fetch()) {
                            echo "<option value='{$u['cod']}'>{$u['cod']} - {$u['nombre']} {$u['apellido']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="cip" class="form-label">Militar Evaluado</label>
                    <select class="form-select" name="cip" required>
                        <option value="" disabled selected>Seleccione...</option>
                        <?php
                        $stmt = $pdo->query("SELECT cip, nombre, apellido, grado FROM militares");
                        while ($m = $stmt->fetch()) {
                            echo "<option value='{$m['cip']}'>{$m['cip']} - {$m['nombre']} {$m['apellido']} ({$m['grado']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" name="fecha" required>
                </div>
                <div class="col-md-4">
                    <label for="cat_especifico" class="form-label">Categoría Específica</label>
                    <input type="number" class="form-control" name="cat_especifico" required>
                </div>
                <div class="col-md-4">
                    <label for="cat_rutinario" class="form-label">Categoría Rutinaria</label>
                    <input type="number" class="form-control" name="cat_rutinario" required>
                </div>
                <div class="col-md-4">
                    <label for="cat_vinculos_externos" class="form-label">Vínculos Externos</label>
                    <input type="number" class="form-control" name="cat_vinculos_externos" required>
                </div>
                <div class="col-md-8">
                    <label for="file_entrevista" class="form-label">Archivo (PDF)</label>
                    <input type="file" class="form-control" name="file_entrevista" accept=".pdf" required>
                </div>
                <div class="col-12">
                    <label for="comentarios" class="form-label">Comentarios</label>
                    <textarea class="form-control" name="comentarios" maxlength="100" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="registrar_entrevista" class="btn btn-primary">Registrar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<?php include_once "views/metrica/footer_metrica.php"; ?>
