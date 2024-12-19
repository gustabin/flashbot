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
            <div id="content" class="p-4 p-md-5 pt-5">
                <!-- Row containing two columns -->
                <div class="row" id="descripcion">
                    <!-- Título -->
                    <div class="col-12 text-center mb-4">
                        <h1 class="display-6 font-weight-bold">Conecta Tu Negocio con un Chatbot en Segundos</h1>
                        <p class="lead">Genera scripts personalizados para integrarte a tu chatbot ¡sin necesidad de
                            programación!</p>
                    </div>

                    <!-- Imagen principal (puedes reemplazar 'image1.jpg' por la ruta de tu imagen) -->
                    <div class="col-md-4">
                        <img src="./../images/conecta.png" class="img-fluid rounded" alt="Conecta tu negocio con un chatbot">
                    </div>

                    <!-- Texto principal -->
                    <div class="col-md-8">
                        <h5 class="display-8 text-justify">¿Quieres integrar un chatbot en tu sitio web o aplicación
                            sin complicaciones?
                            Con nuestro
                            sistema, solo necesitas una breve descripción de lo que necesitas, y nosotros generamos
                            automáticamente el script de conexión perfecto para ti. Ahora cualquier negocio, desde
                            pequeñas
                            empresas hasta grandes corporaciones, puede implementar fácilmente un chatbot que mejora la
                            experiencia del usuario, aumenta las conversiones y agiliza la atención al cliente.</h5>
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