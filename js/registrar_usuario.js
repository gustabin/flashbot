$(document).ready(function () {
    // Intercepta el envío del formulario
    $('#registroForm').submit(function (e) {
        // alert("entro");
        e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional

        var email = $("#email").val();
        var password = $("#password").val();
        // Realiza la solicitud AJAX al microservicio
        $("#barra").show();
        $.ajax({
            type: 'POST',
            url: './php/registrar_usuario.php', // Ajusta la ruta según la ubicación de tu microservicio
            data: JSON.stringify({
                email: email,
                password: password
            }),
            contentType: "application/json",
            dataType: 'json',
            success: function (response) {
                // Muestra el Sweet Alert según la respuesta del microservicio
                // console.log(response);
                $("#barra").hide();
                if (response.status === 'exito') {
                    Swal.fire('Éxito', response.message, 'success');
                    // Redirigir a la página de login después de 3 segundos
                    setTimeout(function () {
                        window.location.href = 'elegirConexionbd.html';
                    }, 3000); // 3000 milisegundos = 3 segundos
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                $("#barra").hide();
                // Muestra un Sweet Alert en caso de error en la solicitud AJAX
                Swal.fire('Error', 'Error de comunicación con el servidor', 'error');
            }
        });
    });

    $("#irAlLogin").click(function () {
        // Redirigir a la página de login
        window.location.href = 'login.html';
    });
});