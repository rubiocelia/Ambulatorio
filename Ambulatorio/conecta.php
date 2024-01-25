<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambulatorio</title>
</head>
<body>

<?php

    // Establecemos la conexión con BD:
    $servidor = "localhost"; 
    $usuario = "root";
    $password = "";
    $bd = "Ambulatorio";
    $conexion = mysqli_connect($servidor, $usuario, $password, $bd) or die("Error de conexión");
    mysqli_select_db($conexion, "Ambulatorio");

    ?>
    
</body>
</html>