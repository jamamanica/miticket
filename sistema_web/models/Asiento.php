<?php
require_once __DIR__ . '/../config.php';

class Asiento
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene N asientos disponibles de una zona y los bloquea (FOR UPDATE)
     * Debe llamarse dentro de una transacción.
     */
    public function obtenerDisponibles(int $idZona, int $cantidad): array
    {
        $stmt = $this->db->prepare(
            'SELECT id_asiento FROM asiento
             WHERE id_zona = :id_zona AND estado = "DISPONIBLE"
             ORDER BY id_asiento
             LIMIT :cantidad
             FOR UPDATE'
        );
        $stmt->bindValue(':id_zona', $idZona, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        return array_column($stmt->fetchAll(), 'id_asiento');
    }

    public function marcarVendido(int $idAsiento): bool
    {
        $stmt = $this->db->prepare('UPDATE asiento SET estado = "VENDIDO" WHERE id_asiento = :id');
        return $stmt->execute(['id' => $idAsiento]);
    }

    /** Genera N asientos numerados (1..N) para una zona recién creada, todos DISPONIBLE */
    public function generarParaZona(int $idZona, int $cantidad, string $prefijo = 'A'): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO asiento (numero_asiento, estado, id_zona) VALUES (:numero, "DISPONIBLE", :id_zona)'
        );
        for ($i = 1; $i <= $cantidad; $i++) {
            $stmt->execute([
                'numero'  => $prefijo . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'id_zona' => $idZona,
            ]);
        }
    }

    public function contarPorZona(int $idZona): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM asiento WHERE id_zona = :id');
        $stmt->execute(['id' => $idZona]);
        return (int) $stmt->fetch()['total'];
    }
}
