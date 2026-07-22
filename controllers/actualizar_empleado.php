<?php
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['cargo_id']) || $_SESSION['cargo_id'] != 2) {
    header("Location: dashboard.php?error=permiso");
    exit();
}

$mysql = new MySQL();
$mysql->conectar();
$conn = $mysql->getConexion();

$id = intval($_GET['id']);
$sql = "SELECT * FROM empleados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$empleado = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $salario = floatval($_POST['salario']);
    $fecha_ingreso = $_POST['fecha_ingreso'];

    $passwordPlano = $_POST['password'];
    $passwordHash = !empty($passwordPlano) ? password_hash($passwordPlano, PASSWORD_BCRYPT) : $empleado['password'];

   $foto = $empleado['foto'];
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $nombreFoto = time() . "_" . basename($_FILES['foto']['name']);
    $rutaDestino = __DIR__ . "/../assets/fotos_empleados/" . $nombreFoto;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
        $foto = $nombreFoto; 
    } else {
        echo "❌ Error al subir la foto.";
    }
}

    $update = "UPDATE empleados 
               SET nombre=?, correo=?, telefono=?, salario=?, fecha_ingreso=?, password=?, foto=? 
               WHERE id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssdsssi", $nombre, $correo, $telefono, $salario, $fecha_ingreso, $passwordHash, $foto, $id);

    if ($stmt->execute()) {
        header("Location: ../views/ver_empleados.php?actualizado=1");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h3>Actualizar Empleado</h3>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo $empleado['nombre']; ?>"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Correo</label>
                        <input type="email" name="correo" class="form-control"
                            value="<?php echo $empleado['correo']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                            value="<?php echo $empleado['telefono']; ?>">
                    </div>
                    <div class="mb-3">
                        <label>Salario</label>
                        <input type="number" step="0.01" name="salario" class="form-control"
                            value="<?php echo $empleado['salario']; ?>">
                    </div>
                    <div class="mb-3">
                        <label>Fecha de Ingreso</label>
                        <input type="date" name="fecha_ingreso" class="form-control"
                            value="<?php echo $empleado['fecha_ingreso']; ?>">
                    </div>
                    <div class="mb-3">
                        <label>Nueva Contraseña (opcional)</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Si no quieres cambiarla, deja este campo vacío.</small>
                    </div>
                    <div class="mb-3">
                        <label>Foto</label><br>
                        <?php if (!empty($empleado['foto'])): ?>
                        <img src="../assets/fotos empleados/<?php echo htmlspecialchars($empleado['foto']); ?>"
                            alt="Foto actual" width="80" class="mb-2 rounded">
                        <?php endif; ?>
                        <input type="file" name="foto" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                    <a href="ver