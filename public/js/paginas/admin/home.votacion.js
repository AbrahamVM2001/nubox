$(function () {
    let conn;
    conn = new WebSocket('ws://localhost:8080');

    conn.onopen = function (e) {
        console.log("Conexión establecida");
    };
    async function cardsVotacion(id_pregunta) {
        try {
            let peticion = await fetch(servidor + `admin/MostrarVotacion/${id_pregunta}`);
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
                let opcionesHTML = preguntas[pregunta].map(opcion => `
                    <div class="opcion">
                        <p style="font-size: 32px;">${opcion.Opcion}</p>
                        <div class="progress red">
                            <div class="progress-bar" style="width:${opcion.Porcentaje}%;background:#f80a0a;"></div>
                        </div>
                    </div>
                `).join("");
                jQuery(`
                    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
                        <div class="row" style="background-color: #fff;">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h1>${pregunta}</h1>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                ${opcionesHTML}
                            </div>  
                        </div>
                    </div>
                `).appendTo("#panel-votacion");
            }
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsVotacion(id_pregunta);

    setTimeout(() => {
        cardsVotacion(id_pregunta);
        setInterval(() => {
            cardsVotacion(id_pregunta);
        }, 5000);
    }, 20500);


    function suspender(id_pregunta) {
        var id_pregunta_base64 = btoa(id_pregunta);
        $.ajax({
            type: "POST",
            url: servidor + "admin/activaDesactivaPorSegundo",
            dataType: "json",
            data: {
                id_pregunta: id_pregunta_base64
            },
            beforeSend: function () {
                $("#loading").addClass("loading");
            },
            success: function (data) {
                console.log(data);
                // Swal.fire({
                //     position: "top-end",
                //     icon: data.estatus,
                //     title: data.titulo,
                //     text: data.respuesta,
                //     showConfirmButton: false,
                //     timer: 2000,
                // });
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
    function suspenderDespuesDe20Segundos(id_pregunta) {
        setTimeout(function () {
            suspender(id_pregunta);
        }, 20000);
    }
    suspenderDespuesDe20Segundos(id_pregunta);
    setTimeout(() => {
        let votacion = 'votacion';
        conn.send(JSON.stringify({ votacion }));
        window.close();
    }, 30000);
});