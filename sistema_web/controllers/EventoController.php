<?php
require_once __DIR__ . '/../models/Evento.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Zona.php';

class EventoController
{
    private Evento $eventoModel;
    private Categoria $categoriaModel;
    private Zona $zonaModel;

    public function __construct()
    {
        $this->eventoModel    = new Evento();
        $this->categoriaModel = new Categoria();
        $this->zonaModel      = new Zona();
    }

    public function catalogo(): void
    {
        $idCategoria = isset($_GET['categoria']) && $_GET['categoria'] !== '' ? (int) $_GET['categoria'] : null;
        $busqueda    = isset($_GET['q']) ? trim($_GET['q']) : null;

        $eventos    = $this->eventoModel->listarPublicados($idCategoria, $busqueda ?: null);
        $categories = $this->categoriaModel->listarTodas();

        require __DIR__ . '/../views/eventos/catalogo.php';
    }

    public function detalle(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $evento = $this->eventoModel->buscarPorId($id);

        if (!$evento) {
            setFlash('error', 'El evento solicitado no existe.');
            redirect('evento/catalogo');
        }

        $zonas = $this->zonaModel->listarPorEvento($id);

        require __DIR__ . '/../views/eventos/detalle.php';
    }

    /** Procesa la creación física del evento y/o nuevo lugar */
    public function crearEvento(): void
    {
        // Se asume la existencia de un helper para verificar rol de Organizador
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('organizador/panel');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);
        $idLugar = (int)($_POST['id_lugar'] ?? 0);

        if (empty($nombre) || empty($fecha) || empty($hora) || $idCategoria <= 0) {
            setFlash('error', 'Por favor, completa todos los campos requeridos del evento.');
            redirect('organizador/crearEvento');
        }

        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            // 1. Si no seleccionó lugar existente, registramos el nuevo lugar
            if ($idLugar <= 0) {
                $nuevoNombre = trim($_POST['nuevo_lugar_nombre'] ?? '');
                $nuevaDireccion = trim($_POST['nuevo_lugar_direccion'] ?? '');
                $nuevaCiudad = trim($_POST['nuevo_lugar_ciudad'] ?? '');
                $nuevaCapacidad = (int)($_POST['nuevo_lugar_capacidad'] ?? 0);

                if (empty($nuevoNombre) || empty($nuevaDireccion) || empty($nuevaCiudad) || $nuevaCapacidad <= 0) {
                    throw new Exception("Todos los campos del nuevo lugar (incluyendo capacidad) son requeridos.");
                }

                $stmtLugar = $db->prepare("INSERT INTO lugar (nombre_lugar, direccion, ciudad, capacidad) VALUES (?, ?, ?, ?)");
                $stmtLugar->execute([$nuevoNombre, $nuevaDireccion, $nuevaCiudad, $nuevaCapacidad]);
                $idLugar = (int) $db->lastInsertId();
            }

            // 2. Insertar el evento asignándole el id_lugar correspondiente en estado BORRADOR
            $stmtEvento = $db->prepare("INSERT INTO evento (nombre, descripcion, fecha, hora, id_categoria, id_lugar, estado) VALUES (?, ?, ?, ?, ?, ?, 'BORRADOR')");
            $stmtEvento->execute([$nombre, $descripcion, $fecha, $hora, $idCategoria, $idLugar]);
            
