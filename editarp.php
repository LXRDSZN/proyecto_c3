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

$consulta = "SELECT * FROM personal WHERE id='$id'";
$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$row = mysqli_fetch_assoc($resultado);

if (!$row) {
    die("No se encontró el registro.");
}
?>

<form action="procesarp.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    NOMBRE:
    <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>"><br><br>

    CARGO:
    <input type="text" name="cargo" value="<?php echo $row['cargo']; ?>"><br><br>

    TELEFONO:
    <input type="text" name="telefono" value="<?php echo $row['telefono']; ?>"><br><br>

    EMAIL:
    <input type="text" name="email" value="<?php echo $row['email']; ?>"><br><br>

     <input type="submit" value="Actualizar">
</form>

</body>
</html>
