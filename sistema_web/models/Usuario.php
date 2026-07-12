<?php
require_once __DIR__ . '/../config.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function buscarPorCorreo(string $correo): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuario WHERE correo = :correo');
        $stmt->execute(['correo' => $correo]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function buscarPorDni(string $dni): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuario WHERE dni = :dni');
        $stmt->execute(['dni' => $dni]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function crear(array $datos): bool
    {
        $sql = 'INSERT INTO usuario (dni, nombre, apellido, correo, contrasena, telefono, fecha_nacimiento)
                VALUES (:dni, :nombre, :apellido, :correo, :contrasena, :telefono, :fecha_nacimiento)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'dni'              => $datos['dni'],
            'nombre'           => $datos['nombre'],
            'apellido'         => $datos['apellido'],
            'correo'           => $datos['correo'],
            'contrasena'       => password_hash($datos['contrasena'], PASSWORD_DEFAULT),
            'telefono'         => $datos['telefono'] ?? null,
            'fecha_nacimiento' => $datos['fecha_nacimiento'],
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario (Sirve para Clientes y Organizadores).
     * Permite actualizar opcionalmente la contraseña si se proporciona una nueva.
     */
    public function actualizarPerfil(string $dni, array $datos): bool
    {
        // Si el usuario decide cambiar su contraseña
        if (!empty($datos['contrasena'])) {
            $sql = 'UPDATE usuario 
                    SET nombre = :nombre, 
                        apellido = :apellido, 
                        correo = :correo, 
                        telefono = :telefono, 
                        contrasena = :contrasena 
                    WHERE dni = :dni';
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                'nombre'     => $datos['nombre'],
                'apellido'   => $datos['apellido'],
                'correo'     => $datos['correo'],
                'telefono'   => $datos['telefono'] ?? null,
                'contrasena' => password_hash($datos['contrasena'], PASSWORD_DEFAULT),
                'dni'        => $dni
            ]);
        } 
        
        // Si el usuario deja la contraseña vacía (no la cambia)
        else {
            $sql = 'UPDATE usuario 
                    SET nombre = :nombre, 
                        apellido = :apellido, 
                        correo = :correo, 
                        telefono = :telefono 
                    WHERE dni = :dni';
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                'nombre'   => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'correo'   => $datos['correo'],
                'telefono' => $datos['telefono'] ?? null,
                'dni'      => $dni
            ]);
        }
    }
}