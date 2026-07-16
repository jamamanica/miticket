<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="form-card" style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="text-align: center; margin-bottom: 20px;">Iniciar Sesión</h2>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=auth/login">
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Correo electrónico</label>
            <input type="email" name="correo" required autofocus style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Contraseña</label>
            <input type="password" name="contrasena" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <!-- SELECTOR DE ROL PARA CUENTAS CON DOBLE PERFIL -->
        <div style="margin-bottom: 20px;">
            <label for="rol" style="display: block; font-weight: bold; margin-bottom: 5px;">Ingresar como:</label>
            <select name="rol" id="rol" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; background-color: #fff;">
                <option value="cliente">Cliente (Comprar entradas)</option>
                <option value="organizador">Organizador (Gestionar eventos)</option>
            </select>
        </div>

        <button type="submit" class="btn" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 1em; cursor: pointer;">
            Ingresar
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 15px; font-size: 0.9em;">
        ¿No tienes cuenta? <a href="<?= BASE_URL ?>/index.php?route=auth/registro" style="color: #007bff; text-decoration: none;">Regístrate aquí</a>
    </p>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>