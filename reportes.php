<?php
session_start();
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

require_once 'config/conexion.php';

// Generar reporte HTML descargable
function generateHTMLReport() {
    try {
        $db = new Database();
        $conexion = $db->getConnection();
        
        $resultado = $conexion->query("SELECT * FROM mantenimiento ORDER BY id");
        
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_mantenimiento_' . date('Y-m-d') . '.html"');
        
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reporte de Mantenimiento - CECYTE</title>
            <style>
                body { 
                    font-family: "Arial", sans-serif; 
                    margin: 20px; 
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                }
                .container {
                    background: white;
                    padding: 30px;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                }
                h1 { 
                    color: #667eea; 
                    text-align: center;
                    font-size: 2.2rem;
                    margin-bottom: 10px;
                }
                .subtitle {
                    text-align: center;
                    color: #666;
                    font-size: 1.1rem;
                    margin-bottom: 30px;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-top: 20px;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }
                th, td { 
                    border: 1px solid #e2e8f0; 
                    padding: 12px 8px; 
                    text-align: left; 
                }
                th { 
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    font-weight: 600;
                    text-transform: uppercase;
                    font-size: 0.9rem;
                }
                tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                tr:hover {
                    background-color: #e3f2fd;
                }
                .fecha { 
                    text-align: center; 
                    color: #666; 
                    margin: 15px 0;
                    font-weight: 500;
                }
                .footer { 
                    text-align: center; 
                    font-size: 12px; 
                    color: #999; 
                    margin-top: 30px;
                    border-top: 2px solid #e2e8f0;
                    padding-top: 20px;
                }
                .stats {
                    display: flex;
                    justify-content: space-around;
                    margin: 20px 0;
                    text-align: center;
                }
                .stat-item {
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    padding: 15px 20px;
                    border-radius: 10px;
                    font-weight: 600;
                }
                @media print {
                    body { 
                        margin: 0; 
                        background: white;
                    }
                    .container {
                        box-shadow: none;
                        padding: 10px;
                    }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>📊 CECYTE - Reporte de Mantenimiento</h1>
                <p class="subtitle">Sistema de Gestión de Mantenimiento Institucional</p>
                <p class="fecha">📅 Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>';
        
        // Estadísticas
        $totalRegistros = $resultado->num_rows;
        $frecuencias = [];
        
        if ($totalRegistros > 0) {
            $resultado->data_seek(0); // Reiniciar el puntero
            while ($row = $resultado->fetch_assoc()) {
                $freq = $row['frecuencia'];
                $frecuencias[$freq] = isset($frecuencias[$freq]) ? $frecuencias[$freq] + 1 : 1;
            }
        }
        
        echo '<div class="stats">
                <div class="stat-item">
                    <div style="font-size: 1.8rem;">' . $totalRegistros . '</div>
                    <div>Total Registros</div>
                </div>
                <div class="stat-item">
                    <div style="font-size: 1.8rem;">' . count($frecuencias) . '</div>
                    <div>Tipos de Frecuencia</div>
                </div>
                <div class="stat-item">
                    <div style="font-size: 1.8rem;">' . date('Y') . '</div>
                    <div>Año Actual</div>
                </div>
              </div>';
        
        echo '<table>
                <thead>
                    <tr>
                        <th>🆔 ID</th>
                        <th>🏢 Área</th>
                        <th>⚙️ Actividad</th>
                        <th>📅 Frecuencia</th>
                        <th>📋 Folio</th>
                        <th>📝 Observaciones</th>
                        <th>🔧 Material</th>
                    </tr>
                </thead>
                <tbody>';
        
        if ($totalRegistros > 0) {
            $resultado->data_seek(0); // Reiniciar el puntero
            while ($row = $resultado->fetch_assoc()) {
                echo '<tr>';
                echo '<td><strong>' . htmlspecialchars($row['id']) . '</strong></td>';
                echo '<td><strong>' . htmlspecialchars($row['areaa']) . '</strong></td>';
                echo '<td>' . htmlspecialchars($row['actividad']) . '</td>';
                echo '<td><span style="background: linear-gradient(135deg, #48bb78, #38a169); color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.8rem;">' . htmlspecialchars($row['frecuencia']) . '</span></td>';
                echo '<td>' . htmlspecialchars($row['folio']) . '</td>';
                echo '<td>' . htmlspecialchars($row['observaciones']) . '</td>';
                echo '<td>' . htmlspecialchars($row['material']) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7" style="text-align: center; color: #999; padding: 30px;">📭 No hay registros de mantenimiento disponibles</td></tr>';
        }
        
        echo '      </tbody>
            </table>';
        
        // Resumen de frecuencias
        if (!empty($frecuencias)) {
            echo '<div style="margin-top: 30px;">
                    <h3 style="color: #667eea;">📊 Resumen por Frecuencia</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">';
            
            foreach ($frecuencias as $freq => $count) {
                echo '<div style="background: #f8f9fa; border: 2px solid #e9ecef; padding: 10px 15px; border-radius: 8px; text-align: center;">
                        <div style="font-weight: bold; color: #667eea;">' . $count . '</div>
                        <div style="font-size: 0.9rem; color: #666;">' . htmlspecialchars($freq) . '</div>
                      </div>';
            }
            
            echo '    </div>
                  </div>';
        }
        
        echo '<p class="footer">
                🏛️ Generado por Sistema CECYTE - ' . date('Y') . '<br>
                📧 Contacto: mantenimiento@cecyte.edu.mx<br>
                🌐 www.cecyte.edu.mx
              </p>
            
            <div class="no-print" style="margin-top: 30px; text-align: center;">
                <button onclick="window.print()" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; margin-right: 10px;">
                    🖨️ Imprimir Reporte
                </button>
                <button onclick="window.close()" style="background: linear-gradient(135deg, #6c757d, #5a6268); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    ❌ Cerrar
                </button>
            </div>
            </div>
        </body>
        </html>';
        
        $db->closeConnection();
        
    } catch (Exception $e) {
        echo '<html><body style="font-family: Arial; padding: 20px;">
                <h1 style="color: #e53e3e;">❌ Error</h1>
                <p>No se pudo generar el reporte: ' . htmlspecialchars($e->getMessage()) . '</p>
                <button onclick="history.back()" style="background: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                    ← Volver
                </button>
              </body></html>';
    }
}

// Ejecutar la generación del reporte
generateHTMLReport();
?>