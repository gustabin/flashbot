$(document).ready(function () {
    // Obtener el token de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    if (token) {
        $('#token').val(token);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Token inválido o no encontrado',
        }).then(() => {
            window.location.href = 'login.html'; // Redirigir a la página de login
        });
    }

    $("#irAlLogin").click(function () {
        // Redirigir a la página de login
        window.location.href = 'login.html';
    });


    $('#cambiarPasswordForm').submit(function (event) {
        event.preventDefault();
        var password = $("#password").val();
        var retipearPassword = $("#retipearPassword").val();
        var token = $("#token").val();
        $.ajax({
            type: 'POST',
            url: './php/cambiar_password.php',
            data: JSON.stringify({
                password: password,
                retipearPassword: retipearPassword,
                token: token
            }),
            contentType: "application/json",
            dataType: 'json',
            success: function (response) {
                console.log(response);
                // Mostrar SweetAlert según el status de la respuesta
                if (response.success === true) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    window.location.href = 'index.html';
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr, status, error) {
                // Mostrar SweetAlert en caso de error en la solicitud Ajax
                Swal.fire('Error', 'Error en la solicitud Ajax: ' + error, 'error');
            }
        });
    });
})