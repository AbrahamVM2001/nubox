$(function () {

    let conn;
    conn = new WebSocket('ws://localhost:8080');

    conn.onopen = function (e) {
        console.log("Conexión establecida");
    };

    $(".btn-votacion").on("click", function () {
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: "POST",
                url: servidor + "admin/guardarVotacion",
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
            let peticion = await fetch(servidor + `admin/conferenciaVotacion`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin conferencia asignadas</h3>`).appendTo("#container-votacion").addClass('text-danger');
                return false;
            }
            $("#container-votacion").empty();
            $('#id_sesion').val(response[0].id_sesiones);
            jQuery(`<table class="table align-items-center mb-0 table table-striped table-bordered" style="width:100%" id="info-table-result">
                <thead><tr>
                <th class="text-uppercase">Tema</th>
                <th class="text-uppercase">Fecha y Hora inicio</th>
                <th class="text-uppercase">Fecha y Hora termino</th>
                <th class="text-uppercase">Descripcion</th>
                <th class="text-uppercase">Acciones</th>
                </tr></thead>
                </table>
                `)
                .appendTo("#container-votacion")
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
                            botones = `
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex justify-content-between align-items-center" >
                                <button data-id="${btoa(btoa(data.id_sesiones))}" data-bs-toggle="tooltip" title="power" type="button" class="btn btn-info btn-modal-votos"><i class="fa-solid fa-question"></i></button>
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
    async function sesion(identificador, actual = null) {
        try {
            $(identificador).empty();
            let peticion = await fetch(servidor + `admin/conferenciaVotacion`);
            let response = await peticion.json();
            let option_select = document.createElement("option")
            option_select.value = '';
            option_select.text = 'Seleccionar sesión...';
            $(identificador).append(option_select);
            for (let item of response) {
                let option = document.createElement("option")
                option.value = item.id_sesiones;
                option.text = item.Tema_sesion;
                if (actual != null) {
                    if (item.id_sesiones == actual) {
                        option.selected = true;
                    }
                }
                $(identificador).append(option)
            }
            console.log('cargando sesion...');
        } catch (err) {
            if (err.name == 'AbortError') { } else { throw err; }
        }
    }
    sesion('#sesion', actual = null);
    $(document).ready(function () {
        var contador = 1;

        function agregarOpcion() {
            var opcionHtml = `
                <div class="row mt-2 opcion">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Opcion Correcta" name="opcion_${contador}" id="opcion_${contador}">
                    </div>
                    <div class="col">
                        <select class="form-select" name="respuesta_${contador}" id="respuesta_${contador}">
                            <option value="1">Correcta</option>
                            <option value="0">Incorrecta</option>
                        </select>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-danger btn-eliminar-opcion" data-id="${contador}"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#opciones-container').append(opcionHtml);
            contador++;
        }

        $('.btn-agregar-opcion').click(function () {
            agregarOpcion();
        });

        $(document).on('click', '.btn-eliminar-opcion', function () {
            $(this).closest('.opcion').remove();
        });
    });
    $(document).ready(function () {
        $(document).on('click', '.btn-modal-votos', function () {
            $("#modalVotacion").modal("show");
            $(".modal-body-crear").hide();
            $(".modal-body-mostrar").show();
            var id_sesiones = $(this).data('id');
            buscarVotacion(id_sesiones);
        });
        $(".btn-agregar-votacion").click(function () {
            $("#modalVotacion").modal("show");
            $(".modal-body-crear").show();
            $(".modal-body-mostrar").hide();
        });
    });
    async function buscarVotacion(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `admin/buscarVotacion/${id_sesiones}`);
            let response = await peticion.json();
            let linkHtml = '';
            let preguntas = {};
            response.forEach(opcion => {
                if (!preguntas[opcion.Pregunta]) {
                    preguntas[opcion.Pregunta] = [];
                }
                preguntas[opcion.Pregunta].push(opcion);
            });
            for (const pregunta in preguntas) {
                linkHtml += `
                <div class="accordion" id="accordion-${preguntas[pregunta][0].id_pregunta}">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse-${preguntas[pregunta][0].id_pregunta}" aria-expanded="true" 
                                aria-controls="collapse-${preguntas[pregunta][0].id_pregunta}"
                                style="background-color: #f0eded; color: #000; font-weight: bold;">
                                <p>${pregunta}</p>
                            </button>
                        </h2>
                        <div id="collapse-${preguntas[pregunta][0].id_pregunta}" class="accordion-collapse collapse show" 
                            data-bs-parent="#accordion-${preguntas[pregunta][0].id_pregunta}">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
                                        <h6>Acciones</h6>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <button type="button" class="btn btn-success btn-activar" data-id="${preguntas[pregunta][0].id_pregunta}" data-estatus="${preguntas[pregunta][0].estatus}"><i class="fa-solid fa-power-off"></i></button>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <button type="button" class="btn btn-success btn-conteo" data-id="${preguntas[pregunta][0].id_pregunta}" data-estatus="${preguntas[pregunta][0].estatus}">Conteo <i class="fa-regular fa-clock"></i></button>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <button type="button" class="btn btn-danger btn-elimar" data-id="${preguntas[pregunta][0].id_pregunta}">Eliminar <i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </div>
                                <form id="form-new">
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
                                            <label>Agregar opción</label>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <input type="hidden" id="id_pregunta" value="${preguntas[pregunta][0].id_pregunta}">
                                            <input type="hidden" id="id_sesion" value="${preguntas[pregunta][0].id_sesiones}">
                                            <input type="text" name="newOpcion" id="newOpcion" class="form-control" required>
                                            <div class="invalid-feedback">
                                                Ingrese el titulo de la pregunta, por favor.
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <select class="form-select" name="newRespuesta" id="newRespuesta">
                                                <option value="1">Correcta</option>
                                                <option value="2">Incorrecta</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-lg-4 d-flex justify-content-end align-items-center">
                                            <button data-formulario="form-new" type="button" class="btn btn-success btn-guardar"><i class="fa-solid fa-floppy-disk"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <form id="form-new-opcion" action="javascript:;" class="needs-validation" novalidate method="post">
                                    <label>Opciones ya guardadas</label>
                                    <div class="row">`;
                preguntas[pregunta].forEach(opcion => {
                    linkHtml += `
                                        <div class="col-md-6 col-lg-4">
                                            <input class="form-control" type="text" name="opcion-${opcion.id_opcion}" id="opcion-${opcion.id_opcion}" value="${opcion.Opciones}">
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <select class="form-select" name="respuesta-${opcion.id_opcion}" id="respuesta-${opcion.id_opcion}">
                                                <option value="1" ${opcion.Correcta ? 'selected' : ''}>Correcta</option>
                                                <option value="0" ${!opcion.Correcta ? 'selected' : ''}>Incorrecta</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-lg-4 d-flex justify-content-end align-items-center">
                                            <button type="button" class="btn btn-success btn-editar-opcion"
                                                data-id="${opcion.id_opcion}"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <button type="button" class="btn btn-danger btn-eliminar-opcion"
                                                data-id="${opcion.id_opcion}"><i class="fa-solid fa-trash"></i></button>
                                        </div>`;
                });
                linkHtml += `
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
            $("#container-preguntas").html(linkHtml);
            $(".btn-activar").each(function () {
                let estatus = parseInt($(this).data("estatus"));
                if (estatus === 1) {
                    $(this).removeClass("btn-danger").addClass("btn-success").text("Desactivar");
                } else if (estatus === 0) {
                    $(this).removeClass("btn-success").addClass("btn-danger").text("Activar");
                }
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    $(document).on("click", ".btn-editar-opcion", function () {
        var opcionID = $(this).data("id");
        var form = $(this).closest("form");

        var opcionInput = form.find(`#opcion-${opcionID}`);
        var respuestaSelect = form.find(`#respuesta-${opcionID}`);

        var formData = {
            id_opcion: opcionID,
            opcion: opcionInput.val(),
            correcta: respuestaSelect.val()
        };

        console.log(formData);

        $.ajax({
            type: "POST",
            url: servidor + "admin/actualizarVotacion",
            dataType: "json",
            data: formData,
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
            },
            complete: function () {
                $("#loading").removeClass("loading");
            },
        });
    });
    $(document).on("click", ".btn-activar", function () {
        var id_pregunta = $(this).data('id');
        var estatus = $(this).data('estatus');
        suspender(id_pregunta, estatus);
    });


    function suspender(id_pregunta, estatus) {
        var id_pregunta_base64 = btoa(id_pregunta);
        $.ajax({
            type: "POST",
            url: servidor + "admin/activaDesactiva",
            dataType: "json",
            data: {
                id_pregunta: id_pregunta_base64,
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
                if (data.respuesta == "Se activo la votacion") {
                    setTimeout(() => {
                        let votacion = 'votacion';
                        conn.send(JSON.stringify({ votacion }));
                        var url = servidor + 'admin/votacion/' + id_pregunta_base64;
                        window.open(url);
                    }, 2000);
                    setTimeout(() => {
                        location.reload();
                    }, 24000);
                } else {
                    setTimeout(() => {
                        let votacion = 'votacion';
                        console.log(votacion);
                        conn.send(JSON.stringify({ votacion }));
                        location.reload();
                    }, 2000);
                }
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
    $(document).on("click", ".btn-conteo", function () {
        var id_pregunta = $(this).data('id');
        var estatus = $(this).data('estatus');
        conteo(id_pregunta, estatus);
    });


    function conteo(id_pregunta, estatus) {
        var id_pregunta_base64 = btoa(id_pregunta);
        $.ajax({
            type: "POST",
            url: servidor + "admin/activaDesactiva",
            dataType: "json",
            data: {
                id_pregunta: id_pregunta_base64,
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
                if (data.respuesta == "Se activo la votacion") {
                    setTimeout(() => {
                        var url = servidor + 'admin/votacion/' + id_pregunta_base64;
                        window.open(url);
                    }, 2000);
                    setTimeout(() => {
                        location.reload();
                    }, 24000);
                } else {
                    setTimeout(() => {
                        let votacion = 'votacion';
                        location.reload();
                    }, 2000);
                }
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
    $(document).on("click", ".btn-guardar", function () {
        // Recopilar datos del formulario
        let form = $("#" + $(this).data("formulario"));
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            var formData = {
                idPregunta: $("#id_pregunta").val(),
                opcion: $("#newOpcion").val(),
                correcta: $("#newRespuesta").val(),
                idSesion: $("#id_sesion").val()
            };
            console.log(formData);
            $.ajax({
                type: "POST",
                url: servidor + "admin/guardarnewVotacion",
                dataType: "json",
                data: formData,
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
                },
                complete: function () {
                    $("#loading").removeClass("loading");
                },
            });
        }
        form.addClass("was-validated");
    });
    $(document).on("click", ".btn-elimar", function () {
        var idOpcion = $(this).data("id");
        elimarPregunta(idOpcion);
    });
    function elimarPregunta(idOpcion) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "¿Estás seguro?",
            text: "Si eliminas, no hay vuelta atrás..",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminarlo",
            cancelButtonText: "No, cancelar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: servidor + `admin/borrarOpcion/${idOpcion}`,
                    dataType: "json",
                    beforeSend: function () {
                        $("#loading").addClass("loading");
                    },
                    success: function (data) {
                        console.log(data);
                        swalWithBootstrapButtons.fire({
                            title: data.titulo,
                            text: data.respuesta,
                            icon: data.estatus
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error: function (xhr, status, error) {
                        console.log("Error ajax");
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    },
                    complete: function () {
                        $("#loading").removeClass("loading");
                    },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Cancelado",
                    text: "¡UF!, estuvo cerca; la opcion sigue intacta.",
                    icon: "error"
                });
            }
        });
    }
    $(document).on("click", ".btn-eliminar-opcion", function () {
        var idPregunta = $(this).data("id");
        elimarOpcion(idPregunta);
    });
    function elimarOpcion(idPregunta) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "¿Estás seguro?",
            text: "Se eliminará todo lo relacionado con la pregunta, no habrá vuelta atrás.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminarlo",
            cancelButtonText: "No, cancelar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: servidor + `admin/borrarOpcion/${idPregunta}`,
                    dataType: "json",
                    beforeSend: function () {
                        $("#loading").addClass("loading");
                    },
                    success: function (data) {
                        console.log(data);
                        swalWithBootstrapButtons.fire({
                            title: data.titulo,
                            text: data.respuesta,
                            icon: data.estatus
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error: function (xhr, status, error) {
                        console.log("Error ajax");
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    },
                    complete: function () {
                        $("#loading").removeClass("loading");
                    },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Cancelado",
                    text: "¡UF!, estuvo cerca; la votación sigue intacta.",
                    icon: "error"
                });
            }
        });
    }
});