<?php
// ====================================================================
// Configuración de la conexión a la base de datos
// Nivel: 4to semestre - ADSO / Ingeniería de Software
// ====================================================================

function obtenerConexion() {
    $servidor    = 'localhost';
    $base_datos  = 'levelup_games';
    $usuario     = 'root';
    $contrasena  = '';
    $puerto      = 3306;

    try {
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        $conexion = new PDO("mysql:host=$servidor;port=$puerto;dbname=$base_datos", $usuario, $contrasena, $opciones);
        return $conexion;
    } catch (PDOException $error) {
        die("Error al conectar con la base de datos: " . $error->getMessage());
    }
}