            $db->commit();
            setFlash('success', '¡El evento ha sido registrado como BORRADOR con éxito!');
            redirect('organizador/panel');

        } catch (Exception $e) {
            $db->rollBack();
            setFlash('error', 'Error al registrar el evento: ' . $e->getMessage());
            redirect('organizador/panel'); // O retornar al formulario
        }
    }

    /** 
     * Método de validación de asientos de ejemplo 
     * Valida que la suma de asientos agregados a las zonas no supere la capacidad del lugar del evento
     * y garantiza que los asientos solo tengan estados ('DISPONIBLE', 'VENDIDO')
     */
    public function registrarAsientoParaZona(int $idEvento, int $idZona, array $asientosAAgregar): bool
    {
        $db = Database::getConnection();
        
        // 1. Obtener la capacidad máxima permitida por el lugar del evento
        $stmtLugar = $db->prepare("
            SELECT l.capacidad, l.id_lugar 
            FROM evento e 
            JOIN lugar l ON e.id_lugar = l.id_lugar 
            WHERE e.id_evento = ?
        ");
        $stmtLugar->execute([$idEvento]);
        $lugarInfo = $stmtLugar->fetch(PDO::FETCH_ASSOC);
        
        if (!$lugarInfo) {
            return false; 
        }
        
        $capacidadMaxima = (int) $lugarInfo['capacidad'];

        // 2. Contar cuántos asientos ya existen registrados para todas las zonas de este evento
        $stmtAsientosExistentes = $db->prepare("
            SELECT COUNT(a.id_asiento) as total 
            FROM asiento a 
            JOIN zona z ON a.id_zona = z.id_zona 
            WHERE z.id_evento = ?
        ");
        $stmtAsientosExistentes->execute([$idEvento]);
        $totalExistentes = (int) ($stmtAsientosExistentes->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

        // 3. Validar si los nuevos asientos a ingresar rompen el límite de capacidad del recinto
        $nuevosAsientosCount = count($asientosAAgregar);
        if (($totalExistentes + $nuevosAsientosCount) > $capacidadMaxima) {
            setFlash('error', "La capacidad de este lugar es de {$capacidadMaxima} personas. Ya tienes registrados {$totalExistentes} asientos y estás intentando registrar {$nuevosAsientosCount} más.");
            return false;
        }

        // 4. Inserción de los asientos garantizando únicamente estados válidos (DISPONIBLE o VENDIDO)
        $db->beginTransaction();
        try {
            $stmtInsert = $db->prepare("INSERT INTO asiento (id_zona, numero_asiento, fila, columna, estado) VALUES (?, ?, ?, ?, ?)");
            foreach ($asientosAAgregar as $asiento) {
                // Forzamos a que si tiene un estado extraño o nulo, sea 'DISPONIBLE' de manera predeterminada. Solo se admite 'DISPONIBLE' y 'VENDIDO'
                $estadoFinal = ($asiento['estado'] === 'VENDIDO') ? 'VENDIDO' : 'DISPONIBLE';
                
                $stmtInsert->execute([
                    $idZona, 
                    $asiento['numero_asiento'], 
                    $asiento['fila'], 
                    $asiento['columna'], 
                    $estadoFinal
                ]);
            }
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    /** Renderiza el formulario de compra de asientos */
    public function comprar(): void
    {
        if (!isset($_SESSION['usuario_dni'])) {
            setFlash('error', 'Debes iniciar sesión para comprar tus entradas.');
            redirect('auth/login');
            return;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $evento = $this->eventoModel->buscarPorId($id);

        if (!$evento || $evento['estado'] !== 'PUBLICADO') {
            setFlash('error', 'El evento no está disponible para la venta.');
            redirect('evento/catalogo');
            return;
        }

        $zonas = $this->zonaModel->listarPorEvento($id);

        require __DIR__ . '/../views/eventos/compra.php';
    }

    /** Endpoint AJAX para obtener los asientos de una zona */
    public function obtenerAsientos(): void
    {
        header('Content-Type: application/json');
        
        $idZona = (int)($_GET['id_zona'] ?? 0);
        if ($idZona <= 0) {
            echo json_encode([]);
            exit;
        }

        $db = Database::getConnection();
        
        // Consultamos asientos garantizando mapear solo 'DISPONIBLE' o 'VENDIDO' en las consultas
        $stmt = $db->prepare("SELECT id_asiento, numero_asiento, fila, columna, estado FROM asiento WHERE id_zona = ? ORDER BY fila ASC, columna ASC");
        $stmt->execute([$idZona]);
        $asientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($asientos);
        exit;
    }
}