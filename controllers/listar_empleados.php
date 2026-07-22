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

// Filtro de búsqueda
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : "";

$sql = "SELECT e.id, e.nombre, e.documento, e.correo, e.telefono, e.fecha_ingreso, 
               e.salario, e.estado, e.foto, c.nombre_car AS cargo, d.nombre_dep AS departamento
        FROM empleados e
        INNER JOIN cargos c ON e.cargo_id = c.id_car
        INNER JOIN departamentos d ON e.departamento_id = d.id_dep";

if (!empty($buscar)) {
    $sql .= " WHERE e.nombre LIKE ? OR e.documento LIKE ? OR e.correo LIKE ?";
    $stmt = $conn->prepare($sql);
    $like = "%$buscar%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$empleados = $result->fetch_all(MYSQLI_ASSOC);
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
            <div class="card-header bg-dark text-white text-center">
                <h4>Lista de Empleados</h4>
            </div>
            <div class="card-body">

                <!-- Barra de búsqueda -->
                <form class="d-flex mb-3" method="GET" action="listar_empleados.php">
                    <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar empleado..."
                        value="<?php echo htmlspecialchars($buscar); ?>">
                    <button class="btn btn-outline-primary">Buscar</button>
                </form>

                <table class="table table-striped text-center">
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
                            <th>Estado</th>
                            <?php if ($_SESSION['cargo'] === 'Administrador'): ?>
                            <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados as $empleado): ?>
                        <tr>
                            <td><?php echo $empleado['id']; ?></td>
                            <td>
                                <?php if (!empty($empleado['foto'])): ?>
                                <img src="../assets/fotos_empleados/<?php echo htmlspecialchars($empleado['foto']); ?>"
                                    alt="Foto" width="60" height="60" class="rounded-circle">
                                <?php else: ?>
                                <img src="../assets/fotos_empleados/default.png" alt="Sin foto" width="60" height="60"
                                    class="rounded-circle">
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
                                <?php if ($empleado['estado'] === 'Activo'): ?>
                                <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <?php if ($_SESSION['cargo'] === 'Administrador'): ?>
                            <td>
                                <a href="actualizar_empleado.php?id=<?php echo $empleado['id']; ?>"
                                    class="btn btn-warning">Actualizar</a>
                                <a href="../controllers/eliminar_empleado.php?id=<?php echo $empleado['id']; ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">
                                    Eliminar
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="text-center mt-3">
                    <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
                    <?php if ($_SESSION['cargo'] === 'Administrador'): ?>
                    <a href="../controllers/insertar_empleado.php" class="btn btn-success">Agregar Empleado</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>