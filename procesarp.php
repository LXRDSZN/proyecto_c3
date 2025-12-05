<?php 
$conexion = mysqli_connect("localhost", "root", "", "areas");

if($conexion->connect_errno){
    die("La conexión falló: " . $conexion->connect_errno);
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$cargo = $_POST['cargo'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$actualizar= "UPDATE personal SET 
nombre='$nombre', 
cargo='$cargo', 
telefono='$telefono', 
email='$email'
WHERE id='$id'";

$resultado = mysqli_query($conexion, $actualizar);

if ($resultado) {
    header("Location: modificar.php");
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>