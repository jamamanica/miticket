<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Tu carrito</h1>

<?php if (!$carrito || empty($carrito['items'])): ?>
    <p>Tu carrito está vacío. <a href="<?= BASE_URL ?>/index.php?route=evento/catalogo">Explora eventos</a>.</p>
<?php else: ?>
    <h2><?= e($evento['nombre']) ?></h2>

    <table class="tabla-carrito">
        <thead>
            <tr>
                <th>Zona</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($carrito['items'] as $item): ?>
            <tr>
                <td><?= e($item['nombre_zona']) ?></td>
                <td><?= formatoMoneda($item['precio']) ?></td>
                <td><?= (int) $item['cantidad'] ?></td>
                <td><?= formatoMoneda($item['precio'] * $item['cantidad']) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/index.php?route=compra/eliminar&id_zona=<?= (int) $item['id_zona'] ?>">Quitar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total: <?= formatoMoneda($total) ?></h3>

    <form method="POST" action="<?= BASE_URL ?>/index.php?route=compra/confirmar" class="form-card">
        <label>Método de pago</label>
        <select name="id_metodo" required>
            <option value="">Selecciona...</option>
            <?php foreach ($metodosPago as $mp): ?>
                <option value="<?= (int) $mp['id_metodo'] ?>"><?= e($mp['nombre_metodo']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Confirmar compra</button>
    </form>
<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
