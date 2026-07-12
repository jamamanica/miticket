<?php
define('BASE_URL', 'http://localhost/miticket/sistema_web/');
function redirect(string $route): void {
    header('Location: ' . BASE_URL . '/index.php?route=' . $route);
    exit;
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(): bool {
    return isset($_SESSION['usuario_dni']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        $_SESSION['flash_error'] = 'Debes iniciar sesión para continuar.';
        redirect('auth/login');
    }
}

function isOrganizador(): bool {
    return isLoggedIn() && ($_SESSION['usuario_rol'] ?? null) === 'organizador';
}

function isCliente(): bool {
    return isLoggedIn() && ($_SESSION['usuario_rol'] ?? null) === 'cliente';
}

function requireOrganizador(): void {
    requireLogin();
    if (!isOrganizador()) {
        setFlash('error', 'Esta sección es solo para organizadores.');
        redirect('evento/catalogo');
    }
}

function requireCliente(): void {
    requireLogin();
    if (!isCliente()) {
        setFlash('error', 'Esta sección es solo para clientes.');
        redirect('evento/catalogo');
    }
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash_' . $type] = $message;
}

function getFlash(string $type): ?string {
    if (!empty($_SESSION['flash_' . $type])) {
        $msg = $_SESSION['flash_' . $type];
        unset($_SESSION['flash_' . $type]);
        return $msg;
    }
    return null;
}

function formatoMoneda(float $valor): string {
    return 'S/ ' . number_format($valor, 2);
}