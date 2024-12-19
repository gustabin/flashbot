<?php
require '../tools/mypathdb.php';

// Configurar para que mysqli use excepciones
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Verificar si se ha proporcionado un token válido en la URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conexión a la base de datos (usando MySQLi)
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $mysqli->set_charset("utf8mb4");

    // Verificar errores de conexión
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }

    // Consulta preparada para actualizar el campo Verificado a true
    $query = "UPDATE user SET verificado = 1 WHERE token = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $token);
    $stmt->execute();


    try {
        // Verificar si la actualización fue exitosa
        if ($stmt->affected_rows > 0) {
            echo '<div id="success-message">Tu dirección de correo electrónico ha sido verificada correctamente.</div>';
            echo '<script>
                    // Redirigir a login.php después de 3 segundos
                    setTimeout(function(){
                        window.location.href = "../login.html";
                    }, 3000);
                  </script>';
            exit();
        } else {
            echo 'No se pudo verificar tu dirección de correo electrónico. Por favor, verifica si el enlace es válido o si ya ha sido utilizado.';
        }
    } catch (Exception $e) {
        echo "Error al insertar el registro en la tabla: " . $e->getMessage();
    }

    // Cerrar la conexión
    $stmt->close();
    $mysqli->close();
} else {
    // En caso de que no se haya proporcionado un token válido en la URL
    echo 'Token no válido.';
    // Redirigir a una página de error
    header('Location: error.php');
    exit();
}
