$(function () {
    async function cardsAsignacion() {
        try {
            let peticion = await fetch(servidor + `admin/viewAsignacion`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin asignaciones</h3>`).appendTo("#container-asignacion").addClass('text-danger');
                return false;
            }
            $("#container-asignacion").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Nombre completo</th>
                <th class="text-uppercase">Nombre salon</th>
                <th class="text-uppercase">fecha inicio</th>
                <th class="text-uppercase">fecha final</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-asignacion")
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
                    { "data": "Nombre_completo", className: 'text-vertical text-center' },
                    { "data": "Nombre_salon", className: 'text-vertical text-center' },
                    { "data": "fecha_inicio", className: 'text-vertical text-center' },
                    { "data": "fecha_final", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let color = (data.estatus == 1) ? 'success' : 'danger';
                            let botones = `
                                <div class="col-sm-12 col-md-12 col-lg-12 col-<xl-12 d-flex justify-content-between align-items-center">
                                    <button data-id="${btoa(data.id_asignacion)}" data-estatus="${btoa(data.estatus)}" data-bs-toggle="tooltip" title="Activa o desactiva la sesión" type="button" class="btn btn-${color} btn-suspender"><i class="fa-solid fa-power-off"></i></button>
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
    cardsAsignacion();
});