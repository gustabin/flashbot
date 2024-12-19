<?php
// Verificar si el token está presente en la URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // Redirigir a reset_password.html con el token como parámetro
    header("Location: ../reset_password.html?token=" . $token);
    exit();
} else {
    echo "Token inválido";
}
