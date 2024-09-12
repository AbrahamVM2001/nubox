$(function () {
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
    // funciones de filtrado
    $('body').on('click', '.btn-formLogear', function () {
        $('#form-new-logear').show();
        $('#form-new-reservacion').hide();
    });
    $('body').on('click', '.btn-formregistro', function () {
        $('#form-new-reservacion').show();
        $('#form-new-logear').hide();
    });
    // mostrar espacio
    async function cardsEspacio(id_espacio) {
        try {
            let peticion = await fetch(servidor + `login/espacio/${id_espacio}`);
            let response = await peticion.json();
            if (response.length === 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Error 404 Espacio no disponible</h3>`).appendTo("#container-espacio").addClass('text-danger');
                return false;
            }
            $("#container-espacio").empty();
            let cardHtml = `
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h1>${response[0].nombre}</h1>
                </div>
            `;
            $("#container-espacio").append(cardHtml);
            $("#carruselContenido").empty();
            response.forEach(item => {
                let cardSwiper = `
                    <swiper-slide>
                        <img src="${servidor}${item.ubicacion}" class="img-thumbnail" alt="${item.nombre}" />
                    </swiper-slide>
                `;
                $("#carruselContenido").append(cardSwiper);
            });
            $('#precio').text(response[0].precio_hora);
            $('#desc').text(response[0].descripcion);
            $('#tipo').text(response[0].tipo_espacio == 1 ? 'Salón' : response[0].tipo_espacio == 2 ? 'Oficina' : 'Otro');
            var map = L.map('map').setView([response[0].latitud, response[0].longitud], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            response.forEach(item => {
                L.marker([item.latitud, item.longitud]).addTo(map)
                    .bindPopup(`<b>${item.nombre}</b><br>${item.descripcion}`);
            });
        } catch (error) {
            if (error.name == 'AbortError') {
            } else {
                console.error('Error al cargar los datos:', error);
            }
        }
    }
    cardsEspacio(id_espacio);
    // mostrar asignaciones
    async function cardsAsignacion(id_espacio) {
        try {
            let peticion = await fetch(servidor + `login/asignacion/${id_espacio}`);
            let response = await peticion.json();
            if (response.length === 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Error 404 Espacio no disponible</h3>`).appendTo("#container-espacio").addClass('text-danger');
                return false;
            }
            $("#container-espacio").empty();
            let events = response.map(item => ({
                title: "ocupado",
                start: item.fecha_inicio,
                end: item.fecha_finalizacion,
                color: 'red'
            }));
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events
            });
            calendar.render();
        } catch (error) {
            if (error.name == 'AbortError') {
            } else {
                console.error('Error al cargar los datos:', error);
            }
        }
    }
    cardsAsignacion(id_espacio);
    // logeo
    $(".btn-logear").click(function () {
        let form = $("#form-new-logear");
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
                            location.href=servidor+"login"+"/"+"pago"+"/"+id_espacio
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
    // registro
    $(".btn-reservacion").click(function () {
        let form = $("#form-new-reservacion");
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: 'POST',
                url: servidor + 'login/registro',
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
                            location.href=servidor+"login"+"/"+"pago"+"/"+id_espacio
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