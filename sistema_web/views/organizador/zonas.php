<?php require __DIR__ . '/../layout/header.php'; ?>

<a href="<?= BASE_URL ?>/index.php?route=organizador/panel" style="text-decoration: none; color: #007bff;">&larr; Volver a mi panel</a>

<h1 style="margin-top: 15px;">Zonas de: <?= e($evento['nombre']) ?></h1>
<p>Estado del evento: <span class="estado-tag estado-<?= strtolower(e($evento['estado'])) ?>"><?= e($evento['estado']) ?></span></p>

<?php if (empty($zonas)): ?>
    <p>Este evento aún no tiene zonas configuradas.</p>
<?php else: ?>
    <table class="tabla-carrito" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
            <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                <th style="padding: 10px; text-align: left;">Zona</th>
                <th style="padding: 10px; text-align: left;">Precio</th>
                <th style="padding: 10px; text-align: left;">Stock disponible</th>
                <th style="padding: 10px; text-align: left;">Capacidad total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($zonas as $z): ?>
            <tr style="border-bottom: 1px solid #dee2e6;">
                <td style="padding: 10px;"><?= e($z['nombre_zona']) ?></td>
                <td style="padding: 10px;"><?= formatoMoneda((float) $z['precio']) ?></td>
                <td style="padding: 10px;"><?= (int) $z['stock'] ?></td>
                <td style="padding: 10px;"><?= (int) $z['capacidad'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="form-card" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 500px; margin-bottom: 20px;">
    <h2 style="margin-top: 0;">Agregar nueva zona</h2>
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=organizador/zonas&id=<?= (int) $evento['id_evento'] ?>">
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nombre de la zona</label>
            <input type="text" name="nombre_zona" placeholder="Ej: General, VIP, Platea..." required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Precio (S/)</label>
            <input type="number" name="precio" step="0.01" min="0.01" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <!-- Inputs de Fila y Columna -->
        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Cantidad de Filas</label>
                <input type="number" id="cant_filas" name="filas" min="1" max="26" value="10" oninput="calcularCapacidadTotal()" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                <small style="color: #666;">Filas (A - Z)</small>
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Asientos por Fila</label>
                <input type="number" id="cant_columnas" name="columnas" min="1" max="30" value="10" oninput="calcularCapacidadTotal()" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                <small style="color: #666;">Columnas físicas</small>
            </div>
        </div>

        <!-- Indicador de capacidad -->
        <div style="margin-bottom: 20px; background: #eef1f6; padding: 12px; border-radius: 4px; border-left: 4px solid #007bff;">
            <p style="margin: 0; font-size: 0.95em;">
                <strong>Capacidad Calculada:</strong> 
                <span id="capacidad_display" style="font-weight: bold; color: #007bff;">100</span> asientos.
            </p>
        </div>

        <button type="submit" class="btn" style="width: 100%; font-weight: bold; padding: 10px;">Agregar zona</button>
    </form>
    <p class="ayuda" style="margin-top: 15px; font-size: 0.85em; color: #666;">
        Al crear la zona se generará automáticamente un mapa de asientos ordenados por filas y columnas distribuidos uniformemente, todos en estado <strong>DISPONIBLE</strong>.
    </p>
</div>

<?php if ($evento['estado'] === 'BORRADOR' && !empty($zonas)): ?>
    <a class="btn" style="display: inline-block; text-decoration: none; text-align: center; margin-top: 10px;" href="<?= BASE_URL ?>/index.php?route=organizador/publicar&id=<?= (int) $evento['id_evento'] ?>"
       onclick="return confirm('¿Publicar este evento? Será visible en el catálogo.');">Publicar evento</a>
<?php endif; ?>

<script>
function calcularCapacidadTotal() {
    const filas = parseInt(document.getElementById('cant_filas').value) || 0;
    const columnas = parseInt(document.getElementById('cant_columnas').value) || 0;
    const total = filas * columnas;
    document.getElementById('capacidad_display').innerText = total;
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>