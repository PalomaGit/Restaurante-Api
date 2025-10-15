<?php
require 'db.php';

if ($accion === "modificar") {
    $sql = "UPDATE reservas SET 
                nombre_cliente='$nombre',
                telefono='$telefono',
                fecha='$fecha',
                hora='$hora',
                num_personas='$num_personas',
                notas='$notas',
                mesa_id='$mesa_id'
            WHERE id='$idAntiguo'";
    mysqli_query($conexion, $sql);
}
header("Location: index.php");
?>
