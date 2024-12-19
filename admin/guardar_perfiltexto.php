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

        if (!isset($_POST['websiteTexto'])) {
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

        $website = htmlspecialchars(trim($_POST['websiteTexto'])); // Sanitiza el input
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            throw new Exception('Website inválido.');
        }

        $token = $_SESSION['token'];

        $contenido = isset($_POST['contenido']) ? htmlspecialchars(trim($_POST['contenido'])) : null;

        if (isset($passwordDB) && !empty($passwordDB)) {
            $hashedPassword = password_hash($passwordDB, PASSWORD_DEFAULT);
        }

        $hostDB = "";
        $userDB = "";
        $passwordDB = "";
        $databaseDB = "";
        $portDB = "";
        $ssl_enabledDB = "";
        $charsetDB = "";
        $typeDB = "texto";

        $query = 'UPDATE user SET website = ?, hostDB = ?, userDB = ?, passwordDB = ?, databaseDB = ?, typeDB = ?, portDB = ?, ssl_enabledDB = ?, charsetDB = ?, contenido =?, activo = 1  WHERE email = ? AND token = ?';
        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $mysqli->error);
        }

        $stmt->bind_param('ssssssssssss', $website, $hostDB, $userDB, $hashedPassword, $databaseDB, $typeDB, $portDB, $ssl_enabledDB, $charsetDB, $contenido, $email, $token);
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
