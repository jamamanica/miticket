<?php require __DIR__ . '/../layout/header.php'; ?>

<a href="<?= BASE_URL ?>/index.php?route=organizador/panel">&larr; Volver a mi panel</a>

<h1>Zonas de: <?= e($evento['nombre']) ?></h1>
<p>Estado del evento: <span class="estado-tag estado-<?= strtolower(e($evento['estado'])) ?>"><?= e($evento['estado']) ?></span></p>

<?php if (empty($zonas)): ?>
    <p>Este evento aún no tiene zonas configuradas.</p>
<?php else: ?>
    <table class="tabla-carrito">
        <thead>
            <tr>
                <th>Zona</th>
                <th>Precio</th>
                <th>Stock disponible</th>
                <th>Capacidad total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($zonas as $z): ?>
            <tr>
                <td><?= e($z['nombre_zona']) ?></td>
                <td><?= formatoMoneda((float) $z['precio']) ?></td>
                <td><?= (int) $z['stock'] ?></td>
                <td><?= (int) $z['capacidad'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="form-card">
    <h2>Agregar nueva zona</h2>
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=organizador/zonas&id=<?= (int) $evento['id_evento'] ?>">
        <label>Nombre de la zona</label>
        <input type="text" name="nombre_zona" placeholder="Ej: General, VIP, Platea..." required>

        <label>Precio (S/)</label>
        <input type="number" name="precio" step="0.01" min="0.01" required>

        <label>Capacidad (cantidad de entradas/asientos)</label>
        <input type="number" name="capacidad" min="1" required>

        <button type="submit" class="btn">Agregar zona</button>
    </form>
    <p class="ayuda">Al crear la zona se generan automáticamente los asientos numerados
        (uno por cada cupo de capacidad), todos en estado <strong>DISPONIBLE</strong>.</p>
</div>

<?php if ($evento['estado'] === 'BORRADOR' && !empty($zonas)): ?>
    <a class="btn" href="<?= BASE_URL ?>/index.php?route=organizador/publicar&id=<?= (int) $evento['id_evento'] ?>"
       onclick="return confirm('¿Publicar este evento? Será visible en el catálogo.');">Publicar evento</a>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
