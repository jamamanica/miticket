<?php
require_once __DIR__ . '/../models/Compra.php';
require_once __DIR__ . '/../models/DetalleCompra.php';
require_once __DIR__ . '/../models/Usuario.php'; 

class CuentaController
{
    private Compra $compraModel;
    private DetalleCompra $detalleModel;
    private Usuario $usuarioModel; 

    public function __construct()
    {
        $this->compraModel  = new Compra();
        $this->detalleModel = new DetalleCompra();
        $this->usuarioModel = new Usuario(); 
    }

    public function historial(): void
    {
        requireCliente();

        $compras = $this->compraModel->listarPorCliente($_SESSION['usuario_dni']);
        foreach ($compras as &$compra) {
            $compra['detalles'] = $this->detalleModel->listarPorCompra($compra['id_compra']);
        }
        unset($compra);

        require __DIR__ . '/../views/cuenta/historial.php';
    }

    /**
     * Muestra el formulario de edición de perfil (Sirve para Cliente u Organizador)
     */
    public function editarPerfil(): void
    {
        // CORRECCIÓN: Si no hay sesión, se redirige correctamente usando BASE_URL y tu enrutador
        if (!isset($_SESSION['usuario_dni'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth/login');
            exit;
        }

        $usuario = $this->usuarioModel->buscarPorDni($_SESSION['usuario_dni']);

        require __DIR__ . '/../views/cuenta/editar_perfil.php';
    }

    /**
     * Procesa los datos del formulario y actualiza la base de datos
     */
    public function guardarPerfil(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = $_SESSION['usuario_dni'];
            
            // Sanitizar datos básicos eliminando espacios extras
            $nombre   = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $correo   = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $password = $_POST['password'] ?? ''; 

            // 1. Validación de campos obligatorios vacíos
            if (empty($nombre) || empty($apellido) || empty($correo)) {
                $error = "El nombre, apellido y correo son obligatorios.";
                $usuario = $this->usuarioModel->buscarPorDni($dni);
                require __DIR__ . '/../views/cuenta/editar_perfil.php';
                return;
            }

            // 2. Seguridad: Validar que nombre y apellido solo tengan letras
            if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/", $nombre) || !preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/", $apellido)) {
                $error = "El nombre y el apellido solo pueden contener letras.";
                $usuario = $this->usuarioModel->buscarPorDni($dni);
                require __DIR__ . '/../views/cuenta/editar_perfil.php';
                return;
            }

            // 3. Seguridad: Validar formato de correo electrónico real
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = "El formato del correo electrónico no es válido.";
                $usuario = $this->usuarioModel->buscarPorDni($dni);
                require __DIR__ . '/../views/cuenta/editar_perfil.php';
                return;
            }

            // 4. Seguridad: Si puso teléfono, validar que sean estrictamente números
            if (!empty($telefono) && !preg_match("/^[0-9]+$/", $telefono)) {
                $error = "El campo teléfono solo debe contener números.";
                $usuario = $this->usuarioModel->buscarPorDni($dni);
                require __DIR__ . '/../views/cuenta/editar_perfil.php';
                return;
            }

            // 5. Seguridad: Si puso contraseña, exigir un mínimo de largo
            if (!empty($password) && strlen($password) < 6) {
                $error = "La nueva contraseña debe tener al menos 6 caracteres.";
                $usuario = $this->usuarioModel->buscarPorDni($dni);
                require __DIR__ . '/../views/cuenta/editar_perfil.php';
                return;
            }

            // Si todo está perfecto, armamos el array y guardamos
            $datosActualizar = [
                'nombre'     => $nombre,
                'apellido'   => $apellido,
                'correo'     => $correo,
                'telefono'   => !empty($telefono) ? $telefono : null,
                'contrasena' => $password 
            ];

            $exito = $this->usuarioModel->actualizarPerfil($dni, $datosActualizar);

            if ($exito) {
                $_SESSION['usuario_nombre'] = $nombre; 
                header('Location: ' . BASE_URL . '/index.php?route=cuenta/editarPerfil&status=success');
                exit;
            } else {
                $error = "Hubo un error al actualizar el perfil.";
                $usuario = $this->usuarioModel->buscarPorDni($dni);
                require __DIR__ . '/../views/cuenta/editar_perfil.php';
            }
        }
    }
}