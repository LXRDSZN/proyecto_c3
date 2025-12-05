<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar</title>
</head>
<body>

<?php
$conexion = mysqli_connect("localhost", "root", "", "areas");

if (!$conexion) {
    die("Error de conexión.");
}

if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

$id = $_GET['id'];

$consulta = "SELECT * FROM mantenimiento WHERE id='$id'";
$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$row = mysqli_fetch_assoc($resultado);

if (!$row) {
    die("No se encontró el registro.");
}
?>

<form action="procesar.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    Area:
    <input type="text" name="areaa" value="<?php echo $row['areaa']; ?>"><br><br>

    Actividad:
    <input type="text" name="actividad" value="<?php echo $row['actividad']; ?>"><br><br>

    Frecuencia:
    <input type="text" name="frecuencia" value="<?php echo $row['frecuencia']; ?>"><br><br>

    Folio:
    <input type="text" name="folio" value="<?php echo $row['folio']; ?>"><br><br>

    Observaciones:
    <input type="text" name="observaciones" value="<?php echo $row['observaciones']; ?>"><br><br>

    Material:
    <input type="text" name="material" value="<?php echo $row['material']; ?>"><br><br>

    <input type="submit" value="Actualizar">
</form>

</body>
</html>
