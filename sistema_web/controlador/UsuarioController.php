<?php
session_start();
require_once '../modelo/Usuario.php';

$action = $_GET['action'] ?? '';

if ($action === 'register') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // 1. Registrar datos en la tabla Padre (usuario)
    $registroExitoso = Usuario::registrarGeneral($dni, $nombre, $apellido, $correo, $contrasena, $telefono, $fecha_nacimiento);

    if ($registroExitoso) {
        // 2. Registrar datos en las tablas Hijas según corresponda
        if ($tipo_usuario === 'cliente') {
            $id_categoria = $_POST['id_categoria'];
            Usuario::registrarCliente($dni, $id_categoria);
        } elseif ($tipo_usuario === 'organizador') {
            $nombre_empresa = $_POST['nombre_empresa'];
            $ruc = $_POST['ruc'];
            Usuario::registrarOrganizador($dni, $nombre_empresa, $ruc);
        }
        
        echo "Cuenta creada con éxito. Ahora puedes iniciar sesión.";
        echo "<br><a href='../vista/usuarios/auth.php'>Ir al Login</a>";
    } else {
        echo "Error al registrar el usuario primario.";
    }
}

if ($action === 'login') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $usuario = Usuario::initSesion($correo, $contrasena);

    if ($usuario) {
        $_SESSION['usuario_dni'] = $usuario['dni'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        
        // Redirigir a la vista de la cartelera principal de eventos
        header("Location: ../vista/home/index.php");
        exit();
    } else {
        echo "Credenciales incorrectas.";
        echo "<br><a href='../vista/usuarios/auth.php'>Volver a intentar</a>";
    }
}
?>