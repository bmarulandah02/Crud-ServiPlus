<?php
require_once '../models/MySQL.php';
session_start();

// Verificamos que exista la sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$mysql = new MySQL();
$mysql->conectar();
$conn = $mysql->getConexion();

// Traemos todos los datos del usuario según su ID
$idUsuario = $_SESSION['usuario_id'];
$sql = "SELECT e.nombre, e.correo, e.foto, c.nombre_car AS cargo
        FROM empleados e
        INNER JOIN cargos c ON e.cargo_id = c.id_car
        WHERE e.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$foto = $usuario['foto'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow text-center" style="max-width: 500px; margin:auto;">
            <div class="card-header bg-dark text-white">
                <h4>Panel de Usuario</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($foto)): ?>
                <img src="../assets/fotos_empleados/<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil"
                    width="120" height="120" class="rounded-circle mb-3">
                <?php else: ?>
                <img src="../assets/fotos_empleados/default.png" alt="Sin foto" width="120" height="120"
                    class="rounded-circle mb-3">
                <?php endif; ?>

                <h5>Bienvenido, <?php echo $usuario['nombre']; ?></h5>
                <p><strong>Correo:</strong> <?php echo $usuario['correo']; ?></p>
                <p><strong>Rol:</strong> <?php echo $usuario['cargo']; ?></p>

                <hr>
                <a href="../controllers/insertar_empleado.php" class="btn btn-success">Agregar Empleado</a>
                <a href="ver_empleados.php" class="btn btn-primary">Ver/Editar Empleados</a>
                <a href="../controllers/logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </div>

</body>

</html>