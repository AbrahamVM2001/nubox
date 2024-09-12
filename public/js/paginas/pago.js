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
    document.querySelector('.btn-reservar').addEventListener('click', function (e) {
        e.preventDefault();
    
        let form = document.getElementById('form-new-reservacion');
        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
            return;
        }
    
        let stripe = Stripe('pk_test_51PxwgDK9TllkJ0UIEN1qLYcxJApO6vqMxXM8EiouAWJxEV3Xw61olZOsqQMb9mRPljxMErEzjQiCRc54k1qTVAfX00nickPbYO');
        let elements = stripe.elements();
    
        stripe.createToken(elements).then(function (result) {
            if (result.error) {
                console.log(result.error.message);
            } else {
                let formData = new FormData(form);
                formData.append('stripeToken', result.token.id);
    
                fetch('https://nubox.devabraham.com/', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.estatus === 'success') {
                        alert(data.respuesta);
                    } else {
                        alert(data.respuesta);
                    }
                })
                .catch(error => console.log('Error:', error));
            }
        });
    });
});