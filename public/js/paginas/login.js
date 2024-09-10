$(function () {
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
    // muestrar en carrusel salones
    async function cardsSalones() {
        try {
            let peticion = await fetch(servidor + `login/viewSalon`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">No hay salones disponibles</h3>`).appendTo("#container-carrusel-salon").addClass('text-danger');
                return false;
            }
            $("#container-carrusel-salon").empty();
            response.forEach(item => {
                let cardHtml = `
                <div class="card swiper-slide" data-bs-toggle="modal" data-bs-target="#BrandingModal"
                    data-image="${item.ubicacion}" data-name="${item.nombre}">
                    <div class="image-content">
                        <div class="card-image">
                            <a href="login/salon/${btoa(btoa(item.id_espacio))}"><img src="${servidor}${item.ubicacion}" class="card-img" alt="${item.nombre}"></a>
                        </div>
                    </div>
                </div>`;
                $("#container-carrusel-salon").append(cardHtml);
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsSalones();
    async function cardsOficina() {
        try {
            let peticion = await fetch(servidor + `login/viewOficina`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">No hay oficina disponibles</h3>`).appendTo("#container-carrusel-oficina").addClass('text-danger');
                return false;
            }
            $("#container-carrusel-oficina").empty();
            response.forEach(item => {
                let cardHtml = `
                <div class="card swiper-slide" data-bs-toggle="modal" data-bs-target="#BrandingModal"
                    data-image="${item.ubicacion}" data-name="${item.nombre}">
                    <div class="image-content">
                        <div class="card-image">
                            <a href="login/salon/${btoa(btoa(item.id_espacio))}"><img src="${servidor}${item.ubicacion}" class="card-img" alt="${item.nombre}"></a>
                        </div>
                    </div>
                </div>`;
                $("#container-carrusel-oficina").append(cardHtml);
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsOficina();
});