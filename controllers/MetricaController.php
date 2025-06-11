<?php

class MetricaController {

    private $rutaJSON;

    public function __construct() {
        $this->rutaJSON = __DIR__ . '/../storage/entrevistas/';
    }

    public function listar() {
        $entrevistas = [];

        foreach (glob($this->rutaJSON . 'entrevistas_*.json') as $archivo) {
            $contenido = file_get_contents($archivo);
            $datos = json_decode($contenido, true);

            if (is_array($datos)) {
                foreach ($datos as $entrevista) {
                    $entrevistas[] = $entrevista;
                }
            }
        }

        $promedios = null;
        if (!empty($entrevistas)) {
            $totales = [
                'cat_especifico' => 0,
                'cat_rutinario' => 0,
                'cat_vinculos_externos' => 0
            ];

            foreach ($entrevistas as $e) {
                $totales['cat_especifico'] += $e['cat_especifico'];
                $totales['cat_rutinario'] += $e['cat_rutinario'];
                $totales['cat_vinculos_externos'] += $e['cat_vinculos_externos'];
            }

            $cantidad = count($entrevistas);
            $promedios = [
                'cat_especifico' => round($totales['cat_especifico'] / $cantidad, 2),
                'cat_rutinario' => round($totales['cat_rutinario'] / $cantidad, 2),
                'cat_vinculos_externos' => round($totales['cat_vinculos_externos'] / $cantidad, 2)
            ];
        }

        $usuariosDisponibles = array_unique(array_column($entrevistas, 'cod'));

        include __DIR__ . '/../views/metrica/listar.php';
    }
}
