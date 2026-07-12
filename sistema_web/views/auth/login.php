<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="form-card">
    <h2>Iniciar sesión</h2>
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=auth/login">
        <label>Correo electrónico</label>
        <input type="email" name="correo" required autofocus>

        <label>Contraseña</label>
        <input type="password" name="contrasena" required>

        <button type="submit" class="btn" style="margin-top: 15px;">Ingresar</button>
    </form>
    <p>¿No tienes cuenta? <a href="<?= BASE_URL ?>/index.php?route=auth/registro">Regístrate aquí</a></p>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>