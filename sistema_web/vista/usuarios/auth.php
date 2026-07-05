<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MITICKET - Ingreso / Registro</title>
    <style>
        .form-section { margin-bottom: 40px; padding: 20px; border: 1px solid #ccc; }
        .hidden { display: none; }
    </style>
</head>
<body>

    <h1>Bienvenido a MITICKET</h1>

    <div class="form-section">
        <h2>Iniciar Sesión</h2>
        <form action="../../controlador/UsuarioController.php?action=login" method="POST">
            <label>Correo Electrónico:</label><br>
            <input type="email" name="correo" required><br><br>
            
            <label>Contraseña:</label><br>
            <input type="password" name="contrasena" required><br><br>
            
            <input type="submit" value="Ingresar">
        </form>
    </div>

    <div class="form-section">
        <h2>¿No tienes cuenta? Regístrate aquí</h2>
        <form action="../../controlador/UsuarioController.php?action=register" method="POST">
            
            <label>Tipo de Usuario:</label><br>
            <select name="tipo_usuario" id="tipo_usuario" onchange="alternarCampos()" required>
                <option value="">-- Selecciona una opción --</option>
                <option value="cliente">Cliente (Comprador)</option>
                <option value="organizador">Organizador de Eventos</option>
            </select><br><br>

            <label>DNI:</label><br>
            <input type="text" name="dni" maxlength="8" required><br><br>

            <label>Nombre:</label><br>
            <input type="text" name="nombre" required><br><br>

            <label>Apellido:</label><br>
            <input type="text" name="apellido" required><br><br>

            <label>Correo Electrónico:</label><br>
            <input type="email" name="correo" required><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="contrasena" required><br><br>

            <label>Teléfono:</label><br>
            <input type="text" name="telefono"><br><br>

            <label>Fecha de Nacimiento:</label><br>
            <input type="date" name="fecha_nacimiento" required><br><br>

            <div id="campos_organizador" class="hidden">
                <label>Nombre de la Empresa:</label><br>
                <input type="text" name="nombre_empresa" id="nombre_empresa"><br><br>

                <label>RUC (11 dígitos):</label><br>
                <input type="text" name="ruc" id="ruc" maxlength="11"><br><br>
            </div>

            <div id="campos_cliente" class="hidden">
                <label>Selecciona tu categoría favorita:</label><br>
                <select name="id_categoria" id="id_categoria">
                    <option value="1">Concierto Rock</option>
                    <option value="2">Concierto Pop</option>
                    <option value="3">Teatro</option>
                    <option value="5">Fútbol</option>
                </select><br><br>
            </div>

            <input type="submit" value="Crear Cuenta">
        </form>
    </div>

    <script>
        function alternarCampos() {
            var tipo = document.getElementById('tipo_usuario').value;
            var divOrganizador = document.getElementById('campos_organizador');
            var divCliente = document.getElementById('campos_cliente');
            
            if (tipo === 'organizador') {
                divOrganizador.classList.remove('hidden');
                divCliente.classList.add('hidden');
                document.getElementById('nombre_empresa').required = true;
                document.getElementById('ruc').required = true;
            } else if (tipo === 'cliente') {
                divCliente.classList.remove('hidden');
                divOrganizador.classList.add('hidden');
                document.getElementById('nombre_empresa').required = false;
                document.getElementById('ruc').required = false;
            } else {
                divOrganizador.classList.add('hidden');
                divCliente.classList.add('hidden');
            }
        }
    </script>
</body>
</html>