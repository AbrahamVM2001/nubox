<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../../assets/img/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Matemasie&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Verificar |
        <?= constant('SOCIEDAD') ?>
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="<?= constant('URL') ?>public/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@latest/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@latest/swiper-bundle.min.js"></script>
    <!-- calendario -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <!-- maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body class="bg-gray-100">
    <!-- preloader -->
    <div id="preloader">
        <section class="loader">
            <div>
                <div>
                    <span class="one h6"></span>
                    <span class="two h3"></span>
                </div>
            </div>
            <div>
                <div>
                    <span class="one h1"></span>
                </div>
            </div>
            <div>
                <div>
                    <span class="two h2"></span>
                </div>
            </div>
            <div>
                <div>
                    <span class="one h4"></span>
                </div>
            </div>
        </section>
    </div>
    <!-- pantalla de carga para procesar -->
    <div id="precesar">
        <div class="procesando">
            <span>Procesando...</span>
        </div>
    </div>
    <!-- menu -->
    <nav>
        <div class="wrapper">
            <div class="logo"><a href="<?= constant('URL') ?>"><img src="<?= constant('URL') ?>public/img/logo.png" width="10px" alt="logo"></a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">
                <li>
                    <div class="group">
                        <span>Inicio</span>
                        <a href="<?= constant('URL') ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="#000000" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m1.5 10.5l9-9l9 9" />
                                    <path d="M3.5 8.5v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7" />
                                </g>
                            </svg>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <span>Oficinas</span>
                        <a href="<?= constant('URL') ?>login/oficinas/">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 22V8c0-2.828 0-4.243-.879-5.121C12.243 2 10.828 2 8 2s-4.243 0-5.121.879C2 3.757 2 5.172 2 8v8c0 2.828 0 4.243.879 5.121C3.757 22 5.172 22 8 22zM6.5 11h-1m5 0h-1m-3-4h-1m1 8h-1m5-8h-1m1 8h-1m9 0h-1m1-4h-1m.5-3h-4v14h4c1.886 0 2.828 0 3.414-.586S22 19.886 22 18v-6c0-1.886 0-2.828-.586-3.414S19.886 8 18 8" color="#000000" />
                            </svg>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <span>Salones</span>
                        <a href="<?= constant('URL') ?>login/salones/">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#000000" d="M2.692 21.5q.61-1.85.809-3.755t.249-3.841q-.975-.298-1.612-1.183Q1.5 11.837 1.5 10.5V9.346q2.76-.912 5.622-2.765T12 2.616q2.016 2.111 4.878 3.965T22.5 9.346V10.5q0 1.337-.638 2.221q-.637.885-1.612 1.183q.05 1.936.249 3.841t.809 3.755zm1.054-12h16.508q-2.335-1.023-4.407-2.435T12 4.008q-1.775 1.644-3.847 3.057Q6.081 8.477 3.746 9.5M14.5 13q.914 0 1.457-.755T16.5 10.5h-4q0 .99.543 1.745T14.5 13m-5 0q.914 0 1.457-.755T11.5 10.5h-4q0 .99.543 1.745T9.5 13m-5 0q.914 0 1.457-.755T6.5 10.5h-4q0 .99.543 1.745T4.5 13m-.504 7.5h3.771q.264-1.692.399-3.37q.134-1.676.19-3.388q-.398-.183-.748-.493T7 12.45q-.356.664-.937 1.065t-1.313.468q-.05 1.655-.194 3.283t-.56 3.234m4.777 0h6.454q-.258-1.625-.39-3.24q-.131-1.616-.187-3.266q-.842.046-1.562-.39q-.72-.435-1.088-1.225q-.367.79-1.093 1.226t-1.557.39q-.056 1.65-.187 3.265q-.132 1.615-.39 3.24m7.46 0h3.77q-.414-1.606-.559-3.234q-.144-1.627-.194-3.283q-.73-.068-1.335-.469q-.603-.4-.915-1.102q-.22.507-.588.827q-.37.32-.768.503q.056 1.712.193 3.389t.396 3.369M19.5 13q.914 0 1.457-.755T21.5 10.5h-4q0 .99.543 1.745T19.5 13" />
                            </svg>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <span>Sesión</span>
                        <a data-bs-toggle="modal" data-bs-target="#iniciarModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#000000" d="m15 13l-4 4v-3H2v-2h9V9zM5 20v-4h2v2h10v-7.81l-5-4.5L7.21 10H4.22L12 3l10 9h-3v8z" />
                            </svg>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- home -->
    <div class="container d-flex justify-content-center align-items-center text-center" style="height: 100vh;" id="verificar">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <form class="form needs-validation" id="form-new-verificacion" action="javascript:;" novalidate method="post">
                    <div class="title">Verificación Número</div>
                    <p class="message">En tu correo se envio un número, ingresalo por favor.</p>
                    <div class="inputs">
                        <input id="numero1" name="numero1" type="text" maxlength="1" oninput="focusNext('numero1', 'numero2')">
                        <input id="numero2" name="numero2" type="text" maxlength="1" oninput="focusNext('numero2', 'numero3')">
                        <input id="numero3" name="numero3" type="text" maxlength="1" oninput="focusNext('numero3', 'numero4')">
                        <input id="numero4" name="numero4" type="text" maxlength="1">
                    </div>
                    <button data-formulario="form-new-verificacion" type="button" class="action btn-verificar">Verificar</button>
                </form>
            </div>
        </div>
    </div>
    <!-- model de inciar sesión -->
    <div class="modal fade" id="iniciarModal" tabindex="-1" aria-labelledby="iniciarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content modal-sesion-estilo">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLibrosLabel">Iniciar sesión</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form id="form-new-iniciar" action="javascript:;" class="needs-validation" novalidate method="post">
                            <input type="hidden" name="fechaActual" id="fechaActual">
                            <input type="hidden" name="ubicacion" id="ubicacion">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
                                    <label for="inicar">Correo Electronico <span>*</span></label>
                                    <input type="email" name="correo" id="correo" class="form-control mt-2" placeholder="example@nubox.com" required>
                                    <div class="invalid-feedback">
                                        Ingresa un correo electronico valido, por favor,
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
                                    <label for="contraseña">Contraseña</label>
                                    <input type="password" name="pass" id="pass" class="form-control mt-2" placeholder="Contraseña..." required>
                                    <button class="btn btn-light btn-ver-password mt-3">Ver contraseña</button><br>
                                    <a class="btn btn-light btn-recuperar mt-2" href="<?= constant('URL') ?>login/password">Olvide mi contraseña</a>
                                    <div class="invalid-feedback">
                                        Ingresa una contraseña valida, por favor.
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button data-formulario="form-new-iniciar" type="button" class="btn btn-primary btn-iniciar">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <footer class="py-5 mt-5" style="background-color: #f6f6f630;" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <h5>Navegacion rápida</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Inicio</a></li>
                        <li class="nav-item mb-2"><a href="#about" class="nav-link p-0 text-body-secondary">Quienes somos</a></li>
                        <li class="nav-item mb-2"><a href="#carrusel-salones" class="nav-link p-0 text-body-secondary">Salones</a></li>
                        <li class="nav-item mb-2"><a href="#carrusel-oficinas" class="nav-link p-0 text-body-secondary">Oficinas</a></li>
                        <li class="nav-item mb-2"><a href="#informacion" class="nav-link p-0 text-body-secondary">Información</a></li>
                        <li class="nav-item mb-2"><a href="#objetivo" class="nav-link p-0 text-body-secondary">Servicios</a></li>
                        <li class="nav-item mb-2"><a href="#opinion" class="nav-link p-0 text-body-secondary">CEO</a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-4 mt-4">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-body-secondary">Salones</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-body-secondary">Oficinas</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-body-secondary">Aviso y privacidad</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-body-secondary">Términos y condiciones</a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-4">
                    <img src="<?= constant('URL') ?>public/img/logo.png" alt="logo" width="150px" height="150px">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 border-end border-dark">
                    <p style="font-size: 14px;">©<span class="fecha" id="fecha"></span> Reservados todos los derechos. Ing. <a href="https://devabraham.com/" style="text-decoration: none; color: #000; font-weight: 500;">Abraham Vera Martinez</a> | Sitio web de México</p>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 d-flex align-items-end justify-content-end">
                    <ul class="list-unstyled d-flex">
                        <li class="ms-3">
                            <a href="https://www.linkedin.com/in/abraham-vera-713789181/">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                    <circle cx="4" cy="4" r="2" fill="#000000" fill-opacity="0">
                                        <animate fill="freeze" attributeName="fill-opacity" dur="0.15s" values="0;1" />
                                    </circle>
                                    <g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
                                        <path stroke-dasharray="12" stroke-dashoffset="12" d="M4 10v10">
                                            <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.15s" dur="0.2s" values="12;0" />
                                        </path>
                                        <path stroke-dasharray="12" stroke-dashoffset="12" d="M10 10v10">
                                            <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.45s" dur="0.2s" values="12;0" />
                                        </path>
                                        <path stroke-dasharray="24" stroke-dashoffset="24" d="M10 15c0 -2.76 2.24 -5 5 -5c2.76 0 5 2.24 5 5v5">
                                            <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.65s" dur="0.2s" values="24;0" />
                                        </path>
                                    </g>
                                </svg>
                            </a>
                        </li>
                        <li class="ms-3">
                            <a href="https://github.com/AbrahamVM2001">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <path stroke-dasharray="32" stroke-dashoffset="32" d="M12 4c1.67 0 2.61 0.4 3 0.5c0.53 -0.43 1.94 -1.5 3.5 -1.5c0.34 1 0.29 2.22 0 3c0.75 1 1 2 1 3.5c0 2.19 -0.48 3.58 -1.5 4.5c-1.02 0.92 -2.11 1.37 -3.5 1.5c0.65 0.54 0.5 1.87 0.5 2.5c0 0.73 0 3 0 3M12 4c-1.67 0 -2.61 0.4 -3 0.5c-0.53 -0.43 -1.94 -1.5 -3.5 -1.5c-0.34 1 -0.29 2.22 0 3c-0.75 1 -1 2 -1 3.5c0 2.19 0.48 3.58 1.5 4.5c1.02 0.92 2.11 1.37 3.5 1.5c-0.65 0.54 -0.5 1.87 -0.5 2.5c0 0.73 0 3 0 3">
                                            <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.7s" values="32;0" />
                                        </path>
                                        <path stroke-dasharray="12" stroke-dashoffset="12" d="M9 19c-1.41 0 -2.84 -0.56 -3.69 -1.19c-0.84 -0.63 -1.09 -1.66 -2.31 -2.31">
                                            <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.8s" dur="0.2s" values="12;0" />
                                        </path>
                                    </g>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- scripts -->
    <script>
        let servidor = '<?= constant('URL') ?>';
        let id_usuario = '<?= $this->confirmar ?>';
    </script>
    <script src="<?= constant("URL") ?>public/js/plugins/jquery/jquery.min.js"></script>
    <script src="<?= constant('URL') ?>public/js/core/popper.min.js"></script>
    <script src="<?= constant('URL') ?>public/js/core/bootstrap.min.js"></script>
    <script src="<?= constant('URL') ?>public/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="<?= constant('URL') ?>public/js/plugins/smooth-scrollbar.min.js"></script>
    <!-- Kanban scripts -->
    <script src="<?= constant('URL') ?>public/js/plugins/dragula/dragula.min.js"></script>
    <script src="<?= constant('URL') ?>public/js/plugins/jkanban/jkanban.js"></script>
    <!-- Select2 -->
    <script src="<?php echo constant("URL"); ?>public/plugins/select2/js/select2.full.min.js"></script>
    <!-- Github buttons -->
    <script async defer src="<?= constant('URL') ?>public/js/github.buttons.js"></script>
    <script src="<?= constant('URL') ?>public/js/fontawesome-4f9827e774.js"></script>
    <script src="<?= constant('URL') ?>public/js/sweetalert.min.js"></script>
    <!-- calendario -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <!-- mapa -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- <script src="https://sdk.mercadopago.com/js/v2"></script> -->
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script>
        $(window).on('load', function() {
            $('#preloader').delay(100).fadeOut('slow', function() {
                $(this).remove();
            });
        });
    </script>
    <script>
        function actualizarFecha() {
            const fechaElemento = document.getElementById('fecha');
            const ahora = new Date();

            const anio = ahora.getFullYear();

            const fechaFormateada = `${anio}`;
            fechaElemento.textContent = fechaFormateada;
        }

        setInterval(actualizarFecha, 1000);

        document.addEventListener('DOMContentLoaded', actualizarFecha);
    </script>
    <!-- password -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const togglePasswordButton = document.querySelector('.btn-ver-password');
            const passwordField = document.querySelector('#pass');
            togglePasswordButton.addEventListener('click', () => {
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                togglePasswordButton.textContent = type === 'password' ? 'Ver contraseña' : 'Ocultar contraseña';
            });
        });
    </script>
    <!-- funcion de saltar al siguiente campo -->
    <script>
        function focusNext(currentId, nextId) {
            const currentInput = document.getElementById(currentId);
            if (currentInput.value.length >= currentInput.maxLength) {
                document.getElementById(nextId).focus();
            }
        }
    </script>
    <script src="<?= constant('URL') ?>public/js/paginas/home.verificar.js"></script>
</body>

</html>