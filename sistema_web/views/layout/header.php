<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiTicket</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="navbar">
    <a class="logo" href="<?= BASE_URL ?>/index.php?route=evento/catalogo">🎫 MiTicket</a>
    <nav>
        <a href="<?= BASE_URL ?>/index.php?route=evento/catalogo">Eventos</a>
        
        <?php if (isOrganizador()): ?>
            <a href="<?= BASE_URL ?>/index.php?route=organizador/panel">Mi panel</a>
            <a href="<?= BASE_URL ?>/index.php?route=cuenta/editarPerfil" class="btn-perfil">Editar Perfil</a>
            <span class="usuario">Hola, <?= e($_SESSION['usuario_nombre']) ?></span>
            <a href="<?= BASE_URL ?>/index.php?route=auth/logout">Cerrar sesión</a>
            
        <?php elseif (isLoggedIn()): ?>
            <a href="<?= BASE_URL ?>/index.php?route=compra/carrito">Carrito</a>
            <a href="<?= BASE_URL ?>/index.php?route=cuenta/historial">Mis compras</a>
            <a href="<?= BASE_URL ?>/index.php?route=cuenta/editarPerfil" class="btn-perfil">Editar Perfil</a>
            <span class="usuario">Hola, <?= e($_SESSION['usuario_nombre']) ?></span>
            <a href="<?= BASE_URL ?>/index.php?route=auth/logout">Cerrar sesión</a>
            
        <?php else: ?>
            <a href="<?= BASE_URL ?>/index.php?route=auth/login">Iniciar sesión</a>
            <a href="<?= BASE_URL ?>/index.php?route=auth/registro">Registrarse</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
    <?php if ($msg = getFlash('success')): ?>
        <div class="alert alert-success"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = getFlash('error')): ?>
        <div class="alert alert-error"><?= e($msg) ?></div>
    <?php endif; ?>