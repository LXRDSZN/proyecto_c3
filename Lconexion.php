<?php
session_start();
require_once 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');
    
    if (empty($usuario) || empty($contraseña)) {
        $_SESSION['error_message'] = "Por favor, completa todos los campos.";
        header("Location: login.php");
        exit();
    }
    
    try {
        $db = new Database();
        $conexion = $db->getConnection();
        
        // Usar prepared statements para evitar inyección SQL
        $stmt = $conexion->prepare("SELECT usuario, contraseña FROM login WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            
            // Verificar contraseña (en el futuro se debe hashear)
            if ($contraseña === $row['contraseña']) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['authenticated'] = true;
                $_SESSION['success_message'] = "Bienvenido, " . $usuario . "!";
                header("Location: inicio.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error_message'] = "Usuario no encontrado.";
        }
        
        $stmt->close();
        $db->closeConnection();
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error del sistema. Inténtalo más tarde.";
    }
    
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>