<?php
require_once __DIR__ . '/../config.php';
class Compra
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function crear(string $dniCliente, float $total): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO compra (fecha_compra, total, estado, dni_cliente)
             VALUES (NOW(), :total, "PENDIENTE", :dni)'
        );
        $stmt->execute(['total' => $total, 'dni' => $dniCliente]);
        return (int) $this->db->lastInsertId();
    }

    public function actualizarEstado(int $idCompra, string $estado): bool
    {
        $stmt = $this->db->prepare('UPDATE compra SET estado = :estado WHERE id_compra = :id');
        return $stmt->execute(['estado' => $estado, 'id' => $idCompra]);
    }

    public function listarPorCliente(string $dniCliente): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM compra WHERE dni_cliente = :dni ORDER BY fecha_compra DESC'
        );
        $stmt->execute(['dni' => $dniCliente]);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM compra WHERE id_compra = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
