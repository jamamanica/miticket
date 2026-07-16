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
                return;
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
            return;
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
            return;
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
                return;
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
            return;
        }

        $categorias = $this->categoriaModel->listarTodas();
        $lugares    = $this->lugarModel->listarTodos();
        require __DIR__ . '/../views/organizador/editar_evento.php';
    }

    /** Gestión de zonas de un evento: listar + agregar (genera asientos físicos en filas y columnas) */
    public function zonas(): void
    {
        requireOrganizador();
        $idEvento = (int) ($_GET['id'] ?? 0);
        $evento = $this->eventoModel->buscarPorIdYOrganizador($idEvento, $_SESSION['usuario_dni']);

        if (!$evento) {
            setFlash('error', 'Evento no encontrado.');
            redirect('organizador/panel');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreZona = trim($_POST['nombre_zona'] ?? '');
            $precio     = (float) ($_POST['precio'] ?? 0);
            $filas      = (int) ($_POST['filas'] ?? 0);
            $columnas   = (int) ($_POST['columnas'] ?? 0);
            $capacidad  = $filas * $columnas;

            if ($nombreZona === '' || $precio <= 0 || $filas < 1 || $columnas < 1) {
                setFlash('error', 'Completa correctamente el nombre, precio, cantidad de filas y asientos.');
                redirect('organizador/zonas&id=' . $idEvento);
                return;
            }

            $db = Database::getConnection();
            $db->beginTransaction();
            try {
                // 1. Insertamos la zona en la base de datos
                $idZona = $this->zonaModel->crear($idEvento, $nombreZona, $precio, $capacidad);
                
                // 2. Generación programada de Asientos estructurados
                $stmtAsiento = $db->prepare("INSERT INTO asiento (numero_asiento, fila, columna, estado, id_zona) VALUES (?, ?, ?, 'DISPONIBLE', ?)");
                
                $letras = range('A', 'Z');

                for ($f = 0; $f < $filas; $f++) {
                    // Si excede la fila 26 (Z), genera AA, BB, etc.
                    $letraFila = $letras[$f] ?? 'Z' . ($f - 25);

                    for ($c = 1; $c <= $columnas; $c++) {
                        $numeroAsiento = $letraFila . "-" . $c;
                        $stmtAsiento->execute([
                            $numeroAsiento,
                            $letraFila,
                            $c,
                            $idZona
                        ]);
                    }
                }

                $db->commit();
                setFlash('success', 'Zona "' . $nombreZona . '" creada con exitosamente. Se generaron ' . $capacidad . ' asientos (' . $filas . ' filas x ' . $columnas . ' columnas).');
            } catch (Exception $e) {
                $db->rollBack();
                setFlash('error', 'No se pudo crear la zona o sus asientos: ' . $e->getMessage());
            }

            redirect('organizador/zonas&id=' . $idEvento);
            return;
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
            return;
        }

        $zonas = $this->zonaModel->listarPorEvento($id);
        if (empty($zonas)) {
            setFlash('error', 'Debes agregar al menos una zona antes de publicar el evento.');
            redirect('organizador/zonas&id=' . $id);
            return;
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
            return;
        }

        $this->eventoModel->cambiarEstado($id, 'CANCELADO');
        setFlash('success', 'Evento cancelado.');
        redirect('organizador/panel');
    }
}