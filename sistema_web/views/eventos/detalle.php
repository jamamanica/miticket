<?php require __DIR__ . '/../layout/header.php'; ?>

<a href="<?= BASE_URL ?>/index.php?route=evento/catalogo">&larr; Volver al catálogo</a>

<h1><?= e($evento['nombre']) ?></h1>
<p class="categoria-tag"><?= e($evento['nombre_categoria']) ?></p>
<p><?= nl2br(e($evento['descripcion'] ?? '')) ?></p>

<div class="info-evento">
    <p>📅 <strong>Fecha:</strong> <?= e(date('d/m/Y', strtotime($evento['fecha']))) ?></p>
    <p>🕒 <strong>Hora:</strong> <?= e(substr($evento['hora'], 0, 5)) ?></p>
    <p>📍 <strong>Lugar:</strong> <?= e($evento['nombre_lugar']) ?> — <?= e($evento['direccion']) ?>, <?= e($evento['ciudad']) ?></p>
    <p>🏢 <strong>Organiza:</strong> <?= e($evento['nombre_empresa']) ?></p>
</div>

<h2>Zonas y precios</h2>

<?php if (empty($zonas)): ?>
    <p>Aún no hay zonas configuradas para este evento.</p>
<?php else: ?>
    <?php foreach ($zonas as $zona): ?>
        <div class="card-zona">
            <div>
                <strong><?= e($zona['nombre_zona']) ?></strong><br>
                <?= formatoMoneda((float) $zona['precio']) ?> —
                <?= $zona['stock'] > 0 ? e($zona['stock']) . ' disponibles' : '<span class="agotado">Agotado</span>' ?>
            </div>

            <?php if (isLoggedIn()): ?>
                <?php if ($zona['stock'] > 0): ?>
                    <form method="POST" action="<?= BASE_URL ?>/index.php?route=compra/agregar" class="form-inline">
                        <input type="hidden" name="id_zona" value="<?= (int) $zona['id_zona'] ?>">
                        <input type="hidden" name="id_evento" value="<?= (int) $evento['id_evento'] ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="<?= (int) $zona['stock'] ?>" required>
                        <button type="submit" class="btn">Agregar</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <a class="btn" href="<?= BASE_URL ?>/index.php?route=auth/login">Inicia sesión para comprar</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
