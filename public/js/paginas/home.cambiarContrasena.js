$(function () {
    // valores en el id_usuario input
    $('#id_usuario').val(id_usuario);
    // funcion de contraseña
    function validatePassword() {
        let password = $('#passRegistro').val();
        let confirmPassword = $('#pass_confir').val();
        let hasUppercase = /[A-Z]/.test(password);
        let hasLowercase = /[a-z]/.test(password);
        let hasNumbers = /[0-9]/.test(password);
        let hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        let isLongEnough = password.length >= 8;
        let isShortEnough = password.length <= 32;
        let passwordsMatch = password === confirmPassword;
        updateValidationUI('.verificar-mayusculas', hasUppercase);
        updateValidationUI('.verificar-minusculas', hasLowercase);
        updateValidationUI('.verificar-numeros', hasNumbers);
        updateValidationUI('.verificar-caracteres', hasSpecial);
        updateValidationUI('.verificar-meno', isLongEnough);
        updateValidationUI('.verificar-mayor', isShortEnough);
        updateValidationUI('.verificar-similar', passwordsMatch && isLongEnough && isShortEnough);
    }
    function updateValidationUI(selector, isValid) {
        let icon = $(selector);
        icon.html('');
        let svg = isValid
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="8.48" viewBox="0 0 1179 1000"><path fill="#059669" d="M1179 72Q929 294 579 822l-115 179Q320 821 0 501l107-107l286 250q150-150 279-271.5T877.5 185T1009 74t77-59l21-14q4 0 11 2t26 19.5t35 49.5"/></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 512 512"><path fill="#e11d48" d="m427.314 107.313l-22.628-22.626L256 233.373L107.314 84.687l-22.628 22.626L233.373 256L84.686 404.687l22.628 22.626L256 278.627l148.686 148.686l22.628-22.626L278.627 256z"/></svg>';
        icon.html(svg);
    }
    $('#passRegistro').on('input', validatePassword);
    $('#pass_confir').on('input', validatePassword);
    // login
    $(".btn-iniciar").click(function () {
        let form = $("#form-new-iniciar");
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: 'POST',
                url: servidor + 'login/acceso',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                    $("#loading").addClass('loading');
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.estatus,
                        title: data.titulo,
                        text: data.respuesta,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    if (data.estatus === 'success') {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function (data) {
                    console.log(data);
                },
                complete: function () {
                    $("#loading").removeClass('loading');
                }
            });
        }
        form.addClass('was-validated');
    });
    // restaurar contraseña
    $(".btn-restaurar").click(function () {
        let form = $("#form-new-cambiarContrasena");
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: 'POST',
                url: servidor + 'login/restaurarContrasena',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                    $("#loading").addClass('loading');
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.estatus,
                        title: data.titulo,
                        text: data.respuesta,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    if (data.estatus === 'success') {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function (data) {
                    console.log(data);
                },
                complete: function () {
                    $("#loading").removeClass('loading');
                }
            });
        }
        form.addClass('was-validated');
    });
});