<?php
session_start();
include('conexion.php');

// Verificamos que solo el admin pueda realizar esta acción
if (!isset($_SESSION['admin_logeado'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibimos los datos del formulario de admin_panel
    $nuevo_usuario = $_POST['nuevo_user'];
    $password_plana = $_POST['nuevo_pass'];
    $id_rol = $_POST['rol'];

    // 1. Encriptamos la contraseña para que no sea texto plano
    $password_hash = password_hash($password_plana, PASSWORD_DEFAULT);

    // 2. Preparamos la consulta para la tabla 'login'
    $sql = "INSERT INTO login (username, password, role_id) VALUES (?, ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $nuevo_usuario, $password_hash, $id_rol);
        
        if (mysqli_stmt_execute($stmt)) {
            // Si tiene éxito, regresamos a la pestaña de usuarios con un mensaje
            header("Location: admin_panel.php?view=usuarios&msg=user_created");
        } else {
            echo "Error al registrar: " . mysqli_error($conexion);
        }
    } else {
        echo "Error en la preparación de la consulta.";
    }
}
?>