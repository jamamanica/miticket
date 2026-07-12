<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>¡Compra confirmada! 🎉</h1>
<p>Compra #<?= (int) $compra['id_compra'] ?> — Total: <?= formatoMoneda((float) $compra['total']) ?> — Estado: <?= e($compra['estado']) ?></p>

<h2>Tus entradas</h2>
<div class="grid-tickets">
    <?php foreach ($tickets as $t): ?>
        <div class="ticket">
            <h3><?= e($t['nombre_evento']) ?></h3>
            <p>📅 <?= e(date('d/m/Y', strtotime($t['fecha']))) ?> — 🕒 <?= e(substr($t['hora'], 0, 5)) ?></p>
            <p>Zona: <strong><?= e($t['nombre_zona']) ?></strong></p>
            <p>Asiento: <strong><?= e($t['numero_asiento']) ?></strong></p>
            <p>Ticket #<?= (int) $t['id_ticket'] ?> — <?= e($t['estado']) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<a class="btn" href="<?= BASE_URL ?>/index.php?route=evento/catalogo">Seguir explorando eventos</a>

<?php require __DIR__ . '/../layout/footer.php'; ?>
