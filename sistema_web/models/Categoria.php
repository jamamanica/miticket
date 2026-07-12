<?php
require_once __DIR__ . '/../config.php';

class Categoria
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarTodas(): array
    {
        $stmt = $this->db->query('SELECT * FROM categoria ORDER BY nombre_categoria');
        return $stmt->fetchAll();
    }
}
