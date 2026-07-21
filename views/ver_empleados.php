<?php
require_once '../models/MySQL.php';
session_start();

// Verificamos que exista la sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificamos que sea administrador
if ((int)$_SESSION['cargo_id'] != 2) {
    header("Location: dashboard.php?error=permiso");
    exit();
}

// Conexión a la base de datos
$mysql = new MySQL();
$mysql->conectar();
$conn = $mysql->getConexion();

// Consulta empleados con JOIN para mostrar cargo y departamento
$sql = "SELECT e.id, e.nombre, e.documento, e.correo, e.telefono, e.fecha_ingreso, e.salario,
               e.foto, c.nombre_car AS cargo, d.nombre_dep AS departamento
        FROM empleados e
        INNER JOIN cargos c ON e.cargo_id = c.id_car
        INNER JOIN departamentos d ON e.departamento_id = d.id_dep";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h3>Lista de Empleados</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Fecha Ingreso</th>
                            <th>Salario</th>
                            <th>Cargo</th>
                            <th>Departamento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($empleado = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $empleado['id']; ?></td>
                            <td>
                                <?php if (!empty($empleado['foto'])): ?>
                                <img src="../uploads/<?php echo $empleado['foto']; ?>" alt="Foto" width="60" height="60"
                                    class="rounded-circle">
                                <?php else: ?>
                                <span class="text-muted">Sin foto</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $empleado['nombre']; ?></td>
                            <td><?php echo $empleado['documento']; ?></td>
                            <td><?php echo $empleado['correo']; ?></td>
                            <td><?php echo $empleado['telefono']; ?></td>
                            <td><?php echo $empleado['fecha_ingreso']; ?></td>
                            <td><?php echo $empleado['salario']; ?></td>
                            <td><?php echo $empleado['cargo']; ?></td>
                            <td><?php echo $empleado['departamento']; ?></td>
                            <td>
                                <a href="editar_empleado.php?id=<?php echo $empleado['id']; ?>"
                                    class="btn btn-warning btn-sm">Editar</a>
                                <a href="../controllers/eliminar_empleado.php?id=<?php echo $empleado['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Seguro que deseas eliminar este empleado?');">Eliminar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
            </div>
        </div>
    </div>

</body>

</html>
<?php
$mysql->desconectar();
?>