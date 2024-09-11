$(function () {
    async function cardsEspacio(id_espacio) {
        try {
            let peticion = await fetch(servidor + `login/espacio/${id_espacio}`);
            let response = await peticion.json();
            $('#pago_dia').val(response[0].precio_hora);
        } catch (error) {
            if (error.name == 'AbortError') {
            } else {
                console.error('Error al cargar los datos:', error);
            }
        }
    }
    cardsEspacio(id_espacio);
    // funcion del pago
    $(".btn-reservacion").click(function () {
        let form = $("#form-new-reservacion");
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: 'POST',
                url: servidor + 'login/pago',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                    $("#loading").addClass('loading');
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.estatus,
                        title: data.titulo,
                        text: data.respuesta,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    if (data.estatus === 'success') {
                        setTimeout(() => {
                            location.href=servidor+"login"+"/"+"pago"+"/"+id_espacio
                        }, 2000);
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
});