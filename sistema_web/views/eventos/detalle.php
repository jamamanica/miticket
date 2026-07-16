<?php require __DIR__ . '/../layout/header.php'; ?>

<!-- Contenedor flotante para notificaciones dinámicas con AJAX -->
<div id="notificacion-ajax" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 99999; background-color: #28a745; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.25); font-weight: bold; font-family: sans-serif; transition: opacity 0.5s ease;">
    ¡Entradas agregadas con éxito!
</div>

<!-- Botón flotante para acceder rápidamente al carrito una vez que se han añadido elementos -->
<div id="btn-flotante-carrito" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; display: none;">
    <a href="<?= BASE_URL ?>/index.php?route=compra/carrito" style="display: flex; align-items: center; gap: 8px; background-color: #007bff; color: white; text-decoration: none; padding: 15px 25px; border-radius: 50px; font-weight: bold; box-shadow: 0 4px 15px rgba(0,0,0,0.3); font-size: 1.1em; transition: transform 0.2s ease;">
        🛒 Ver mi carrito
    </a>
</div>

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
        <div class="card-zona" style="margin-bottom: 15px; padding: 15px; border: 1px solid #eaeaea; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; background-color: #fff;">
            <div>
                <strong style="font-size: 1.15em;"><?= e($zona['nombre_zona']) ?></strong><br>
                <span style="color: #555;"><?= formatoMoneda((float) $zona['precio']) ?></span> —
                <?= $zona['stock'] > 0 ? '<span style="color: #28a745; font-weight: bold;">' . e($zona['stock']) . ' disponibles</span>' : '<span class="agotado" style="color: #dc3545; font-weight: bold;">Agotado</span>' ?>
            </div>

            <?php if (isLoggedIn()): ?>
                <?php if ($zona['stock'] > 0): ?>
                    <!-- Formulario con clase "form-agregar-carrito-ajax" para ser controlado con JavaScript -->
                    <form method="POST" action="<?= BASE_URL ?>/index.php?route=compra/agregar" class="form-inline form-agregar-carrito-ajax" style="display: flex; gap: 8px; align-items: center;">
                        <input type="hidden" name="id_zona" value="<?= (int) $zona['id_zona'] ?>">
                        <input type="hidden" name="id_evento" value="<?= (int) $evento['id_evento'] ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="<?= (int) $zona['stock'] ?>" required style="width: 70px; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                        <button type="submit" class="btn" style="background-color: #ffc107; color: #000; border: none; padding: 8px 15px; font-weight: bold; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;">
                            Agregar
                        </button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <a class="btn" href="<?= BASE_URL ?>/index.php?route=auth/login" style="background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-size: 0.9em; font-weight: bold;">Inicia sesión para comprar</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- SCRIPT DE JAVASCRIPT AJAX PARA EVITAR RECARGAR -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const formularios = document.querySelectorAll('.form-agregar-carrito-ajax');

    formularios.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevenir el envío clásico que recarga la página

            const btnSubmit = form.querySelector('button[type="submit"]');
            const btnOriginalText = btnSubmit.innerText;
            
            // Efecto visual de carga en el botón pulsado
            btnSubmit.disabled = true;
            btnSubmit.innerText = "Agregando...";

            // Capturamos los datos del formulario actual
            const formData = new FormData(form);
            formData.append('ajax', '1'); // Indicador para indicarle al backend que es AJAX

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la conexión del servidor.");
                }
                return response.json();
            })
            .then(data => {
                btnSubmit.disabled = false;
                btnSubmit.innerText = btnOriginalText;

                if (data.success) {
                    // 1. Mostrar la notificación flotante con el texto de éxito
                    const notif = document.getElementById('notificacion-ajax');
                    notif.innerText = data.message;
                    notif.style.display = 'block';
                    notif.style.opacity = '1';

                    // Desvanecer la notificación tras 3.5 segundos
                    setTimeout(() => {
                        notif.style.opacity = '0';
                        setTimeout(() => { notif.style.display = 'none'; }, 500);
                    }, 3500);

                    // 2. Activar y hacer visible de forma elegante el botón flotante "Ver mi carrito"
                    const btnCarrito = document.getElementById('btn-flotante-carrito');
                    btnCarrito.style.display = 'block';
                    btnCarrito.style.transform = 'scale(1.1)';
                    setTimeout(() => { btnCarrito.style.transform = 'scale(1)'; }, 200);

                } else {
                    // Si el servidor retornó un error de stock u otro problema, alertar al usuario
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                btnSubmit.disabled = false;
                btnSubmit.innerText = btnOriginalText;
                console.error("Error en la petición:", error);
                alert("Ocurrió un error inesperado al procesar tu solicitud.");
            });
        });
    });
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>