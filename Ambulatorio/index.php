<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Página de Inicio</title>
</head>

<body class="index">
        <div class="menu-container">
            <p >Ayuntamiento de Quintinilla del Matojo</p>
            <a href="index.php">Inicio</a>
        </div>
        <h1 class="bienvenida">Bienvenido al ambulatorio de Quintinilla del Matojo</h1>
        <h1>🤒🩺🏥💊🥼🚑</h1>
    <main class="inicio">
        <!-- Pagina de inicio de sesión -->
        <h1>Inicio de sesión</h1>
        <form action="procesar_inicio.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>
            <br> <br>
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" required>
            <br>
            <p>Seleccione su tipo de usuario:</p>
            <label>
            Paciente <input type="radio" name="tipo_usuario" value="paciente" required>
            </label>
            <label>
            Médico <input type="radio" name="tipo_usuario" value="medico" required>
            </label>
            <br> <br>
            <button type="submit">Ingresar</button>
        </form>
    </main>
</body>

</html>
