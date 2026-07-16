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
                <th style="text-align: center;">Asientos</th>
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
                <td style="text-align: center; min-width: 180px;">
                    <!-- Botón para abrir el selector de asientos -->
                    <button type="button" class="btn" 
                            onclick="abrirModalAsientos(<?= (int)$item['id_zona'] ?>, <?= (int)$item['cantidad'] ?>, '<?= e($item['nombre_zona']) ?>')" 
                            style="background-color: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 0.9em; cursor: pointer; font-weight: bold;">
                        Elegir <?= (int)$item['cantidad'] ?> asiento(s)
                    </button>
                    <!-- Indicador visual de los asientos seleccionados -->
                    <div id="asientos_elegidos_visual_<?= (int)$item['id_zona'] ?>" style="margin-top: 5px; font-size: 0.85em; color: #dc3545; font-weight: bold;">
                        [Asientos no seleccionados]
                    </div>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/index.php?route=compra/eliminar&id_zona=<?= (int) $item['id_zona'] ?>">Quitar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total: <?= formatoMoneda($total) ?></h3>

    <form method="POST" action="<?= BASE_URL ?>/index.php?route=compra/confirmar" class="form-card" onsubmit="return validarSeleccionAsientos();">
        
        <!-- Inputs ocultos para enviar los IDs de los asientos al backend agrupados por zona -->
        <?php foreach ($carrito['items'] as $item): ?>
            <input type="hidden" name="asientos_seleccionados[<?= (int)$item['id_zona'] ?>]" id="input_asientos_<?= (int)$item['id_zona'] ?>" required>
        <?php endforeach; ?>

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

<!-- ================= MODAL INTERACTIVO DE ASIENTOS ================= -->
<div id="modal_asientos" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); align-items: center; justify-content: center;">
    <div style="background: white; width: 90%; max-width: 650px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); max-height: 85vh; overflow-y: auto; text-align: left;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eaeaea; padding-bottom: 10px;">
            <h3 id="modal_titulo" style="margin: 0; color: #333;">Selecciona tus asientos</h3>
            <button type="button" onclick="cerrarModal()" style="background: none; border: none; font-size: 1.5em; cursor: pointer; font-weight: bold; color: #999;">&times;</button>
        </div>

        <p style="margin-top: 10px; color: #555; font-size: 0.95em;">
            Debes seleccionar exactamente <strong id="modal_limite" style="color: #007bff;">0</strong> asiento(s) para la zona <strong id="modal_nombre_zona"></strong>.
        </p>

        <!-- Leyenda de colores -->
        <div style="display: flex; gap: 15px; margin: 15px 0; justify-content: center; font-size: 0.85em;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <div style="width: 18px; height: 18px; background-color: #fff; border: 1px solid #ccc; border-radius: 3px;"></div> Disponible
            </div>
            <div style="display: flex; align-items: center; gap: 5px;">
                <div style="width: 18px; height: 18px; background-color: #ffc107; border: 1px solid #ff9800; border-radius: 3px;"></div> Seleccionado
            </div>
            <div style="display: flex; align-items: center; gap: 5px;">
                <div style="width: 18px; height: 18px; background-color: #dc3545; border: 1px solid #b21f2d; border-radius: 3px;"></div> Ocupado
            </div>
        </div>

        <div style="text-align: center; font-weight: bold; margin-bottom: 15px; background: #eaeaea; color: #333; padding: 5px; border-radius: 4px; letter-spacing: 2px; font-size: 0.8em;">ESCENARIO / PANTALLA</div>

        <!-- Contenedor del Grid -->
        <div style="overflow-x: auto; padding: 15px; background: #fafafa; border: 1px dashed #ccc; border-radius: 6px; margin-bottom: 15px;">
            <div id="modal_grid" style="display: grid; gap: 8px; justify-content: center; margin: 0 auto;"></div>
        </div>

        <div style="text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
            <button type="button" onclick="cerrarModal()" style="background: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; font-weight: bold;">Cancelar</button>
            <button type="button" onclick="guardarAsientosSeleccionados()" style="background: #28a745; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Confirmar Asientos</button>
        </div>
    </div>
</div>

<!-- ESTILOS EXCLUSIVOS PARA LOS ASIENTOS INTERACTIVOS -->
<style>
.asiento-btn-modal {
    width: 36px;
    height: 36px;
    border-radius: 4px;
    border: 1px solid #ccc;
    background-color: #ffffff;
    font-size: 0.75em;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}
.asiento-btn-modal:hover:not(.asiento-vendido) {
    border-color: #ffc107;
    background-color: #fff9db;
    transform: scale(1.05);
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
    opacity: 0.75;
}
</style>

