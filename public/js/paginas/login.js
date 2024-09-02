$(function () {
    $(".btn-iniciar").click(function () {
        let form = $("#form-new-iniciar");
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.ajax({
                type: 'POST',
                url: servidor + 'login/acceso',
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
                            location.reload();
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
    // muestrar en carrusel salones
    async function cardsSalones() {
        try {
            let peticion = await fetch(servidor + `login/viewSalon`);
            let response = await peticion.json();
            console.log(response);
            if (response.length == 0) {
                jQuery(`<h3 class="mt-4 text-center text-uppercase"></h3>`).appendTo("#card-salon").addClass('text-danger');
                return false;
            }
            $("#card-salon").empty();
        } catch (error) {
            if (error.name == 'AbortError') { } else { throw error; }
        }
    }
    cardsSalones();
});