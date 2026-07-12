<?php
require_once __DIR__ . '/../config.php';
class Pago
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function crear(int $idCompra, int $idMetodo, float $monto, string $estado = 'APROBADO'): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pago (monto, fecha_pago, estado_pago, id_compra, id_metodo)
             VALUES (:monto, NOW(), :estado, :id_compra, :id_metodo)'
        );
        $stmt->execute([
            'monto'     => $monto,
            'estado'    => $estado,
            'id_compra' => $idCompra,
            'id_metodo' => $idMetodo,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