<!-- SCRIPT JS PARA EL MAPA DINÁMICO -->
<script>
let zonaActivaId = null;
let limiteSeleccion = 0;
let asientosElegidosTemporales = []; 
let zonasRequeridas = <?= json_encode(array_column($carrito['items'], 'id_zona')) ?>;

function abrirModalAsientos(idZona, cantidad, nombreZona) {
    zonaActivaId = idZona;
    limiteSeleccion = cantidad;
    asientosElegidosTemporales = [];
    
    document.getElementById('modal_limite').innerText = cantidad;
    document.getElementById('modal_nombre_zona').innerText = nombreZona;

    const grid = document.getElementById('modal_grid');
    grid.innerHTML = "<p style='text-align:center; color:#666;'>Cargando mapa de asientos...</p>";
    document.getElementById('modal_asientos').style.display = 'flex';

    // Llamamos al endpoint dinámico de tu EventoController
    fetch(`<?= BASE_URL ?>/index.php?route=evento/obtenerAsientos&id_zona=${idZona}`)
        .then(res => res.json())
        .then(asientos => {
            grid.innerHTML = "";
            if (asientos.length === 0) {
                grid.innerHTML = "<p style='text-align:center; padding: 10px;'>Esta zona no tiene asientos configurados físicamente.</p>";
                return;
            }

            // Mapeo ordenado de filas y columnas del plano físico
            let maxCol = 1;
            const filaMapa = {};
            let indFila = 1;

            asientos.forEach(a => {
                if (!filaMapa[a.fila]) {
                    filaMapa[a.fila] = indFila++;
                }
                if (parseInt(a.columna) > maxCol) maxCol = parseInt(a.columna);
            });
            const maxFil = indFila - 1;

            // Ajustamos el tamaño del CSS Grid de manera proporcional
            grid.style.gridTemplateColumns = `repeat(${maxCol}, 36px)`;
            grid.style.gridTemplateRows = `repeat(${maxFil}, 36px)`;

            // Renderizamos botón por botón
            asientos.forEach(asiento => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'asiento-btn-modal';
                btn.innerText = asiento.numero_asiento;
                
                // Posicionar botón en las coordenadas exactas de la matriz
                btn.style.gridRow = filaMapa[asiento.fila];
                btn.style.gridColumn = asiento.columna;

                if (asiento.estado === 'VENDIDO') {
                    btn.classList.add('asiento-vendido');
                } else {
                    btn.addEventListener('click', () => {
                        const index = asientosElegidosTemporales.findIndex(x => x.id === asiento.id_asiento);
                        
                        if (index > -1) {
                            asientosElegidosTemporales.splice(index, 1);
                            btn.classList.remove('asiento-seleccionado');
                        } else {
                            if (asientosElegidosTemporales.length >= limiteSeleccion) {
                                alert(`Solo puedes seleccionar un máximo de ${limiteSeleccion} asientos.`);
                                return;
                            }
                            asientosElegidosTemporales.push({ id: asiento.id_asiento, numero: asiento.numero_asiento });
                            btn.classList.add('asiento-seleccionado');
                        }
                    });
                }
                grid.appendChild(btn);
            });
        })
        .catch(err => {
            console.error("Error cargando asientos: ", err);
            grid.innerHTML = "<p style='color:red; text-align:center;'>Error al cargar el mapa físico.</p>";
        });
}

function cerrarModal() {
    document.getElementById('modal_asientos').style.display = 'none';
}

function guardarAsientosSeleccionados() {
    if (asientosElegidosTemporales.length !== limiteSeleccion) {
        alert(`Por favor, selecciona exactamente ${limiteSeleccion} asientos antes de confirmar.`);
        return;
    }

    // 1. Guardar los IDs en el input oculto de su respectiva zona
    const ids = asientosElegidosTemporales.map(x => x.id).join(',');
    document.getElementById(`input_asientos_${zonaActivaId}`).value = ids;

    // 2. Cambiar diseño e indicar al usuario sus asientos elegidos
    const numeros = asientosElegidosTemporales.map(x => x.numero).join(', ');
    const visualDiv = document.getElementById(`asientos_elegidos_visual_${zonaActivaId}`);
    visualDiv.innerText = `Elegidos: ${numeros}`;
    visualDiv.style.color = '#28a745'; // Cambia a verde exitoso

    cerrarModal();
}

function validarSeleccionAsientos() {
    // Validamos que todas las zonas en el carrito tengan sus asientos cargados en sus inputs
    for (let idZona of zonasRequeridas) {
        const valorInput = document.getElementById(`input_asientos_${idZona}`).value;
        if (!valorInput || valorInput.trim() === "") {
            alert("Debes seleccionar tus asientos en el mapa interactivo antes de realizar el pago.");
            return false;
        }
    }
    return true;
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>