<?php
session_start();
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: login.php');
    exit;
}

require_once('config/conexion.php');
// Generar reporte HTML para PDF

try {
    $db = new Database();
    $conexion = $db->getConnection();
    
    // Obtener datos
    $sql = "SELECT * FROM personal ORDER BY id ASC";
    $resultado = $conexion->query($sql);
    
    if (!$resultado) {
        die("Error en la consulta: " . $conexion->error);
    }
    
    // Generar HTML para PDF
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Reporte de Personal</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
            .title { font-size: 20px; font-weight: bold; color: #333; }
            .info { font-size: 12px; color: #666; margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 11px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #667eea; color: white; font-weight: bold; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
            @media print { body { margin: 0; } .no-print { display: none; } }
        </style>
    </head>
    <body>
        <div class="no-print" style="text-align: center; margin-bottom: 10px;">
            <button onclick="window.print()" style="background: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">🖨️ Imprimir / Guardar como PDF</button>
        </div>
        
        <div class="header">
            <div class="title">CECYTE - REPORTE DE PERSONAL</div>
            <div class="info">Generado el: <?php echo date('d/m/Y H:i:s'); ?></div>
            <div class="info">Total de personal: <?php echo $resultado->num_rows; ?></div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cargo</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['cargo']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; font-style: italic;">No hay personal registrado</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            <p>Sistema CECYTE - <?php echo date('Y'); ?> | Reporte generado el <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
        
        <script>
            // Auto-abrir diálogo de impresión después de 1 segundo
            setTimeout(function() {
                if (confirm('¿Deseas imprimir o guardar este reporte como PDF?')) {
                    window.print();
                }
            }, 1000);
        </script>
    </body>
    </html>
    <?php
    
    $db->closeConnection();
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>