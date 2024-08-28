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
    <title>Login |
        <?= constant('SOCIEDAD') ?>
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
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
    <!-- menu -->
    <nav>
        <div class="wrapper">
            <div class="logo"><a href="#"><img src="img/logo/dark-prueba.png" alt="logo"></a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">
                <li>
                    <div class="group">
                        <span>Inicio</span>
                        <a href="#">
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
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 22V8c0-2.828 0-4.243-.879-5.121C12.243 2 10.828 2 8 2s-4.243 0-5.121.879C2 3.757 2 5.172 2 8v8c0 2.828 0 4.243.879 5.121C3.757 22 5.172 22 8 22zM6.5 11h-1m5 0h-1m-3-4h-1m1 8h-1m5-8h-1m1 8h-1m9 0h-1m1-4h-1m.5-3h-4v14h4c1.886 0 2.828 0 3.414-.586S22 19.886 22 18v-6c0-1.886 0-2.828-.586-3.414S19.886 8 18 8" color="#000000" />
                            </svg>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <span>Salones</span>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#000000" d="M2.692 21.5q.61-1.85.809-3.755t.249-3.841q-.975-.298-1.612-1.183Q1.5 11.837 1.5 10.5V9.346q2.76-.912 5.622-2.765T12 2.616q2.016 2.111 4.878 3.965T22.5 9.346V10.5q0 1.337-.638 2.221q-.637.885-1.612 1.183q.05 1.936.249 3.841t.809 3.755zm1.054-12h16.508q-2.335-1.023-4.407-2.435T12 4.008q-1.775 1.644-3.847 3.057Q6.081 8.477 3.746 9.5M14.5 13q.914 0 1.457-.755T16.5 10.5h-4q0 .99.543 1.745T14.5 13m-5 0q.914 0 1.457-.755T11.5 10.5h-4q0 .99.543 1.745T9.5 13m-5 0q.914 0 1.457-.755T6.5 10.5h-4q0 .99.543 1.745T4.5 13m-.504 7.5h3.771q.264-1.692.399-3.37q.134-1.676.19-3.388q-.398-.183-.748-.493T7 12.45q-.356.664-.937 1.065t-1.313.468q-.05 1.655-.194 3.283t-.56 3.234m4.777 0h6.454q-.258-1.625-.39-3.24q-.131-1.616-.187-3.266q-.842.046-1.562-.39q-.72-.435-1.088-1.225q-.367.79-1.093 1.226t-1.557.39q-.056 1.65-.187 3.265q-.132 1.615-.39 3.24m7.46 0h3.77q-.414-1.606-.559-3.234q-.144-1.627-.194-3.283q-.73-.068-1.335-.469q-.603-.4-.915-1.102q-.22.507-.588.827q-.37.32-.768.503q.056 1.712.193 3.389t.396 3.369M19.5 13q.914 0 1.457-.755T21.5 10.5h-4q0 .99.543 1.745T19.5 13" />
                            </svg>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="group">
                        <span>Sesión</span>
                        <a href="#">
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
    <section id="inicio" class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="container text-center">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h1 class="text">Renta de oficinas y salones</h1>
                    <p>"Espacios Modernos, Negocios Exitosos"</p>
                </div>
            </div>
        </div>
    </section>
    <!-- quines somo -->
    <section id="about" class="mt-2">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card" style="height: 200px;">
                        <div class="card-title text-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24">
                                <path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.998 2C8.99 2 7.04 4.019 4.734 4.755c-.938.3-1.407.449-1.597.66c-.19.21-.245.519-.356 1.135c-1.19 6.596 1.41 12.694 7.61 15.068c.665.255.998.382 1.61.382s.946-.128 1.612-.383c6.199-2.373 8.796-8.471 7.606-15.067c-.111-.616-.167-.925-.357-1.136s-.658-.36-1.596-.659C16.959 4.019 15.006 2 11.998 2M12 7v2" color="#000000" />
                            </svg>
                        </div>
                        <div class="card-body text-center">
                            <h2>Confianza</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card" style="height: 200px;">
                        <div class="card-title text-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24">
                                <g fill="none" fill-rule="evenodd">
                                    <path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                    <path fill="#000000" d="M10.753 2.197a2 2 0 0 0-1.662.182l-4.12 2.472A2 2 0 0 0 4 6.566V20H3a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2h-1V6.72a2 2 0 0 0-1.367-1.896zM18 20V6.72l-7-2.332V20zM9 4.766l-3 1.8V20h3z" />
                                </g>
                            </svg>
                        </div>
                        <div class="card-body text-center">
                            <h3>Espacios Modernos</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card" style="height: 200px;">
                        <div class="card-title text-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24">
                                <g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="#000000">
                                    <path d="M12 9c-1.105 0-2 .672-2 1.5s.895 1.5 2 1.5s2 .672 2 1.5s-.895 1.5-2 1.5m0-6c.87 0 1.612.417 1.886 1M12 9V8m0 7c-.87 0-1.612-.417-1.886-1M12 15v1" />
                                    <path d="M11.998 2C8.99 2 7.04 4.019 4.734 4.755c-.938.3-1.407.449-1.597.66c-.19.21-.245.519-.356 1.135c-1.19 6.596 1.41 12.694 7.61 15.068c.665.255.998.382 1.61.382s.946-.128 1.612-.383c6.199-2.373 8.796-8.471 7.606-15.067c-.111-.616-.167-.925-.357-1.136s-.658-.36-1.596-.659C16.959 4.019 15.006 2 11.998 2" />
                                </g>
                            </svg>
                        </div>
                        <div class="card-body text-center">
                            <h5>Pagos seguros desde linea</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card" style="height: 200px;">
                        <div class="card-title text-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24">
                                <path fill="#000000" fill-rule="evenodd" d="M5.25 7.7c0-3.598 3.059-6.45 6.75-6.45c3.608 0 6.612 2.725 6.745 6.208l.478.16c.463.153.87.289 1.191.439c.348.162.667.37.911.709s.341.707.385 1.088c.04.353.04.78.04 1.269v5.748c0 .61 0 1.13-.047 1.547c-.05.438-.161.87-.463 1.237a2.3 2.3 0 0 1-.62.525c-.412.237-.855.276-1.296.253c-.42-.022-.933-.107-1.534-.208l-.041-.007c-1.293-.215-1.814-.296-2.322-.254q-.278.023-.552.083c-.498.109-.976.342-2.159.933l-.122.061c-1.383.692-2.234 1.118-3.154 1.251q-.415.06-.835.06c-.928-.002-1.825-.301-3.28-.786l-.127-.043l-.384-.128l-.037-.012c-.463-.154-.87-.29-1.191-.44c-.348-.162-.667-.37-.911-.709s-.341-.707-.385-1.088c-.04-.353-.04-.78-.04-1.269v-5.02c0-.786 0-1.448.067-1.967c.07-.542.23-1.072.666-1.47a2.3 2.3 0 0 1 .42-.304c.517-.287 1.07-.27 1.605-.166q.164.032.342.078q-.1-.67-.1-1.328m.499 3.01a9 9 0 0 0-1.028-.288c-.395-.077-.525-.03-.586.004a1 1 0 0 0-.14.101c-.053.048-.138.156-.19.556c-.053.41-.055.974-.055 1.825v4.93c0 .539.001.88.03 1.138c.028.238.072.327.112.381c.039.055.109.125.326.226c.236.11.56.219 1.07.39l.384.127c1.624.541 2.279.75 2.936.752q.31 0 .617-.044c.65-.094 1.276-.397 2.82-1.17l.093-.046c1.06-.53 1.714-.857 2.417-1.01q.37-.081.747-.113c.717-.06 1.432.06 2.593.253l.1.017c.655.109 1.083.18 1.407.196c.312.016.419-.025.471-.055a.8.8 0 0 0 .207-.175c.039-.047.097-.146.132-.456c.037-.323.038-.757.038-1.42v-5.667c0-.539-.001-.88-.03-1.138c-.028-.238-.072-.327-.112-.381c-.039-.055-.109-.125-.326-.226c-.236-.11-.56-.219-1.07-.39l-.06-.019a10.7 10.7 0 0 1-1.335 3.788c-.912 1.568-2.247 2.934-3.92 3.663a3.5 3.5 0 0 1-2.794 0c-1.673-.73-3.008-2.095-3.92-3.663a11 11 0 0 1-.934-2.087M12 2.75c-2.936 0-5.25 2.252-5.25 4.95c0 1.418.437 2.98 1.23 4.341c.791 1.362 1.908 2.47 3.223 3.044c.505.22 1.089.22 1.594 0c1.316-.574 2.432-1.682 3.224-3.044c.792-1.36 1.229-2.923 1.229-4.34c0-2.699-2.314-4.951-5.25-4.951m0 4a1.25 1.25 0 1 0 0 2.5a1.25 1.25 0 0 0 0-2.5M9.25 8a2.75 2.75 0 1 1 5.5 0a2.75 2.75 0 0 1-5.5 0" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="card-body text-center">
                            <h5>Mejores ubicaciones</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-4 mt-5"></div>
                <div class="col-sm-12 col-md-12 col-lg-4 mt-5">
                    <h2>Quienes somos</h2>
                    <p>Nubox: tu espacio, nuestros soluciones. Somos una empresa Mexicana en la cdmx, especializada en ofrecer oficinas y salones
                        para eventos. Creando entornos inspiradores, flexibles y completamente equipados, estamos aquí para hacer tus reuniones
                        y eventos inolvidables. ¡Con Nubox, tus ideas se vuelven realidad!.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <script>
        let servidor = '<?= constant('URL') ?>';
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
    <script src="<?= constant('URL') ?>public/js/paginas/login.js"></script>
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
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="<?= constant('URL') ?>public/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
</body>

</html>