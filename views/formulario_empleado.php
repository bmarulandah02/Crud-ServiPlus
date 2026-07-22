<?php
require_once '../models/MySQL.php';
session_start();

// Verificamos que exista la sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?error=sesion");
    exit();
}

// Verificamos que sea administrador
if (!isset($_SESSION['cargo_id']) || $_SESSION['cargo_id'] != 2) {
    header("Location: dashboard.php?error=permiso");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3>Registrar Nuevo Empleado</h3>
            </div>
            <div class="card-body">
                <form action="../controllers/insertar_empleado.php" method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Número de documento</label>
                        <input type="text" name="documento" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cargo</label>
                        <select name="cargo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="1">Técnico</option>
                            <option value="2">Administrador</option>
                            <option value="3">Operario</option>
                            <option value="4">Asistente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Departamento</label><br>
                        <input type="radio" name="departamento" value="1" required> Electricidad
                        <input type="radio" name="departamento" value="2"> Mantenimiento
                        <input type="radio" name="departamento" value="3"> Recursos Humanos
                        <input type="radio" name="departamento" value="4"> Contabilidad
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de ingreso</label>
                        <input type="date" name="fecha_ingreso" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Salario base</label>
                        <input type="number" name="salario" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto del empleado</label>
                        <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <a href="dashboard.php" class="btn btn-secondary">Volver</a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>