$(function () {
    async function conferencia() {
        try {
            $('#titulo-conferencia').empty();
            let peticion = await fetch(servidor + `User/infoActualConferencia`);
            let response = await peticion.json();
            let tema = response[0].Tema_sesion;
            let id = response[0].id_sesiones;
            let transmision = response[0].Link_transmision_nativo;
            $('#titulo-conferencia').text(tema);
            $('#idSesion').val(id);
            $('#link_transmision').attr('src', transmision);
            $('#idSesionPregunta').val(id);
            console.log('cargando info conferencia...');
        } catch (err) {
            if (err.name == 'AbortError') { } else { throw err; }
        }
    }
    conferencia();

    let id_sesiones;
    let conn;

    window.onload = function () {
        id_sesiones = document.getElementById("idSesion").value;
        cardsComentarios(id_sesiones);
        conn = new WebSocket('ws://localhost:8080');

        conn.onopen = function (e) {
            console.log("Conexión establecida");
        };
        conn.onmessage = function (e) {
            var messageData = JSON.parse(e.data);
            // console.log(typeof messageData.tipo);
            if(messageData.cierre == 'cierre') {
                location.reload();
            } else if (messageData.votacion == 'votacion') {
                document.getElementById("votacion").contentWindow.location.reload();
            } else {
                cardsComentarios(id_sesiones);
            }
        };
    };

    async function cardsComentarios(id_sesiones) {
        try {
            let peticion = await fetch(servidor + `User/MostrarComentarios/${id_sesiones}`);
            let response = await peticion.json();
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase">Sin comentarios que mostrar</h3>`).appendTo("#card-body-mensaje").addClass('text-danger');
                return false;
            }
            $("#card-body-mensaje").empty();
            response.forEach((item, index) => {
                jQuery(`
                    <div class="comentario" id="comentario">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <p>${item.Nombre_Completo}</p>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <p>${item.Fecha_publicacion}</p>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <p>${item.Mensaje}</p>
                            </div>
                        </div>
                    </div>
                `).appendTo("#card-body-mensaje");
            });
        } catch (error) {
            if (error.name == 'AbortError') { } else {
                throw error;
            }
        }
    }
    $(".btn-publicar").click(function () {
        let form = $("#form-new-comentario");
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: servidor + 'User/RegistroComentario',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                    $("#loading").addClass('loading');
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.estatus,
                        title: "<span style='color: #fff'>" + data.titulo + "</span>",
                        html: "<span style='color: #fff'>" + data.respuesta + "</span>",
                        background: '#000818',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    if (data.estatus == 'success') {
                        form[0].reset();
                        form.removeClass('was-validated');
                        let comentario = document.getElementById('comentario').value;
                        conn.send(JSON.stringify({ comentario }));
                        document.getElementById('comentario').value = '';
                        cardsComentarios(id_sesiones);
                    }
                },
                error: function (data) {
                    console.log(data);
                },
                complete: function () {
                    $("#loading").removeClass('loading');
                }
            });
        }
        form.addClass('was-validated');
    });

    $(".btn-preguntar").click(function () {
        let form = $("#form-new-pregunta");
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: 'POST',
                url: servidor + 'User/RegistroPreguntas',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                    $("#loading").addClass('loading');
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.estatus,
                        title: "<span style='color: #fff'>" + data.titulo + "</span>",
                        html: "<span style='color: #fff'>" + data.respuesta + "</span>",
                        background: '#000818',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    if (data.estatus == 'success') {
                        let pregunta = "pregunta";
                        console.log(pregunta);
                        conn.send(JSON.stringify({ pregunta }));
                        document.getElementById('pregunta').value = '';
                        form[0].reset();
                        form.removeClass('was-validated');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
                complete: function () {
                    $("#loading").removeClass('loading');
                }
            });
        }
        form.addClass('was-validated');
    });
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
    // setInterval(cerrar, 1000);
});