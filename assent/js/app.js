// Evento para validar el código QR ingresado
btnValidar.addEventListener("click", function() {
    var codigoQR = codigoQRInput.value;
  
    // Enviamos el código QR al back-end para su validación
    $.ajax({
      url: "/validar-codigo-qr",
      method: "POST",
      data: {
        codigoQR: codigoQR
      },
      success: function(respuesta) {
        // Si la respuesta es exitosa, mostramos el mensaje
        if (respuesta.exito) {
          $("#validar").html("Código QR válido");
        } else {
          // Si la respuesta no es exitosa, mostramos el error
          $("#validar").html(respuesta.mensaje);
        }
      }
    });
  });
  