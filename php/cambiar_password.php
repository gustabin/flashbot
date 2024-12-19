<?php

require '../tools/mypathdb.php';

// Configurar para que mysqli use excepciones
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$response = array();
try {
    // Conectar a la base de datos MySQL
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $mysqli->set_charset("utf8mb4");

    // Verificar si la conexión tuvo algún error
    if ($mysqli->connect_error) {
        throw new Exception('Error de conexión: ' . $mysqli->connect_error);
    }

    // Obtener el cuerpo de la solicitud en formato JSON
    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

    // Validar si se pudieron obtener los datos correctamente
    $token = $data['token'];
    $password = $data['password'];
    $retipearPassword = $data['retipearPassword'];

    if ($password != $retipearPassword) {
        throw new Exception('Las contraseñas no son iguales.');
    }

    // Validar la entrada
    if (!$password || !$retipearPassword) {
        throw new Exception('El campo de contraseña no puede estar vacío.');
    }

    // Buscar el usuario y actualizar el password
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE user SET passwordhash = ? WHERE tokenRecuperacion = ?";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception('Error en la preparación de la consulta: ' . $mysqli->error);
    }
    $stmt->bind_param('ss', $passwordhash, $token);
    $stmt->execute();

    // Verificar si se actualizó correctamente
    if ($stmt->affected_rows === 0) {
        throw new Exception('No se pudo actualizar la contraseña.');
    }
    $response['success'] = true;
    $response['message'] = 'Contraseña actualizada correctamente';
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} finally {
    // Cerrar la conexión
    if (isset($mysqli)) {
        $mysqli->close();
    }
    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
