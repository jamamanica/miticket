<?php require __DIR__ . '/../layout/header.php'; ?>

<a href="<?= BASE_URL ?>/index.php?route=organizador/panel">&larr; Volver a mi panel</a>

<div class="form-card form-card-ancho">
    <h1>Editar evento</h1>
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=organizador/editarEvento&id=<?= (int) $evento['id_evento'] ?>">
        <label>Nombre del evento</label>
        <input type="text" name="nombre" value="<?= e($evento['nombre']) ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion" rows="3"><?= e($evento['descripcion'] ?? '') ?></textarea>

        <div class="fila">
            <div>
                <label>Fecha</label>
                <input type="date" name="fecha" value="<?= e($evento['fecha']) ?>" required>
            </div>
            <div>
                <label>Hora</label>
                <input type="time" name="hora" value="<?= e(substr($evento['hora'], 0, 5)) ?>" required>
            </div>
        </div>

        <label>Categoría</label>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= (int) $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $evento['id_categoria'] ? 'selected' : '' ?>>
                    <?= e($cat['nombre_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Lugar</label>
        <select name="id_lugar" required>
            <?php foreach ($lugares as $lug): ?>
                <option value="<?= (int) $lug['id_lugar'] ?>" <?= $lug['id_lugar'] == $evento['id_lugar'] ? 'selected' : '' ?>>
                    <?= e($lug['nombre_lugar']) ?> — <?= e($lug['ciudad']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn">Guardar cambios</button>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
