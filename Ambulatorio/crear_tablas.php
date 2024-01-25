<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear tablas</title>
</head>

<body>
    <?php
        // Incluimos el archivo de conexión
        include('conecta.php');

        // Verificamos si la conexión fue exitosa
        if (!$conexion) {
            echo "Conexión fallida";
        } else {
            // Creamos la base de datos
            $sql = "CREATE DATABASE IF NOT EXISTS Ambulatorio";
            if (mysqli_query($conexion, $sql)) { // Lanzar BD contra el servidor

                // Seleccionamos la base de datos recién creada
                mysqli_select_db($conexion, "Ambulatorio");

                // Creación de la tabla medico
                $sqlMedico = "CREATE TABLE IF NOT EXISTS medico (
                    id_medico INT AUTO_INCREMENT PRIMARY KEY,
                    Nombre VARCHAR(50) NOT NULL,
                    Apellidos VARCHAR(50) NOT NULL,
                    Especialidad VARCHAR(50) NOT NULL
                )";
                    
                if (!mysqli_query($conexion, $sqlMedico)) {
                    echo "Error al crear la tabla medico: " . mysqli_error($conexion);
                }

                // Creación de la tabla medicamento
                $sqlMedicamento = "CREATE TABLE IF NOT EXISTS medicamento (
                    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
                    Medicamento VARCHAR(50) NOT NULL
                )";
                    
                if (!mysqli_query($conexion, $sqlMedicamento)) {
                    echo "Error al crear la tabla medicamento: " . mysqli_error($conexion);
                }
                
                // Creación de la tabla paciente
                $sqlPaciente = "CREATE TABLE IF NOT EXISTS paciente (
                    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
                    DNI VARCHAR(10) NOT NULL,
                    Nombre VARCHAR(50) NOT NULL,
                    Apellidos VARCHAR(50) NOT NULL,
                    Genero ENUM('M', 'F', 'O') NOT NULL,
                    Fecha_nac DATE NOT NULL,
                    id_med TEXT
                )";
                    
                if (!mysqli_query($conexion, $sqlPaciente)) {
                    echo "Error al crear la tabla paciente: " . mysqli_error($conexion);
                }

                // Creación de la tabla consulta
                $sqlConsulta = "CREATE TABLE IF NOT EXISTS consulta (
                    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
                    id_medico INT,
                    id_paciente INT,
                    Fecha_consulta DATE NOT NULL,
                    Diagnostico TEXT,
                    Sintomatologia TEXT,
                    pdf VARCHAR(255),
                    FOREIGN KEY (id_medico) REFERENCES medico(id_medico),
                    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente)
                )";
                    
                if (!mysqli_query($conexion, $sqlConsulta)) {
                    echo "Error al crear la tabla consulta: " . mysqli_error($conexion);
                }
                
                // Creación de la tabla receta
                $sqlReceta = "CREATE TABLE IF NOT EXISTS receta (
                    id_medicamento INT,
                    id_consulta INT,
                    Posologia VARCHAR(50) NOT NULL,
                    Fecha_fin DATE NOT NULL,
                    FOREIGN KEY (id_medicamento) REFERENCES medicamento(id_medicamento),
                    FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta)
                )";

                    
                if (!mysqli_query($conexion, $sqlReceta)) {
                    echo "Error al crear la tabla receta: " . mysqli_error($conexion);
                }

                // Verificamos si las tablas están vacías
                $queryMedicoVacia = "SELECT COUNT(*) as total FROM medico";
                //mysqli_query ejecuta la consulta en la conexión a la base de datos
                $resultMedico = mysqli_query($conexion, $queryMedicoVacia);
                //mysqli_fetch_assoc obtiene la primera fila de resultados 
                //como un array asociativo y lo almacena en la variable $rowMedico
                $rowMedico = mysqli_fetch_assoc($resultMedico); 
                
                
                $queryPacienteVacia = "SELECT COUNT(*) as total FROM paciente";
                $resultPaciente = mysqli_query($conexion, $queryPacienteVacia);
                $rowPaciente = mysqli_fetch_assoc($resultPaciente);
                
                $queryRecetaVacia = "SELECT COUNT(*) as total FROM receta";
                $resultReceta = mysqli_query($conexion, $queryRecetaVacia);
                $rowReceta = mysqli_fetch_assoc($resultReceta);
                
                $queryConsultaVacia = "SELECT COUNT(*) as total FROM consulta";
                $resultConsulta = mysqli_query($conexion, $queryConsultaVacia);
                $rowConsulta = mysqli_fetch_assoc($resultConsulta);
                
                $queryMedicamentoVacia = "SELECT COUNT(*) as total FROM medicamento";
                $resultMedicamento = mysqli_query($conexion, $queryMedicamentoVacia);
                $rowMedicamento = mysqli_fetch_assoc($resultMedicamento);
                

                // Insertamos datos si las tablas están vacías
                if ($rowMedico['total'] == 0) {
                    $insertMedico = "INSERT INTO medico (Nombre, Apellidos, Especialidad) VALUES
                    ('Dr. Juan', 'Pérez', 'Médico de cabecera'),
                    ('Dra. María', 'Gómez', 'Dermatología'),
                    ('Dr. Carlos', 'López', 'Neurología'),
                    ('Dra. Laura', 'Martínez', 'Alergología')";
                    mysqli_query($conexion, $insertMedico);
                }

                if ($rowPaciente['total'] == 0) {
                    $insertPaciente = "INSERT INTO paciente (DNI, Nombre, Apellidos, Genero, Fecha_nac, id_med) VALUES
                    ('123456789A', 'Ana', 'González', 'F', '1990-05-15', '1'),
                    ('112233445C', 'Elena', 'Fernández', 'F', '1978-12-10', '1,2'),
                    ('987654321B', 'Luis', 'Rodríguez', 'M', '1985-08-20', '1,3'),
                    ('556677889D', 'Javier', 'Ruiz', 'M', '1995-03-25', '1,4')";
                    mysqli_query($conexion, $insertPaciente);
                }

                
                if ($rowConsulta['total'] == 0) {
                    $insertConsulta = "INSERT INTO consulta (id_medico, id_paciente, Fecha_consulta, Diagnostico, Sintomatologia) VALUES
                    (1, 1, '2023-12-10', 'Gripe', ''),
                    (1, 1, '2023-09-14', 'Resfriado', 'Tos y mocos'),
                    (1, 1, '2023-01-09', 'Anginas', 'Dolor de garganta'),
                    (2, 2, '2023-11-05', 'Dermatitis', 'Rojeces en la piel'),
                    (2, 2, '2023-12-15', 'Dermatitis', ''),
                    (3, 3, '2023-11-27', 'Alzehimer', 'Pérdida de memoria'),
                    (3, 3, '2023-12-27', 'Alzehimer', ''),
                    (4, 4, '2023-11-01', 'Alergia', 'Dar receta pastillas'),
                    (4, 4, '2023-12-01', 'Alergia', 'Dar receta pastillas'),
                    (4, 4, '2024-01-01', 'Alergia', '')";
                    mysqli_query($conexion, $insertConsulta);
                }

                if ($rowMedicamento['total'] == 0) {
                    $insertMedicamento = "INSERT INTO medicamento (id_medicamento, Medicamento) VALUES
                    (1, 'Antigripal'),
                    (2, 'Crema antiinflamatoria'),
                    (3, 'Donepezilo'),
                    (4, 'Paracetamol'),
                    (5, 'Ibuprofeno'),
                    (6, 'Frenadol'),
                    (7, 'Aerius')";
                    mysqli_query($conexion, $insertMedicamento);
                }

                if ($rowReceta['total'] == 0) {
                    $insertReceta = "INSERT INTO receta (id_medicamento, id_consulta, Posologia, Fecha_fin) VALUES
                    (2, 4, 'Aplicar en la zona afectada', '2023-11-28'),
                    (4, 4, 'Tomar cada 8 horas', '2023-01-20'),
                    (5, 2, 'Tomar cada 8 horas', '2023-11-30'),
                    (5, 2, 'Tomar cada 8 horas cuando note molestias', '2023-11-30'),
                    (3, 6, 'Uno al dia', '2023-12-01'),
                    (5, 6, 'Uno al dia', '2023-12-01'),
                    (7, 8, 'Uno al dia por la mañana', '2023-11-30')";
                    mysqli_query($conexion, $insertReceta);
                }
                echo "Tablas creadas y datos insertados correctamente.";
            } else {
                echo "Error al crear la base de datos: " . mysqli_error($conexion);
            }

            // Cerramos la conexión
            mysqli_close($conexion);
        }
    ?>
</body>

</html>
