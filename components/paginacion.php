    <link href="CSS/paginacion.css" rel="stylesheet" type="text/css" />

<div class="d-flex justify-content-center align-items-center mt-4 py-3 mi-paginacion" style="min-height: 60px;">
    <nav>
        <ul class="pagination justify-content-center mb-0">

            <!-- Botón Anterior -->
            <?php
            $paginaAnterior = max(1, $paginaActual - 1);
            $urlAnterior = "app.php?view={$modulo}&page={$paginaAnterior}";
            if (!empty($query)) {
                $urlAnterior .= '&q=' . urlencode($query);
            }
            ?>
            <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $paginaActual <= 1 ? '#' : $urlAnterior ?>">
                    ← Anterior
                </a>
            </li>

            <!-- Números de página -->
            <?php
            $rango = 2;
            $puntos = false;
            for ($i = 1; $i <= $totalPaginas; $i++) {
                if ($i == 1 || $i == $totalPaginas || abs($i - $paginaActual) <= $rango) {
                    if ($puntos) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        $puntos = false;
                    }
                    $active = $i == $paginaActual ? 'active' : '';
                    $url = "app.php?view={$modulo}&page={$i}" . (!empty($query) ? '&q=' . urlencode($query) : '');
                    echo "<li class='page-item $active'><a class='page-link' href='$url'>$i</a></li>";
                } else {
                    $puntos = true;
                }
            }
            ?>

            <!-- Botón Siguiente -->
            <?php
            $paginaSiguiente = min($totalPaginas, $paginaActual + 1);
            $urlSiguiente = "app.php?view={$modulo}&page={$paginaSiguiente}";
            if (!empty($query)) {
                $urlSiguiente .= '&q=' . urlencode($query);
            }
            ?>
            <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $paginaActual >= $totalPaginas ? '#' : $urlSiguiente ?>">
                    Siguiente →
                </a>
            </li>

        </ul>
    </nav>
</div>
