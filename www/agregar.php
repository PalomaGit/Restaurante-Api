<?php
require 'db.php';

if ($accion === "alta") {
    
    $check = mysqli_query($conexion, "SELECT * FROM reservas 
        WHERE fecha='$fecha' AND hora='$hora' AND mesa_id='$mesa_id'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('⚠️ Ya existe una reserva para esa mesa en ese horario'); 
              window.location='index.php';</script>";
        exit;
    }

    $sql = "INSERT INTO reservas (nombre_cliente, telefono, fecha, hora, num_personas, notas, mesa_id)
            VALUES ('$nombre', '$telefono', '$fecha', '$hora', '$num_personas', '$notas', '$mesa_id')";
    mysqli_query($conexion, $sql);
}
header("Location: index.php");
?>
