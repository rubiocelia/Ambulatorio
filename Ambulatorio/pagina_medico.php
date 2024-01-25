<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Página del médico</title>
</head>

<body>

        <div class="menu-container">
            <p>Ayuntamiento de Quintinilla del Matojo</p>
            <a href="index.php">Inicio</a>
        </div>

    <main>
        <?php

            // Incluir el archivo de conexión
            include('conecta.php');

                // Verificar si hay datos de nombre y apellidos en la URL
                if (isset($_GET["nombre"]) && isset($_GET["apellidos"])) {
                    $nombre = $_GET["nombre"];
                    $apellidos = $_GET["apellidos"];

                    // Consulta para obtener la información del médico
                    $query = "SELECT * FROM medico WHERE Nombre = '$nombre' AND Apellidos = '$apellidos'";
                    $result = mysqli_query($conexion, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);

                        if ($row) {
                            // Mostrar la información del médico
                            $nombreMedico = $row['Nombre'] . ' ' . $row['Apellidos'];
                            $especialidadMedico = $row['Especialidad'];

                            echo "<div class='infoPaciente'>";
                            echo "<h1>Información del médico</h1>";
                            echo "<p>Nombre: $nombreMedico | Especialidad: $especialidadMedico</p>";
                            

                            // Mostrar número de consultas en los próximos 7 días
                            $idMedico = $row['id_medico'];
                            $fechaActual = date("Y-m-d");
                            $fecha7Dias = date("Y-m-d", strtotime("+7 days"));

                            $queryProximasCitasMedico = "SELECT COUNT(*) as total FROM consulta 
                                                        WHERE id_medico = $idMedico 
                                                        AND Fecha_consulta BETWEEN '$fechaActual' AND '$fecha7Dias'";
                            $resultProximasCitasMedico = mysqli_query($conexion, $queryProximasCitasMedico);
                            $rowProximasCitasMedico = mysqli_fetch_assoc($resultProximasCitasMedico);
                            $totalProximasCitasMedico = $rowProximasCitasMedico['total'];
                            echo "<p>Próximas consultas (próximos 7 días): $totalProximasCitasMedico</p>";
                            echo "</div>";

                            echo "<div class='consultasHoy'>";
                           
                            // Mostrar consultas de hoy
                            $queryConsultasHoy = "SELECT consulta.id_consulta, paciente.Nombre as nombre_paciente, paciente.Apellidos as apellidos_paciente, SUBSTRING(consulta.Sintomatologia, 1, 100) as extracto_sintomatologia
                                                FROM consulta
                                                JOIN paciente ON consulta.id_paciente = paciente.id_paciente
                                                WHERE id_medico = $idMedico
                                                AND Fecha_consulta = '$fechaActual'";
                            $resultConsultasHoy = mysqli_query($conexion, $queryConsultasHoy);

                            if (mysqli_num_rows($resultConsultasHoy) > 0) {
                                echo "<h2>Consultas de hoy</h2>";
                                echo "<ul>";

                                //bucle para mostrar las citas de hoy
                                while ($rowConsultasHoy = mysqli_fetch_assoc($resultConsultasHoy)) {
                                    $idCitaHoy = $rowConsultasHoy['id_consulta'];
                                    $nombrePacienteHoy = $rowConsultasHoy['nombre_paciente'] . ' ' . $rowConsultasHoy['apellidos_paciente'];
                                    $Sintomatologia = $rowConsultasHoy['extracto_sintomatologia'];

                                    echo "<li>ID Cita: $idCitaHoy | Paciente: $nombrePacienteHoy | Sintomatología: $Sintomatologia | <a href='detalle_consulta.php?id=$idCitaHoy'>Pasar Consulta</a></li>";
                                }
                                
                                echo "</ul>";
                                echo "</div>";
                            } else {
                                echo "<h2>Consultas de hoy</h2>";
                                echo "<p>No hay consultas programadas para hoy.</p>";
                            }               
                            echo "</div>";
                        } else {
                            echo "No se encontró información para el médico.";
                        }
                    } else {
                        echo "Error al realizar la consulta: " . mysqli_error($conexion);
                    }
                } else {
                    echo "Error en los parámetros del formulario.";
                }
        ?>
    </main>
</body>

</html>
