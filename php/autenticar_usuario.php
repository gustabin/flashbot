<?php
require '../tools/mypathdb.php';

require '../tools/jwt.php';
// TODO SED
require '../tools/sed.php';

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
    $email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : null;
    $password = isset($data['password']) ? $data['password'] : null;


    // Variables de entrada (validación básica)
    // $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : null;
    // $password = isset($_POST['password']) ? $_POST['password'] : null;


    // Validar la entrada
    if (!$email || !$password) {
        throw new Exception('Correo electrónico o contraseña no válidos.');
    }

    // Consulta preparada para obtener el hash de la contraseña del usuario
    $query = "
    SELECT u.id, u.name, u.website, u.api_key, u.hostDB, u.userDB, u.passwordDB, u.databaseDB, u.typeDB, u.portDB, 
    u.ssl_enabledDB, u.charsetDB, u.contenido, u.vencimiento, u.activo, u.token, u.verificado, u.licencia, u.passwordhash, u.rol 
    FROM user u
    WHERE u.email = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception('Error en la preparación de la consulta: ' . $mysqli->error);
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró un usuario con el correo electrónico proporcionado
    if ($stmt->num_rows === 0) {
        throw new Exception('No se encontró ningún usuario con el correo electrónico proporcionado.');
    }

    // Si se encontró el usuario, obtener los datos
    $stmt->bind_result(
        $id,
        $nombre,
        $website,
        $apikey,
        $hostDB,
        $userDB,
        $passwordDB,
        $databaseDB,
        $typeDB,
        $portDB,
        $ssl_enabledDB,
        $charsetDB,
        $contenido,
        $vencimiento,
        $activo,
        $token,
        $verificado,
        $licencia,
        $passwordhash,
        $rol,
    );
    $stmt->fetch();

    // Verificar la contraseña usando password_verify
    if (password_verify($password, $passwordhash)) {
        // La contraseña es correcta        

        $_SESSION['id'] = $id;
        $_SESSION['name'] = $nombre;
        $_SESSION['email'] = $email;
        $_SESSION['rol'] = $rol;
        $_SESSION['website'] = $website;
        $_SESSION['api_key'] = $apikey;
        $_SESSION['hostDB'] = $hostDB;
        $_SESSION['userDB'] = $userDB;
        $_SESSION['passwordDB'] = $passwordDB;
        $_SESSION['databaseDB'] = $databaseDB;
        $_SESSION['typeDB'] = $typeDB;
        $_SESSION['portDB'] = $portDB;
        $_SESSION['ssl_enabledDB'] = $ssl_enabledDB;
        $_SESSION['charsetDB'] = $charsetDB;
        $_SESSION['contenido'] = $contenido;
        $_SESSION['vencimiento'] = $vencimiento;
        $_SESSION['activo'] = $activo;
        $_SESSION['token'] = $token;
        $_SESSION['verificado'] = $verificado;
        $_SESSION['licencia'] = $licencia;
        $_SESSION['passwordhash'] = $passwordhash;
        $_SESSION['rol'] = $rol;


        // Verificar si el usuario está verificado
        if ($verificado == 0) {
            registrarActividad($id, 'Intento de inicio de sesión con cuenta no verificada');
            throw new Exception('Tu cuenta no está verificada. Por favor, revisa tu correo electrónico para verificarla.');
        }

        // Verificar si el usuario tiene una licencia válida
        if ($licencia === null) {
            registrarActividad($id, 'Intento de inicio de sesión con licencia inválida');
            throw new Exception('Tu licencia no es válida. Visita nuestro sitio para adquirir una licencia válida.');
        }
        // Verificar si el usuario completo el registro
        if ($apikey == null) {
            $response['status'] = "apikey";
            registrarActividad($id, 'No se completo el registro de la cuenta falta la conexión');
            throw new Exception('Falta la conexión de tu cuenta. Realiza la configuracion.');
            exit();
        }

        // Verificar si el usuario está activo
        if ($activo == 0) {
            registrarActividad($id, 'Intento de inicio de sesión con cuenta inactiva');
            throw new Exception('Tu cuenta no está activa. Realiza el pago de tu licencia para activarla.');
        }

        // Verificar el vencimiento de la licencia
        $fechaActual = new DateTime();
        $fechaVencimiento = new DateTime($vencimiento);
        if ($fechaActual > $fechaVencimiento) {
            registrarActividad($id, 'Intento de inicio de sesión con licencia vencida');
            throw new Exception('Tu licencia ha expirado. Debes renovarla.');
        }

        // Genera el token JWT  
        $payload = array(
            "usuarioID" => $id,
            "email" => $email,
            'rol' => $rol,
            'verificado' => $verificado,
            'licencia' => $licencia,
            'activo' => $activo,
            'vencimiento' => $vencimiento,
            "exp" => time() + 3600 // Expira en una hora
        );

        // Llamar al JWT para hacer encode
        $token = jwt_encode($payload, SECRET_KEY);

        // Agregar el token JWT a la respuesta
        $response['success'] = true;
        $response['email'] = $email;
        $response['token'] = $token;
        $response['message'] = 'Inicio de sesión exitoso';

        registrarActividad($id, $response['message']);
    } else {
        // La contraseña es incorrecta
        $response['success'] = false;
        $response['message'] = 'Correo electrónico o contraseña incorrecta';
        registrarActividad($id, $response['message']);
    }
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
