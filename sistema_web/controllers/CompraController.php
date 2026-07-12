<?php
require_once __DIR__ . '/../models/Zona.php';
require_once __DIR__ . '/../models/Asiento.php';
require_once __DIR__ . '/../models/Compra.php';
require_once __DIR__ . '/../models/DetalleCompra.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/MetodoPago.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/Evento.php';

class CompraController
{
    private Zona $zonaModel;
    private Asiento $asientoModel;
    private Compra $compraModel;
    private DetalleCompra $detalleModel;
    private Pago $pagoModel;
    private MetodoPago $metodoPagoModel;
    private Ticket $ticketModel;
    private Evento $eventoModel;

    public function __construct()
    {
        $this->zonaModel       = new Zona();
        $this->asientoModel    = new Asiento();
        $this->compraModel     = new Compra();
        $this->detalleModel    = new DetalleCompra();
        $this->pagoModel       = new Pago();
        $this->metodoPagoModel = new MetodoPago();
        $this->ticketModel     = new Ticket();
        $this->eventoModel     = new Evento();
    }

    /** Agrega una zona/cantidad al carrito en sesión */
    public function agregar(): void
    {
        requireCliente();

        $idZona   = (int) ($_POST['id_zona'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 0);
        $idEvento = (int) ($_POST['id_evento'] ?? 0);

        $zona = $this->zonaModel->buscarPorId($idZona);

        if (!$zona || $cantidad < 1) {
            setFlash('error', 'Selección inválida.');
            redirect('evento/detalle&id=' . $idEvento);
        }

        if ($cantidad > $zona['stock']) {
            setFlash('error', 'No hay suficiente stock disponible en esa zona.');
            redirect('evento/detalle&id=' . $idEvento);
        }

        if (!isset($_SESSION['carrito']) || ($_SESSION['carrito']['id_evento'] ?? null) !== $idEvento) {
            // Si cambia de evento, se reinicia el carrito
            $_SESSION['carrito'] = ['id_evento' => $idEvento, 'items' => []];
        }

        $_SESSION['carrito']['items'][$idZona] = [
            'id_zona'    => $idZona,
            'nombre_zona'=> $zona['nombre_zona'],
            'precio'     => (float) $zona['precio'],
            'cantidad'   => $cantidad,
        ];

        setFlash('success', 'Entradas agregadas al carrito.');
        redirect('compra/carrito');
    }

    public function eliminar(): void
    {
        requireCliente();
        $idZona = (int) ($_GET['id_zona'] ?? 0);
        if (isset($_SESSION['carrito']['items'][$idZona])) {
            unset($_SESSION['carrito']['items'][$idZona]);
        }
        redirect('compra/carrito');
    }

    public function carrito(): void
    {
        requireCliente();

        $carrito = $_SESSION['carrito'] ?? null;
        $evento  = null;
        $total   = 0;

        if ($carrito && !empty($carrito['items'])) {
            $evento = $this->eventoModel->buscarPorId($carrito['id_evento']);
            foreach ($carrito['items'] as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }
        }

        $metodosPago = $this->metodoPagoModel->listarTodos();

        require __DIR__ . '/../views/compra/carrito.php';
    }

    public function confirmar(): void
    {
        requireCliente();

        $carrito = $_SESSION['carrito'] ?? null;
        if (!$carrito || empty($carrito['items'])) {
            setFlash('error', 'Tu carrito está vacío.');
            redirect('evento/catalogo');
        }

        $idMetodo = (int) ($_POST['id_metodo'] ?? 0);
        if ($idMetodo < 1) {
            setFlash('error', 'Selecciona un método de pago.');
            redirect('compra/carrito');
        }

        $dniCliente = $_SESSION['usuario_dni'];
        $db = Database::getConnection();

        $db->beginTransaction();
        try {
            $total = 0;
            foreach ($carrito['items'] as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }

            $idCompra = $this->compraModel->crear($dniCliente, $total);

            foreach ($carrito['items'] as $item) {
                // Bloquear stock de forma segura
                $ok = $this->zonaModel->descontarStock($item['id_zona'], $item['cantidad']);
                if (!$ok) {
                    throw new Exception('No hay stock suficiente para la zona "' . $item['nombre_zona'] . '".');
                }

                $this->detalleModel->crear($idCompra, $item['id_zona'], $item['cantidad'], $item['precio']);

                $asientos = $this->asientoModel->obtenerDisponibles($item['id_zona'], $item['cantidad']);
                if (count($asientos) < $item['cantidad']) {
                    throw new Exception('No hay asientos disponibles suficientes en la zona "' . $item['nombre_zona'] . '".');
                }

                foreach ($asientos as $idAsiento) {
                    $this->asientoModel->marcarVendido($idAsiento);
                    $this->ticketModel->crear($idCompra, $item['id_zona'], $idAsiento);
                }
            }

            $this->pagoModel->crear($idCompra, $idMetodo, $total, 'APROBADO');
            $this->compraModel->actualizarEstado($idCompra, 'PAGADA');

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            setFlash('error', 'Ocurrió un problema al procesar la compra: ' . $e->getMessage());
            redirect('compra/carrito');
        }

        unset($_SESSION['carrito']);
        setFlash('success', '¡Compra realizada con éxito!');
        redirect('compra/confirmacion&id=' . $idCompra);
    }

    public function confirmacion(): void
    {
        requireCliente();
        $idCompra = (int) ($_GET['id'] ?? 0);

        $compra = $this->compraModel->buscarPorId($idCompra);
        if (!$compra || $compra['dni_cliente'] !== $_SESSION['usuario_dni']) {
            setFlash('error', 'Compra no encontrada.');
            redirect('evento/catalogo');
        }

        $tickets = $this->ticketModel->listarPorCompra($idCompra);

        require __DIR__ . '/../views/compra/confirmacion.php';
    }
}
