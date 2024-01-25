<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Derivar Cita</title>
</head>

<body>

<main>
<?php

// Verificar si la solicitud es a través del método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar si hay un parámetro "especialista" en el formulario POST
    if (isset($_POST["especialista"])) {
        // Obtener el ID del especialista desde el formulario POST
        $idEspecialista = $_POST["especialista"];

        // Conectar a la base de datos
        include('conecta.php');

        // Obtener datos del formulario
        $fechaCita = $_POST['fecha'];
        $sintomatologia = mysqli_real_escape_string($conexion, $_POST['sintomatologia']);
        $nombrePaciente = $_POST['nombre_paciente']; 
        $apellidosPaciente = $_POST['apellidos_paciente']; 

        // Obtener el ID del paciente directamente en la consulta
        $queryNuevaCita = "INSERT INTO consulta (id_medico, id_paciente, Fecha_consulta, Sintomatologia) 
        SELECT '$idEspecialista', paciente.id_paciente, '$fechaCita', '$sintomatologia'
        FROM paciente
        WHERE paciente.Nombre = '$nombrePaciente' AND paciente.Apellidos = '$apellidosPaciente'";
        $resultNuevaCita = mysqli_query($conexion, $queryNuevaCita);


        if ($resultNuevaCita) {
            echo "<div class='mensajeCita'>";
            echo "<h1>Cita derivada correctamente</h1>";
            echo "<p>La cita ha sido programada exitosamente.</p>";
            echo "<p>Fecha de la cita: $fechaCita</p>";
            echo "<p>Sintomatología: $sintomatologia</p>";
            echo "</div>";
        } else {
            echo "<div class='mensajeCita'>";
            echo "<h1>Error al derivar la cita</h1>";
            echo "<p>Ocurrió un error al intentar derivar la cita. Por favor, inténtalo de nuevo.</p>";
            echo "</div>";
        }

        // Cerrar la conexión
        mysqli_close($conexion);
    } else {
        echo "<div class='mensajeCita'>";
        echo "<h1>Falta el parámetro de ID del especialista</h1>";
        echo "<p>El formulario no contiene el parámetro necesario para procesar la solicitud.</p>";
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
