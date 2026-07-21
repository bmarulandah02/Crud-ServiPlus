<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - ServiPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }

    .login-container {
        max-width: 900px;
        width: 100%;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .left-panel {
        background-color: #004d40;
        /* Verde oscuro */
        color: white;
        padding: 40px;
        text-align: center;
    }

    .left-panel h1 {
        font-size: 3rem;
        font-weight: bold;
    }

    .left-panel p {
        margin-top: 20px;
        font-size: 1.2rem;
    }

    .right-panel {
        background-color: white;
        padding: 40px;
    }
    </style>
</head>

<body>
    <div class="login-container row">

        <div class="col-md-6 left-panel d-flex flex-column justify-content-center">
            <h1>S+</h1>
            <h3>GESTIÓN DE TALENTO HUMANO</h3>
            <p>ServiPlus</p>
            <p>Acceso seguro al registro centralizado de empleados.</p>
        </div>

        <div class="col-md-6 right-panel">
            <h3 class="mb-4">Iniciar sesión</h3>
            <p class="text-muted">Usa tu documento y contraseña asignada.</p>
            <form action="../controllers/login.php" method="post">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Ingresar</button>
            </form>
        </div>
    </div>
</body>

</html>