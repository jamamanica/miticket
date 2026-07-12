<?php
require_once __DIR__ . '/../config.php';
class Cliente
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function crear(string $dni): bool
    {
        $stmt = $this->db->prepare('INSERT INTO cliente (dni_cliente) VALUES (:dni)');
        return $stmt->execute(['dni' => $dni]);
    }

    public function existe(string $dni): bool
    {
        $stmt = $this->db->prepare('SELECT dni_cliente FROM cliente WHERE dni_cliente = :dni');
        $stmt->execute(['dni' => $dni]);
        return (bool) $stmt->fetch();
    }
}
