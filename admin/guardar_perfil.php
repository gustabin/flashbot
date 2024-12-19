<?php

require '../tools/mypathdb.php';
require('../mail/index.php');

$response = array();
try {
    if (($_SERVER['REQUEST_METHOD'] === 'POST') and ($_SESSION['token'])) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $mysqli->set_charset("utf8mb4");

        if ($mysqli->connect_error) {
            throw new Exception('Error de Conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }

        if (!isset($_POST['website'], $_POST['typeDB'])) {
            $response['success'] = false;
            $response['status'] = 'error';
            $response['message'] = 'Los campos con * son requeridos.';
            echo json_encode($response);
            exit;
        }

        $email = filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            throw new Exception('Email inválido.');
        }

        $website = htmlspecialchars(trim($_POST['website'])); // Sanitiza el input
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            throw new Exception('Website inválido.');
        }

        $typeDB = htmlspecialchars(trim($_POST['typeDB']));
        $validTypeDB = ['mysql', 'postgresql', 'sqlite', 'oracle', 'sqlserver', 'mongodb', 'texto'];
        if (!in_array($typeDB, $validTypeDB, true)) {
            throw new Exception('Tipo de base de datos inválido.');
        }

        $token = $_SESSION['token'];

        $hostDB = isset($_POST['hostDB']) ? htmlspecialchars(trim($_POST['hostDB'])) : null;
        $userDB = isset($_POST['userDB']) ? htmlspecialchars(trim($_POST['userDB'])) : null;
        $passwordDB = isset($_POST['passwordDB']) ? htmlspecialchars(trim($_POST['passwordDB'])) : null;
        $databaseDB = isset($_POST['databaseDB']) ? htmlspecialchars(trim($_POST['databaseDB'])) : null;
        $portDB = isset($_POST['portDB']) ? htmlspecialchars(trim($_POST['portDB'])) : null;
        $ssl_enabledDB = isset($_POST['ssl_enabledDB']) ? htmlspecialchars(trim($_POST['ssl_enabledDB'])) : null;
        $charsetDB = isset($_POST['charsetDB']) ? htmlspecialchars(trim($_POST['charsetDB'])) : null;

        $api_key = bin2hex(random_bytes(15));

        $_SESSION['api_key'] = $api_key;
        if (isset($passwordDB) && !empty($passwordDB)) {
            $hashedPassword = password_hash($passwordDB, PASSWORD_DEFAULT);
        }

        $contenido = "";
        $query = 'UPDATE user SET website = ?, api_key = ?, hostDB = ?, userDB = ?, passwordDB = ?, databaseDB = ?, typeDB = ?, portDB = ?, ssl_enabledDB = ?, charsetDB = ?, contenido =?, activo = 1  WHERE email = ? AND token = ?';
        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $mysqli->error);
        }

        $stmt->bind_param('sssssssssssss', $website, $api_key, $hostDB, $userDB, $hashedPassword, $databaseDB, $typeDB, $portDB, $ssl_enabledDB, $charsetDB, $contenido, $email, $token);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response['status'] = 'exito';
        } else {
            throw new Exception('Error al registrar la base de datos: No se realizaron cambios.');
        }

        $_SESSION['website'] = $website;
        $_SESSION['hostDB'] = $hostDB;
        $_SESSION['userDB'] = $userDB;
        $_SESSION['passwordDB'] = $passwordDB;
        $_SESSION['databaseDB'] = $databaseDB;
        $_SESSION['typeDB'] = $typeDB;
        $_SESSION['portDB'] = $portDB;
        $_SESSION['ssl_enabledDB'] = $ssl_enabledDB;
        $_SESSION['charsetDB'] = $charsetDB;
        $_SESSION['contenido'] = $contenido;
        $_SESSION['token'] = $token;

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
