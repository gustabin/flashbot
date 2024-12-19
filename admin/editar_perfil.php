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
                        <span style="width: 200px !important; display: none;" id="barra">
                            <img style="width: 286px" src="./../images/barra.gif" alt="Procesando..." />
                        </span>
                    </div>

                    <div class="card-body" style="background-color: dodgerblue;">
                        <form id="registroCuentaForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text"><strong>Rol:</strong> <?php echo htmlspecialchars($user['rol']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="website">* Sitio Web: </label>
                                        <input type="text" class="form-control" id="website" name="website" required
                                            autocomplete="off" value="<?php echo htmlspecialchars($user['website']); ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-12 me-2">
                                    <p class="card-text"><strong>API Key:</strong> <?php echo htmlspecialchars($user['api_key']); ?></p>
                                </div>
                                <div class="form-group col-md-4 me-2">
                                    <label for="hostDB">Host:</label>
                                    <input type="text" class="form-control" id="hostDB" name="hostDB" value="<?php echo htmlspecialchars($user['hostDB']); ?>">
                                </div>
                                <div class="form-group col-md-4 me-2">
                                    <label for="userDB">Usuario DB:</label>
                                    <input type="text" class="form-control" id="userDB" name="userDB" value="<?php echo htmlspecialchars($user['userDB']); ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="databaseDB">* Database:</label>
                                    <input type="text" class="form-control" id="databaseDB" name="databaseDB" required value="<?php echo htmlspecialchars($user['databaseDB']); ?>">
                                </div>
                                <div class="form-group col-md-4 me-2">
                                    <label for="typeDB" class="me-2">Tipo de BD: </label>
                                    <select id="typeDB" name="typeDB" class="form-control ms-auto" style="width: auto;margin-left: 0px;">
                                        <option value="mysql" <?php echo $user['typeDB'] == 'mysql' ? 'selected' : ''; ?>>MySQL</option>
                                        <option value="postgresql" <?php echo $user['typeDB'] == 'postgresql' ? 'selected' : ''; ?>>PostgreSQL</option>
                                        <option value="sqlite" <?php echo $user['typeDB'] == 'sqlite' ? 'selected' : ''; ?>>SQLite</option>
                                        <option value="oracle" <?php echo $user['typeDB'] == 'oracle' ? 'selected' : ''; ?>>Oracle</option>
                                        <option value="sqlserver" <?php echo $user['typeDB'] == 'sqlserver' ? 'selected' : ''; ?>>SQL Server</option>
                                        <option value="mongodb" <?php echo $user['typeDB'] == 'mongodb' ? 'selected' : ''; ?>>MongoDB</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4 me-2">
                                    <label for="portDB">Puerto DB:</label>
                                    <input type="number" class="form-control" id="portDB" name="portDB" min="0"
                                        max="65535" value="<?php echo htmlspecialchars($user['portDB']); ?>">
                                    <div class="invalid-feedback">Por favor, introduce un número de puerto entre 0 y
                                        65535.</div>
                                </div>
                                <div class="form-group col-md-4 me-2">
                                    <label class="form-label">SSL Enabled:</label>
                                    <div class="d-block mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ssl_enabledDB" id="sslEnabledYes" value="yes"
                                                <?php echo $user['ssl_enabledDB'] == 1 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="sslEnabledYes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ssl_enabledDB" id="sslEnabledNo" value="no"
                                                <?php echo $user['ssl_enabledDB'] == 0 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="sslEnabledNo">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="charsetDB">Charset:</label>
                                    <input type="text" class="form-control" id="charsetDB" name="charsetDB" value="<?php echo htmlspecialchars($user['charsetDB']); ?>">
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
                                <div style="margin-left: auto; display: flex; gap: 10px;">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body" style="background-color: lightblue;">
                        <form id="registroCuentaTextoForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="websiteTexto">* Sitio Web: </label>
                                        <input type="text" class="form-control" id="websiteTexto" name="websiteTexto" required
                                            autocomplete="off" value="<?php echo htmlspecialchars($user['website']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-left: 0px; ">
                                    <label for="contenido" class="label-top">* Contenido:</label>
                                    <textarea minlength="100" id="contenido" name="contenido" rows="5" cols="100%"
                                        placeholder="contenido" style="resize: both;" required><?php echo htmlspecialchars($user['contenido']); ?></textarea>
                                </div>
                                <div style="margin-left: auto; display: flex; gap: 10px;">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </form>
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
    <script src="./js/editar_perfil.js"></script>

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