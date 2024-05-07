$(function () {
    let conn;
    conn = new WebSocket('ws://localhost:8080');

    conn.onopen = function (e) {
        console.log("Conexión establecida");
    };
    async function cardsLogeo() {
        try {
            let peticion = await fetch(servidor + `admin/muestraLogeo`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin logeos todavia</h3>`).appendTo("#container-logeo").addClass('text-danger');
                return false;
            }
            $("#container-logeo").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Usuario</th>
                <th class="text-uppercase">Informacion del modelo</th>
                <th class="text-uppercase">Direccion</th>
                <th class="text-uppercase">Fecha y Hora</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-logeo")
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
                    { "data": "Nombre_Completo", className: 'text-vertical text-center' },
                    { "data": "infoModelo", className: 'text-vertical text-center' },
                    { "data": "Direccion", className: 'text-vertical text-center' },
                    { "data": "FechaTiempo", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="col-sm-12 col-md-12 col-lg-12 col-<xl-12 d-flex justify-content-between align-items-center">
                                    <button data-id="${btoa(data.id_fk_usuario)}" data-estatus="${btoa(data.estatus)}" data-bs-toggle="tooltip" title="Prohibir el acceso del usuario" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
                                </div>`;
                            return botones;
                        },
                        className: 'text-vertical text-center'
                    }

                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-logeo')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsLogeo();
    $(document).on('click', '.btn-suspender', function () {
        var idUsuario = $(this).data('id');
        var estatus = $(this).data('estatus');
        suspender(idUsuario, estatus);
    });
    function suspender(idUsuario, estatus) {
        $.ajax({
            type: "POST",
            url: servidor + "admin/desahabilitar_usuario",
            dataType: "json",
            data: {
                idUsuario: idUsuario,
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
                    let cierre = 'cierre';
                    conn.send(JSON.stringify({ cierre }));
                    
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
});