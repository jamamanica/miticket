<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Mis compras</h1>

<?php if (empty($compras)): ?>
    <p>Aún no tienes compras realizadas.</p>
<?php else: ?>
    <?php foreach ($compras as $c): ?>
        <div class="card-compra">
            <h3>Compra #<?= (int) $c['id_compra'] ?> — <?= e(date('d/m/Y H:i', strtotime($c['fecha_compra']))) ?></h3>
            <p>Estado: <span class="estado-<?= strtolower(e($c['estado'])) ?>"><?= e($c['estado']) ?></span> — Total: <?= formatoMoneda((float) $c['total']) ?></p>
            <ul>
                <?php foreach ($c['detalles'] as $d): ?>
                    <li><?= e($d['nombre_evento']) ?> — <?= e($d['nombre_zona']) ?> x<?= (int) $d['cantidad'] ?> — <?= formatoMoneda((float) $d['subtotal']) ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= BASE_URL ?>/index.php?route=compra/confirmacion&id=<?= (int) $c['id_compra'] ?>">Ver entradas</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
