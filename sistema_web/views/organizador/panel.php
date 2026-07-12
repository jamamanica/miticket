<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="panel-header">
    <h1>Mi panel de organizador</h1>
    <a class="btn" href="<?= BASE_URL ?>/index.php?route=organizador/crearEvento">+ Crear evento</a>
</div>

<?php if (empty($eventos)): ?>
    <p>Aún no has creado ningún evento.</p>
<?php else: ?>
    <div class="grid-eventos">
        <?php foreach ($eventos as $ev): ?>
            <div class="card-evento">
                <h3><?= e($ev['nombre']) ?></h3>
                <p class="categoria-tag"><?= e($ev['nombre_categoria']) ?></p>
                <p>📅 <?= e(date('d/m/Y', strtotime($ev['fecha']))) ?> — 🕒 <?= e(substr($ev['hora'], 0, 5)) ?></p>
                <p>📍 <?= e($ev['nombre_lugar']) ?></p>
                <p>Estado: <span class="estado-tag estado-<?= strtolower(e($ev['estado'])) ?>"><?= e($ev['estado']) ?></span></p>

                <div class="acciones-evento">
                    <a href="<?= BASE_URL ?>/index.php?route=organizador/zonas&id=<?= (int) $ev['id_evento'] ?>">Zonas</a>
                    <a href="<?= BASE_URL ?>/index.php?route=organizador/editarEvento&id=<?= (int) $ev['id_evento'] ?>">Editar</a>
                    <?php if ($ev['estado'] === 'BORRADOR'): ?>
                        <a href="<?= BASE_URL ?>/index.php?route=organizador/publicar&id=<?= (int) $ev['id_evento'] ?>"
                           onclick="return confirm('¿Publicar este evento? Será visible en el catálogo.');">Publicar</a>
                    <?php endif; ?>
                    <?php if ($ev['estado'] !== 'CANCELADO'): ?>
                        <a href="<?= BASE_URL ?>/index.php?route=organizador/cancelar&id=<?= (int) $ev['id_evento'] ?>"
                           onclick="return confirm('¿Cancelar este evento?');" class="link-danger">Cancelar</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
