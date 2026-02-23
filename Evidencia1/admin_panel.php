<?php
session_start();
include('conexion.php');

// 1. Candado de seguridad: Solo entra si está logeado
if (!isset($_SESSION['admin_logeado'])) {
    header("Location: login.php");
    exit();
}

// 2. Lógica de Navegación (Pestañas)
$view = isset($_GET['view']) ? $_GET['view'] : 'ordenes';

// 3. Procesar Actualización de Status (Si se envía desde la tabla)
if (isset($_POST['actualizar_status'])) {
    $id_orden = $_POST['id_orden'];
    $nuevo_status = $_POST['nuevo_status'];
    $sql_update = "UPDATE ordenes SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql_update);
    mysqli_stmt_bind_param($stmt, "si", $nuevo_status, $id_orden);
    mysqli_stmt_execute($stmt);
    header("Location: admin_panel.php?view=ordenes&msg=updated");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Proyecto Halcón</title>
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <nav class="barra"> 
        <a href="index.php" class="button"> Order Status </a>
        <a href="logout.php" class="button"> Cerrar Sesión </a>
    </nav>

    <div class="admin-nav">
        <a href="admin_panel.php?view=ordenes" class="<?php echo $view == 'ordenes' ? 'active-tab' : ''; ?>">📦 Ver Órdenes</a>
        <a href="admin_panel.php?view=ventas" class="<?php echo $view == 'ventas' ? 'active-tab' : ''; ?>">➕ Nueva Venta</a>
        <a href="admin_panel.php?view=registrar" class="<?php echo $view == 'registrar' ? 'active-tab' : ''; ?>">👤 Registrar Usuario</a>
        <a href="admin_panel.php?view=usuarios" class="<?php echo $view == 'usuarios' ? 'active-tab' : ''; ?>">👥 Ver Usuarios</a>
    </div>

    <div class="admin-container">
        
        <?php if ($view == 'ordenes'): ?>
            <h1 style="text-align:center;">Gestión de Órdenes</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer #</th>
                        <th>Invoice #</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $resultado = mysqli_query($conexion, "SELECT * FROM ordenes ORDER BY id DESC");
                    while ($orden = mysqli_fetch_assoc($resultado)): 
                    ?>
                    <tr>
                        <td><?php echo $orden['id']; ?></td>
                        <td><?php echo $orden['customer_number']; ?></td>
                        <td><?php echo $orden['invoice_number']; ?></td>
                        <td><?php echo $orden['company']; ?></td> 
                        <td><?php echo $orden['date']; ?></td>
                        <td><strong><?php echo $orden['status']; ?></strong></td>
                        <td>
                            <form action="admin_panel.php" method="POST">
                                <input type="hidden" name="id_orden" value="<?php echo $orden['id']; ?>">
                                <select name="nuevo_status">
                                    <option value="Ordered" <?php if($orden['status'] == 'Ordered') echo 'selected'; ?>>Ordered</option>
                                    <option value="In Process" <?php if($orden['status'] == 'In Process') echo 'selected'; ?>>In Process</option>
                                    <option value="In Route" <?php if($orden['status'] == 'In Route') echo 'selected'; ?>>In Route</option>
                                    <option value="Delivered" <?php if($orden['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                </select>
                                <button type="submit" name="actualizar_status">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($view == 'ventas'): ?>
            <div class="form-container">
                <h2>Registrar Nueva Venta</h2>
                <form action="guardar_venta.php" method="POST">
                    <input type="number" name="customer_number" placeholder="Customer #" required>
                    <input type="number" name="invoice_number" placeholder="Invoice #" required>
                    <input type="text" name="company" placeholder="Company Name">
                    <input type="text" name="unique_name" placeholder="Unique Order Name" required>
                    <textarea name="client_data" placeholder="Client Details" rows="3"></textarea>
                    <textarea name="address" placeholder="Shipping Address" rows="3"></textarea>
                    <button type="submit" class="button">Guardar Orden</button>
                </form>
            </div>

        <?php elseif ($view == 'registrar'): ?>
            <div class="form-container">
                <h2>Crear Nuevo Usuario</h2>
                <form action="procesar_registro.php" method="POST">
                    <input type="text" name="nuevo_user" placeholder="Username" required>
                    <input type="password" name="nuevo_pass" placeholder="Password" required>
                    <select name="rol">
                        <?php
                        $roles = mysqli_query($conexion, "SELECT * FROM roles");
                        while($r = mysqli_fetch_assoc($roles)) {
                            echo "<option value='".$r['id']."'>".$r['rol']."</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Crear Usuario</button>
                </form>
            </div>

        <?php elseif ($view == 'usuarios'): ?>
            <h1 style="text-align:center;">Usuarios Registrados</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                </tr>
                <?php 
                $usuarios = mysqli_query($conexion, "SELECT l.id, l.username, r.rol FROM login l JOIN roles r ON l.role_id = r.id");
                while ($u = mysqli_fetch_assoc($usuarios)): 
                ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo $u['username']; ?></td>
                    <td><?php echo $u['rol']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>

    </div>
</body>
</html>