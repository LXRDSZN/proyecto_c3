<?php
session_start();
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

require_once 'config/conexion.php';

// Generar reporte HTML descargable para Personal
function generatePersonalReport() {
    try {
        $db = new Database();
        $conexion = $db->getConnection();
        
        $resultado = $conexion->query("SELECT * FROM personal ORDER BY nombre");
        
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_personal_' . date('Y-m-d') . '.html"');
        
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reporte de Personal - CECYTE</title>
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
                .avatar {
                    width: 40px;
                    height: 40px;
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    border-radius: 50%;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: bold;
                    margin-right: 10px;
                    vertical-align: middle;
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
                <h1>👥 CECYTE - Directorio de Personal</h1>
                <p class="subtitle">Sistema de Gestión de Recursos Humanos</p>
                <p class="fecha">📅 Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>';
        
        // Estadísticas
        $totalPersonal = $resultado->num_rows;
        $cargos = [];
        
        if ($totalPersonal > 0) {
            $resultado->data_seek(0); // Reiniciar el puntero
            while ($row = $resultado->fetch_assoc()) {
                $cargo = $row['cargo'];
                $cargos[$cargo] = isset($cargos[$cargo]) ? $cargos[$cargo] + 1 : 1;
            }
        }
        
        echo '<div class="stats">
                <div class="stat-item">
                    <div style="font-size: 1.8rem;">' . $totalPersonal . '</div>
                    <div>Total Personal</div>
                </div>
                <div class="stat-item">
                    <div style="font-size: 1.8rem;">' . count($cargos) . '</div>
                    <div>Tipos de Cargo</div>
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
                        <th>👤 Empleado</th>
                        <th>💼 Cargo</th>
                        <th>📞 Teléfono</th>
                        <th>📧 Email</th>
                    </tr>
                </thead>
                <tbody>';
        
        if ($totalPersonal > 0) {
            $resultado->data_seek(0); // Reiniciar el puntero
            while ($row = $resultado->fetch_assoc()) {
                $inicial = strtoupper(substr($row['nombre'], 0, 1));
                echo '<tr>';
                echo '<td><strong>' . htmlspecialchars($row['id']) . '</strong></td>';
                echo '<td>
                        <div style="display: flex; align-items: center;">
                            <div class="avatar">' . $inicial . '</div>
                            <strong>' . htmlspecialchars($row['nombre']) . '</strong>
                        </div>
                      </td>';
                echo '<td><span style="background: linear-gradient(135deg, #ed8936, #dd6b20); color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.8rem;">' . htmlspecialchars($row['cargo']) . '</span></td>';
                echo '<td>📱 ' . htmlspecialchars($row['telefono']) . '</td>';
                echo '<td>✉️ ' . htmlspecialchars($row['email']) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5" style="text-align: center; color: #999; padding: 30px;">👤 No hay personal registrado</td></tr>';
        }
        
        echo '      </tbody>
            </table>';
        
        // Resumen por cargos
        if (!empty($cargos)) {
            echo '<div style="margin-top: 30px;">
                    <h3 style="color: #667eea;">📊 Distribución por Cargo</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">';
            
            foreach ($cargos as $cargo => $count) {
                $porcentaje = round(($count / $totalPersonal) * 100, 1);
                echo '<div style="background: #f8f9fa; border: 2px solid #e9ecef; padding: 15px; border-radius: 8px; text-align: center; min-width: 120px;">
                        <div style="font-weight: bold; color: #667eea; font-size: 1.4rem;">' . $count . '</div>
                        <div style="font-size: 0.9rem; color: #666; margin-bottom: 5px;">' . htmlspecialchars($cargo) . '</div>
                        <div style="font-size: 0.8rem; color: #999;">(' . $porcentaje . '%)</div>
                      </div>';
            }
            
            echo '    </div>
                  </div>';
        }
        
        // Información de contacto rápido
        echo '<div style="margin-top: 30px; background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 5px solid #667eea;">
                <h4 style="color: #667eea; margin-bottom: 15px;">📋 Información de Contacto</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px;">';
        
        if ($totalPersonal > 0) {
            $resultado->data_seek(0);
            while ($row = $resultado->fetch_assoc()) {
                echo '<div style="background: white; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <strong>' . htmlspecialchars($row['nombre']) . '</strong><br>
                        <small style="color: #666;">📞 ' . htmlspecialchars($row['telefono']) . ' | ✉️ ' . htmlspecialchars($row['email']) . '</small>
                      </div>';
            }
        }
        
        echo '    </div>
              </div>';
        
        echo '<p class="footer">
                🏛️ Generado por Sistema CECYTE - ' . date('Y') . '<br>
                📧 Contacto: rh@cecyte.edu.mx<br>
                🌐 www.cecyte.edu.mx
              </p>
            
            <div class="no-print" style="margin-top: 30px; text-align: center;">
                <button onclick="window.print()" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; margin-right: 10px;">
                    🖨️ Imprimir Directorio
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
generatePersonalReport();
?>