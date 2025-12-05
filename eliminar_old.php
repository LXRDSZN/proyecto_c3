<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar registros</title>
</head>
<body>

<?php
$conexion = mysqli_connect("localhost", "root", "", "areas");
?>


<form action="" method="POST" id="form_mantto">
    <fieldset>
        <legend>Eliminación de datos (Mantenimiento)</legend>

        <label>ID a eliminar</label><br>
        <input type="text" name="id_mantto" required>

        <input type="reset">
        <input type="submit" value="ELIMINAR" name="eliminar_mantto">
        <br><br>

        <?php
        if (isset($_POST['eliminar_mantto'])) {

            $id = mysqli_real_escape_string($conexion, $_POST['id_mantto']);
            $query = "DELETE FROM mantenimiento WHERE id='$id'";

            if ($conexion->query($query)) {
                echo "<p style='color:green;'>Registro eliminado correctamente</p>";
            } else {
                echo "<p style='color:red;'>Error al eliminar</p>";
            }
        }
        ?>
    </fieldset>
</form>

<br><br>

<table border="1">
    <tr>
        <th colspan="7">Mantenimiento</th>
    </tr>
    <tr>
        <th>ID</th>
        <th>AREA</th>
        <th>ACTIVIDADES</th>
        <th>FRECUENCIA</th>
        <th>FOLIO</th>
        <th>OBSERVACIONES</th>
        <th>MATERIAL</th>
    </tr>

<?php
$mantto = mysqli_query($conexion, "SELECT * FROM mantenimiento");
while ($row = mysqli_fetch_assoc($mantto)) {
?>
    <tr>
        <td><?= $row["id"] ?></td>
        <td><?= $row["areaa"] ?></td>
        <td><?= $row["actividad"] ?></td>
        <td><?= $row["frecuencia"] ?></td>
        <td><?= $row["folio"] ?></td>
        <td><?= $row["observaciones"] ?></td>
        <td><?= $row["material"] ?></td>
    </tr>
<?php
}
?>
</table>

<br><br>



<form action="" method="POST" id="form_personal">
    <fieldset>
        <legend>Eliminación de datos (Personal)</legend>

        <label>ID a eliminar</label><br>
        <input type="text" name="id_personal" required>

        <input type="reset">
        <input type="submit" value="ELIMINAR" name="eliminar_personal">
        <br><br>

        <?php
        if (isset($_POST['eliminar_personal'])) {

            $id = mysqli_real_escape_string($conexion, $_POST['id_personal']);
            $query = "DELETE FROM personal WHERE id='$id'";

            if ($conexion->query($query)) {
                echo "<p style='color:green;'>Registro eliminado correctamente</p>";
            } else {
                echo "<p style='color:red;'>Error al eliminar</p>";
            }
        }
        ?>
    </fieldset>
</form>

<br><br>

<table border="1">
    <tr>
        <th colspan="5">Personal</th>
    </tr>
    <tr>
        <th>ID</th>
        <th>NOMBRE</th>
        <th>CARGO</th>
        <th>TELEFONO</th>
        <th>EMAIL</th>
    </tr>

<?php
$personal = mysqli_query($conexion, "SELECT * FROM personal");
while ($row = mysqli_fetch_assoc($personal)) {
?>
    <tr>
        <td><?= $row["id"] ?></td>
        <td><?= $row["nombre"] ?></td>
        <td><?= $row["cargo"] ?></td>
        <td><?= $row["telefono"] ?></td>
        <td><?= $row["email"] ?></td>
    </tr>
<?php
}
?>
</table>

<?php
mysqli_close($conexion);
?>



<script>
document.getElementById('form_mantto').addEventListener('submit', function(e) {
    if (!confirm("¿Seguro que deseas eliminar este registro de Mantenimiento?")) {
        e.preventDefault();
    }
});

document.getElementById('form_personal').addEventListener('submit', function(e) {
    if (!confirm("¿Seguro que deseas eliminar este registro de Personal?")) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
