$(document).ready(function () {
    // alert("entro a login.js");
    $("#loginForm").submit(function (event) {
        event.preventDefault();

        var email = $("#email").val();
        var password = $("#password").val();
        $("#barra").show();
        $.ajax({
            type: "POST",
            url: "./php/autenticar_usuario.php",
            data: JSON.stringify({
                email: email,
                password: password
            }),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                $("#barra").hide();
                // console.log(response);
                if (response.token) {
                    localStorage.setItem('jwt_token', response.token);
                }
                if (response.success == true) {
                    localStorage.setItem('jwt_token', response.token);
                    localStorage.setItem('email', response.email);
                    window.location.href = './admin/';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.message
                    });
                    if (response.status == "apikey") {
                        window.location.href = './elegirConexionbd.html';
                    }
                }
            },
            error: function () {
                $("#barra").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al procesar la solicitud.'
                });
            }
        });
        return false;
    });


    $("#recuperarPassword").click(function () {
        // Redirigir a la página de restablecer contraseña
        window.location.href = 'reestablecer_password.html';
    });
});