<?php
session_start();
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: login.php');
    exit;
}

require_once('config/conexion.php');
require_once('fpdf.php');

try {
    $db = new Database();
    $conexion = $db->getConnection();
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'CECYTE - REPORTE DE PERSONAL',0,1,'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Pagina '.$this->PageNo().' - Generado: '.date('d/m/Y H:i:s'),0,0,'C');
    }

    // Tabla de datos
    function BasicTable($header, $data)
    {
        // Cabecera
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(231, 76, 60); // Color rojo para personal
        $this->SetTextColor(255, 255, 255);
        
        $widths = array(20, 50, 40, 30, 50); // Anchos de columnas
        
        for($i = 0; $i < count($header); $i++) {
            $this->Cell($widths[$i], 8, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        // Datos
        $this->SetFont('Arial','',9);
        $this->SetTextColor(0, 0, 0);
        $fill = false;
        
        foreach($data as $row)
        {
            if ($fill) {
                $this->SetFillColor(245, 245, 245);
            } else {
                $this->SetFillColor(255, 255, 255);
            }
            
            $this->Cell($widths[0], 7, $row['id'], 1, 0, 'C', true);
            $this->Cell($widths[1], 7, substr($row['nombre'], 0, 25), 1, 0, 'L', true);
            $this->Cell($widths[2], 7, substr($row['cargo'], 0, 20), 1, 0, 'L', true);
            $this->Cell($widths[3], 7, $row['telefono'], 1, 0, 'C', true);
            $this->Cell($widths[4], 7, substr($row['email'], 0, 25), 1, 0, 'L', true);
            $this->Ln();
            $fill = !$fill;
        }
    }
}

// Obtener datos
$sql = "SELECT * FROM personal ORDER BY id DESC";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

$data = array();
while ($row = $resultado->fetch_assoc()) {
    $data[] = $row;
}

// Crear PDF
$pdf = new PDF('P', 'mm', 'A4'); // Portrait para personal
$pdf->AddPage();

// Información del reporte
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0, 8, 'Fecha de generacion: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
$pdf->Cell(0, 8, 'Total de personal: ' . count($data), 0, 1, 'L');
$pdf->Ln(5);

// Cabeceras de la tabla
$header = array('ID', 'Nombre', 'Cargo', 'Telefono', 'Email');

// Generar tabla
if (count($data) > 0) {
    $pdf->BasicTable($header, $data);
} else {
    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(0, 10, 'No hay registros de personal para mostrar.', 0, 1, 'C');
}

// Cerrar conexión
if (isset($db)) {
    $db->closeConnection();
}

// Enviar PDF
$filename = 'reporte_personal_' . date('Y-m-d_H-i-s') . '.pdf';
$pdf->Output('D', $filename);
?>