<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ambulatorio - Detalle Consulta</title>
    <script src="consulta.js" ></script>
</head>

<body>

        <div class="menu-container">
            <p >Ayuntamiento de Quintinilla del Matojo</p>
            <a href="index.php">Inicio</a>
        </div>
<main>
<?php

    if (isset($_GET["id"])) {
        $idConsulta = $_GET["id"];

        // Conectar a la base de datos
        include('conecta.php');

        // Consulta para obtener la información de la consulta y del paciente
        $queryConsulta = "SELECT consulta.id_consulta, consulta.Fecha_consulta, consulta.Sintomatologia, 
                        consulta.pdf, consulta.diagnostico,
                        medico.Nombre AS MedicoNombre, medico.Apellidos AS MedicoApellidos,
                        paciente.Nombre AS PacienteNombre, paciente.Apellidos AS PacienteApellidos
                        FROM consulta
                        JOIN medico ON consulta.id_medico = medico.id_medico
                        JOIN paciente ON consulta.id_paciente = paciente.id_paciente
                        WHERE consulta.id_consulta = $idConsulta";

        $resultConsulta = mysqli_query($conexion, $queryConsulta);

        if ($resultConsulta) {
            $rowConsulta = mysqli_fetch_assoc($resultConsulta);

            if ($rowConsulta) {
                // Mostrar la información de la consulta que no se puede editar
                echo "<div class='info-section'>";
                echo "<h1>Información consulta</h1>";
                echo "<p>- ID consulta: " . $rowConsulta['id_consulta'] . " | Fecha: " . $rowConsulta['Fecha_consulta'] . "</p>";
                echo "<p>- Nombre del médico: " . $rowConsulta['MedicoNombre'] . " " . $rowConsulta['MedicoApellidos'] . "</p>";
                echo "<p>- Nombre del paciente: " . $rowConsulta['PacienteNombre'] . " " . $rowConsulta['PacienteApellidos'] . "</p>";
                echo "</div>";

                // Formulario para la información editable de la consulta
                echo "<div class='form-section'>";
                echo "<form  action='boton_guardar_datos.php' method='POST' enctype= 'multipart/form-data'  onsubmit='return agregarMedicacion();'>";
                echo "<input type='hidden' name='id_consulta' value='$idConsulta'>";
                
                
                // Sintomatología
                echo "<h2>Añadir información</h2>";
                echo "<label for='sintomatologia'>- Sintomatología:</label>";
                echo "<textarea name='sintomatologia'>" . $rowConsulta['Sintomatologia'] . "</textarea>";

                // Diagnóstico
                echo "<label for='diagnostico'>- Diagnóstico:</label>";
                echo "<textarea name='diagnostico'>" . $rowConsulta['diagnostico'] . "</textarea>";

                // PDF
                echo "<br><br>";
                echo "<label for='cronica'>- PDF: </label>";
                echo "<input type='file' name='archivoPDF' accept= '.pdf'>";
                echo "<br><br>";

                // Medicación
                $consultaMedicamentos = "SELECT id_medicamento, Medicamento FROM medicamento";
                $resultMedicamentos = mysqli_query($conexion, $consultaMedicamentos);

                echo "<h2>Añadir medicación</h2>";
                echo "<label for='medicamento'>Medicamento:</label>";
                
                echo "<select name='medicamento'>";
                // Mostrar las opciones en el desplegable
                while ($rowMedicamento = mysqli_fetch_assoc($resultMedicamentos)) {
                    echo "<option value='" . $rowMedicamento["id_medicamento"] . "'>";
                    echo $rowMedicamento["Medicamento"];
                    echo "</option>";
                }
                echo "</select>";

                // Cantidad
                echo "<br> <br>";
                echo "<label for='cantidad'>Cantidad: </label>";
                echo "<input type='text' name='cantidad'>";

                //Frecuencia
                echo "<br> <br>";
                echo "<label for='frecuencia'>Frecuencia: </label>";
                echo "<input type='text' name='frecuencia'>";

                //Numero de dias
                echo "<br><br>";
                echo "<label for='dias'>Número de días: </label>";
                echo "<input type='number' name='dias'>";

                //cronica
                echo "<br><br>";
                echo "<label for='cronica'>Medicación crónica: </label>";
                echo "<input type='checkbox' name='cronica'>";
                echo "<br><br>";

                // Botón de Registro
                echo "<button type='submit'>Guardar datos</button>";
                echo "</form>";
                echo "</div>";
                
                echo "<div class='info-section'>";

                // Mostrar medicación anteriormente añadida
                $consultaMedicacionAnterior = "SELECT medicamento.Medicamento, receta.Posologia, receta.Fecha_fin
                FROM receta
                JOIN medicamento ON receta.id_medicamento = medicamento.id_medicamento
                WHERE receta.id_consulta = $idConsulta";
                $resultMedicacionAnterior = mysqli_query($conexion, $consultaMedicacionAnterior);

                if ($resultMedicacionAnterior) {
                echo "<h2>Medicación:</h2>";
                echo "<table border='1'>";
                echo "<tr><th>Medicamento</th><th>Posología</th><th>Fecha fin</th></tr>";

                while ($rowMedicacionAnterior = mysqli_fetch_assoc($resultMedicacionAnterior)) {
                echo "<tr>";
                echo "<td>" . $rowMedicacionAnterior['Medicamento'] . "</td>";
                echo "<td>" . $rowMedicacionAnterior['Posologia'] . "</td>";
                echo "<td>" . $rowMedicacionAnterior['Fecha_fin'] . "</td>";
                echo "</tr>";
                }

                echo "</table>";
                echo "</div>";
                } else {
                echo "Error al obtener la medicación anterior: " . mysqli_error($conexion);
                echo "</div>";
                }


                // Segundo formulario para derivar a especialista
                echo "<form class='formPaciente' action='boton_derivar_cita.php' method='post' onsubmit='return validarDerivacion();'>";
                echo "<h2>Derivar a especialista</h2>";

                // Campos ocultos para enviar nombre y apellidos del paciente
                echo "<input type='hidden' name='nombre_paciente' value='" . $rowConsulta['PacienteNombre'] . "'>";
                echo "<input type='hidden' name='apellidos_paciente' value='" . $rowConsulta['PacienteApellidos'] . "'>";


                echo "<label for='especialista'>Especialista: </label>";
                echo "<select name='especialista'>";

                // Consulta para obtener la lista de médicos con sus especialidades
                $consultaMedicos = "SELECT id_medico, Nombre, Apellidos, Especialidad FROM medico";
                $resultMedicos = mysqli_query($conexion, $consultaMedicos);

                // Mostrar las opciones en el desplegable
                while ($rowMedico = mysqli_fetch_assoc($resultMedicos)) {
                    echo "<option value='" . $rowMedico["id_medico"] . "'>";
                    echo $rowMedico["Nombre"] . " " . $rowMedico["Apellidos"] . " - " . $rowMedico["Especialidad"];
                    echo "</option>";
                }
                echo "</select>";
                echo "<br><br>";
                echo "<label for='fecha'>Fecha de la cita: </label>";
                echo "<input type='date' name='fecha'>";
                echo "<br> <br>";
                echo "<label for='sintomatologia'>Sintomatología (opcional): </label>";
                echo "<textarea name='sintomatologia'></textarea>";
                echo "<br> <br>";
                echo "<button type='submit' name='pedir_cita'>Pedir cita</button>";
                echo "</form>";
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
        echo "Falta el parámetro de ID en la URL.";
    }
?>
</main>
</body>
</html>
