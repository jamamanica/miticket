<?php
require_once __DIR__ . '/../config.php';
class Zona
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarPorEvento(int $idEvento): array
    {
        $stmt = $this->db->prepare('SELECT * FROM zona WHERE id_evento = :id ORDER BY precio DESC');
        $stmt->execute(['id' => $idEvento]);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM zona WHERE id_zona = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function crear(int $idEvento, string $nombre, float $precio, int $capacidad): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO zona (nombre_zona, precio, stock, capacidad, id_evento)
             VALUES (:nombre, :precio, :capacidad, :capacidad2, :id_evento)'
        );
        $stmt->execute([
            'nombre'     => $nombre,
            'precio'     => $precio,
            'capacidad'  => $capacidad,
            'capacidad2' => $capacidad,
            'id_evento'  => $idEvento,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function descontarStock(int $idZona, int $cantidad): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE zona SET stock = stock - :cantidad WHERE id_zona = :id AND stock >= :cantidad2'
        );
        return $stmt->execute([
            'cantidad'  => $cantidad,
            'id'        => $idZona,
            'cantidad2' => $cantidad,
        ]) && $stmt->rowCount() > 0;
    }
}
