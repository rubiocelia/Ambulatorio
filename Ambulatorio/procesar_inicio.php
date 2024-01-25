<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $tipo_usuario = $_POST["tipo_usuario"];

    if ($tipo_usuario == "paciente") {
        header("Location: pagina_paciente.php?nombre=$nombre&apellidos=$apellidos");
    } elseif ($tipo_usuario == "medico") {
        header("Location: pagina_medico.php?nombre=$nombre&apellidos=$apellidos");
    } else {
        echo "Tipo de usuario no válido";
    }

    exit();
}
?>
