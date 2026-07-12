<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Organizador.php';

class AuthController
{
    private Usuario $usuarioModel;
    private Cliente $clienteModel;
    private Organizador $organizadorModel;

    public function __construct()
    {
        $this->usuarioModel     = new Usuario();
        $this->clienteModel     = new Cliente();
        $this->organizadorModel = new Organizador();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo     = trim($_POST['correo'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';

            $usuario = $this->usuarioModel->buscarPorCorreo($correo);

            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                $esOrganizador = $this->organizadorModel->existe($usuario['dni']);
                $esCliente     = $this->clienteModel->existe($usuario['dni']);

                $_SESSION['usuario_dni']    = $usuario['dni'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];

                // DETECCIÓN AUTOMÁTICA DEL ROL DESDE BASE DE DATOS
                if ($esOrganizador) {
                    $_SESSION['usuario_rol'] = 'organizador';
                } elseif ($esCliente) {
                    $_SESSION['usuario_rol'] = 'cliente';
                } else {
                    // Por seguridad, si el usuario existe pero no está en ninguna tabla hija
                    setFlash('error', 'Tu cuenta no tiene un tipo de rol asignado.');
                    redirect('auth/login');
                    return;
                }

                setFlash('success', 'Bienvenido, ' . $usuario['nombre'] . '.');
                redirect($_SESSION['usuario_rol'] === 'organizador' ? 'organizador/panel' : 'evento/catalogo');
            }

            setFlash('error', 'Correo o contraseña incorrectos.');
            redirect('auth/login');
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function registro(): void
    {
        // Instanciamos la conexión aquí arriba para usarla tanto en el POST como en el GET de abajo
        $db = Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni              = trim($_POST['dni'] ?? '');
            $nombre           = trim($_POST['nombre'] ?? '');
            $apellido         = trim($_POST['apellido'] ?? '');
            $correo           = trim($_POST['correo'] ?? '');
            $contrasena       = $_POST['contrasena'] ?? '';
            $telefono         = trim($_POST['telefono'] ?? '');
            $fechaNacimiento  = $_POST['fecha_nacimiento'] ?? '';
            $tipoCuenta       = $_POST['tipo_cuenta'] ?? 'cliente'; // cliente | organizador
            $nombreEmpresa    = trim($_POST['nombre_empresa'] ?? '');
            $ruc              = trim($_POST['ruc'] ?? '');

            // MODIFICACIÓN: Capturamos el array de categorías seleccionadas (viene de los checkboxes)
            $categoriasSeleccionadas = $_POST['id_categoria'] ?? [];

            $errores = [];
            if (!preg_match('/^\d{8}$/', $dni)) {
                $errores[] = 'El DNI debe tener 8 dígitos.';
            }
            if ($nombre === '' || $apellido === '') {
                $errores[] = 'Nombre y apellido son obligatorios.';
            }
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo no es válido.';
            }
            if (strlen($contrasena) < 6) {
                $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
            }
            if ($fechaNacimiento === '') {
                $errores[] = 'La fecha de nacimiento es obligatoria.';
            }
            if (!in_array($tipoCuenta, ['cliente', 'organizador'], true)) {
                $tipoCuenta = 'cliente';
            }
            if ($tipoCuenta === 'organizador' && $nombreEmpresa === '') {
                $errores[] = 'El nombre de la empresa es obligatorio para cuentas de organizador.';
            }
            if ($tipoCuenta === 'organizador' && $ruc !== '' && !preg_match('/^\d{11}$/', $ruc)) {
                $errores[] = 'El RUC debe tener 11 dígitos.';
            }
            if ($this->usuarioModel->buscarPorDni($dni)) {
                $errores[] = 'Ya existe un usuario registrado con ese DNI.';
            }
            if ($this->usuarioModel->buscarPorCorreo($correo)) {
                $errores[] = 'Ya existe un usuario registrado con ese correo.';
            }

            if (!empty($errores)) {
                setFlash('error', implode(' ', $errores));
                redirect('auth/registro');
            }

            $db->beginTransaction();
            try {
                $this->usuarioModel->crear([
                    'dni'              => $dni,
                    'nombre'           => $nombre,
                    'apellido'         => $apellido,
                    'correo'           => $correo,
                    'contrasena'       => $contrasena,
                    'telefono'         => $telefono,
                    'fecha_nacimiento' => $fechaNacimiento,
                ]);

                if ($tipoCuenta === 'organizador') {
                    $this->organizadorModel->crear($dni, $nombreEmpresa, $ruc ?: null);
                } else {
                    $this->clienteModel->crear($dni);

                    // MODIFICACIÓN: Guardamos todas las preferencias seleccionadas iterando el array
                    if (is_array($categoriasSeleccionadas) && !empty($categoriasSeleccionadas)) {
                        $stmtPuente = $db->prepare("INSERT INTO cliente_categoria (dni_cliente, id_categoria) VALUES (?, ?)");
                        foreach ($categoriasSeleccionadas as $idCat) {
                            if (!empty($idCat)) {
                                $stmtPuente->execute([$dni, $idCat]);
                            }
                        }
                    }
                }

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                setFlash('error', 'No se pudo completar el registro: ' . $e->getMessage());
                redirect('auth/registro');
            }

            setFlash('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
            redirect('auth/login');
        }

        // Consultamos las categorías usando 'nombre_categoria' para listarlas dinámicamente en el formulario
        $stmtCat = $db->query("SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria ASC");
        $categoriasBD = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/auth/registro.php';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();
        setFlash('success', 'Sesión cerrada correctamente.');
        redirect('auth/login');
    }
}