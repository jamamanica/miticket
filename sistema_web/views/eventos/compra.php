<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="form-card" style="max-width: 800px; margin: 30px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <h2>Comprar Entradas para: <?= htmlspecialchars($evento['nombre'] ?? $evento['titulo'] ?? '') ?></h2>
    <p><strong>Fecha y Hora:</strong> <?= htmlspecialchars($evento['fecha']) ?> - <?= htmlspecialchars($evento['hora']) ?></p>

    <!-- Formulario que enviará la compra al controlador correspondiente -->
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=compra/procesar">
        <input type="hidden" name="id_evento" value="<?= (int) $evento['id_evento'] ?>">
        
        <!-- 1. Selección de Zona -->
        <label for="id_zona" style="display:block; font-weight:bold; margin-bottom: 8px;">Selecciona tu Zona:</label>
        <select name="id_zona" id="id_zona" onchange="cargarMapaAsientos()" required style="width:100%; padding:10px; border-radius:4px; border:1px solid #ccc; margin-bottom: 15px;">
            <option value="">-- Elige una zona --</option>
            <?php foreach ($zonas as $zona): ?>
                <option value="<?= $zona['id_zona'] ?>"><?= htmlspecialchars($zona['nombre_zona']) ?> (S/. <?= number_format($zona['precio'], 2) ?>)</option>
            <?php endforeach; ?>
        </select>

        <!-- 2. Contenedor del Mapa Interactivo (Se muestra dinámicamente) -->
        <div id="seccion_asientos" style="display: none; margin-top: 25px;">
            <h3 style="border-bottom: 2px solid #ddd; padding-bottom: 8px;">Selecciona tu Asiento</h3>
            
            <!-- Leyenda de colores -->
            <div style="display: flex; gap: 15px; margin: 15px 0; justify-content: center; font-size: 0.9em;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="width: 20px; height: 20px; background-color: #fff; border: 1px solid #ccc; border-radius: 4px;"></div> Disponible
                </div>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="width: 20px; height: 20px; background-color: #ffc107; border: 1px solid #ff9800; border-radius: 4px;"></div> Seleccionado
                </div>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="width: 20px; height: 20px; background-color: #dc3545; border: 1px solid #b21f2d; border-radius: 4px;"></div> Vendido/Ocupado
                </div>
            </div>

            <div style="text-align: center; font-weight: bold; margin-bottom: 15px; background: #eaeaea; padding: 5px; border-radius: 4px; letter-spacing: 2px;">ESCENARIO / PANTALLA</div>

            <!-- Aquí JS pintará dinámicamente los botones de los asientos -->
            <div style="overflow-x: auto; max-width: 100%; padding: 10px; background: #f9f9f9; border-radius: 8px; border: 1px dashed #ccc;">
                <div id="mapa_grid" style="display: grid; gap: 10px; justify-content: center; margin: 0 auto;"></div>
            </div>

            <!-- Campo oculto donde guardaremos el id del asiento seleccionado para enviarlo al servidor -->
            <input type="hidden" name="id_asiento" id="asiento_seleccionado_id" required>
            
            <div id="info_seleccion" style="margin-top: 15px; text-align: center; display: none; background: #fff3cd; padding: 10px; border-radius: 4px;">
                <p style="font-size: 1.1em; margin: 0;">Has seleccionado el asiento: <strong id="asiento_nombre" style="color: #d39e00;">Ninguno</strong></p>
            </div>
        </div>

        <button type="submit" id="btn_continuar" class="btn" style="margin-top: 20px; width: 100%; display: none; font-weight: bold; padding: 12px;">Continuar con el Pago</button>
    </form>
</div>

