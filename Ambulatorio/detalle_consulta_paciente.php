<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Detalle Consulta Paciente</title>
</head>

<body>
<main>
<?php
// Verificar si hay datos de la consulta en la URL
if (isset($_GET["id"])) {
    $idConsulta = $_GET["id"];

    // Conectar a la base de datos
    include('conecta.php');

    // Consulta para obtener la información detallada de la consulta
    $query = "SELECT consulta.id_consulta, consulta.Fecha_consulta, medico.Nombre AS nombre_medico, medico.Apellidos AS apellidos_medico, consulta.Sintomatologia, consulta.Diagnostico, consulta.pdf, medicamento.Medicamento, receta.Posologia, receta.Fecha_fin
          FROM consulta
          JOIN medico ON consulta.id_medico = medico.id_medico
          JOIN receta ON consulta.id_consulta = receta.id_consulta
          JOIN medicamento ON receta.id_medicamento = medicamento.id_medicamento
          WHERE consulta.id_consulta = $idConsulta";


    $result = mysqli_query($conexion, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            // Mostrar la información detallada de la consulta
            $idConsulta = $row['id_consulta'];
            $fechaConsulta = $row['Fecha_consulta'];
            $nombreMedico = $row['nombre_medico'];
            $apellidosMedico = $row['apellidos_medico'];
            $sintomatologia = $row['Sintomatologia'];
            $diagnostico = $row['Diagnostico'];
            $medicamento = $row['Medicamento'];
            $posologia = $row['Posologia'];
            $fechaFin = $row['Fecha_fin'];
            $PDF = $row['pdf'];

            echo "<div class='mensajeCita'>";
            echo "<h1>Detalles de la Consulta</h1>";
            echo "<p>ID Consulta: $idConsulta | Fecha: $fechaConsulta</p>";
            echo "<p>Médico: $nombreMedico $apellidosMedico</p>";
            echo "<p>Sintomatología: $sintomatologia</p>";
            echo "<p>Diagnóstico: $diagnostico</p>";

            // Mostrar medicamentos
            echo "<h2>Medicación:</h2>";
            echo "<ul>";

            // Reinicia el puntero del resultado a la primera fila
            mysqli_data_seek($result, 0);

            // Bucle para mostrar todos los medicamentos
            while ($row = mysqli_fetch_assoc($result)) {
                $medicamento = $row['Medicamento'];
                $posologia = $row['Posologia'];
                $fechaFin = $row['Fecha_fin'];

                echo "<li>$medicamento | Posología: $posologia | Fecha de Fin: $fechaFin</li>";
            }

            echo "</ul>";
            
            // Mostrar el enlace solo si la variable $PDF no es nula
            if ($PDF !== null) {
                echo "<a href='archivos/$PDF' target='_blank'>PDF</a>";
            }else{
                echo "<p>No hay pdf</p> ";
            }
            echo "</div>";
        } else {
            echo "No se encontró información para la consulta.";
        }
    } else {
        echo "Error al realizar la consulta: " . mysqli_error($conexion);
    }

    // Cerrar la conexión
    mysqli_close($conexion);
} else {
    echo "Falta el parámetro de ID de la consulta en la URL.";
}
?>
</main>
</body>

</html>
