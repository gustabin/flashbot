<?php
session_start();
if (!isset($_SESSION['email'])) {
    // Si no hay una sesión activa, redirigir al usuario al inicio de sesión
    header("Location: ../");
}

$user = [
    'id' => $_SESSION['id'],
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'rol' => $_SESSION['rol'],
    'website' => $_SESSION['website'],
    'api_key' => $_SESSION['api_key'],
    'hostDB' => $_SESSION['hostDB'],
    'userDB' => $_SESSION['userDB'],
    'passwordDB' => $_SESSION['passwordDB'],
    'databaseDB' => $_SESSION['databaseDB'],
    'typeDB' => $_SESSION['typeDB'],
    'portDB' => $_SESSION['portDB'],
    'ssl_enabledDB' => $_SESSION['ssl_enabledDB'],
    'charsetDB' => $_SESSION['charsetDB'],
    'contenido' => $_SESSION['contenido'],
    'vencimiento' => $_SESSION['vencimiento'],
    'activo' => $_SESSION['activo'],
    'token' => $_SESSION['token'],
];

// Convertir el rol numérico a su descripción en texto
$roleDescriptions = [
    0 => 'Usuario',
    1 => 'Operador',
    2 => 'Administrador'
];
$user['rol'] = $roleDescriptions[$user['rol']] ?? 'Desconocido'; // Si el rol no existe, muestra 'Desconocido'
?>
<!DOCTYPE html>

<meta charset="utf-8">
<link rel="icon" href="./../images/favicon.png" type="image/gif" />
<!-- bootstrap css -->
<link rel="stylesheet" href="./../css/bootstrap.min.css">
<!-- style css -->
<link rel="stylesheet" href="css/style.css">

<div id="headContainer"></div>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <div id="navContainer"></div>
        <section>
            <div id="content" class="p-4 p-md-5 pt-5">
                <h2>Perfil de Usuario</h2>
                <div class="card">
                    <div class="card-header" style="background-color: black; color: white; display: flex; align-items: center; justify-content: space-between;">
                        <span>Información del Usuario</span>

                        <div style="margin-left: auto; display: flex; gap: 10px;">
                            <a href="editar_perfil.php" class="btn btn-primary" style="margin-left: auto;">Editar</a>
                        </div>
                    </div>
                    <div class="card-body" style="background-color: antiquewhite;">
                        <!-- <h5 class="card-title">Nombre: <?php echo htmlspecialchars($user['name']); ?></h5> -->

                        <div class="row">
                            <div class="col-md-4">
                                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="card-text"><strong>Rol:</strong> <?php echo htmlspecialchars($user['rol']); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="card-text"><strong>Sitio Web:</strong></p>
                                <a href="<?php echo htmlspecialchars($user['website']); ?>" target="_blank"><?php echo htmlspecialchars($user['website']); ?></a>

                            </div>

                            <div class="form-group col-md-12 me-2">
                                <p class="card-text"><strong>API Key:</strong> <?php echo htmlspecialchars($user['api_key']); ?></p>
                            </div>

                            <div class="form-group col-md-4 me-2">
                                <label for="hostDB">Host:</label>
                                <input readonly type="text" class="form-control" id="hostDB" name="hostDB" value="<?php echo htmlspecialchars($user['hostDB']); ?>">
                            </div>

                            <div class="form-group col-md-4 me-2">
                                <label for="userDB">Usuario DB:</label>
                                <input readonly type="text" class="form-control" id="userDB" name="userDB" value="<?php echo htmlspecialchars($user['userDB']); ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="databaseDB">Database:</label>
                                <input readonly type="text" class="form-control" id="databaseDB" name="databaseDB" value="<?php echo htmlspecialchars($user['databaseDB']); ?>">
                            </div>


                            <div class="col-md-4">
                                <p class="card-text"><strong>Tipo de BD:</strong></p> <?php echo htmlspecialchars($user['typeDB']); ?>
                            </div>

                            <div class="form-group col-md-4 me-2">
                                <label for="portDB">Puerto DB:</label>
                                <input readonly type="number" class="form-control" id="portDB" name="portDB" min="0"
                                    max="65535" required value="<?php echo htmlspecialchars($user['portDB']); ?>">
                                <div class="invalid-feedback">Por favor, introduce un número de puerto entre 0 y
                                    65535.</div>
                            </div>
                            <div class="col-md-4">
                                <p class="card-text"><strong>SSL Habilitado:</strong></p> <?php echo htmlspecialchars($user['ssl_enabledDB'] ? 'Sí' : 'No'); ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="charsetDB">Charset:</label>
                                <input readonly type="text" class="form-control" id="charsetDB" name="charsetDB" value="<?php echo htmlspecialchars($user['charsetDB']); ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <p class="card-text"><strong>Vencimiento:</strong> </p><?php echo htmlspecialchars($user['vencimiento']); ?>
                            </div>
                            <div class="form-group col-md-4">
                                <p class="card-text"><strong>Activo:</strong></p> <?php echo htmlspecialchars($user['activo'] ? 'Sí' : 'No'); ?>
                            </div>
                            <div class="col-md-12 mb-5">
                                <p class="card-text"><strong>Token:</strong> <?php echo htmlspecialchars($user['token']); ?></p>
                            </div>

                            <div class="col-md-12">
                                <p class="card-text"><strong>Contenido:</strong> <?php echo htmlspecialchars($user['contenido']); ?></p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <!-- Agrega los scripts de Bootstrap (jQuery y Popper.js) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="./js/main.js"></script>

    <script>
        $(document).ready(function() {
            $("#headContainer").load("head.html");
            $('#navContainer').load('nav.html', function() {
                $('#sidebarCollapse').on('click', function() {
                    $('#sidebar').toggleClass('active');
                });
            });
        });
    </script>

</body>

</html>