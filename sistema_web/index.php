<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
</head>

<body>

<h2>Registrar Usuario</h2>

<form action="guardar_usuario.php" method="POST">

    DNI<br>
    <input type="text" name="dni" maxlength="8" required><br><br>

    Nombre<br>
    <input type="text" name="nombre" required><br><br>

    Apellido<br>
    <input type="text" name="apellido" required><br><br>

    Correo<br>
    <input type="email" name="correo" required><br><br>

    Contraseña<br>
    <input type="password" name="contrasena" required><br><br>

    Teléfono<br>
    <input type="text" name="telefono"><br><br>

    Fecha Registro<br>
    <input type="date" name="fecha_registro" required><br><br>

    <input type="submit" value="Guardar">

</form>

</body>
</html>