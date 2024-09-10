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
                            location.href=servidor
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
    // funcion de la galeria de imagenes
    const ITEMS_PER_PAGE = 9;
    let currentPage = 1;
    let data = [];

    async function fetchSalones() {
        try {
            let peticion = await fetch(servidor + `login/mostrarOficinas`);
            let response = await peticion.json();
            if (response.length === 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Error 404 Espacio no disponible</h3>`).appendTo("#galeria").addClass('text-danger');
                return false;
            }
            data = response;
            renderPage(currentPage);
            createPaginationButtons();
        } catch (error) {
            if (error.name == 'AbortError') {
            } else {
                console.error('Error al cargar los datos:', error);
            }
        }
    }

    function renderPage(page) {
        $("#oficinas").empty();
        const startIndex = (page - 1) * ITEMS_PER_PAGE;
        const endIndex = Math.min(startIndex + ITEMS_PER_PAGE, data.length);
        const paginatedData = data.slice(startIndex, endIndex);

        paginatedData.forEach(item => {
            let salones = `
            <div class="col-sm-12 col-md-4 mt-5">
                <div class="card">
                    <img src="${servidor}${item.ubicacion}">
                    <div class="card-title p-3">
                        <h5>${item.nombre}</h5>
                        <p>${item.descripcion}</p>
                        <a href="${servidor}login/salon/${btoa(btoa(item.id_espacio))}" class="btn btn-primary">Reservar</a>
                    </div>
                </div>
            </div>
        `;
            $("#oficinas").append(salones);
        });
    }

    function createPaginationButtons() {
        const totalPages = Math.ceil(data.length / ITEMS_PER_PAGE);
        $("#pagination").empty();
        for (let i = 1; i <= totalPages; i++) {
            const button = $(`<button class="btn btn-outline-success page-button m-1" data-page="${i}">${i}</button>`);
            button.on('click', function () {
                currentPage = $(this).data('page');
                renderPage(currentPage);
            });
            $("#pagination").append(button);
        }
    }
    fetchSalones();

});