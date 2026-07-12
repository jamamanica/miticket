<?php
require_once __DIR__ . '/../config.php';
class Ticket
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function crear(int $idCompra, int $idZona, int $idAsiento): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO ticket (fecha_emision, estado, id_compra, id_zona, id_asiento)
             VALUES (NOW(), "ACTIVO", :id_compra, :id_zona, :id_asiento)'
        );
        return $stmt->execute([
            'id_compra' => $idCompra,
            'id_zona'   => $idZona,
            'id_asiento'=> $idAsiento,
        ]);
    }

    public function listarPorCompra(int $idCompra): array
    {
        $sql = 'SELECT t.*, a.numero_asiento, z.nombre_zona, e.nombre AS nombre_evento, e.fecha, e.hora
                FROM ticket t
                JOIN asiento a ON a.id_asiento = t.id_asiento
                JOIN zona z ON z.id_zona = t.id_zona
                JOIN evento e ON e.id_evento = z.id_evento
                WHERE t.id_compra = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idCompra]);
        return $stmt->fetchAll();
    }
}
