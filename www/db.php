<?php
$conexion = mysqli_connect("db", "root", "test");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Crear BD
$crearBd = mysqli_query($conexion, "CREATE DATABASE IF NOT EXISTS restaurante");
$usarBd = mysqli_select_db($conexion, "restaurante");

// Tabla Mesas
$crearTablaMesas = mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS mesas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
)");

// Tabla Reservas con relación a Mesas
$crearTablaReservas = mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_cliente VARCHAR(255) NOT NULL,
  telefono VARCHAR(50),
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  num_personas INT NOT NULL,
  notas TEXT,
  mesa_id INT NOT NULL,
  FOREIGN KEY (mesa_id) REFERENCES mesas(id),
  UNIQUE KEY uq_reserva (fecha, hora, mesa_id)
)");


mysqli_query($conexion, "INSERT IGNORE INTO mesas (id, nombre) VALUES
(1, 'Mesa 1 Interior'),
(2, 'Mesa 2 Terraza'),
(3, 'Mesa VIP')");

$accion = $_POST["accion"] ?? "";
$id = $_POST["id"] ?? "";
$nombre = $_POST["nombre_cliente"] ?? "";
$telefono = $_POST["telefono"] ?? "";
$fecha = $_POST["fecha"] ?? "";
$hora = $_POST["hora"] ?? "";
$num_personas = $_POST["num_personas"] ?? "";
$notas = $_POST["notas"] ?? "";
$idAntiguo = $_POST["idAntiguo"] ?? "";
$mesa_id = $_POST["mesa_id"] ?? "";
?>
