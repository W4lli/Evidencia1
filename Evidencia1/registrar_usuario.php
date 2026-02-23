<form action="procesar_registro.php" method="POST" class="login">
    <input type="text" name="nuevo_user" placeholder="Nombre de usuario" required><br>
    <input type="password" name="nuevo_pass" placeholder="Contraseña" required><br>
    <select name="rol">
        <?php
        $roles = mysqli_query($conexion, "SELECT * FROM roles");
        while($r = mysqli_fetch_assoc($roles)) {
            echo "<option value='".$r['id']."'>".$r['rol']."</option>";
        }
        ?>
    </select><br>
    <button type="submit">Crear Usuario</button>
</form>
