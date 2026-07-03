<?php

include("conexion.php");

$sql = "SELECT * FROM usuario";

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Usuarios</title>

</head>

<body>

<h2>Lista de Usuarios</h2>

<table border="1">

<tr>

<th>DNI</th>

<th>Nombre</th>

<th>Apellido</th>

<th>Correo</th>

<th>Teléfono</th>

<th>Fecha</th>

</tr>

<?php

while($fila = $resultado->fetch_assoc())
{

?>

<tr>

<td><?php echo $fila['dni']; ?></td>

<td><?php echo $fila['nombre']; ?></td>

<td><?php echo $fila['apellido']; ?></td>

<td><?php echo $fila['correo']; ?></td>

<td><?php echo $fila['telefono']; ?></td>

<td><?php echo $fila['fecha_registro']; ?></td>

</tr>

<?php

}

?>

</table>

</body>

</html>