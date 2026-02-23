<?php
session_start();
include('conexion.php');

// Verificamos sesión
if (!isset($_SESSION['admin_logeado'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibimos los datos del formulario (ajusta los nombres si cambiaste el HTML)
    $c_num       = $_POST['customer_number'];
    $i_num       = $_POST['invoice_number'];
    $c_data      = $_POST['client_data'];
    $company     = $_POST['company'];
    $address     = $_POST['address'];
    $u_name      = $_POST['unique_name'];
    $status_init = "Ordered"; // Status por defecto al crearla

    // Preparamos la consulta con tus columnas exactas
    $sql = "INSERT INTO ordenes (customer_number, invoice_number, status, client_data, company, date, address, unique_name) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql);
    
    // "iisssss" -> int, int, string, string, string, string, string (la fecha se pone con NOW())
    mysqli_stmt_bind_param($stmt, "iisssss", $c_num, $i_num, $status_init, $c_data, $company, $address, $u_name);

    if (mysqli_stmt_execute($stmt)) {
        // Redirigir al panel de control con éxito
        header("Location: admin_panel.php?view=ordenes&status=created");
    } else {
        echo "Error en la base de datos: " . mysqli_error($conexion);
    }
}
?>
<div class="login">
    <h2>New Order Entry</h2>
    <form action="guardar_venta.php" method="POST">
        <input type="number" name="customer_number" placeholder="Customer #" required><br>
        <input type="number" name="invoice_number" placeholder="Invoice #" required><br>
        <input type="text" name="company" placeholder="Company Name"><br>
        <input type="text" name="unique_name" placeholder="Unique Order Name" required><br>
        <textarea name="client_data" placeholder="Client Details"></textarea><br>
        <textarea name="address" placeholder="Shipping Address"></textarea><br>
        
        <button type="submit" class="button">Save Order</button>
    </form>
</div>