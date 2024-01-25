<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Botón Guardar Datos</title>
</head>

<body>

<main>
<?php
// Verificar si la solicitud es a través del método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar si hay un parámetro "id_consulta" en el formulario POST
    if (isset($_POST["id_consulta"])) {
        // Obtener el ID de la consulta desde el formulario POST
        $idConsulta = $_POST["id_consulta"];

        // Conectar a la base de datos
        include('conecta.php');

        // Obtener datos del formulario
        $diagnostico = mysqli_real_escape_string($conexion, $_POST['diagnostico']);
        $sintomatologia = mysqli_real_escape_string($conexion, $_POST['sintomatologia']);
        $medicamentoId = $_POST['medicamento'];
        $cantidad = $_POST['cantidad'];
        $frecuencia = $_POST['frecuencia'];
        $dias = $_POST['dias'];
        $esCronica = isset($_POST['cronica']) ? 1 : 0;

        // Obtener la fecha actual
        $fechaActual = date('Y-m-d');

        // Calcular la fecha de finalización de la medicación
        $fechaFinalizacion = date('Y-m-d', strtotime("+$dias days"));

        if ($esCronica) {
            $fechaFinalizacion = date('Y-m-d', strtotime("+365 days"));
        }

        $pdfNombre = null;
        // Guardar el pdf con el mismo nombre que tiene en la carpeta "archivos"
            if (isset($_FILES['archivoPDF']) && $_FILES['archivoPDF']['error'] === UPLOAD_ERR_OK) {
                $pdfNombre = null;
                $pdfNombre = $_FILES['archivoPDF']['name'];
                $pdfRutaTemporal = $_FILES['archivoPDF']['tmp_name'];
                $pdfRutaDestino = 'archivos/' . $pdfNombre;

                // Verificar si ya existe un archivo con el mismo nombre
                $contador = 1;
                while (file_exists($pdfRutaDestino)) {
                    $nombreSinExtension = pathinfo($pdfNombre, PATHINFO_FILENAME);
                    $pdfNombre = $nombreSinExtension . '_' . $contador . '.' . pathinfo($pdfNombre, PATHINFO_EXTENSION);
                    $pdfRutaDestino = 'archivos/' . $pdfNombre;
                    $contador++;
                }

                if (move_uploaded_file($pdfRutaTemporal, $pdfRutaDestino)) {
                    // El archivo PDF se movió correctamente, ahora actualiza la ruta en la base de datos
                    $sqlActualizarRutaPDF = "UPDATE Consulta SET pdf = 'archivos/$pdfRutaDestino' WHERE id_consulta = $idConsulta";
                    $resultadoActualizarRutaPDF = mysqli_query($conexion, $sqlActualizarRutaPDF);

                    if (!$resultadoActualizarRutaPDF) {
                        echo "Error al actualizar la ruta del archivo PDF en la base de datos: " . mysqli_error($conexion);
                        exit();
                    }
                } else {
                    echo "Error al mover el archivo PDF.";
                    exit();
                }
            }

        // Actualizar datos en la tabla consulta
        $queryActualizarConsulta = "UPDATE consulta SET Diagnostico='$diagnostico', Sintomatologia='$sintomatologia', pdf='$pdfNombre' WHERE id_consulta='$idConsulta'";
        $resultActualizarConsulta = mysqli_query($conexion, $queryActualizarConsulta);

        if ($resultActualizarConsulta) {
            // Insertar datos en la tabla receta
            $queryReceta = "INSERT INTO receta (id_consulta, id_medicamento, Posologia, Fecha_fin) VALUES ('$idConsulta', '$medicamentoId', 'Tomar $cantidad $frecuencia', '$fechaFinalizacion')";
            $resultReceta = mysqli_query($conexion, $queryReceta);

            if ($resultReceta) {
                echo "<div class='mensajeCita'>";
                echo "<h1>Datos guardados correctamente</h1>";
                echo "<p>La información de la consulta ha sido actualizada exitosamente.</p>";
                echo "<p>Posología: Tomar $cantidad $frecuencia</p>";
                echo "<p>Fecha de finalización: $fechaFinalizacion</p>";
                echo "</div>";
            } else {
                echo "<div class='mensajeCita'>";
                echo "<h1>Error al insertar en la tabla receta</h1>";
                echo "<p>Ocurrió un error al intentar insertar en la tabla receta. Por favor, inténtalo de nuevo.</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='mensajeCita'>";
            echo "<h1>Error al actualizar en la tabla consulta</h1>";
            echo "<p>Ocurrió un error al intentar actualizar en la tabla consulta. Por favor, inténtalo de nuevo.</p>";
            echo "</div>";
        }

        // Cerrar la conexión
        mysqli_close($conexion);
    } else {
        echo "<div class='mensajeCita'>";
        echo "<h1>Falta el parámetro de ID de la consulta</h1>";
        echo "<p>La URL no contiene el parámetro necesario para procesar la solicitud.</p>";
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
