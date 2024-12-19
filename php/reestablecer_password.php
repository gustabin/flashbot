<?php
require '../tools/mypathdb.php';
require('../mail/index.php'); // Requerir el archivo de envío de correo

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$response = array();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $mysqli->set_charset("utf8mb4");

        // Obtener el cuerpo de la solicitud en formato JSON
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        if (!isset($data['email'])) {
            throw new Exception('El email es requerido.');
        }

        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            throw new Exception('Dirección de correo electrónico no válida.');
        }

        $numeroAleatorio = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);

        $query = "UPDATE user SET tokenRecuperacion = ? WHERE email = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss', $token, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $subjectAdmin = "Solicitud de cambio de contraseña - Cod: $numeroAleatorio";
            $bodyAdmin = "<h2>Hola, se ha solicitado un restablecimiento de contraseña en el sistema.</h2>
            <p>Detalles:</p>
            <ul>
            <li>Email: $email</li>
            <li>Token: $token</li>
            </ul>
            <p>Puede procesar esta solicitud <a href='https://chatpana.com/flashbot/php/reset_password.php?token=$token'>aquí</a>.</p>
            <br><br>
            <br>
            El equipo de Chatpana.<br>
            <img src=https://www.chatpana.com/flashbot/images/logoEmpresa.png height=50px width=50px />
            <a href=https://www.facebook.com/gustabin2.0>
            <img src=https://www.chatpana.com/flashbot/images/logoFacebook.jpg alt=Logo Facebook height=50px width=50px></a>
            <h5>Desarrollado por Stackcodelab<br>
            Copyright © 2024. Todos los derechos reservados. Version 1.0.0 <br></h5>
            ";
            enviarEmail(SMTP_USERNAME, $subjectAdmin, $bodyAdmin);


            $subjectUser = "Recuperación de contraseña - Cod: $numeroAleatorio";
            $bodyUser = "<h2>Recuperación de contraseña</h2>
            <p>Hemos recibido su solicitud para restablecer la contraseña de su cuenta.</p>
            <p>Puede restablecer su contraseña <a href='https://chatpana.com/flashbot/php/reset_password.php?token=$token'>aquí</a>.</p>
            Gracias por confiar en nosotros.
            <br>
            El equipo de Chatpana.<br>
            <img src=https://www.chatpana.com/flashbot/images/logoEmpresa.png height=50px width=50px />
            <a href=https://www.facebook.com/gustabin2.0>
            <img src=https://www.chatpana.com/flashbot/images/logoFacebook.jpg alt=Logo Facebook height=50px width=50px></a>
            <h5>Desarrollado por Stackdodelab<br>
            Copyright © 2024. Todos los derechos reservados. Version 1.0.0 <br></h5>
            ";
            enviarEmail($email, $subjectUser, $bodyUser);

            $response['status'] = 'exito';
            $response['message'] = '📧¡Correo de recuperación enviado! Por favor, revisa tu bandeja de entrada y sigue las instrucciones para restablecer tu contraseña. Si no recibes el correo en unos minutos, revisa tu carpeta de correo no deseado o spam.';
            echo json_encode($response);
            exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: No se encontró ese email, verifica que sea correcto.';
            echo json_encode($response);
            exit();
        }
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
    echo json_encode($response);
    exit;
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($mysqli)) $mysqli->close();
}
// Enviar la respuesta final si no se ha enviado antes
if (!headers_sent()) {
    echo json_encode($response);
    exit();
}
