<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="form-card">
    <h2>Crear cuenta</h2>
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=auth/registro">

        <label>Tipo de cuenta</label>
        <select name="tipo_cuenta" id="tipo_cuenta" onchange="toggleOrganizador()">
            <option value="cliente">Cliente (comprar entradas)</option>
            <option value="organizador">Organizador (crear eventos)</option>
        </select>

        <label>DNI (8 dígitos)</label>
        <input type="text" name="dni" maxlength="8" pattern="\d{8}" required>

        <label>Nombre</label>
        <input type="text" name="nombre" required>

        <label>Apellido</label>
        <input type="text" name="apellido" required>

        <label>Correo electrónico</label>
        <input type="email" name="correo" required>

        <label>Contraseña</label>
        <input type="password" name="contrasena" minlength="6" required>

        <label>Teléfono</label>
        <input type="text" name="telefono">

        <label>Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" required>

        <!-- MODIFICACIÓN REPARADA: Preferencias múltiples alineadas correctamente -->
        <fieldset id="campos_cliente" class="fieldset-lugar" style="padding: 15px; margin-bottom: 15px;">
            <legend>Tus Preferencias</legend>
            <label style="display: block; margin-bottom: 15px; font-weight: bold; width: 100%;">¿Qué categorías de eventos te interesan? (Selecciona varias si deseas)</label>
            
            <?php if (!empty($categoriasBD)): ?>
                <!-- Contenedor en cuadrícula (grid) para ordenar las opciones -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; width: 100%;">
                    <?php foreach ($categoriasBD as $cat): ?>
                        <!-- Forzamos flexbox para que el checkbox y el texto queden pegados horizontalmente -->
                        <label style="display: flex !important; align-items: center !important; gap: 8px !important; font-weight: normal !important; width: auto !important; margin: 0 !important; cursor: pointer; white-space: nowrap;">
                            <input type="checkbox" name="id_categoria[]" value="<?= htmlspecialchars($cat['id_categoria']) ?>" style="width: auto !important; margin: 0 !important; height: auto !important; cursor: pointer;">
                            <span style="font-size: 0.95em; line-height: 1; display: inline-block; margin: 0; padding: 0; float: none; clear: none; width: auto;"><?= htmlspecialchars($cat['nombre_categoria']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="font-size: 0.9em; color: #666; margin: 0;">No hay categorías disponibles en este momento.</p>
            <?php endif; ?>
        </fieldset>

        <fieldset id="campos_organizador" class="fieldset-lugar" style="display:none;">
            <legend>Datos de la empresa organizadora</legend>
            <label>Nombre de la empresa</label>
            <input type="text" name="nombre_empresa">

            <label>RUC (11 dígitos, opcional)</label>
            <input type="text" name="ruc" maxlength="11" pattern="\d{11}">
        </fieldset>

        <button type="submit" class="btn">Registrarme</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/index.php?route=auth/login">Inicia sesión</a></p>
</div>

<script>
function toggleOrganizador() {
    var tipo = document.getElementById('tipo_cuenta').value;
    
    if (tipo === 'organizador') {
        document.getElementById('campos_organizador').style.display = 'block';
        document.getElementById('campos_cliente').style.display = 'none';
    } else {
        document.getElementById('campos_organizador').style.display = 'none';
        document.getElementById('campos_cliente').style.display = 'block';
    }
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>