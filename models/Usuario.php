<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario
{
    public static function all()
    {
        $pdo = conectar(); // Llama a la función de conexión
        $sql = 'SELECT * FROM usuarios';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function generarCodigoNuevo()
    {
        $pdo = conectar();
        $sql = 'SELECT cod FROM usuarios ORDER BY cod DESC LIMIT 1';
        $stmt = $pdo->query($sql);
        $ultimo = $stmt->fetchColumn();

        if ($ultimo) {
            $num = (int) substr($ultimo, 1);
            $nuevoNum = $num + 1;
            return 'U' . str_pad($nuevoNum, 3, '0', STR_PAD_LEFT);
        } else {
            return 'U001';
        }
    }

    public static function insertar($usuario)
    {
        $pdo = conectar();
        $sql = "INSERT INTO usuarios (cod, nombre, apellido, usuario, contra)
            VALUES (:cod, :nombre, :apellido, :usuario, :contra)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cod' => $usuario['cod'],
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':usuario' => $usuario['usuario'],
            ':contra' => $usuario['contra'],
        ]);
    }

    public static function actualizar($usuario)
    {
        $pdo = conectar();
        $sql = "UPDATE usuarios SET
                nombre = :nombre,
                apellido = :apellido,
                usuario = :usuario,
                contra = :contra
            WHERE cod = :cod";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cod' => $usuario['cod'],
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':usuario' => $usuario['usuario'],
            ':contra' => $usuario['contra'],
        ]);
    }

    public static function eliminar($cod)
    {
        $pdo = conectar();
        $sql = 'DELETE FROM usuarios WHERE cod = :cod';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':cod' => $cod]);
    }
}
