<?php
include('conexion.php'); 
$orden_encontrada = null; // Cambiamos la variable a null para manejar mejor los datos
$mensaje_error = "";

if (isset($_GET['customer_num']) && isset($_GET['invoice_num'])) {
    $customer = $_GET['customer_num'];
    $invoice = $_GET['invoice_num'];

    // 1. Pedimos todas las columnas relevantes en el SELECT
    $sql = "SELECT status, company, date, address, unique_name, client_data 
            FROM ordenes 
            WHERE customer_number = ? AND invoice_number = ?";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $customer, $invoice);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $orden_encontrada = $fila; // Guardamos toda la fila con los datos
    } else {
        $mensaje_error = "No se encontró ninguna orden con esos datos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="barra"> 
        <a href="index.php" class="button"> Order Status </a>
        <a href="login.php" class="button"> Login Admin </a>
    </nav>

    <div class="order-status">
        <h1>View Order Status</h1>
        <form action="index.php" method="GET">
            <h2>Customer Number</h2>
            <input type="number" name="customer_num" required> 
            
            <h2>Invoice Number</h2>
            <input type="number" name="invoice_num" required><br>
            
            <button type="submit">Search</button>
        </form>

        <?php if ($mensaje_error !== ""): ?>
            <div class="mensaje-resultado" style="color: red; margin-top: 20px;">
                <p><?php echo $mensaje_error; ?></p>
            </div>
        <?php endif; ?>

        <?php if ($orden_encontrada): ?>
            <div class="mensaje-resultado" style="margin-top: 20px; padding: 20px; border: 2px solid #333; text-align: left; background: #fff;">
                <h2 style="text-align: center; color: #007bff;">Order Details</h2>
                <hr>
                <p><strong>Status:</strong> <?php echo $orden_encontrada['status']; ?></p>
                <p><strong>Date:</strong> <?php echo $orden_encontrada['date']; ?></p>
                <p><strong>Company:</strong> <?php echo $orden_encontrada['company']; ?></p>
                <p><strong>Product/Service:</strong> <?php echo $orden_encontrada['unique_name']; ?></p>
                <p><strong>Shipping Address:</strong> <?php echo $orden_encontrada['address']; ?></p>
                <p><strong>Notes:</strong> <?php echo $orden_encontrada['client_data']; ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>