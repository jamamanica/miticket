<?php require __DIR__ . '/../layout/header.php'; ?>

<a href="<?= BASE_URL ?>/index.php?route=organizador/panel">&larr; Volver a mi panel</a>

<div class="form-card form-card-ancho">
    <h1>Crear nuevo evento</h1>
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=organizador/crearEvento" onsubmit="return validarFormulario();">
        <label>Nombre del evento</label>
        <input type="text" name="nombre" required>

        <label>Descripción</label>
        <textarea name="descripcion" rows="3"></textarea>

        <div class="fila">
            <div>
                <label>Fecha</label>
                <input type="date" name="fecha" required>
            </div>
            <div>
                <label>Hora</label>
                <input type="time" name="hora" required>
            </div>
        </div>

        <label>Categoría</label>
        <select name="id_categoria" required>
            <option value="">Selecciona...</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= (int) $cat['id_categoria'] ?>"><?= e($cat['nombre_categoria']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Lugar existente</label>
        <select name="id_lugar" id="select_lugar">
            <option value="">-- Ninguno (registraré uno nuevo abajo) --</option>
            <?php foreach ($lugares as $lug): ?>
                <option value="<?= (int) $lug['id_lugar'] ?>"><?= e($lug['nombre_lugar']) ?> — <?= e($lug['ciudad']) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Bloque contenedor con un ID asignado para poder manipularlo con JS -->
        <fieldset class="fieldset-lugar" id="contenedor_nuevo_lugar" style="transition: all 0.3s ease; margin-top: 15px;">
            <legend>O registra un lugar nuevo</legend>
            <label>Nombre del lugar</label>
            <input type="text" name="nuevo_lugar_nombre" id="nuevo_lugar_nombre">

            <label>Dirección</label>
            <input type="text" name="nuevo_lugar_direccion" id="nuevo_lugar_direccion">

            <label>Ciudad</label>
            <input type="text" name="nuevo_lugar_ciudad" id="nuevo_lugar_ciudad">

            <label>Capacidad</label>
            <input type="number" name="nuevo_lugar_capacidad" id="nuevo_lugar_capacidad" min="1">
        </fieldset>

        <button type="submit" class="btn" style="margin-top: 15px;">Crear evento (queda como BORRADOR)</button>
    </form>
    <p class="ayuda" style="margin-top: 15px;">El evento se crea en estado <strong>BORRADOR</strong>. Después de crearlo, agrega al menos una zona y luego podrás publicarlo desde tu panel.</p>
</div>

<!-- Lógica interactiva para mostrar/ocultar los campos de registro de lugar -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectLugar = document.getElementById('select_lugar');
    const contenedorNuevoLugar = document.getElementById('contenedor_nuevo_lugar');
    
    // Obtener las referencias de los campos de texto
    const camposNuevoLugar = {
        nombre: document.getElementById('nuevo_lugar_nombre'),
        direccion: document.getElementById('nuevo_lugar_direccion'),
        ciudad: document.getElementById('nuevo_lugar_ciudad'),
        capacidad: document.getElementById('nuevo_lugar_capacidad')
    };

    function alternarFormularioLugar() {
        const tieneLugarSeleccionado = selectLugar.value !== "";

        if (tieneLugarSeleccionado) {
            // Ocultar sección de nuevo lugar
            contenedorNuevoLugar.style.display = "none";
            
            // Quitar requerimientos para no bloquear el submit
            for (let key in camposNuevoLugar) {
                camposNuevoLugar[key].required = false;
                camposNuevoLugar[key].value = ""; // Limpiar el contenido anterior
            }
        } else {
            // Mostrar sección de nuevo lugar
            contenedorNuevoLugar.style.display = "block";
            
            // Hacer que los campos de registro sean obligatorios
            for (let key in camposNuevoLugar) {
                camposNuevoLugar[key].required = true;
            }
        }
    }

    // Escuchar el cambio en el selector de lugares existentes
    selectLugar.addEventListener('change', alternarFormularioLugar);
    
    // Ejecutar al iniciar la página para cargar el estado correcto por defecto
    alternarFormularioLugar();
});

function validarFormulario() {
    const selectLugar = document.getElementById('select_lugar');
    const capacidadInput = document.getElementById('nuevo_lugar_capacidad');

    // Validar si es un nuevo lugar, que la capacidad sea un número entero válido mayor a cero
    if (selectLugar.value === "") {
        const capacidad = parseInt(capacidadInput.value);
        if (isNaN(capacidad) || capacidad <= 0) {
            alert("La capacidad del nuevo lugar debe ser un número entero positivo mayor a 0.");
            return false;
        }
    }
    return true;
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>