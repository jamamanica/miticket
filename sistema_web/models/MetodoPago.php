<?php
require_once __DIR__ . '/../config.php';
class MetodoPago
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query('SELECT * FROM metodo_pago ORDER BY id_metodo');
        return $stmt->fetchAll();
    }
}
