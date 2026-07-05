<?php
session_start();
if (!isset($_SESSION['usuario_dni'])) {
    header("Location: ../usuarios/auth.php");
    exit();
}

// Conectar temporalmente para listar eventos vigentes
$mysqli = new mysqli("localhost", "root", "", "DBMITICKET");
$eventos = $mysqli->query("SELECT e.*, l.nombre_lugar, l.ciudad FROM evento e INNER JOIN lugar l ON e.id_lugar = l.id_lugar WHERE e.estado = 'PUBLICADO'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MITICKET - Cartelera Principal</title>
    <style>
        .evento-card { border: 1px solid #222; padding: 15px; margin: 15px; width: 300px; display: inline-block; vertical-align: top;}
    </style>
</head>
<body>

    <header>
        <h1>MITICKET</h1>
        <p>Bienvenido, <strong><?php echo $_SESSION['usuario_nombre']; ?></strong> | <a href="../usuarios/auth.php">Cerrar Sesión</a></p>
    </header>

    <hr>
    <h2>Eventos Disponibles</h2>

    <?php while($ev = $eventos->fetch_assoc()): ?>
        <div class="evento-card">
            <h3><?php echo $ev['nombre']; ?></h3>
            <p><?php echo $ev['descripcion']; ?></p>
            <p><strong>Lugar:</strong> <?php echo $ev['nombre_lugar'] . " (" . $ev['ciudad'] . ")"; ?></p>
            <p><strong>Fecha:</strong> <?php echo $ev['fecha']; ?> | <strong>Hora:</strong> <?php echo $ev['hora']; ?></p>
            
            <form action="../compras/proceso.php" method="GET">
                <input type="hidden" name="id_evento" value="<?php echo $ev['id_evento']; ?>">
                <input type="submit" value="Comprar Entradas" style="background-color: green; color: white; padding: 10px; cursor: pointer;">
            </form>
        </div>
    <?php endwhile; ?>

</body>
</html>