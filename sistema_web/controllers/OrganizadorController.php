<?php
require_once __DIR__ . '/../models/Evento.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Lugar.php';
require_once __DIR__ . '/../models/Zona.php';
require_once __DIR__ . '/../models/Asiento.php';

class OrganizadorController
{
    private Evento $eventoModel;
    private Categoria $categoriaModel;
    private Lugar $lugarModel;
    private Zona $zonaModel;
    private Asiento $asientoModel;

    public function __construct()
    {
        $this->eventoModel    = new Evento();
        $this->categoriaModel = new Categoria();
        $this->lugarModel     = new Lugar();
        $this->zonaModel      = new Zona();
        $this->asientoModel   = new Asiento();
    }

    /** Panel principal: lista de eventos del organizador logueado */
    public function panel(): void
    {
        requireOrganizador();
        $eventos = $this->eventoModel->listarPorOrganizador($_SESSION['usuario_dni']);
        require __DIR__ . '/../views/organizador/panel.php';
    }

    public function crearEvento(): void
    {
        requireOrganizador();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre       = trim($_POST['nombre'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $fecha        = $_POST['fecha'] ?? '';
            $hora         = $_POST['hora'] ?? '';
            $idCategoria  = (int) ($_POST['id_categoria'] ?? 0);
            $idLugar      = (int) ($_POST['id_lugar'] ?? 0);

            // Opción de crear un lugar nuevo desde el mismo formulario
            $nuevoLugarNombre    = trim($_POST['nuevo_lugar_nombre'] ?? '');
            $nuevoLugarDireccion = trim($_POST['nuevo_lugar_direccion'] ?? '');
            $nuevoLugarCiudad    = trim($_POST['nuevo_lugar_ciudad'] ?? '');
            $nuevoLugarCapacidad = (int) ($_POST['nuevo_lugar_capacidad'] ?? 0);

            $errores = [];
            if ($nombre === '') $errores[] = 'El nombre del evento es obligatorio.';
            if ($fecha === '' || $hora === '') $errores[] = 'La fecha y hora son obligatorias.';
            if ($idCategoria < 1) $errores[] = 'Selecciona una categoría.';

            if ($idLugar < 1 && $nuevoLugarNombre === '') {
                $errores[] = 'Selecciona un lugar existente o registra uno nuevo.';
            }
            if ($nuevoLugarNombre !== '' && $nuevoLugarCapacidad < 1) {
                $errores[] = 'La capacidad del nuevo lugar debe ser mayor a 0.';
            }

            if (!empty($errores)) {
                setFlash('error', implode(' ', $errores));
                redirect('organizador/crearEvento');
            }

            if ($nuevoLugarNombre !== '') {
                $idLugar = $this->lugarModel->crear(
                    $nuevoLugarNombre,
                    $nuevoLugarDireccion,
                    $nuevoLugarCiudad,
                    $nuevoLugarCapacidad
                );
            }

            $idEvento = $this->eventoModel->crear([
                'nombre'          => $nombre,
                'descripcion'     => $descripcion,
                'fecha'           => $fecha,
                'hora'            => $hora,
                'dni_organizador' => $_SESSION['usuario_dni'],
                'id_lugar'        => $idLugar,
                'id_categoria'    => $idCategoria,
            ]);

            setFlash('success', 'Evento creado en estado BORRADOR. Ahora agrega zonas antes de publicarlo.');
            redirect('organizador/zonas&id=' . $idEvento);
        }

        $categorias = $this->categoriaModel->listarTodas();
        $lugares    = $this->lugarModel->listarTodos();
        require __DIR__ . '/../views/organizador/crear_evento.php';
    }

    public function editarEvento(): void
    {
        requireOrganizador();
        $id = (int) ($_GET['id'] ?? 0);
        $evento = $this->eventoModel->buscarPorIdYOrganizador($id, $_SESSION['usuario_dni']);

        if (!$evento) {
            setFlash('error', 'Evento no encontrado.');
            redirect('organizador/panel');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre      = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $fecha       = $_POST['fecha'] ?? '';
            $hora        = $_POST['hora'] ?? '';
            $idCategoria = (int) ($_POST['id_categoria'] ?? 0);
            $idLugar     = (int) ($_POST['id_lugar'] ?? 0);

            if ($nombre === '' || $fecha === '' || $hora === '' || $idCategoria < 1 || $idLugar < 1) {
                setFlash('error', 'Todos los campos son obligatorios.');
                redirect('organizador/editarEvento&id=' . $id);
            }

            $this->eventoModel->actualizar($id, [
                'nombre'       => $nombre,
                'descripcion'  => $descripcion,
                'fecha'        => $fecha,
                'hora'         => $hora,
                'id_lugar'     => $idLugar,
                'id_categoria' => $idCategoria,
            ]);

            setFlash('success', 'Evento actualizado correctamente.');
            redirect('organizador/panel');
        }

        $categorias = $this->categoriaModel->listarTodas();
        $lugares    = $this->lugarModel->listarTodos();
        require __DIR__ . '/../views/organizador/editar_evento.php';
    }

    /** Gestión de zonas de un evento: listar + agregar (genera asientos automáticamente) */
    public function zonas(): void
    {
        requireOrganizador();
        $idEvento = (int) ($_GET['id'] ?? 0);
        $evento = $this->eventoModel->buscarPorIdYOrganizador($idEvento, $_SESSION['usuario_dni']);

        if (!$evento) {
            setFlash('error', 'Evento no encontrado.');
            redirect('organizador/panel');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreZona = trim($_POST['nombre_zona'] ?? '');
            $precio     = (float) ($_POST['precio'] ?? 0);
            $capacidad  = (int) ($_POST['capacidad'] ?? 0);

            if ($nombreZona === '' || $precio <= 0 || $capacidad < 1) {
                setFlash('error', 'Completa correctamente nombre, precio y capacidad de la zona.');
                redirect('organizador/zonas&id=' . $idEvento);
            }

            $db = Database::getConnection();
            $db->beginTransaction();
            try {
                $idZona = $this->zonaModel->crear($idEvento, $nombreZona, $precio, $capacidad);
                // Genera automáticamente un asiento por cada cupo de la zona
                $prefijo = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nombreZona) ?: 'Z', 0, 3));
                $this->asientoModel->generarParaZona($idZona, $capacidad, $prefijo);
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                setFlash('error', 'No se pudo crear la zona: ' . $e->getMessage());
                redirect('organizador/zonas&id=' . $idEvento);
            }

