<?php

require '../tools/mypathdb.php';
require('../mail/index.php');

$response = array();
try {
    // echo 'Hola';
    // print_r("entro por aqui");
    // $response = ['debug' => $hashedPassword];
    // echo json_encode($response);
    // exit;
    // var_dump($email);
    // die();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Configurar para que mysqli use excepciones
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        // Crear conexión a la base de datos MySQL
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $mysqli->set_charset("utf8mb4");

        if ($mysqli->connect_error) {
            throw new Exception('Error de Conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }

        // Obtener el cuerpo de la solicitud en formato JSON
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);


        if (!isset($data['email'], $data['password'])) {
            $response['success'] = false;
            $response['status'] = 'error';
            $response['message'] = 'El email y el password son requeridos.';
            echo json_encode($response);
            exit;
        }

        $token = bin2hex(random_bytes(32));

        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = $data['password'];
        $rol = 0;
        $vencimiento = date('Y-m-d', strtotime('+5 days'));
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO user (email, passwordhash, rol, vencimiento, token) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);

        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $mysqli->error);
        }

        $stmt->bind_param('ssiss', $email, $hashedPassword, $rol, $vencimiento, $token);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response['status'] = 'exito';
            $response['message'] = 'Cuenta registrada correctamente';

            // Generar un número aleatorio para el código
            $numeroAleatorio = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $reply = SMTP_USERNAME; // Dirección de correo electrónico para respuestas

            // Enviar email al administrador
            $subject = "Activar su cuenta - Cod: $numeroAleatorio";
            $body = "<h2>Hola,</h2><br><br>
                Un usuario registro una cuenta en Flashbot <br><br>
                Su email es $email <br><br>
                <a href='https://chatpana.com/flashbot/php/activacion.php?token=$token'>Activar cuenta</a><br><br>
                El equipo de Flashbot.<br>
                <img src=https://www.stackcodelab.com/flashbot/images/logoEmpresa.png height=50px width=50px />
                <a href=https://www.facebook.com/gustabin2.0>
                <img src=https://www.stackcodelab.com/flashbot/images/logoFacebook.jpg alt=Logo Facebook height=50px width=50px></a>
                <h5>Desarrollado por Stackcodelab<br>
                Copyright © 2024. Todos los derechos reservados. Version 1.0.0 <br></h5>
                ";
            enviarEmail(SMTP_USERNAME, $subject, $body, $reply);

            // Enviar email al usuario
            $subject = "Activar su cuenta - Cod: $numeroAleatorio";
            $body = "<h2>Hola,</h2><br><br>
                Bienvenido a Flashbot.
                Para activar su cuenta, haga clic en el siguiente enlace: <br><br>
                <a href='https://chatpana.com/flashbot/php/activacion.php?token=$token'>Activar cuenta</a><br><br>
                El equipo de Flashbot.<br>
                <img src=https://www.stackcodelab.com/flashbot/images/logoEmpresa.png height=50px width=50px />
                <a href=https://www.facebook.com/gustabin2.0>
                <img src=https://www.stackcodelab.com/flashbot/images/logoFacebook.jpg alt=Logo Facebook height=50px width=50px></a>
                <h5>Desarrollado por Stackcodelab<br>
                Copyright © 2024. Todos los derechos reservados. Version 1.0.0 <br></h5>
                ";

            enviarEmail($email, $subject, $body, $reply);
            // Preparar la respuesta exitosa
            $response['status'] = 'exito';
            $response['email'] = $email;
            $response['message'] = 'Usuario registrado correctamente, ahora debes registrar la conexión (No saltes este paso!)';
            $_SESSION['token'] = $token;
            $_SESSION['email'] = $email;

            $stmt->close();
            $mysqli->close();
        } else {
            throw new Exception('Error al actualizar el usuario: ' . $stmt->error);
        }
    }
} catch (Exception $e) {
    error_log("Error al registrar el usuario: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = 'Error al registrar el usuario. Ese correo ya se encuentra registrado.';
}

header('Content-Type: application/json');
echo json_encode($response);
