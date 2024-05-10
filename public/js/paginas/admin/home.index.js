$(function () {
    $(".btn-empleado").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: "POST",
                url: servidor + "admin/guardarEmpleado",
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
    async function cardsEmpleado() {
        try {
            let peticion = await fetch(servidor + `admin/viewEmpleado`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin empleados asignadas</h3>`).appendTo("#container-empleado").addClass('text-danger');
                return false;
            }
            $("#container-empleado").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Nombre completo</th>
                <th class="text-uppercase">Edad</th>
                <th class="text-uppercase">Fecha nacimiento</th>
                <th class="text-uppercase">Curp</th>
                <th class="text-uppercase">Telefono</th>
                <th class="text-uppercase">Correo</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-empleado")
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
                    { "data": "Nombre_completo", className: 'text-vertical text-center' },
                    { "data": "Edad", className: 'text-vertical text-center' },
                    { "data": "Fecha_nacimiento", className: 'text-vertical text-center' },
                    { "data": "Curp", className: 'text-vertical text-center' },
                    { "data": "Telefono", className: 'text-vertical text-center' },
                    { "data": "correo", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="col-sm-12 col-md-12 col-lg-12 col-<xl-12 d-flex justify-content-between align-items-center">
                                    <button data-id="${btoa(data.id_empleado)}" data-estatus="${btoa(data.estatus)}" data-bs-toggle="tooltip" title="Activa o desactiva la sesión" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
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
    cardsEmpleado();
    $(document).on('click', '.btn-suspender', function () {
        var idSesion = $(this).data('id');
        var estatus = $(this).data('estatus');
        suspender(idSesion, estatus);
    });
    function suspender(idSesion, estatus) {
        $.ajax({
            type: "POST",
            url: servidor + "admin/ActDec_empleado",
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
    $('body').on('dblclick', '#info-table-result tbody tr', function () {
        var data = $('#info-table-result').DataTable().row(this).data();
        if (data['id_sesiones'] == 0) {
            registroNoEditar();
        } else {
            $("#form-new-empleado")[0].reset();
            $('#modalEmpleadoLabel').text('Editar empleado');
            $('#modalEmpleado').modal('show');
            buscarEmpleado(data['id_empleado']);
        }
    });
    async function buscarEmpleado(id_empleado) {
        try {
            let peticion = await fetch(servidor + `admin/buscarEmpleado/${id_empleado}`);
            let response = await peticion.json();
            $('#tipo').val('');
            $('#empleado').val(response['id_empleado']);
            $('#nombre').val(response['Nombre']);
            $('#apellidos').val(response['Apellidos']);
            $('#nacimiento').val(response['Fecha_nacimiento']);
            $('#edad').val(response['Edad']);
            $('#telefono').val(response['Telefono']);
            $('#curp').val(response['Curp']);
            $('#mail').val(response['correo']);
            $('#pass').val(response['password']);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
});