<?php
require "db.php";

if ($accion === "baja") {
    $sql = "DELETE FROM reservas WHERE id='$id'";
    mysqli_query($conexion, $sql);
}
header("Location: index.php");
?>
