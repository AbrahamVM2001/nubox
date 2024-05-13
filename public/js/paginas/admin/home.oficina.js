$(function () {
    async function cardsOficina() {
        try {
            let peticion = await fetch(servidor + `admin/viewOficina`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin oficinas publicados</h3>`).appendTo("#container-oficina").addClass('text-danger');
                return false;
            }
            $("#container-oficina").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Nombre</th>
                <th class="text-uppercase">Descripcion</th>
                <th class="text-uppercase">Caracteristicas</th>
                <th class="text-uppercase">Ubicacion</th>
                <th class="text-uppercase">Aforo</th>
                <th class="text-uppercase">Precio</th>
                <th class="text-uppercase">titulo</th>
                <th class="text-uppercase">Imagen</th>
                <th class="text-uppercase">token</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-oficina")
                .removeClass("text-danger");
            $('#info-table-result').DataTable({
                "drawCallback": function (settings) {
                    $('.paginate_button').addClass("btn").removeClass("paginate_button");
                    $('.dataTables_length').addClass('pull-left');
                    $('#info-table-result_filter').addClass('pull-right');
                    $('input').addClass("form-control");
                    $('select').addClass('form-control');
                    $('.previous.disabled').addClass("btn-outline-info opacity-5 btn-rounded mx-2 mt-3");
                    $('.next.disabled').addClass("btn-outline-info opacity-5 btn-rounded mx-2 mt-3");
                    $('.previous').addClass("btn-outline-info btn-rounded mx-2 mt-3");
                    $('.next').addClass("btn-outline-info btn-rounded mx-2 mt-3");
                },
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "pageLength": 5,
                "lengthMenu": [[5, 10, 15], [5, 10, "All"]],
                data: response,
                "columns": [
                    { "data": "Nombre", className: 'text-vertical text-center' },
                    { "data": "Descripcion", className: 'text-vertical text-center' },
                    { "data": "Caracteristicas", className: 'text-vertical text-center' },
                    { "data": "Ubicacion", className: 'text-vertical text-center' },
                    { "data": "Aforo", className: 'text-vertical text-center' },
                    { "data": "Precio", className: 'text-vertical text-center' },
                    { "data": "titulo", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            return `<img src="${servidor}${data.imagen}" style="max-width: 100px; max-height: 100px;">`;
                        },
                        className: 'text-vertical text-center'
                    },
                    { "data": "token", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="col-sm-12 col-md-12 col-lg-12 col-<xl-12 d-flex justify-content-between align-items-center">
                                    <button data-id="${btoa(data.id_oficina)}" data-estatus="${btoa(data.estatus)}" data-bs-toggle="tooltip" title="Activa o desactiva la sesión" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
                                </div>`;
                            return botones;
                        },
                        className: 'text-vertical text-center'
                    }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-usuario')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsOficina();
    $(".btn-oficina").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: "POST",
                url: servidor + "admin/guardarOficina",
                dataType: "json",
                data: new FormData(form.get(0)),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    // setting a timeout
                    $("#loading").addClass("loading");
                },
                success: function (data) {
                    console.log(data);
                    Swal.fire({
                        position: "top-end",
                        icon: data.estatus,
                        title: data.titulo,
                        text: data.respuesta,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function (data) {
                    console.log("Error ajax");
                    console.log(data);
                    /* console.log(data.log); */
                },
                complete: function () {
                    $("#loading").removeClass("loading");
                },
            });
        }
        form.addClass("was-validated");
    });
    $(document).on('click', '.btn-suspender', function () {
        var idOficina = $(this).data('id');
        var estatus = $(this).data('estatus');
        suspender(idOficina, estatus);
    });
    function suspender(idOficina, estatus) {
        $.ajax({
            type: "POST",
            url: servidor + "admin/ActDec_oficina",
            dataType: "json",
            data: {
                idoficina: idOficina,
                estatus: estatus
            },
            beforeSend: function () {
                $("#loading").addClass("loading");
            },
            success: function (data) {
                console.log(data);
                Swal.fire({
                    position: "top-end",
                    icon: data.estatus,
                    title: data.titulo,
                    text: data.respuesta,
                    showConfirmButton: false,
                    timer: 2000,
                });
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function (data) {
                console.log("Error ajax");
                console.log(data);
                /* console.log(data.log); */
            },
            complete: function () {
                $("#loading").removeClass("loading");
            },
        });
    }
    $('body').on('dblclick', '#info-table-result tbody tr', function () {
        var data = $('#info-table-result').DataTable().row(this).data();
        if (data['id_oficina'] == 0) {
            registroNoEditar();
        } else {
            $("#form-new-oficina")[0].reset();
            $('#modalOficinaLabel').text('Editar oficina');
            $('#modalOficina').modal('show');
            buscarOficina(data['id_oficina']);
        }
    });
    async function buscarOficina(id_oficina) {
        try {
            let peticion = await fetch(servidor + `admin/buscarOficina/${id_oficina}`);
            let response = await peticion.json();
            $('#tipo').val('');
            $('#oficina').val(response['id_oficina']);
            $('#nombre').val(response['Nombre']);
            $('#decripcion').val(response['Descripcion']);
            $('#caracteristicas').val(response['Caracteristicas']);
            $('#ubicacion').val(response['Ubicacion']);
            $('#aforo').val(response['Aforo']);
            $('#precio').val(response['Precio']);
            $('#titulo').val(response['titulo']);
            $('#estatus').val(response['estatus']);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
});