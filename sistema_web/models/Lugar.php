<?php
require_once __DIR__ . '/../config.php';
class Lugar
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query('SELECT * FROM lugar ORDER BY nombre_lugar');
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM lugar WHERE id_lugar = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function crear(string $nombre, string $direccion, string $ciudad, int $capacidad): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO lugar (nombre_lugar, direccion, ciudad, capacidad) VALUES (:nombre, :direccion, :ciudad, :capacidad)'
        );
        $stmt->execute([
            'nombre'    => $nombre,
            'direccion' => $direccion,
            'ciudad'    => $ciudad,
            'capacidad' => $capacidad,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
