<?php 
session_start();
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

require_once 'config/conexion.php';

try {
    $db = new Database();
    $conexion = $db->getConnection();
    
    $id = $_POST['id'];
    $tipo = $_POST['tipo'] ?? 'mantenimiento';
    
    if ($tipo == 'mantenimiento') {
        $areaa = $_POST['areaa'];
        $actividad = $_POST['actividad'];
        $frecuencia = $_POST['frecuencia'];
        $folio = $_POST['folio'];
        $observaciones = $_POST['observaciones'];
        $material = $_POST['material'];

        $stmt = $conexion->prepare("UPDATE mantenimiento SET areaa=?, actividad=?, frecuencia=?, folio=?, observaciones=?, material=? WHERE id=?");
        $stmt->bind_param("ssssssi", $areaa, $actividad, $frecuencia, $folio, $observaciones, $material, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registro de mantenimiento actualizado correctamente.";
            header("Location: inicio.php");
        } else {
            $_SESSION['error_message'] = "Error al actualizar el registro de mantenimiento.";
            header("Location: editar.php?id=$id&tipo=mantenimiento");
        }
    } else {
        $nombre = $_POST['nombre'];
        $cargo = $_POST['cargo'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        $stmt = $conexion->prepare("UPDATE personal SET nombre=?, cargo=?, telefono=?, email=? WHERE id=?");
        $stmt->bind_param("ssssi", $nombre, $cargo, $telefono, $email, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registro de personal actualizado correctamente.";
            header("Location: inicio.php");
        } else {
            $_SESSION['error_message'] = "Error al actualizar el registro de personal.";
            header("Location: editar.php?id=$id&tipo=personal");
        }
    }
    
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error del sistema: " . $e->getMessage();
    header("Location: inicio.php");
}

if (isset($db)) {
    $db->closeConnection();
}
?>

