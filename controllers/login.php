<?php
// Importamos la clase de conexión
require_once '../models/MySQL.php';

// Iniciamos la sesión
session_start();

// Validamos que se hayan enviado los campos
if (isset($_POST['correo'], $_POST['password']) && !empty($_POST['correo']) && !empty($_POST['password'])) {
    
    // Creamos instancia de la clase MySQL
    $mysql = new MySQL();
    $mysql->conectar();
    $conn = $mysql->getConexion();

    // Sanitizamos el correo
    $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Consulta con JOIN para traer cargo y departamento
    $sql = "SELECT e.*, c.nombre_car AS cargo_nombre, d.nombre_dep AS departamento_nombre
            FROM empleados e
            INNER JOIN cargos c ON e.cargo_id = c.id_car
            INNER JOIN departamentos d ON e.departamento_id = d.id_dep
            WHERE e.correo = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($usuario = $resultado->fetch_assoc()) {
        // Verificamos la contraseña encriptada
        if (password_verify($password, $usuario['password'])) {
            // Guardamos datos en la sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['cargo_id'] = $usuario['cargo_id'];
            $_SESSION['cargo_nombre'] = $usuario['cargo_nombre'];
            $_SESSION['departamento_nombre'] = $usuario['departamento_nombre'];
            $_SESSION['foto'] = $usuario['foto']; 


            // Redirigimos al dashboard
            header("Location: ../views/dashboard.php");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: ../views/login.php?error=1");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: ../views/login.php?error=1");
        exit();
    }

    $mysql->desconectar();
} else {
    // Si los campos están vacíos
    header("Location: ../views/login.php?error=1");
    exit();
}
?>