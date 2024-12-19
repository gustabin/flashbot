<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
function enviarEmail($email, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = 'true';
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USERNAME, 'Ventas Chatpana');
        $mail->addAddress($email);
        $mail->CharSet = 'UTF-8';

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        // echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        // echo "El mensaje no pudo ser enviado. Error del correo: {$mail->ErrorInfo}";
    }
}
