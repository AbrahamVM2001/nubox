$(function () {
    // carga de pago
    
    // mostrar espacios
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
    // Inicializar Stripe
    const stripe = Stripe('pk_test_51PxwgDK9TllkJ0UIEN1qLYcxJApO6vqMxXM8EiouAWJxEV3Xw61olZOsqQMb9mRPljxMErEzjQiCRc54k1qTVAfX00nickPbYO'); // Reemplaza con tu clave pública
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    // Escuchar el botón de reservación
    $(".btn-reservar").click(async function (event) {
        event.preventDefault();
        let form = $("#form-new-reservacion");
        
        if (form[0].checkValidity() === false) {
            event.stopPropagation();
        } else {
            const { token, error } = await stripe.createToken(card);
            
            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                $("#stripeToken").val(token.id);
                console.log(token.id);
                
                $.ajax({
                    type: 'POST',
                    url: servidor + 'login/procesamientoPago/',
                    dataType: 'json',
                    data: form.serialize(),
                    beforeSend: function () {
                        $("#precesar").show();
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
                                location.href = servidor + "login" + "/" + "salir";
                            }, 2000);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    },
                    complete: function () {
                        $("#precesar").hide(); 
                    }
                });
            }
        }
        form.addClass('was-validated');
    });
});