<?php
require_once __DIR__ . '/../config.php';
class Organizador
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function crear(string $dni, string $nombreEmpresa, ?string $ruc): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO organizador (dni_organizador, nombre_empresa, ruc) VALUES (:dni, :empresa, :ruc)'
        );
        return $stmt->execute([
            'dni'     => $dni,
            'empresa' => $nombreEmpresa,
            'ruc'     => $ruc ?: null,
        ]);
    }

    public function existe(string $dni): bool
    {
        $stmt = $this->db->prepare('SELECT dni_organizador FROM organizador WHERE dni_organizador = :dni');
        $stmt->execute(['dni' => $dni]);
        return (bool) $stmt->fetch();
    }

    public function buscarPorDni(string $dni): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM organizador WHERE dni_organizador = :dni');
        $stmt->execute(['dni' => $dni]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
