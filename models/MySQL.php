<?php
// Clase para manejar la conexión a la base de datos con MySQLi
class MySQL {

    // Variable para almacenar la conexión
    private $conexion;

    // Método para conectar a la base de datos
    public function conectar() {
        // Configuración de Laragon
        $host = "localhost";      // Servidor
        $usuario = "root";        // Usuario por defecto en Laragon
        $contrasena = "";         // Contraseña (vacía en Laragon)
        $basedatos = "serviplus"; // Nombre de tu base de datos en MySQL

        // Crear conexión
        $this->conexion = new mysqli($host, $usuario, $contrasena, $basedatos);

        // Verificar errores
        if ($this->conexion->connect_errno) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        // Configurar charset para acentos y ñ
        $this->conexion->set_charset("utf8mb4");
    }

    // Retorna la conexión activa
    public function getConexion() {
        return $this->conexion;
    }

    // Cierra la conexión
    public function desconectar() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>