<?php

include("conexion.php");

$dni = $_POST['dni'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$telefono = $_POST['telefono'];
$fecha = $_POST['fecha_registro'];

$sql = "INSERT INTO usuario
(dni,nombre,apellido,correo,contrasena,telefono,fecha_registro)
VALUES
('$dni','$nombre','$apellido','$correo','$contrasena','$telefono','$fecha')";

if($conn->query($sql)==TRUE)
{
    echo "Usuario registrado correctamente";
}
else
{
    echo "Error: ".$conn->error;
}

$conn->close();

?>