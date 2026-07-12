<?php
require_once __DIR__ . '/../config.php';
class DetalleCompra
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function crear(int $idCompra, int $idZona, int $cantidad, float $precioUnitario): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO detalle_compra (cantidad, precio_unitario, subtotal, id_compra, id_zona)
             VALUES (:cantidad, :precio, :subtotal, :id_compra, :id_zona)'
        );
        return $stmt->execute([
            'cantidad'  => $cantidad,
            'precio'    => $precioUnitario,
            'subtotal'  => $precioUnitario * $cantidad,
            'id_compra' => $idCompra,
            'id_zona'   => $idZona,
        ]);
    }

    public function listarPorCompra(int $idCompra): array
    {
        $sql = 'SELECT dc.*, z.nombre_zona, e.nombre AS nombre_evento
                FROM detalle_compra dc
                JOIN zona z ON z.id_zona = dc.id_zona
                JOIN evento e ON e.id_evento = z.id_evento
                WHERE dc.id_compra = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idCompra]);
        return $stmt->fetchAll();
    }
}
