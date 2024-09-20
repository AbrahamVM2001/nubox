$(function () {
    // mostrar las asignaciones del espacio
    async function cardsUsuario() {
        try {
            let peticion = await fetch(servidor + `usuario/asignacion`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin asignaciones de eventos</h3>`).appendTo("#container-eventos").addClass('text-danger');
                return false;
            }
            $("#container-eventos").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Nombre del cliente</th>
                <th class="text-uppercase">Espacio</th>
                <th class="text-uppercase">Fecha pago</th>
                <th class="text-uppercase">Monto</th>
                <th class="text-uppercase">Numero tarjeta</th>
                <th class="text-uppercase">Fecha Inicio de la reserva</th>
                <th class="text-uppercase">Fecha Finalizacion de la reserva</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-eventos")
                .removeClass("text-danger");
            $('#info-table-result').DataTable({
                "drawCallback": function (settings) {
                    $('.paginate_button').addClass("btn").removeClass("paginate_button");
                    $('.dataTables_length').addClass('pull-left');
                    $('#info-table-result_filter').addClass('pull-right');
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
                    { "data": "Nombre_del_cliente", className: 'text-vertical text-center' },
                    { "data": "Espacio", className: 'text-vertical text-center' },
                    { "data": "Fecha_pago", className: 'text-vertical text-center' },
                    { "data": "Monto", className: 'text-vertical text-center' },
                    { "data": "Numero_tarjeta", className: 'text-vertical text-center' },
                    { "data": "Fecha_inicio", className: 'text-vertical text-center' },
                    { "data": "Fecha_finalizacion", className: 'text-vertical text-center' },
                    {
                        data: null,
                        render: function (data) {
                            let botones = `
                                <style>
                                    .group {
                                        position: relative;
                                        display: inline-block;
                                    }   

                                    .group button {
                                        background: none;
                                        border: none;
                                        cursor: pointer;
                                    }   

                                    .group svg {
                                        width: 2rem;
                                        transition: transform 0.2s, stroke 0.2s;
                                    }   

                                    .group svg:hover {
                                        transform: scale(1.25);
                                        stroke: #3b82f6;
                                    }   

                                    .group span {
                                        position: absolute;
                                        bottom: 100%;
                                        left: 50%;
                                        transform: translateX(-50%) translateY(-0.5rem) scale(0);
                                        z-index: 20;
                                        padding: 0.5rem 1rem;
                                        border-radius: 0.5rem;
                                        border: 1px solid #d1d5db;
                                        background-color: #fff;
                                        color: #000;
                                        font-size: 0.875rem;
                                        font-weight: bold;
                                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                        transition: transform 0.3s ease-in-out;
                                    }   

                                    .group:hover span {
                                        transform: translateX(-50%) translateY(-0.5rem) scale(1);
                                    }
                                </style>
                                <div class="row">
                                    <div class="col-sm-3 mx-4">
                                        <div class="group">
                                            <span>Generar reporte</span>
                                            <a class="btn btn-danger btn-generar-reporte" data-id="${btoa(btoa(data.fk_usuario))}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="#fff" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m-1-4h2V7h-2zm-2.75 8L3 15.75v-7.5L8.25 3h7.5L21 8.25v7.5L15.75 21zm.85-2h5.8l4.1-4.1V9.1L14.9 5H9.1L5 9.1v5.8zm2.9-7"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mx-4">
                                        <div class="group">
                                            <span>Marcar como terminado</span>
                                            <a class="btn btn-success btn-finalizado" data-id="${btoa(data.id_asignacion_usuario_reservacion)}" data-estatus="${btoa(data.estatus)}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><g fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2"><path d="m9 10l3.258 2.444a1 1 0 0 0 1.353-.142L20 5"/><path d="M21 12a9 9 0 1 1-6.67-8.693"/></g></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            return botones;
                        },
                        className: 'text-vertical text-center'
                    }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-asignacion')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsUsuario();
    // marcar como terminado
    $(document).on('click', '.btn-finalizado', function () {
        suspender($(this).data('id'), $(this).data('estatus'));
    });
    function suspender(id_asignacion_usuario_reservacion, estatus) {
        $.ajax({
            type: "POST",
            url: servidor + "usuario/terminado",
            dataType: "json",
            data: { id_asignacion_usuario_reservacion: id_asignacion_usuario_reservacion, estatus: estatus },
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
    // generar el reporte
    $('body').on('click', '.btn-generar-reporte', function () {
        $("#form-new-reporte")[0].reset();
        $('#modalReporte').modal('show');
        buscarUsuario($(this).data('id'));
    });
    async function buscarUsuario(id_usuario) {
        try {
            let peticion = await fetch(servidor + `usuario/buscarUsuario/${id_usuario}`);
            let response = await peticion.json();
            $('#id_usuario').val(response['id_usuario']);
            $('#correo').val(response['correo']);
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    $(".btn-reporte").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            if ($('#titulo').val().length == 0 || $('#desc').val().length == 0) {
                Swal.fire("Por favor llenar todos los campos por favor!");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: servidor + "usuario/generarReporte",
                    dataType: "json",
                    data: new FormData(form.get(0)),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $("#modalReporte").hide();
                        $("#precesar").show();
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
                    complete: function () {
                        $("#precesar").hide(); 
                    }
                });
            }
        }
        form.addClass("was-validated");
    });
});