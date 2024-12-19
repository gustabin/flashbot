$('#restablecerForm').submit(function (event) {
    event.preventDefault();

    var email = $("#email").val();
    $("#barra").show();
    $.ajax({
        type: "POST",
        url: './php/reestablecer_password.php',
        data: JSON.stringify({
            email: email
        }),
        contentType: "application/json",
        dataType: "json",
        success: function (response) {
            $("#barra").hide();
            // console.log(response);
            if (response.status === 'exito') {
                Swal.fire('¡Éxito!', response.message, 'success');
            }
            if (response.status === 'error') {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function (xhr, status, error) {
            $("#barra").hide();
            Swal.fire('Error', 'Error en la solicitud Ajax: ' + error, 'error');
        }
    });
});