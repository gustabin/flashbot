$(document).ready(function () {
    // Intercepta el envío del formulario
    $('#registroCuentaForm').submit(function (e) {
        e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional
        // Realiza la solicitud AJAX al microservicio
        $("#barra").show();
        $.ajax({
            type: 'POST',
            url: './guardar_perfil.php', // Ajusta la ruta según la ubicación de tu microservicio
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                // Muestra el Sweet Alert según la respuesta del microservicio
                console.log(response);
                $("#barra").hide();
                if (response.status === 'exito') {
                    // Swal.fire('Éxito', response.message, 'success');
                    // Redirigir a la página de login después de 3 segundos
                    setTimeout(function () {
                        window.location.href = './perfil.php';
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

    $('#registroCuentaTextoForm').submit(function (e) {
        e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional
        // Realiza la solicitud AJAX al microservicio
        $("#barra").show();
        $.ajax({
            type: 'POST',
            url: './guardar_perfiltexto.php', // Ajusta la ruta según la ubicación de tu microservicio
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                // Muestra el Sweet Alert según la respuesta del microservicio
                // console.log(response);
                $("#barra").hide();
                if (response.status === 'exito') {
                    Swal.fire('Éxito', response.message, 'success');
                    // Redirigir a la página de login después de 3 segundos
                    setTimeout(function () {
                        window.location.href = './perfil.php';
                    }, 3000); // 3000 milisegundos = 3 segundos
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                // Muestra un Sweet Alert en caso de error en la solicitud AJAX
                Swal.fire('Error', 'Error de comunicación con el servidor', 'error');
            }
        });
    });
});