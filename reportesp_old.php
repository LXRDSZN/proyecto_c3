<?php

ob_start(); /* es una opcion de guardar en memoria todo lo que tiene esta pagina*/

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>insertar</title>
</head>
<body>
<?php
$conexion = mysqli_connect("localhost", "root", "", "areas");
$mantto = "SELECT * FROM mantenimiento";
?>

<h2>MANTENIMIENTO</h2>


<br>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>AREA</th>
        <th>ACTIVIDAD</th>
        <th>FRECUENCIA</th>
        <th>FOLIO</th>
        <th>OBSERVACIONES</th>
        <th>MATERIALES</th>
    </tr>

<?php
$resultado = mysqli_query($conexion, "SELECT * FROM mantenimiento");

while ($row = mysqli_fetch_assoc($resultado)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['areaa']}</td>
            <td>{$row['actividad']}</td>
            <td>{$row['frecuencia']}</td>
            <td>{$row['folio']}</td>
            <td>{$row['observaciones']}</td>
            <td>{$row['material']}</td>
          </tr>";
}
?>
</table>


</body>
</body>
</html>

<?php

$html=ob_get_clean();

// echo $html;

require_once '../MANTTO/administrador/libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf= new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

// $dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("archivo.pdf", array("Attachment" => true));

?>