<?php
require 'db.php';

$editarId = $_GET['editarId'] ?? null;
$orden = $_GET['orden'] ?? "fecha";
$busqueda = $_GET['busqueda'] ?? "";

$sql = "SELECT r.*, m.nombre AS mesa
        FROM reservas r
        JOIN mesas m ON r.mesa_id = m.id
        WHERE r.nombre_cliente LIKE '%$busqueda%' 
           OR r.telefono LIKE '%$busqueda%'
        ORDER BY $orden";

$resultado = mysqli_query($conexion, $sql);

$mesas = mysqli_query($conexion, "SELECT * FROM mesas ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Reservas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center bg-blue-100 text-gray-800">
<div class="max-w-6xl mx-auto p-4">
    <h1 class="text-4xl font-bold text-center mb-8">Gestión de Reservas del Restaurante</h1>

    <h2 class="text-2xl font-bold my-4">Hacer una reserva</h2>
    <form action="agregar.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8 max-w-md">
        <input type="hidden" name="idAntiguo" value="">

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
            <input type="text" name="nombre_cliente" required class="shadow border rounded w-full py-2 px-3">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
            <input type="text" name="telefono" class="shadow border rounded w-full py-2 px-3">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Mesa:</label>
            <select name="mesa_id" required class="shadow border rounded w-full py-2 px-3">
                <option value="">-- Selecciona una mesa --</option>
                <?php while($mesa = mysqli_fetch_assoc($mesas)) { ?>
                    <option value="<?php echo $mesa['id']; ?>"><?php echo $mesa['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-4 flex gap-4">
            <div class="flex-1">
                <label class="block text-gray-700 text-sm font-bold mb-2">Fecha:</label>
                <input type="date" name="fecha" required class="shadow border rounded w-full py-2 px-3">
            </div>
            <div class="flex-1">
                <label class="block text-gray-700 text-sm font-bold mb-2">Hora:</label>
                <input type="time" name="hora" required class="shadow border rounded w-full py-2 px-3">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nº Personas:</label>
            <input type="number" name="num_personas" min="1" required class="shadow border rounded w-full py-2 px-3">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Notas:</label>
            <input type="text" name="notas" class="shadow border rounded w-full py-2 px-3">
        </div>

        <div class="flex gap-2">
            <button type="submit" name="accion" value="alta" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Dar de alta</button>
        </div>
    </form>

    <h2 class="text-2xl font-bold my-4">Listado de reservas</h2>

    <form method="get" class="mb-4 flex gap-2">
        <input type="text" name="busqueda" placeholder="Buscar cliente o teléfono"
               value="<?php echo $busqueda; ?>"
               class="border rounded px-3 py-1 w-64">
        <button class="bg-blue-500 text-white px-4 py-1 rounded">Buscar</button>
    </form>

    <div class="overflow-x-auto">
        <table class="border-separate border-spacing-x-6 border-spacing-y-2 w-full bg-white border border-gray-200 shadow-sm rounded">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-3 py-2"><a href="?orden=id">ID</a></th>
                    <th class="px-3 py-2"><a href="?orden=nombre_cliente">Cliente</a></th>
                    <th class="px-3 py-2"><a href="?orden=telefono">Teléfono</a></th>
                    <th class="px-3 py-2"><a href="?orden=fecha">Fecha</a></th>
                    <th class="px-3 py-2"><a href="?orden=hora">Hora</a></th>
                    <th class="px-3 py-2"><a href="?orden=num_personas">Personas</a></th>
                    <th class="px-3 py-2"><a href="?orden=mesa">Mesa</a></th>
                    <th class="px-3 py-2">Notas</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)) { 
                    $fecha = date("d-m-Y", strtotime($fila["fecha"]));
                ?>

                    <?php if ($editarId == $fila['id']) { ?>
                        <tr class="bg-yellow-50">
                            <form action="modificar.php" method="post">
                                <td><?php echo $fila["id"]; ?></td>
                                <td><input type="text" name="nombre_cliente" value="<?php echo $fila["nombre_cliente"]; ?>" class="border rounded w-full px-2"></td>
                                <td><input type="text" name="telefono" value="<?php echo $fila["telefono"]; ?>" class="border rounded w-full px-2"></td>
                                <td><input type="date" name="fecha" value="<?php echo $fila["fecha"]; ?>" class="border rounded w-full px-2"></td>
                                <td><input type="time" name="hora" value="<?php echo $fila["hora"]; ?>" class="border rounded w-full px-2"></td>
                                <td><input type="number" name="num_personas" value="<?php echo $fila["num_personas"]; ?>" class="border rounded w-full px-2"></td>
                                <td>
                                    <select name="mesa_id" class="border rounded w-full px-2">
                                        <?php 
                                        $mesas2 = mysqli_query($conexion, "SELECT * FROM mesas");
                                        while($m = mysqli_fetch_assoc($mesas2)) { ?>
                                            <option value="<?php echo $m['id']; ?>" <?php echo $m['id']==$fila['mesa_id']?"selected":""; ?>>
                                                <?php echo $m['nombre']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="text" name="notas" value="<?php echo $fila["notas"]; ?>" class="border rounded w-full px-2"></td>
                                <td class="flex gap-2">
                                    <input type="hidden" name="idAntiguo" value="<?php echo $fila['id']; ?>">
                                    <button type="submit" name="accion" value="modificar" class="bg-yellow-500 text-white px-3 rounded">Guardar</button>
                                    <a href="index.php" class="bg-gray-400 text-white px-3 rounded">Cancelar</a>
                                </td>
                            </form>
                        </tr>
                    <?php } else { ?>
                        <tr class="hover:bg-gray-50">
                            <td><?php echo $fila["id"]; ?></td>
                            <td><?php echo $fila["nombre_cliente"]; ?></td>
                            <td><?php echo $fila["telefono"]; ?></td>
                            <td><?php echo $fecha; ?></td>
                            <td><?php echo $fila["hora"]; ?></td>
                            <td><?php echo $fila["num_personas"]; ?></td>
                            <td><?php echo $fila["mesa"]; ?></td>
                            <td><?php echo $fila["notas"]; ?></td>
                            <td class="flex gap-2">
                                <form action="borrar.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $fila["id"]; ?>">
                                    <button type="submit" name="accion" value="baja" class="bg-red-500 text-white px-3 rounded" onclick="return confirm('¿Seguro que quieres borrar esta reserva?')">Borrar</button>
                                </form>
                                <a href="?editarId=<?php echo $fila['id']; ?>" class="bg-blue-500 text-white px-3 rounded">Modificar</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
