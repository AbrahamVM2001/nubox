document.getElementById('startTutorial').addEventListener('click', function () {
    document.querySelector('.overlay-tutorial').style.display = 'flex';
    document.getElementById('textAnimation').textContent = "";

    let currentStep = 1;

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
                document.querySelector('.circle-clic-libro').style.display = 'block';
                typeWriter("Solo da clic sobre el libro de tu preferencia.", function() {
                    currentStep++;
                });
                break;
            case 5:
                closeTutorial();
            default:
                break;
        }
    }

    function closeTutorial() {
        document.querySelector('.overlay-tutorial').style.display = 'none';
        currentStep = 1;
    }

    document.getElementById('siguienteBtn').addEventListener('click', function() {
        nextStep();
    });

    document.getElementById('cerrarBtn').addEventListener('click', function() {
        closeTutorial();
    });
    typeWriter("¡Bienvenido al tutorial! Aquí aprenderás cómo utilizar nuestra aplicación");
});
