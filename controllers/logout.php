<?php
// Iniciamos la sesión
session_start();

// Eliminamos todas las variables de sesión
session_unset();

// Destruimos la sesión
session_destroy();

// Redirigimos al login
header("Location: ../views/login.php");
exit();
?>