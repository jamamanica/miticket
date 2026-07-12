<?php require __DIR__ . '/../layout/header.php'; ?>

<a href="<?= BASE_URL ?>/index.php?route=organizador/panel">&larr; Volver a mi panel</a>

<div class="form-card form-card-ancho">
    <h1>Crear nuevo evento</h1>
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=organizador/crearEvento">
        <label>Nombre del evento</label>
        <input type="text" name="nombre" required>

        <label>Descripción</label>
        <textarea name="descripcion" rows="3"></textarea>

        <div class="fila">
            <div>
                <label>Fecha</label>
                <input type="date" name="fecha" required>
            </div>
            <div>
                <label>Hora</label>
                <input type="time" name="hora" required>
            </div>
        </div>

        <label>Categoría</label>
        <select name="id_categoria" required>
            <option value="">Selecciona...</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= (int) $cat['id_categoria'] ?>"><?= e($cat['nombre_categoria']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Lugar existente</label>
        <select name="id_lugar" id="select_lugar">
            <option value="">-- Ninguno (registraré uno nuevo abajo) --</option>
            <?php foreach ($lugares as $lug): ?>
                <option value="<?= (int) $lug['id_lugar'] ?>"><?= e($lug['nombre_lugar']) ?> — <?= e($lug['ciudad']) ?></option>
            <?php endforeach; ?>
        </select>

        <fieldset class="fieldset-lugar">
            <legend>O registra un lugar nuevo</legend>
            <label>Nombre del lugar</label>
            <input type="text" name="nuevo_lugar_nombre">

            <label>Dirección</label>
            <input type="text" name="nuevo_lugar_direccion">

            <label>Ciudad</label>
            <input type="text" name="nuevo_lugar_ciudad">

            <label>Capacidad</label>
            <input type="number" name="nuevo_lugar_capacidad" min="1">
        </fieldset>

        <button type="submit" class="btn">Crear evento (queda como BORRADOR)</button>
    </form>
    <p class="ayuda">El evento se crea en estado <strong>BORRADOR</strong>. Después de crearlo, agrega al menos una zona
        y luego podrás publicarlo desde tu panel.</p>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
