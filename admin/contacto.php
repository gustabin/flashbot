<?php
session_start();
if (!isset($_SESSION['email'])) {
    // Si no hay una sesión activa, redirigir al usuario al inicio de sesión
    header("Location: ../");
}
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
        <div id="content" class="p-md-5 pt-5">
            <h2 class="mb-4">Contáctanos:</h2>
            <div class="row" id="scriptListo">
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <!-- Contenedor del texto centrado -->
                            <div style="flex-grow: 1; text-align: center;">
                                Escríbenos a:
                            </div>
                        </div>

                        <!-- Contenedor del código a copiar, dentro del alert -->
                        <div style="margin-top: 10px; text-align: center;">
                            <pre id="scriptText"><a href="mailto:" id="emailLink">info[at]chatpana[dot]com</a></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

        // Obfuscar el correo para que lo armen solo los navegadores
        document.addEventListener('DOMContentLoaded', function() {
            var emailLink = document.getElementById('emailLink');
            var email = 'info' + '@' + 'chatpana' + '.com';
            emailLink.href = 'mailto:' + email;
            emailLink.innerText = email;
        });
    </script>

</body>

</html>