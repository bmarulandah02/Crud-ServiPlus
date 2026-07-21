<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - ServiPlus</title>
</head>

<body>
    <h2>Iniciar Sesión</h2>
    <form action="../controllers/login.php" method="post">
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Ingresar</button>
    </form>


</body>

</html>