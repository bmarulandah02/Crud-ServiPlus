<?php
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['cargo_id']) || $_SESSION['cargo_id'] != 2) {
    header("Location: ../views/dashboard.php?error=permiso");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $mysql = new MySQL();
    $mysql->conectar();
    $conn = $mysql->getConexion();

    // 1. Buscar la foto del empleado
    $sql = "SELECT foto FROM empleados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado = $result->fetch_assoc();

    if ($empleado && !empty($empleado['foto'])) {
        $rutaFoto = "../assets/fotos_empleados/" . $empleado['foto'];
        if (file_exists($rutaFoto)) {
            unlink($rutaFoto); // 👈 elimina la foto del servidor
        }
    }

    // 2. Eliminar el registro del empleado
    $sql = "DELETE FROM empleados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // 3. Redirigir de vuelta a la lista
    header("Location: ../views/dashboard.php");
    exit();
} else {
    header("Location: ../views/dashboard.php");
    exit();
}