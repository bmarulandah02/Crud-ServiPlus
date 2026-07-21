<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - ServiPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>Panel de Usuario</h3>
            </div>
            <div class="card-body">
                <h4 class="mb-3">Bienvenido, <?php echo $_SESSION['nombre']; ?> </h4>
                <p><strong>Correo:</strong> <?php echo $_SESSION['correo']; ?></p>

                <?php if ($_SESSION['cargo_id'] == 2): // Supongamos que 2 = Administrador ?>
                <p><strong>Rol:</strong> Administrador</p>
                <a href="formulario_empleado.php" class="btn btn-success">Agregar Empleado</a>
                <a href="ver_empleados.php" class="btn btn-info">Ver/Editar Empleados</a>
                <?php else: ?>
                <p><strong>Área:</strong> <?php echo $_SESSION['departamento_nombre']; ?></p>
                <p><strong>Cargo:</strong> <?php echo $_SESSION['cargo_nombre']; ?></p>
                <p class="text-muted">No tienes permisos para modificar empleados.</p>
                <?php endif; ?>

                <a href="../controllers/logout.php" class="btn btn-danger mt-3">Cerrar sesión</a>
            </div>
        </div>
    </div>
    <?php var_dump($_SESSION['cargo_id']);
?>
</body>

</html>