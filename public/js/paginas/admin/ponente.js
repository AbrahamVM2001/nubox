$(function () {
    async function cardsPreguntaPonente(id_sesion) {
        try {
            let peticion = await fetch(servidor + `admin/pregunta_ponenteB/${id_sesion}`);
            let response = await peticion.json();
            console.log(response);
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin preguntas</h3>`).appendTo("#container-preguntas-ponente").addClass('text-danger');
                return false;
            }
            $("#container-preguntas-ponente").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result-pregunta">
                <thead><tr>
                <th class="text-uppercase">Tema sesion</th>
                <th class="text-uppercase">Nombre completo</th>
                <th class="text-uppercase">Pregunta</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-preguntas-ponente")
                .removeClass("text-danger");
            $('#info-table-result-pregunta').DataTable({
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
                "pageLength": 50,
                "lengthMenu": [[50, 100, 150], [50, 100, "All"]],
                data: response,
                "columns": [
                    { "data": "Tema sesion", className: 'text-vertical text-center' },
                    { "data": "Nombre completo", className: 'text-vertical text-center' },
                    { "data": "Pregunta", className: 'text-vertical text-center'}
    
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-preguntas')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsPreguntaPonente(id_sesion);
    conn = new WebSocket('ws://localhost:8080');

    conn.onopen = function (e) {
        console.log("Conexión establecida");
    };

    conn.onmessage = function (e) {
        var messageData = JSON.parse(e.data);
        console.log(messageData);
        // console.log(typeof messageData.tipo);
        console.log(messageData.pregunta);
        if (messageData.pregunta === 'pregunta') {
            cardsPreguntaPonente(id_sesion);
        } else if(messageData.acceso == 'acceso') {
            cardTotalEstado(id_sesion);
            cardTotalPais(id_sesion);
        } else if(messageData.ponente == 'ponente'){
            $("#container-preguntas-ponente").empty();
            cardsPreguntaPonente(id_sesion);
        }else {
            cardChatgrupal(id_sesion);
        }
    };
    async function cardChatgrupal(id_sesion) {
        try {
            let peticion = await fetch(servidor + `admin/chatGrupal_ponenteB/${id_sesion}`);
            let response = await peticion.json();
            console.log(response);
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin chat todavia</h3>`).appendTo("#container-comentarios-chat").addClass('text-danger');
                return false;
            }
            $("#container-comentarios-chat").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result-chat">
                <thead><tr>
                <th class="text-uppercase">Nombre completo</th>
                <th class="text-uppercase">Mensaje</th>
                <th class="text-uppercase">Fecha_publicacion</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-comentarios-chat")
                .removeClass("text-danger");
            $('#info-table-result-chat').DataTable({
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
                "pageLength": 50,
                "lengthMenu": [[50, 100, 150], [50, 100, "All"]],
                data: response,
                "columns": [
                    { "data": "Nombre completo", className: 'text-vertical text-center' },
                    { "data": "Mensaje", className: 'text-vertical text-center' },
                    { "data": "Fecha publicacion", className: 'text-vertical text-center'}
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-chat')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardChatgrupal(id_sesion);
    async function cardTotalPais(id_sesion) {
        try {
            let peticion = await fetch(servidor + `admin/totalPais_ponenteB/${id_sesion}`);
            let response = await peticion.json();
            console.log(response);
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin visitas pais</h3>`).appendTo("#container-total-pais").addClass('text-danger');
                return false;
            }
            $("#container-total-pais").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result-pais">
                <thead><tr>
                <th class="text-uppercase">Pais</th>
                <th class="text-uppercase">Total de usuarios logeados</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-total-pais")
                .removeClass("text-danger");
            $('#info-table-result-pais').DataTable({
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
                "pageLength": 50,
                "lengthMenu": [[50, 100, 150], [50, 100, "All"]],
                data: response,
                "columns": [
                    { "data": "Pais", className: 'text-vertical text-center' },
                    { "data": "Total de usuarios logeados", className: 'text-vertical text-center' }
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-pais')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardTotalPais(id_sesion);
    async function cardTotalEstado(id_sesion) {
        try {
            let peticion = await fetch(servidor + `admin/totalEstado_ponenteB/${id_sesion}`);
            let response = await peticion.json();
            console.log(response);
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin visitas estados</h3>`).appendTo("#container-total-estado").addClass('text-danger');
                return false;
            }
            $("#container-total-estado").empty();
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result-estado">
                <thead><tr>
                <th class="text-uppercase">Estado</th>
                <th class="text-uppercase">Total de usuarios logeados</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-total-estado")
                .removeClass("text-danger");
            $('#info-table-result-estado').DataTable({
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
                "pageLength": 50,
                "lengthMenu": [[50, 100, 150], [50, 100, "All"]],
                data: response,
                "columns": [
                    { "data": "Estado", className: 'text-vertical text-center' },
                    { "data": "Total de usuarios logeados", className: 'text-vertical text-center' }
    
                ],
                createRow: function (row, data, dataIndex) {
                    $(row).addClass('tr-estados')
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardTotalEstado(id_sesion);
    // setInterval(function() {
    //     cardsPreguntaPonente();
    //     cardChatgrupal();
    //     cardTotalPais();
    //     cardTotalEstado();
    // }, 5000);
});