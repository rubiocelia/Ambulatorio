<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Página del paciente</title>
    <script src="pacientes.js"></script>

</head>
<body>
        <div class="menu-container">
            <p >Ayuntamiento de Quintinilla del Matojo</p>
            <a href="index.php">Inicio</a>
        </div>
        <main>
            <?php
                // Verificar si hay datos de nombre y apellidos en la URL
                if (isset($_GET["nombre"]) && isset($_GET["apellidos"])) {
                    $nombre = $_GET["nombre"];
                    $apellidos = $_GET["apellidos"];

                    // Conectar a la base de datos
                    include('conecta.php');

                    // Consulta para obtener la información del paciente
                    $query = "SELECT * FROM paciente WHERE Nombre = '$nombre' AND Apellidos = '$apellidos'";
                    $result = mysqli_query($conexion, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);

                        if ($row) {
                            // Mostrar la información del paciente
                            $idPaciente = $row['id_paciente'];
                            $dni = $row['DNI'];

                            echo "<div class='infoPaciente'>";
                            echo "<h1>Información del paciente</h1>";
                            echo "<p>DNI: $dni | Nombre:  $apellidos, $nombre</p>";
                            echo "</div>";

                            
                            // Mostrar medicación actual
                            $queryMedicacion = "SELECT receta.Posologia, receta.Fecha_fin, medicamento.Medicamento
                                    FROM receta
                                    JOIN consulta ON receta.id_consulta = consulta.id_consulta
                                    JOIN medicamento ON receta.id_medicamento = medicamento.id_medicamento
                                    WHERE consulta.id_paciente = $idPaciente AND receta.Fecha_fin >= CURDATE()";
                            $resultMedicacion = mysqli_query($conexion, $queryMedicacion);

                            if (mysqli_num_rows($resultMedicacion) > 0) {
                                echo "<div class='medcActual'>";
                                echo "<h2>Medicación actual</h2>";
                                echo "<ul>";

                                //bucle que muestra las medicaciones que tiene actualmente
                                while ($rowMedicacion = mysqli_fetch_assoc($resultMedicacion)) {
                                    $medicamento = $rowMedicacion['Medicamento'];
                                    $posologia = $rowMedicacion['Posologia'];
                                    $fechaFin = $rowMedicacion['Fecha_fin'];

                                    echo "<li>Medicamento: $medicamento | Posología: $posologia | Fin: $fechaFin</li>";
                                    
                                }
                                echo "</ul>";
                                echo "</div>";
                            } else {
                                echo "<div class='medcActual'>";
                                echo "<h2>Medicación Actual</h2>";
                                echo "<ul>";
                                echo "<li>No hay medicación actual</li>";
                                echo "</ul>";
                                echo "</div>";
                            }

                            echo "<div class='consultas'>";

                            // Mostrar próximas citas
                            $queryProximasCitas = "SELECT * FROM consulta WHERE id_paciente = $idPaciente AND Fecha_consulta > CURDATE()";
                            $resultProximasCitas = mysqli_query($conexion, $queryProximasCitas);

                            if (mysqli_num_rows($resultProximasCitas) > 0) {
                                echo "<div class='proxCitas'>";
                                echo "<h2>Próximas Citas</h2>";
                                echo "<ul>";

                                while ($rowCitas = mysqli_fetch_assoc($resultProximasCitas)) {
                                    $idCita = $rowCitas['id_consulta'];
                                    $idMedico = $rowCitas['id_medico'];
                                    $fechaConsulta = $rowCitas['Fecha_consulta'];

                                    // Obtener el nombre del médico
                                    $queryNombreMedico = "SELECT Nombre FROM medico WHERE id_medico = $idMedico";
                                    $resultNombreMedico = mysqli_query($conexion, $queryNombreMedico);
                                    $rowNombreMedico = mysqli_fetch_assoc($resultNombreMedico);
                                    $nombreMedico = $rowNombreMedico['Nombre'];

                                    echo "<li>ID cita: $idCita | Médico: $nombreMedico | Fecha: $fechaConsulta</li>";
                            
                                }
                                echo "</ul>";
                                echo "</div>";
                            } else {
                                echo "<div class='proxCitas'>";
                                echo "<h2>Próximas Citas</h2>";
                                echo "<ul>";
                                echo "<li>No hay próximas citas</li>";
                                echo "</ul>";
                                echo "</div>";                                
                            }
                            
                            // Mostrar consultas pasadas
                            $queryConsultasPasadas = "SELECT * FROM consulta WHERE id_paciente = $idPaciente AND Fecha_consulta <= CURDATE()";
                            $resultConsultasPasadas = mysqli_query($conexion, $queryConsultasPasadas);

                            if (mysqli_num_rows($resultConsultasPasadas) > 0) {
                                echo "<div class='consultPasadas'>";
                                echo "<h2>Consultas Pasadas</h2>";
                                echo "<ul>";
                                while ($rowConsultasPasadas = mysqli_fetch_assoc($resultConsultasPasadas)) {
                                    $idConsultaPasada = $rowConsultasPasadas['id_consulta'];
                                    $fechaConsultaPasada = $rowConsultasPasadas['Fecha_consulta'];

                                    echo "<li>ID consulta: $idConsultaPasada | Fecha: $fechaConsultaPasada | <a href='detalle_consulta_paciente.php?id=$idConsultaPasada'>Ver detalles de la consulta</a></li>";
                                }
                                echo "</ul>";
                                echo "</div>";
                            } else {
                                echo "<div class='consultPasadas'>";
                                echo "<h2>Consultas pasadas</h2>";
                                echo "<ul>";
                                echo "<li>No hay consultas pasadas</li>";
                                echo "</ul>";
                                echo "</div>";
                            }
                            echo "</div>";
                            
                            // Formulario para solicitar una nueva cita
                            echo "<form class='formPaciente' action='procesar_cita.php' method='post' onsubmit='return validarFormularios()'>";
                            echo "<h2>Solicitar Cita</h2>";
                            echo "<label for='fecha'>Fecha de la cita: </label>";
                            echo "<input type='date' name='fecha'>";
                            echo "<br> <br>";
                            echo "<label for='sintomatologia'>Sintomatología (opcional): </label>";
                            echo "<textarea name='sintomatologia'></textarea>";
                            echo "<br> <br>";
                            echo "<label for='id_medico'>Selecciona un médico: </label>";
                            echo "<select id='id_medico' name='id_medico'>";

                            // Obtener la lista de médicos asociados a este paciente
                            $consulta_medicos_paciente = "SELECT DISTINCT medico.id_medico, CONCAT(medico.Nombre, ' ', medico.Apellidos) AS nombre_medico, medico.Especialidad
                            FROM medico
                            JOIN consulta ON medico.id_medico = consulta.id_medico
                            JOIN paciente ON FIND_IN_SET(medico.id_medico, paciente.id_med)
                            WHERE paciente.id_paciente = $idPaciente";

                            $resultado_medicos_paciente = mysqli_query($conexion, $consulta_medicos_paciente);

                            // Verificar si hay errores en la consulta
                            if (!$resultado_medicos_paciente) {
                            die('Error en la consulta de médicos asociados al paciente: ' . mysqli_error($conexion));
                            }

                            // Mostrar las opciones en el desplegable
                            while ($medico_paciente = mysqli_fetch_assoc($resultado_medicos_paciente)) {
                            echo "<option value='" . $medico_paciente["id_medico"] . "'>";
                            echo $medico_paciente["nombre_medico"] . " - " . $medico_paciente["Especialidad"];
                            echo "</option>";
                            }
                            echo "</select>";
                            echo "<input type='hidden' name='id_paciente' value='$idPaciente'>";
                            echo "<br> <br>";
                            echo "<button type='submit'>Pedir Cita</button>";
                            echo "</form>";
                        } else {
                            echo "No se encontró información para el paciente.";
                        }
                    } else {
                        echo "Error al realizar la consulta: " . mysqli_error($conexion);
                    }

                    // Cerrar la conexión
                    mysqli_close($conexion);
                } else {
                    echo "Faltan parámetros de nombre y apellidos en la URL.";
                }
            ?>
        </main>
    </body>
</html>
