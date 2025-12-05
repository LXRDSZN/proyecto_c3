<?php 
$conexion = mysqli_connect("localhost", "root", "", "areas");

if($conexion->connect_errno){
    die("La conexión falló: " . $conexion->connect_errno);
}

$id = $_POST['id'];
$areaa = $_POST['areaa'];
$actividad = $_POST['actividad'];
$frecuencia = $_POST['frecuencia'];
$folio = $_POST['folio'];
$observaciones = $_POST['observaciones'];
$material = $_POST['material'];

$actualizar= "UPDATE mantenimiento SET 
areaa='$areaa', 
actividad='$actividad', 
frecuencia='$frecuencia', 
folio='$folio', 
observaciones='$observaciones',
material='$material' 
WHERE id='$id'";

$resultado = mysqli_query($conexion, $actualizar);

if ($resultado) {
    header("Location: modificar.php");
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>

