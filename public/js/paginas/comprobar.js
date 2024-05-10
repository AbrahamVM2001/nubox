$(function () {
    let conn;
    conn = new WebSocket('ws://localhost:8080');

    conn.onmessage = function (e) {
        var messageData = JSON.parse(e.data);
        // console.log(typeof messageData.tipo);
        if (messageData.cierre === 'cierre') {
            cerrar();
            location.reload();
        } else {
            console.log("Sin valor");
        }
    };
    function cerrar() {
        $.ajax({
            type: "POST",
            url: servidor + "admin/comprobar",
            dataType: "json",
            data: {},
            beforeSend: function () {
                $("#loading").addClass("loading");
            },
            success: function (data) {
                console.log(data);
                if (data.estatus === 'warning') {
                    Swal.fire({
                        position: "top-end",
                        icon: data.estatus,
                        title: data.titulo,
                        text: data.respuesta,
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log("Error ajax: " + error);
            },
            complete: function () {
                $("#loading").removeClass("loading");
            },
        });
    }
});