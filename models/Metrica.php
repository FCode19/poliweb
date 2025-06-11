<?php

class Metrica {
    private $rutaBase;
    
    public function __construct($rutaBase = "storage/") {
        $this->rutaBase = rtrim($rutaBase, "/") . "/";
    }
    public function obtenerEntrevistasPorUsuario($codUsuario) {
        $rutaArchivo = $this->rutaBase . "entrevistas_" . $codUsuario . ".json";

        if (!file_exists($rutaArchivo)) {
            return [];
        }

        $contenido = file_get_contents($rutaArchivo);
        return json_decode($contenido, true) ?? [];
    }

    public function obtenerEntrevistasPorUsuarios($codUsuarios = []) {
        $resultados = [];

        foreach ($codUsuarios as $cod) {
            $entrevistas = $this->obtenerEntrevistasPorUsuario($cod);
            foreach ($entrevistas as $entrevista) {
                $entrevista['cod'] = $cod;
                $resultados[] = $entrevista;
            }
        }

        return $resultados;
    }

    public function calcularPromediosPorCategoria($entrevistas) {
        $totales = ['cat_especifico' => 0, 'cat_rutinario' => 0, 'cat_vinculos_externos' => 0];
        $conteo = 0;

        foreach ($entrevistas as $e) {
            $totales['cat_especifico'] += $e['cat_especifico'] ?? 0;
            $totales['cat_rutinario'] += $e['cat_rutinario'] ?? 0;
            $totales['cat_vinculos_externos'] += $e['cat_vinculos_externos'] ?? 0;
            $conteo++;
        }

        return $conteo > 0 ? array_map(fn($val) => round($val / $conteo, 2), $totales) : $totales;
    }
}
