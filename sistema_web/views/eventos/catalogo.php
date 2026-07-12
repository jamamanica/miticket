<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Eventos disponibles</h1>

<form method="GET" action="<?= BASE_URL ?>/index.php" class="filtros">
    <input type="hidden" name="route" value="evento/catalogo">
    <input type="text" name="q" placeholder="Buscar evento..." value="<?= e($busqueda ?? '') ?>">
    <select name="categoria">
        <option value="">Todas las categorías</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= (int) $cat['id_categoria'] ?>" <?= ($idCategoria ?? null) == $cat['id_categoria'] ? 'selected' : '' ?>>
                <?= e($cat['nombre_categoria']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn">Filtrar</button>
</form>

<div class="grid-eventos">
    <?php if (empty($eventos)): ?>
        <p>No se encontraron eventos.</p>
    <?php endif; ?>

    <?php foreach ($eventos as $ev): ?>
        <div class="card-evento">
            <h3><?= e($ev['nombre']) ?></h3>
            <p class="categoria-tag"><?= e($ev['nombre_categoria']) ?></p>
            <p>📅 <?= e(date('d/m/Y', strtotime($ev['fecha']))) ?> — 🕒 <?= e(substr($ev['hora'], 0, 5)) ?></p>
            <p>📍 <?= e($ev['nombre_lugar']) ?>, <?= e($ev['ciudad']) ?></p>
            <a class="btn" href="<?= BASE_URL ?>/index.php?route=evento/detalle&id=<?= (int) $ev['id_evento'] ?>">Ver detalle</a>
        </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
