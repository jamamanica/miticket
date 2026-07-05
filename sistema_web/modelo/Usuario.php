<?php
require_once 'Conexion.php';

class Usuario {
    
    public static function iniciarSesion($correo, $contrasena) {
        $db = Conexion::conectar();
        $sql = "SELECT * FROM usuario WHERE correo = '$correo' AND contrasena = '$contrasena'";
        $resultado = $db->query($sql);
        
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return false;
    }

    public static function registrarGeneral($dni, $nombre, $apellido, $correo, $contrasena, $telefono, $fecha_nacimiento) {
        $db = Conexion::conectar();
        $sql = "INSERT INTO usuario (dni, nombre, apellido, correo, contrasena, telefono, fecha_nacimiento) 
                VALUES ('$dni', '$nombre', '$apellido', '$correo', '$contrasena', '$telefono', '$fecha_nacimiento')";
        return $db->query($sql);
    }

    public static function registrarCliente($dni, $id_categoria) {
        $db = Conexion::conectar();
        // Insertar en la tabla cliente
        $sqlCliente = "INSERT INTO cliente (dni_cliente) VALUES ('$dni')";
        $db->query($sqlCliente);
        
        // Insertar su categoría de preferencia
        $sqlPref = "INSERT INTO cliente_categoria (dni_cliente, id_categoria) VALUES ('$dni', '$id_categoria')";
        return $db->query($sqlPref);
    }

    public static function registrarOrganizador($dni, $nombre_empresa, $ruc) {
        $db = Conexion::conectar();
        $sql = "INSERT INTO organizador (dni_organizador, nombre_empresa, ruc) 
                VALUES ('$dni', '$nombre_empresa', '$ruc')";
        return $db->query($sql);
    }
}
?>