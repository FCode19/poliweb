<?php

require_once __DIR__ . '/../models/Metrica.php';

class MetricaController {

    public function listar() {
        $modelo = new Metrica();

        // Obtener todas las entrevistas desde la BD
        $entrevistas = $modelo->obtenerEntrevistas();

        // Calcular promedios
        $promedios = $modelo->calcularPromediosPorCategoria($entrevistas);

        // Obtener usuarios únicos desde entrevistas
        /*$usuariosDisponibles = array_unique(array_column($entrevistas, 'cod'));*/
        /*$usuariosDisponibles = [];
        foreach ($entrevistas as $e) {
            $usuariosDisponibles[$e['cod']] = $e['nombre_completo'];
        }*/
        
        $usuariosDisponibles = $modelo->obtenerCipsConNombreCompleto();
        
        // Mostrar la vista
        include __DIR__ . '/../views/metrica/listar.php';
    }
}
