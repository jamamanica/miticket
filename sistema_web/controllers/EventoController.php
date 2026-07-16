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
        $categorias = $this->categoriaModel->listarTodas();

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

    /** Renders el formulario interactivo de compra de asientos */
    public function comprar(): void
    {
        // Aseguramos sesión activa de cliente
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

        // Renderizamos tu vista compra.php
        require __DIR__ . '/../views/eventos/compra.php';
    }

    /** Endpoint AJAX para obtener los asientos estructurados de una zona */
    public function obtenerAsientos(): void
    {
        header('Content-Type: application/json');
        
        $idZona = (int)($_GET['id_zona'] ?? 0);
        if ($idZona <= 0) {
            echo json_encode([]);
            exit;
        }

        $db = Database::getConnection();
        
        // Consultamos todos los asientos de la zona ordenados por fila y columna
        $stmt = $db->prepare("SELECT id_asiento, numero_asiento, fila, columna, estado FROM asiento WHERE id_zona = ? ORDER BY fila ASC, columna ASC");
        $stmt->execute([$idZona]);
        $asientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($asientos);
        exit;
    }
}