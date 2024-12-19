<?php

require '../tools/mypathdb.php';

$response = array();
try {
    if (($_SERVER['REQUEST_METHOD'] === 'POST') and ($_SESSION['token'])) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $mysqli->set_charset("utf8mb4");

        if ($mysqli->connect_error) {
            throw new Exception('Error de Conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }

        if (!isset($_POST['website'])) {
            $response['success'] = false;
            $response['status'] = 'error';
            $response['message'] = 'El website es requerido.';
            echo json_encode($response);
            exit;
        }

        if (isset($_SESSION['email'])) {
            $email = filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                throw new Exception('Email inválido.');
            }
        } else {
            throw new Exception('Email no definido en la sesión.');
        }


        $website = htmlspecialchars(trim($_POST['website'])); // Sanitiza el input
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            throw new Exception('Website inválido.');
        }

        $typeDB = 'texto';

        $token = $_SESSION['token'];

        $contenido = isset($_POST['contenido']) ? htmlspecialchars(trim($_POST['contenido'])) : null;

        $api_key = bin2hex(random_bytes(15));

        $_SESSION['api_key'] = $api_key;

        $hostDB = "";
        $userDB = "";
        $passwordDB = "";
        $databaseDB = "";
        $portDB = "";
        $ssl_enabledDB = "";
        $charsetDB = "";

        $query = 'UPDATE user SET hostDB = ?, userDB = ?, passwordDB = ?, databaseDB = ?, portDB = ?, ssl_enabledDB = ?, charsetDB = ?, website = ?, api_key = ?, typeDB = ?, contenido = ?, activo = 1  WHERE email = ? AND token = ?';
        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $mysqli->error);
        }

        $stmt->bind_param('ssssissssssss', $hostDB, $userDB, $passwordDB, $databaseDB, $portDB, $ssl_enabledDB, $charsetDB, $website, $api_key, $typeDB, $contenido, $email, $token);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response['status'] = 'exito';
        } else {
            throw new Exception('Error al registrar el contenido: No se realizaron cambios.');
        }
        $stmt->close();
        $mysqli->close();
    }
} catch (Exception $e) {
    error_log("Error al registrar la cuenta: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = 'Error al registrar la cuenta: ' . $e->getMessage();
}


header('Content-Type: application/json');
echo json_encode($response);
