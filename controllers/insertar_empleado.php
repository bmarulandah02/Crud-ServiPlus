<?php
require_once '../models/MySQL.php';
session_start();

// Verificamos que exista la sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php?error=sesion");
    exit();
}

if (!isset($_SESSION['cargo_id']) || $_SESSION['cargo_id'] != 2) {
    header("Location: ../views/dashboard.php?error=permiso");
    exit();
}

// Si llegaste aquí, eres administrador y puedes continuar...


// 🔹 Validamos que se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysql = new MySQL();
    $mysql->conectar();
    $conn = $mysql->getConexion();

    // Recibimos los datos del formulario
    $nombre = trim($_POST['nombre']);
    $documento = trim($_POST['documento']);
    $cargo_id = intval($_POST['cargo']);
    $departamento_id = intval($_POST['departamento']);
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $salario = floatval($_POST['salario']);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $telefono = trim($_POST['telefono']);
    $passwordPlano = $_POST['password'];

    // 🔹 Encriptamos la contraseña
    $passwordHash = password_hash($passwordPlano, PASSWORD_BCRYPT);

    // 🔹 Manejo de la foto (opcional)
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nombreFoto = time() . "_" . basename($_FILES['foto']['name']);
        $rutaDestino = "../uploads/" . $nombreFoto;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            $foto = $nombreFoto;
        }
    }

    // 🔹 Insertamos el empleado
    $sql = "INSERT INTO empleados 
            (nombre, documento, cargo_id, departamento_id, fecha_ingreso, salario, correo, telefono, password, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiissssss", 
        $nombre, 
        $documento, 
        $cargo_id, 
        $departamento_id, 
        $fecha_ingreso, 
        $salario, 
        $correo, 
        $telefono, 
        $passwordHash, 
        $foto
    );

    if ($stmt->execute()) {
        header("Location: ../views/dashboard.php?registrado=1");
        exit();
    } else {
        echo "❌ Error al registrar empleado: " . $conn->error;
    }

    $mysql->desconectar();
} else {
    header("Location: ../views/formulario_empleado.php");
    exit();
}
?>