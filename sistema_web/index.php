<?php
session_start();
require_once 'config.php';
require_once 'modelo/Conexion.php';

if (!isset($_SESSION['usuario_dni'])) {
    header("Location: vista/usuarios/auth.php");
    exit();
} else {
    header("Location: vista/home/index.php");
    exit();
}
?>