function generateQR(data) {
  const qrcode = new QRCode("qr-code-container", {
    width: 200,
    height: 200,
  });
  qrcode.addData(data);
  qrcode.make();
}

document.getElementById("qrForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const data = document.getElementById("data").value;
  generateQR(data);
});

document.getElementById("sendButton").addEventListener("click", function () {
  // Agrega aquí la lógica para enviar el código QR, por ejemplo, a través de una solicitud AJAX.
  alert("Código QR enviado.");
});
