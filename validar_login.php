<?php
session_start(); // Inicia la sesión para "recordar" al admin
include('conexion.php');

$user = $_POST['usuario'];
$pass = $_POST['password'];

// Buscamos al usuario en la tabla login
// Usamos "JOIN" para asegurarnos de que el rol sea 'admin'
$sql = "SELECT l.id, l.password, r.rol 
        FROM login l 
        INNER JOIN roles r ON l.role_id = r.id 
        WHERE l.username = ? AND r.rol = 'Administrador'"; 

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $user);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($datos = mysqli_fetch_assoc($resultado)) {
    // Verificamos si la contraseña coincide (usando la encriptación segura)
    if (password_verify($pass, $datos['password'])) {
        $_SESSION['admin_logeado'] = true;
        $_SESSION['nombre_admin'] = $user;
        
        // Si todo está bien, mandamos al admin a su panel
        header("Location: admin_panel.php");
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado o no es administrador.";
}
?>