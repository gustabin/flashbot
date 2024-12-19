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
        <section>
            <div id="content" class="p-md-5 pt-5">
                <h2 class="mb-4">Script de conexión</h2>
                <div class="row" id="scriptListo">
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <!-- Contenedor del texto centrado -->
                                <div style="flex-grow: 1; text-align: center;">
                                    Copia y pega este script en tu sitio web:
                                </div>

                                <!-- Botón de copiar alineado a la derecha -->
                                <button onclick="copyToClipboard()" style="background: none; border: none; cursor: pointer;">
                                    <i class="fa fa-copy" aria-hidden="true"></i>
                                </button>
                            </div>

                            <!-- Contenedor del código a copiar, dentro del alert -->
                            <div style="margin-top: 10px; text-align: center;">
                                <pre id="scriptText">&lt;script src="https://chatpana.com/flashbot_verify/static/js/chatbot.js" data-api-key="<?php echo $_SESSION['api_key']; ?>"&gt;&lt;/script&gt;</pre>
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

        function copyToClipboard() {
            // Obtener el contenido del <pre>
            const scriptText = document.getElementById('scriptText').innerText;

            // Crear un elemento de texto temporal
            const tempInput = document.createElement('textarea');
            tempInput.value = scriptText;
            document.body.appendChild(tempInput);

            // Seleccionar y copiar el texto al portapapeles
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // Para dispositivos móviles
            document.execCommand("copy");

            // Eliminar el elemento temporal
            document.body.removeChild(tempInput);

            // Mostrar un mensaje de confirmación
            alert("Texto copiado al portapapeles");
        }
    </script>

</body>

</html>