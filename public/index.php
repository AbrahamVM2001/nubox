<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Tiempo Real</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="chat">
        <div id="chat-window"></div>
        <input type="text" id="message-input" placeholder="Escribe un mensaje...">
        <button onclick="enviarMensaje()">Enviar</button>
    </div>

    <script>
        var conn = new WebSocket('ws://localhost:8080');

        conn.onopen = function(e) {
            console.log("Conexión establecida");
        };

        conn.onmessage = function(e) {
            var chatWindow = document.getElementById('chat-window');
            chatWindow.innerHTML += '<p>' + e.data + '</p>';
        };

        function enviarMensaje() {
            var mensaje = document.getElementById('message-input').value;
            conn.send(mensaje);
            // Limpiar el campo de entrada después de enviar el mensaje
            document.getElementById('message-input').value = '';
        }
    </script>
</body>
</html>
