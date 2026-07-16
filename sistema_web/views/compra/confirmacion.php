<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="max-width: 800px; margin: 30px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <h1 style="color: #28a745; margin-top: 0;">¡Compra confirmada! 🎉</h1>
    <p style="font-size: 1.1em; margin-bottom: 25px;">
        Compra <strong>#<?= (int) $compra['id_compra'] ?></strong> — Total: <strong><?= formatoMoneda((float) $compra['total']) ?></strong> — Estado: <span style="background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-weight: bold;"><?= e($compra['estado']) ?></span>
    </p>

    <h2>Tus entradas</h2>
    <div class="grid-tickets" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <?php foreach ($tickets as $t): ?>
            <div class="ticket" style="border: 2px dashed #bbb; border-radius: 8px; padding: 15px; background: #fafafa; position: relative;">
                <h3 style="margin-top: 0; margin-bottom: 10px; color: #333;"><?= e($t['nombre_evento']) ?></h3>
                <p style="margin: 5px 0; font-size: 0.9em; color: #666;">📅 <?= e(date('d/m/Y', strtotime($t['fecha']))) ?> — 🕒 <?= e(substr($t['hora'], 0, 5)) ?></p>
                <p style="margin: 5px 0;">Zona: <strong><?= e($t['nombre_zona']) ?></strong></p>
                <p style="margin: 5px 0;">Asiento: <strong style="color: #007bff; font-size: 1.1em;"><?= e($t['numero_asiento']) ?></strong></p>
                
                <hr style="border: none; border-top: 1px dashed #ccc; margin: 10px 0;">
                
                <p style="margin: 0; font-size: 0.85em; color: #888; text-align: right;">Ticket #<?= (int) $t['id_ticket'] ?> — <strong><?= e($t['estado']) ?></strong></p>
            </div>
        <?php endforeach; ?>
    </div>

    <a class="btn" href="<?= BASE_URL ?>/index.php?route=evento/catalogo" style="display: inline-block; text-decoration: none; padding: 10px 20px; font-weight: bold;">Seguir explorando eventos</a>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>