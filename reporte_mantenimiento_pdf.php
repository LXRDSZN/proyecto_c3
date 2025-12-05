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
        $this->Cell(0,10,'CECYTE - REPORTE DE MANTENIMIENTO',0,1,'C');
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
        $this->SetFont('Arial','B',9);
        $this->SetFillColor(102, 126, 234);
        $this->SetTextColor(255, 255, 255);
        
        $widths = array(20, 30, 40, 30, 25, 35, 30); // Anchos de columnas
        
        for($i = 0; $i < count($header); $i++) {
            $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        // Datos
        $this->SetFont('Arial','',8);
        $this->SetTextColor(0, 0, 0);
        $fill = false;
        
        foreach($data as $row)
        {
            if ($fill) {
                $this->SetFillColor(245, 245, 245);
            } else {
                $this->SetFillColor(255, 255, 255);
            }
            
            $this->Cell($widths[0], 6, $row['id'], 1, 0, 'C', true);
            $this->Cell($widths[1], 6, substr($row['areaa'], 0, 15), 1, 0, 'L', true);
            $this->Cell($widths[2], 6, substr($row['actividad'], 0, 20), 1, 0, 'L', true);
            $this->Cell($widths[3], 6, substr($row['frecuencia'], 0, 15), 1, 0, 'L', true);
            $this->Cell($widths[4], 6, substr($row['folio'], 0, 12), 1, 0, 'C', true);
            $this->Cell($widths[5], 6, substr($row['observaciones'], 0, 18), 1, 0, 'L', true);
            $this->Cell($widths[6], 6, substr($row['material'], 0, 15), 1, 0, 'L', true);
            $this->Ln();
            $fill = !$fill;
        }
    }
}

// Obtener datos
$sql = "SELECT * FROM mantenimiento ORDER BY id DESC";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

$data = array();
while ($row = $resultado->fetch_assoc()) {
    $data[] = $row;
}

// Crear PDF
$pdf = new PDF('L', 'mm', 'A4'); // Landscape para más espacio
$pdf->AddPage();

// Información del reporte
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0, 8, 'Fecha de generacion: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
$pdf->Cell(0, 8, 'Total de registros: ' . count($data), 0, 1, 'L');
$pdf->Ln(5);

// Cabeceras de la tabla
$header = array('ID', 'Area', 'Actividad', 'Frecuencia', 'Folio', 'Observaciones', 'Material');

// Generar tabla
if (count($data) > 0) {
    $pdf->BasicTable($header, $data);
} else {
    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(0, 10, 'No hay registros de mantenimiento para mostrar.', 0, 1, 'C');
}

// Cerrar conexión
if (isset($db)) {
    $db->closeConnection();
}

// Enviar PDF
$filename = 'reporte_mantenimiento_' . date('Y-m-d_H-i-s') . '.pdf';
$pdf->Output('D', $filename);
?>