<!-- ESTILOS EXCLUSIVOS PARA LOS ASIENTOS INTERACTIVOS -->
<style>
.asiento-btn {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background-color: #ffffff;
    font-size: 0.8em;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.asiento-btn:hover:not(.asiento-vendido) {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}

.asiento-seleccionado {
    background-color: #ffc107 !important;
    border-color: #ff9800 !important;
    color: #000 !important;
}

.asiento-vendido {
    background-color: #dc3545 !important;
    border-color: #b21f2d !important;
    color: #ffffff !important;
    cursor: not-allowed !important;
    opacity: 0.8;
}
</style>

<!-- SCRIPT JS PARA EL MAPA DINÁMICO -->
<script>
function cargarMapaAsientos() {
    const idZona = document.getElementById('id_zona').value;
    const seccionAsientos = document.getElementById('seccion_asientos');
    const mapaGrid = document.getElementById('mapa_grid');
    const btnContinuar = document.getElementById('btn_continuar');
    const inputAsiento = document.getElementById('asiento_seleccionado_id');
    const infoSeleccion = document.getElementById('info_seleccion');

    // Limpiamos selecciones previas
    inputAsiento.value = "";
    infoSeleccion.style.display = "none";
    btnContinuar.style.display = "none";

    if (!idZona) {
        seccionAsientos.style.display = "none";
        return;
    }

    // Petición al endpoint del controlador
    fetch(`<?= BASE_URL ?>/index.php?route=evento/obtenerAsientos&id_zona=${idZona}`)
        .then(response => response.json())
        .then(asientos => {
            mapaGrid.innerHTML = "";
            if (asientos.length === 0) {
                mapaGrid.innerHTML = "<p style='padding: 15px;'>Esta zona no tiene asientos configurados físicamente.</p>";
                seccionAsientos.style.display = "block";
                return;
            }

            // Calculamos cuántas filas y columnas tiene la zona para configurar el CSS Grid automáticamente
            let maxColumna = 1;
            const filaAMapa = {};
            let indiceFila = 1;

            asientos.forEach(asiento => {
                if (!filaAMapa[asiento.fila]) {
                    filaAMapa[asiento.fila] = indiceFila++;
                }
                if (parseInt(asiento.columna) > maxColumna) {
                    maxColumna = parseInt(asiento.columna);
                }
            });
            const maxFilaNum = indiceFila - 1;

            // Definimos dinámicamente las columnas y filas de CSS Grid en el contenedor
            mapaGrid.style.gridTemplateColumns = `repeat(${maxColumna}, 40px)`;
            mapaGrid.style.gridTemplateRows = `repeat(${maxFilaNum}, 40px)`;

            asientos.forEach(asiento => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'asiento-btn';
                btn.innerText = asiento.numero_asiento;
                btn.dataset.id = asiento.id_asiento;
                btn.dataset.nombre = asiento.numero_asiento;

                // Asignamos coordenadas de grid según los datos de la Base de Datos
                btn.style.gridRow = filaAMapa[asiento.fila];
                btn.style.gridColumn = asiento.columna;

                if (asiento.estado === 'VENDIDO') {
                    btn.classList.add('asiento-vendido');
                    btn.disabled = true;
                } else {
                    btn.addEventListener('click', function() {
                        // Quitamos selección previa
                        document.querySelectorAll('.asiento-btn').forEach(b => b.classList.remove('asiento-seleccionado'));
                        
                        // Añadimos selección al actual
                        btn.classList.add('asiento-seleccionado');
                        
                        // Guardamos datos en los inputs correspondientes
                        inputAsiento.value = btn.dataset.id;
                        document.getElementById('asiento_nombre').innerText = btn.dataset.nombre;
                        
                        infoSeleccion.style.display = "block";
                        btnContinuar.style.display = "block";
                    });
                }

                mapaGrid.appendChild(btn);
            });

            seccionAsientos.style.display = "block";
        })
        .catch(error => {
            console.error("Error al cargar los asientos: ", error);
            mapaGrid.innerHTML = "<p style='color:red; padding:15px;'>Ocurrió un error al cargar el mapa de asientos.</p>";
        });
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>