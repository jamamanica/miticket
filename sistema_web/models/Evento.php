<?php
require_once __DIR__ . '/../config.php';

class Evento
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listarPublicados(?int $idCategoria = null, ?string $busqueda = null): array
    {
        $sql = "SELECT e.*, l.nombre_lugar, l.ciudad, c.nombre_categoria
                FROM evento e
                JOIN lugar l ON l.id_lugar = e.id_lugar
                JOIN categoria c ON c.id_categoria = e.id_categoria
                WHERE e.estado = 'PUBLICADO'";
        $params = [];

        if ($idCategoria) {
            $sql .= ' AND e.id_categoria = :id_categoria';
            $params['id_categoria'] = $idCategoria;
        }
        if ($busqueda) {
            $sql .= ' AND e.nombre LIKE :busqueda';
            $params['busqueda'] = '%' . $busqueda . '%';
        }

        $sql .= ' ORDER BY e.fecha ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT e.*, l.nombre_lugar, l.ciudad, l.direccion, l.capacidad AS capacidad_lugar,
                       c.nombre_categoria, o.nombre_empresa
                FROM evento e
                JOIN lugar l ON l.id_lugar = e.id_lugar
                JOIN categoria c ON c.id_categoria = e.id_categoria
                JOIN organizador o ON o.dni_organizador = e.dni_organizador
                WHERE e.id_evento = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function listarPorOrganizador(string $dniOrganizador): array
    {
        $sql = "SELECT e.*, l.nombre_lugar, c.nombre_categoria
                FROM evento e
                JOIN lugar l ON l.id_lugar = e.id_lugar
                JOIN categoria c ON c.id_categoria = e.id_categoria
                WHERE e.dni_organizador = :dni
                ORDER BY e.fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['dni' => $dniOrganizador]);
        return $stmt->fetchAll();
    }

    /** Igual que buscarPorId pero valida que pertenezca al organizador (para editar) */
    public function buscarPorIdYOrganizador(int $id, string $dniOrganizador): ?array
    {
        $evento = $this->buscarPorId($id);
        if ($evento && $evento['dni_organizador'] === $dniOrganizador) {
            return $evento;
        }
        return null;
    }

    public function crear(array $datos): int
    {
        $sql = 'INSERT INTO evento (nombre, descripcion, fecha, hora, estado, dni_organizador, id_lugar, id_categoria)
                VALUES (:nombre, :descripcion, :fecha, :hora, "BORRADOR", :dni_organizador, :id_lugar, :id_categoria)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre'          => $datos['nombre'],
            'descripcion'     => $datos['descripcion'],
            'fecha'           => $datos['fecha'],
            'hora'            => $datos['hora'],
            'dni_organizador' => $datos['dni_organizador'],
            'id_lugar'        => $datos['id_lugar'],
            'id_categoria'    => $datos['id_categoria'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE evento SET nombre = :nombre, descripcion = :descripcion, fecha = :fecha,
                hora = :hora, id_lugar = :id_lugar, id_categoria = :id_categoria
                WHERE id_evento = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre'       => $datos['nombre'],
            'descripcion'  => $datos['descripcion'],
            'fecha'        => $datos['fecha'],
            'hora'         => $datos['hora'],
            'id_lugar'     => $datos['id_lugar'],
            'id_categoria' => $datos['id_categoria'],
            'id'           => $id,
        ]);
    }

    public function cambiarEstado(int $id, string $estado): bool
    {
        $stmt = $this->db->prepare('UPDATE evento SET estado = :estado WHERE id_evento = :id');
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }
}
