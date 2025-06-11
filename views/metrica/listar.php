<?php include_once "views/metrica/header_metrica.php"; ?>

<div class="container mt-4">
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entrevistas as $e): ?>
                    <tr>
                        <td><?= $e['num_entrevista'] ?></td>
                        <td><?= $e['cod'] ?></td>
                        <td><?= $e['cip'] ?></td>
                        <td><?= $e['fecha'] ?></td>
                        <td><?= $e['cat_especifico'] ?></td>
                        <td><?= $e['cat_rutinario'] ?></td>
                        <td><?= $e['cat_vinculos_externos'] ?></td>
                        <td><?= $e['comentarios'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <h2 class="mb-4">Métricas por Usuario</h2>

    <div class="mb-3">
        <label for="usuarioSelect" class="form-label">Seleccionar Usuarios:</label>
        <select id="usuarioSelect" class="form-select" multiple style="max-width: 400px;">
            <?php foreach ($usuariosDisponibles as $usuario): ?>
                <option value="<?= $usuario ?>"><?= $usuario ?></option>
            <?php endforeach; ?>
        </select>
        <div class="form-text">Mantén Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples usuarios.</div>
    </div>

    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Específico</th>
                    <th>Rutinario</th>
                    <th>Vínculos Externos</th>
                </tr>
            </thead>
            <tbody id="tablaEntrevistas">
                <?php foreach ($entrevistas as $e): ?>
                    <tr>
                        <td><?= $e['cod'] ?></td>
                        <td><?= $e['cat_especifico'] ?></td>
                        <td><?= $e['cat_rutinario'] ?></td>
                        <td><?= $e['cat_vinculos_externos'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <canvas id="graficoBarras" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="graficoPie" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const entrevistas = <?= json_encode($entrevistas) ?>;
    const usuariosUnicos = [...new Set(entrevistas.map(e => e.cod))];
    const colores = ['#4e79a7', '#f28e2b', '#e15759'];

    const selectUsuarios = document.getElementById('usuarioSelect');
    const tablaBody = document.getElementById('tablaEntrevistas');
    let chartBar = null;
    let chartPie = null;

    function filtrarEntrevistasPorUsuarios(seleccionados) {
        return entrevistas.filter(e => seleccionados.includes(e.cod));
    }

    function actualizarTabla(entrevistasFiltradas) {
        tablaBody.innerHTML = '';
        entrevistasFiltradas.forEach(e => {
            const fila = `
                <tr>
                    <td>${e.cod}</td>
                    <td>${e.cat_especifico}</td>
                    <td>${e.cat_rutinario}</td>
                    <td>${e.cat_vinculos_externos}</td>
                </tr>
            `;
            tablaBody.innerHTML += fila;
        });
    }

    function renderGraficos(usuariosFiltrados) {
        const entrevistasFiltradas = filtrarEntrevistasPorUsuarios(usuariosFiltrados);
        const datosAgrupados = {};
        usuariosFiltrados.forEach(u => {
            datosAgrupados[u] = entrevistasFiltradas.filter(e => e.cod === u);
        });

        const datasets = ['cat_especifico', 'cat_rutinario', 'cat_vinculos_externos'].map((cat, i) => ({
            label: cat.replace('cat_', '').replace('_', ' ').toUpperCase(),
            data: usuariosFiltrados.map(u =>
                datosAgrupados[u].reduce((acc, e) => acc + parseFloat(e[cat]), 0)
            ),
            backgroundColor: colores[i]
        }));

        const promedios = ['cat_especifico', 'cat_rutinario', 'cat_vinculos_externos'].map(cat => {
            const valores = entrevistasFiltradas.map(e => parseFloat(e[cat]));
            const total = valores.reduce((a, b) => a + b, 0);
            return valores.length > 0 ? (total / valores.length).toFixed(2) : 0;
        });

        if (chartBar) chartBar.destroy();
        if (chartPie) chartPie.destroy();

        chartBar = new Chart(document.getElementById('graficoBarras'), {
            type: 'bar',
            data: {
                labels: usuariosFiltrados,
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

        actualizarTabla(entrevistasFiltradas);
    }

    renderGraficos(usuariosUnicos);

    selectUsuarios.addEventListener('change', () => {
        const seleccionados = [...selectUsuarios.selectedOptions].map(opt => opt.value);
        renderGraficos(seleccionados.length > 0 ? seleccionados : usuariosUnicos);
    });
</script>

<?php include_once "views/metrica/footer_metrica.php"; ?>
