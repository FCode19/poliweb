<?php
require_once __DIR__ . '/../config/conexion.php';

class Metrica {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar();
    }

    /*public function obtenerEntrevistas() {
        $sql = "SELECT * FROM entrevista";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/
    
    public function obtenerEntrevistas() {
        $pdo = conectar();

        $sql = "SELECT e.*, m.nombre AS nombre_militar, m.apellido AS apellido_militar FROM entrevista e JOIN militares m ON e.cip = m.cip";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $entrevistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($entrevistas as &$row) {
            $row['nombre_completo'] = $row['nombre_militar'] . ' ' . $row['apellido_militar'];
        }

        return $entrevistas;
    }

    public function obtenerEntrevistasPorUsuario($codUsuario) {
        $sql = "SELECT * FROM entrevista WHERE cod = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$codUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEntrevistasPorUsuarios($codUsuarios = []) {
        if (empty($codUsuarios)) return [];

        $placeholders = implode(',', array_fill(0, count($codUsuarios), '?'));
        $sql = "SELECT * FROM entrevista WHERE cod IN ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($codUsuarios);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularPromediosPorCategoria($entrevistas) {
        $totales = ['cat_especifico' => 0, 'cat_rutinario' => 0, 'cat_vinculos_externos' => 0];
        $conteo = count($entrevistas);

        foreach ($entrevistas as $e) {
            $totales['cat_especifico'] += $e['cat_especifico'] ?? 0;
            $totales['cat_rutinario'] += $e['cat_rutinario'] ?? 0;
            $totales['cat_vinculos_externos'] += $e['cat_vinculos_externos'] ?? 0;
        }

        return $conteo > 0 ? array_map(fn($val) => round($val / $conteo, 2), $totales) : $totales;
    }
    
    public function obtenerCipsConNombreCompleto() {
        require_once __DIR__ . '/../config/conexion.php';
        $pdo = conectar();

        $stmt = $pdo->query("SELECT DISTINCT m.cip, m.nombre, m.apellido FROM entrevista e JOIN militares m ON e.cip = m.cip");

        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[$row['cip']] = $row['cip'] . ' - ' . $row['nombre'] . ' ' . $row['apellido'];
        }

        return $usuarios;
    }
}
