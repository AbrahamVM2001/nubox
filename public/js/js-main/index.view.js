document.getElementById('startTutorial').addEventListener('click', function () {
    document.querySelector('.overlay-tutorial').style.display = 'flex';
    document.getElementById('textAnimation').textContent = "";

    let currentStep = 1; // Variable para rastrear el progreso del tutorial

    function typeWriter(text, callback1, callback2 = null) {
        let charIndex = 0;
        const speed = 50;
        const textElement = document.getElementById('textAnimation');
        const textLength = text.length;

        function type() {
            if (charIndex < textLength) {
                textElement.textContent += text.charAt(charIndex);
                charIndex++;
                setTimeout(type, speed);
            } else {
                if (callback1) {
                    callback1();
                }
                if (callback2) {
                    setTimeout(callback2, 500);
                }
            }
        }

        type();
    }

    function nextStep() {
        switch (currentStep) {
            case 1:
                document.getElementById('textAnimation').textContent = "";
                document.querySelector('.clic-menu').style.display = 'block';
                typeWriter("El menú te ayudará a poder navegar sobre la aplicación", function () {
                    currentStep++;
                });
                break;
            case 2:
                document.querySelector('.clic-menu').style.display = 'none';
                document.getElementById('textAnimation').textContent = "";
                document.querySelector('.slider-circle').style.display = 'block';
                typeWriter("Podrás navegar por los diferentes libros deslizando de izquierda a derecha!", function () {
                    currentStep++;
                });
                break;
            case 3:
                document.getElementById('textAnimation').textContent = "";
                document.querySelector('.clic-busqueda').style.display = 'block';
                typeWriter("Puedes buscar los libros en tiempo real por autor, editorial, idioma.", function () {
                    currentStep++;
                });
                break;
            case 4:
                document.querySelector('.clic-busqueda').style.display = 'none';
                document.getElementById('textAnimation').textContent = "";
                document.querySelector('.clic-libro').style.display = 'block';
                typeWriter("Para leer el libro solo necesitas dar doble clic en la portada del libro.", function () {
                    currentStep++;
                });
                break;
            case 5:
                document.querySelector('.clic-libro').style.display = 'none';
                document.getElementById('textAnimation').textContent = "";
                typeWriter("Gracias por siguir el tutorial cualquier duda relacionada puedes llamar a soporte!, da clic en siguiente para terminar el tutorial.", function(){
                    currentStep++;
                });
                break;
            default:
                closeTutorial();
                break;
        }
    }

    function closeTutorial() {
        document.querySelector('.overlay-tutorial').style.display = 'none';
        currentStep = 1; // Reiniciar el progreso del tutorial
    }

    document.getElementById('siguienteBtn').addEventListener('click', function () {
        this.disabled = true;
        nextStep();
        setTimeout(() => {
            document.getElementById('siguienteBtn').disabled = false;
        }, 5000);
    });

    document.getElementById('cerrarBtn').addEventListener('click', function () {
        closeTutorial();
    });
    typeWriter("¡Bienvenido al tutorial! Aquí aprenderás cómo utilizar nuestra aplicación");
});