            setFlash('success', 'Zona "' . $nombreZona . '" creada con ' . $capacidad . ' asientos disponibles.');
            redirect('organizador/zonas&id=' . $idEvento);
        }

        $zonas = $this->zonaModel->listarPorEvento($idEvento);
        require __DIR__ . '/../views/organizador/zonas.php';
    }

    public function publicar(): void
    {
        requireOrganizador();
        $id = (int) ($_GET['id'] ?? 0);
        $evento = $this->eventoModel->buscarPorIdYOrganizador($id, $_SESSION['usuario_dni']);

        if (!$evento) {
            setFlash('error', 'Evento no encontrado.');
            redirect('organizador/panel');
        }

        $zonas = $this->zonaModel->listarPorEvento($id);
        if (empty($zonas)) {
            setFlash('error', 'Debes agregar al menos una zona antes de publicar el evento.');
            redirect('organizador/zonas&id=' . $id);
        }

        $this->eventoModel->cambiarEstado($id, 'PUBLICADO');
        setFlash('success', 'Evento publicado. Ya es visible en el catálogo.');
        redirect('organizador/panel');
    }

    public function cancelar(): void
    {
        requireOrganizador();
        $id = (int) ($_GET['id'] ?? 0);
        $evento = $this->eventoModel->buscarPorIdYOrganizador($id, $_SESSION['usuario_dni']);

        if (!$evento) {
            setFlash('error', 'Evento no encontrado.');
            redirect('organizador/panel');
        }

        $this->eventoModel->cambiarEstado($id, 'CANCELADO');
        setFlash('success', 'Evento cancelado.');
        redirect('organizador/panel');
    }
}
