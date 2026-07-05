<?php
class Conexion {
    private static $conexion = null;

    public static function conectar() {
        if (self::$conexion == null) {
            $host = "localhost";
            $usuario = "root";
            $password = "";
            $bd = "DBMITICKET";

            self::$conexion = new mysqli($host, $usuario, $password, $bd);

            if (self::$conexion->connect_error) {
                die("Error de conexión: " . self::$conexion->connect_error);
            }
            self::$conexion->set_charset("utf8");
        }
        return self::$conexion;
    }
}
?>