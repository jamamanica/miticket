<?php
/**
 * Conexión a la base de datos usando PDO (Singleton)
 */
class Database
{
    private static ?PDO $instance = null;

    // ---- Ajusta estos datos a tu entorno local ----
    private const HOST   = '127.0.0.1';
    private const DBNAME = 'DBMITICKET';
    private const USER   = 'root';
    private const PASS   = '';
    private const CHARSET = 'utf8mb4';
    // ------------------------------------------------

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DBNAME . ';charset=' . self::CHARSET;
            try {
                self::$instance = new PDO($dsn, self::USER, self::PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
