<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Procesar Cita</title>
</head>
<body>
        <div class="menu-container">
            <p >Ayuntamiento de Quintinilla del Matojo</p>
            <a href="index.php">Inicio</a>
        </div>
<main>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si hay datos de fecha, id_paciente, id_medico y sintomatologia en el formulario
    if (isset($_POST["fecha"]) && isset($_POST["id_paciente"]) && isset($_POST["id_medico"]) && isset($_POST["sintomatologia"])) {
        $fecha = $_POST["fecha"];
        $idPaciente = $_POST["id_paciente"];
        $idMedico = $_POST["id_medico"];
        $sintomatologia = $_POST["sintomatologia"];

        // Conectar a la base de datos
        include('conecta.php');

        // Insertar la nueva cita en la tabla consulta
        $queryInfoPaciente = "SELECT Nombre, Apellidos FROM paciente WHERE id_paciente = $idPaciente";
        $resultInfoPaciente = mysqli_query($conexion, $queryInfoPaciente);

        if ($resultInfoPaciente && $rowInfoPaciente = mysqli_fetch_assoc($resultInfoPaciente)) {
            $nombrePaciente = $rowInfoPaciente["Nombre"];
            $apellidosPaciente = $rowInfoPaciente["Apellidos"];

            // Insertar la nueva cita en la tabla consulta
            $queryInsertCita = "INSERT INTO consulta (id_paciente, id_medico, Fecha_consulta, Sintomatologia) VALUES ($idPaciente, $idMedico, '$fecha', '$sintomatologia')";
            $resultInsertCita = mysqli_query($conexion, $queryInsertCita);

            if ($resultInsertCita) {
                echo "<div class='mensajeCita'>";
                echo "<h1>Cita solicitada con éxito</h1>";
                echo "<p>La cita para el paciente $nombrePaciente $apellidosPaciente ha sido programada para el $fecha.</p>";
                echo "</div>";
            } else {
                echo "<div class='mensajeCita'>";
                echo "<h1>Error al solicitar la cita</h1>";
                echo "<p>Ocurrió un error al intentar programar la cita. Por favor, inténtalo de nuevo.</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='mensajeCita'>";
            echo "<h1>Error al obtener información del paciente</h1>";
            echo "<p>Ocurrió un error al obtener información del paciente. Por favor, inténtalo de nuevo.</p>";
            echo "</div>";
        }

        // Cerrar la conexión
        mysqli_close($conexion);
    } else {
        echo "<div class='mensajeCita'>";
        echo "<h1>Error en los parámetros del formulario</h1>";
        echo "<p>Faltan parámetros en el formulario para procesar la cita.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='mensajeCita'>";
    echo "<h1>Error en el método de solicitud</h1>";
    echo "<p>Esta página solo acepta solicitudes POST.</p>";
    echo "</div>";
}

?>
</main>
</body>
</html>