<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="form-card" style="margin: 30px auto;">
    <h2>Editar Mi Perfil</h2>

    <form action="<?= BASE_URL ?>/index.php?route=cuenta/guardarPerfil" method="POST">
        
        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= e($usuario['nombre']) ?>" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="El nombre solo debe contener letras" required>
        </div>

        <div>
            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= e($usuario['apellido']) ?>" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="El apellido solo debe contener letras" required>
        </div>

        <div>
            <label>Correo Electrónico:</label>
            <input type="email" name="correo" value="<?= e($usuario['correo']) ?>" required>
        </div>

        <div>
            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= e($usuario['telefono'] ?? '') ?>" pattern="[0-9]+" maxlength="9" title="El teléfono solo debe contener números (máximo 9 dígitos)">
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin-top: 20px;">
        <p class="ayuda">Si no deseas cambiar tu contraseña, deja el siguiente campo en blanco.</p>

        <div>
            <label>Nueva Contraseña:</label>
            <input type="password" name="password" minlength="6" placeholder="Mínimo 6 caracteres">
        </div>

        <button type="submit" class="btn">Guardar Cambios</button>
        
        <!-- MODIFICACIÓN: Redirección corregida hacia el catálogo de eventos -->
        <a href="<?= BASE_URL ?>/index.php?route=evento/catalogo" style="display: inline-block; margin-left: 15px; color: #666; text-decoration: none; font-size: 0.9em; font-weight: bold;">Cancelar</a>
    </form>
</div>