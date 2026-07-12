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
}
