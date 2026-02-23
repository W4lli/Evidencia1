<?php
include('conexion.php');

$customer = $_GET['customer_num'];
$invoice = $_GET['invoice_num'];

// Buscamos la orden que coincida con AMBOS números
$sql = "SELECT status FROM ordenes WHERE customer_number = ? AND invoice_number = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ii", $customer, $invoice);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($fila = mysqli_fetch_assoc($resultado)) {
    echo "<h1>El estado de su orden es: " . $fila['status'] . "</h1>";
} else {
    echo "<h1>No se encontró ninguna orden con esos datos.</h1>";
}

echo '<br><a href="index.php">Volver</a>';
?>