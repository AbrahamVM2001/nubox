$(function () {
    $('#tipo_contenido').val(tipo);
    $('#id_espacio').val(id_espacio);
    // mostrar contenido
    async function cardsContenido(id_espacio) {
        try {
            let peticion = await fetch(servidor + `admin/viewContenido/${id_espacio}`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin contenido registrados</h3>`).appendTo("#container-contenido").addClass('text-danger');
                return false;
            }
            $("#container-contenido").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Nombre Contenido</th>
                <th class="text-uppercase">Fecha Ingreso</th>
                <th class="text-uppercase">Token</th>
                <th class="text-uppercase">Ubicacion</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-contenido")
                .removeClass("text-danger");
            $('#info-table-result').DataTable({
                "drawCallback": function (settings) {
                    $('.paginate_button').addClass("btn").removeClass("paginate_button");
                    $('.dataTables_length').addClass('pull-left text-light');
                    $('#info-table-result_filter').addClass('pull-right text-light');
                    $('input').addClass("form-control");
                    $('select').addClass('form-control');
                    $('.previous.disabled').addClass("btn-light btn-rounded mx-2 mt-3");
                    $('.next.disabled').addClass("btn-light btn-rounded mx-2 mt-3");
                    $('.previous').addClass("btn-light btn-rounded mx-2 mt-3");
                    $('.next').addClass("btn-light btn-rounded mx-2 mt-3");
                },
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "pageLength": 5,
                "lengthMenu": [[5, 10, 15], [5, 10, "All"]],
                data: response,
                "columns": [
                    { "data": "nombre_espacio", className: 'text-vertical text-center' },
                    { "data": "fecha", className: 'text-vertical text-center' },
                    { "data": "token", className: 'text-vertical text-center' },
                    { "data": "ubicacion", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <button data-id="${btoa(data.id_asignacion_contenido)}" data-estatus="${btoa(data.estatus)}" data-bs-toggle="tooltip" title="Activa o desactiva la sesión" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
                                    </div>
                                </div>
                            `;
                            return botones;
                        },
                        className: 'text-vertical text-center'
                    }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-contenido')
                }
            });            
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsContenido(id_espacio);
    // guardar o modificar
    $(".btn-contenido").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: "POST",
                url: servidor + "admin/guardarContenido",
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
                        title: data.respuesta,
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
                },
            });
        }
        form.addClass("was-validated");
    });
    // buscar contenido
    $('body').on('dblclick', '#info-table-result tbody tr', function () {
        var data = $('#info-table-result').DataTable().row(this).data();
        if (data['id_asignacion_contenido'] == 0) {
            registroNoEditar();
        } else {
            $("#form-new-contenido")[0].reset();
            $('#modalContenidoLabel').text('Editar contenido');
            $('#modalContenido').modal('show');
            buscarContenido(data['id_asignacion_contenido']);
        }
    });
    async function buscarContenido(id_asignacion_contenido) {
        try {
            let peticion = await fetch(servidor + `admin/buscarContenido/${id_asignacion_contenido}`);
            let response = await peticion.json();
            console.log(response);
            $('#tipo').val('');
            $('#id_contenido').val(response['id_asignacion_contenido']);
            $('#mostrar-imagen').empty();
            $(`<img src="${servidor}${response.ubicacion}" alt="Imagen guardada">`)
                .appendTo("#mostrar-imagen");
        } catch (error) {
            if (error.name === 'AbortError') {
                console.log("Petición abortada.");
            } else {
                console.error("Error:", error);
                throw error;
            }
        }
    }
    // activar y desactivar estatus contenido
    $(document).on('click', '.btn-suspender', function () {
        suspender($(this).data('id'), $(this).data('estatus'));
    });
    function suspender(id_asignacion_contenido, estatus) {
        $.ajax({
            type: "POST",
            url: servidor + "admin/activar_desactivar_contenido",
            dataType: "json",
            data: { id_asignacion_contenido: id_asignacion_contenido, estatus: estatus },
            beforeSend: () => $("#loading").addClass("loading"),
            success: (data) => {
                Swal.fire({
                    position: "top-end",
                    icon: data.estatus,
                    title: data.titulo,
                    text: data.respuesta,
                    showConfirmButton: false,
                    timer: 2000,
                });
                setTimeout(() => location.reload(), 2000);
            },
            error: (data) => console.log("Error ajax", data),
            complete: () => $("#loading").removeClass("loading"),
        });
    }
});