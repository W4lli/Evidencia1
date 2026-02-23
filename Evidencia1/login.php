<?php
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <nav class="barra"> 
        <a href="index.php" class="button"> Order Status </a>
        <a href="login.php" class="button"> Login Admin </a>
    </nav>
</head>
<body>
    <div class="login">
        <h1>Administrator Login</h1>
        <form action="validar_login.php" method="POST">
            <h2>User</h2>
            <input type="text" name="usuario" required> 
            
            <h2>Password</h2>
            <input type="password" name="password" required><br>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>