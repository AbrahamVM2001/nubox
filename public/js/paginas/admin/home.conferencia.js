$(function () {
    const fechaHoraInicio = document.getElementById('fechaHora_inicio');
    const fechaHoraFinalizacion = document.getElementById('fechaHoraFinalizacion');
    const fechaHoraActual = new Date().toISOString().slice(0, 16);

    const fechaActualSoloFecha = new Date().toISOString().slice(0, 10);

    fechaHoraInicio.setAttribute('min', fechaHoraActual);
    fechaHoraFinalizacion.setAttribute('min', fechaHoraActual);

    $(".btn-conferencia").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: "POST",
                url: servidor + "admin/guardarConferencia",
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
    async function cardsConferencia() {
        try {
            let peticion = await fetch(servidor + `admin/conferencia`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin conferencia asignadas</h3>`).appendTo("#container-conferencia").addClass('text-danger');
                return false;
            }
            $("#container-conferencia").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Tema</th>
                <th class="text-uppercase">Fecha y Hora inicio</th>
                <th class="text-uppercase">Fecha y Hora termino</th>
                <th class="text-uppercase">Descripcion</th>
                <th class="text-uppercase">Estatus</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-conferencia")
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
                "pageLength": 4,
                "lengthMenu": [[4, 8, 12], [4, 8, "All"]],
                data: response,
                "columns": [
                    { "data": "Tema_sesion", className: 'text-vertical text-center' },
                    { "data": "Fecha_Hora_Inicio", className: 'text-vertical text-center' },
                    { "data": "Fecha_Hora_Termino", className: 'text-vertical text-center' },
                    { "data": "Descripcion", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let tipo_estatus = (data.Estatus == 1) ? 'Habilitado' : 'Desabilitado';
                            return tipo_estatus;
                        },
                        className: 'text-vertical text-center'
                    },
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.Estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="col-sm-12 col-md-12 col-lg-12 col-<xl-12 d-flex justify-content-between align-items-center">
                                    <button data-id="${btoa(data.id_sesiones)}" data-estatus="${btoa(data.Estatus)}" data-bs-toggle="tooltip" title="Activa o desactiva la sesión" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
                                    <button data-id="${btoa(btoa(data.id_sesiones))}" title="Link de las sesiones" class="btn btn-info btn-link"><i class="fa-solid fa-link"></i></button>
                                    <button data-id="${btoa(btoa(data.id_sesiones))}" class="btn btn-primary btn-copiar" title="Remplicar la información"><i class="fa-solid fa-copy"></i></button>
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
    cardsConferencia();
    $(document).on('click', '.btn-suspender', function () {
        var idSesion = $(this).data('id');
        var estatus = $(this).data('estatus');
        suspender(idSesion, estatus);
    });
    $(document).on('click', '.btn-agregar-conferencia', function () {
        $("#form-new-conferencia")[0].reset();
    });
    function suspender(idSesion, estatus) {
        $.ajax({
            type: "POST",
            url: servidor + "admin/desahabilitar_conferencia",
            dataType: "json",
            data: {
                idSesion: idSesion,
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
    $(document).on('click', '.btn-link', function () {
        var sessionId = $(this).data('id');
        $('#modalLinksLabel').text('Enlaces');
        var linkHtml = `
            <div class="row">
                <div class="col-sm-12">
                    <a href="${servidor}login" class="btn btn-link"><i class="fas fa-link"></i> Iniciar sesión</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <a href="${servidor}admin/borrar/${sessionId}" class="btn btn-link"><i class="fas fa-link"></i> Enlace borrar</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <a href="${servidor}admin/ponente/$${sessionId}" class="btn btn-link"><i class="fas fa-link"></i> Enlace Preguntas</a>
                </div>
            </div>
        `;
        $("#container-links").html(linkHtml);
        $('#modalLinks').modal('show');
    });
    $('body').on('dblclick', '#info-table-result tbody tr', function () {
        var data = $('#info-table-result').DataTable().row(this).data();
        if (data['id_sesiones'] == 0) {
            registroNoEditar();
        } else {
            $("#form-new-conferencia")[0].reset();
            $('#modalConferenciasLabel').text('Editar conferencia');
            $('#modalConferencias').modal('show');
            buscarConferencia(data['id_sesiones']);
        }
    });
    async function buscarConferencia(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `admin/buscarConferencia/${id_sesiones}`);
            let response = await peticion.json();
            $('#tipo').val('');
            $('#id_conferencia').val(response['id_sesiones']);
            $('#tema_sesion').val(response['Tema_sesion']);
            $('#fechaHora_inicio').val(response['Fecha_Hora_Inicio']);
            $('#fechaHoraFinalizacion').val(response['Fecha_Hora_Termino']);
            $('#descripcion').val(response['Descripcion']);
            $('#linkTransmision_nativo').val(response['Link_transmision_nativo']);
            $('#linkTransmision_traducida').val(response['Link_transmision_traducida']);
            $('#estatus').val(response['Estatus']);
            $('#permiso').val(response['Permisos']);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    $(document).on('click', '.btn-copiar', function () {
        var id_sesiones = $(this).data('id');
        $("#form-new-conferencia")[0].reset();
        $('#modalConferenciasLabel').text('Crear conferencia nueva');
        $('#modalConferencias').modal('show');
        buscarConferenciaReplica(id_sesiones);
    });
    async function buscarConferenciaReplica(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `admin/buscarConferenciaReplica/${id_sesiones}`);
            let response = await peticion.json();
            $('#tipo').val('nuevo');
            $('#id_conferencia').val(response['id_sesiones']);
            $('#tema_sesion').val(response['Tema_sesion']);
            $('#fechaHora_inicio').val(response['Fecha_Hora_Inicio']);
            $('#fechaHoraFinalizacion').val(response['Fecha_Hora_Termino']);
            $('#descripcion').val(response['Descripcion']);
            $('#linkTransmision_nativo').val(response['Link_transmision_nativo']);
            $('#linkTransmision_traducida').val(response['Link_transmision_traducida']);
            $('#estatus').val(response['Estatus']);
            $('#permiso').val(response['Permisos']);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
});