$(function () {
    $(document).on('click', '#recargar', function () {
        location.reload();
    });
    async function conferencia() {
        try {
            $('#titulo-conferencia').empty();
            let peticion = await fetch(servidor + `User/infoActualConferencia`);
            let response = await peticion.json();
            let id = response[0].id_sesiones;
            $('#idSesion').val(id);
            console.log('cargando info conferencia...');
            if (id) {
                cardsVotacion(id);
            }
        } catch (err) {
            if (err.name == 'AbortError') { } else { throw err; }
        }
    }
    conferencia();

    let id_sesiones;
    let conn;

    window.onload = function () {
        id_sesiones = document.getElementById("idSesion").value;
        conn = new WebSocket('ws://localhost:8080');

        conn.onopen = function (e) {
            console.log("Conexión establecida votacion");
        };

        conn.onmessage = function (e) {
            var messageData = JSON.parse(e.data);
            // console.log(typeof messageData.tipo);
            if (messageData.votacion === 'votacion') {
                location.reload();
            } else {
                console.log("Sin valor");
            }
        };
    };
    async function cardsVotacion(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `User/MostrarVotacion/${id_sesiones}`);
            let response = await peticion.json();
            console.log(response);
            $("#panel-votacion").empty();
            if (response.length == 0) {
                jQuery(`
                    <h3 class="mt-4 text-center text-uppercase" style="color: #ff0000; font-size: 18px;">
                        No hay votaciones disponibles <span style="color: #999999; font-size: 10px">(De clic en el botón recargar para visualizar la pregunta cuando el ponente lo pida)</span> 
                        <button id="recargar"><i class="fa-solid fa-arrows-rotate"></i></button>
                    </h3>
                `).appendTo("#panel-votacion").addClass('text-danger');
                return false;
            }
            $("#panel-votacion").empty();
            let preguntas = {};
            response.forEach((item, index) => {
                if (!preguntas.hasOwnProperty(item.Pregunta)) {
                    preguntas[item.Pregunta] = [];
                }
                preguntas[item.Pregunta].push(item);
            });
            for (const pregunta in preguntas) {
                let idPregunta = preguntas[pregunta][0].id_pregunta;
                let contador = 1;
                jQuery(`
                    <div class="comentario" id="comentario">
                        <form id="form-resp-pregunta" action="javascript:;" class="needs-validation" novalidate method="post">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <input type="hidden" id="idPregunta" name="idPregunta" value="${idPregunta}">
                                    <p>${pregunta}</p>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                ${preguntas[pregunta].map(opcion => `
                                    <p>
                                        ${opcion.opcion}
                                        <input type="hidden" name="idOpcion[]" class="idOpcion" value="${opcion.id_opcion}">
                                        <input class="form-check-input opcion-checkbox" type="radio" name="opcion" value="${opcion.correcta}" required>
                                    </p>
                                    `).join("")}
                                    <button data-formulario="form-resp-pregunta" type="button" class="btn-respuesta">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                `).appendTo("#panel-votacion");
            }
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsVotacion(id_sesiones);
    // En el evento click del botón de respuesta
    $(document).on('click', '.btn-respuesta', function () {
        const formId = $(this).data("formulario");
        const form = $("#" + formId);

        if (form.length) {
            if (form[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                const idOpcionSeleccionado = form.find('.opcion-checkbox:checked').siblings('.idOpcion').val();
                const formData = new FormData(form.get(0));
                formData.append('idOpcion', idOpcionSeleccionado);
                $.ajax({
                    type: "POST",
                    url: servidor + "User/respuesta",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $("#loading").addClass("loading");
                    },
                    success: function (data) {
                        console.log(data);
                        Swal.fire({
                            position: "top-end",
                            icon: data.estatus,
                            title: "<span style='color: #fff'>" + data.titulo + "</span>",
                            html: "<span style='color: #fff'>" + data.respuesta + "</span>",
                            background: '#000818',
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        setTimeout(() => {
                            $("#panel-votacion").empty();
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
        } else {
            console.error("Form element with ID", formId, "not found.");
        }
        form.addClass("was-validated");
    });

    // setTimeout(() => {
    //     location.reload();
    // }, 5000);
    // document.addEventListener('contextmenu', function(e) {
    //     e.preventDefault();
    // });
    // function cerrar() {
    //     $.ajax({
    //         type: "POST",
    //         url: servidor + "User/comprobar",
    //         dataType: "json",
    //         data: {},
    //         beforeSend: function () {
    //             $("#loading").addClass("loading");
    //         },
    //         success: function (data) {
    //             console.log(data);
    //             if (data.estatus === 'warning') {
    //                 Swal.fire({
    //                     position: "top-end",
    //                     icon: data.estatus,
    //                     title: data.titulo,
    //                     text: data.respuesta,
    //                     showConfirmButton: false,
    //                     timer: 2000,
    //                 }).then(() => {
    //                     location.reload();
    //                 });
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.log("Error ajax: " + error);
    //         },
    //         complete: function () {
    //             $("#loading").removeClass("loading");
    //         },
    //     });
    // }
    // setInterval(cerrar,1000);
});
