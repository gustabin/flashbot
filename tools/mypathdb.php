<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

session_start();

// Cargar configuraciones desde .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function registrarActividad($user_id, $activity)
{
    // Conectar a la base de datos
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $mysqli->set_charset("utf8mb4");

    // Verificar si la conexión tuvo algún error
    if ($mysqli->connect_error) {
        die('Error de conexión: ' . $mysqli->connect_error);
    }

    // Obtener la sesión, dirección IP y user agent
    $session_id = session_id();
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    // Consulta preparada para insertar la actividad de sesión
    $query = "INSERT INTO session_activity (session_id, user_id, activity, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sisss', $session_id, $user_id, $activity, $ip_address, $user_agent);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

// Detectar el entorno (localhost o remoto)
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Configuración de la base de datos para entorno local
    if (!defined('DB_HOST')) {
        define('DB_HOST', getenv('DB_HOST_LOCAL'));
    }
    if (!defined('DB_USER')) {
        define('DB_USER', getenv('DB_USER_LOCAL'));
    }
    if (!defined('DB_PASSWORD')) {
        define('DB_PASSWORD', getenv('DB_PASSWORD_LOCAL'));
    }
    if (!defined('DB_NAME')) {
        define('DB_NAME', getenv('DB_NAME_LOCAL'));
    }
} elseif ($_SERVER['SERVER_NAME'] == 'chatpana.com' || $_SERVER['SERVER_NAME'] == 'www.chatpana.com') {
    // Configuración de la base de datos para entorno remoto
    if (!defined('DB_HOST')) {
        define('DB_HOST', getenv('DB_HOST_REMOTE'));
    }
    if (!defined('DB_USER')) {
        define('DB_USER', getenv('DB_USER_REMOTE'));
    }
    if (!defined('DB_PASSWORD')) {
        define('DB_PASSWORD', getenv('DB_PASSWORD_REMOTE'));
    }
    if (!defined('DB_NAME')) {
        define('DB_NAME', getenv('DB_NAME_REMOTE'));
    }
}

// Configuración del servidor SMTP
if (!defined('SMTP_USERNAME')) {
    define('SMTP_USERNAME', getenv('SMTP_USERNAME'));
}
if (!defined('SMTP_PASSWORD')) {
    define('SMTP_PASSWORD', getenv('SMTP_PASSWORD'));
}
if (!defined('SMTP_HOST')) {
    define('SMTP_HOST', getenv('SMTP_HOST'));
}
if (!defined('SMTP_PORT')) {
    define('SMTP_PORT', getenv('SMTP_PORT'));
}

// Clave secreta para JWT
if (!defined('SECRET_KEY')) {
    define('SECRET_KEY', getenv('SECRET_KEY'));
}
