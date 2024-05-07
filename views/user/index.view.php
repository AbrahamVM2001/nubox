<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../../assets/img/favicon.png">
    <title>Login |
        <?= constant('SOCIEDAD') ?>
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?= constant('URL') ?>public/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?= constant('URL') ?>public/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="<?= constant('URL') ?>public/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= constant('URL') ?>public/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
    <link href="<?= constant('URL') ?>public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- particulas -->

    <div id="particles-js"></div>

    <!-- informacion de loader -->
    <div id="preloader">
        <div id="preloader-container">
            <div id="page">
                <div id="container-preloader">
                    <div id="ring"></div>
                    <div id="ring"></div>
                    <div id="ring"></div>
                    <div id="ring"></div>
                    <div id="h3"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="128" viewBox="0 0 24 24"><path fill="#ffffff" d="M21.33 12.91c.09 1.55-.62 3.04-1.89 3.95l.77 1.49c.23.45.26.98.06 1.45c-.19.47-.58.84-1.06 1l-.79.25a1.687 1.687 0 0 1-1.86-.55L14.44 18c-.89-.15-1.73-.53-2.44-1.1c-.5.15-1 .23-1.5.23c-.88 0-1.76-.27-2.5-.79c-.53.16-1.07.23-1.62.22c-.79.01-1.57-.15-2.3-.45a4.105 4.105 0 0 1-2.43-3.61c-.08-.72.04-1.45.35-2.11c-.29-.75-.32-1.57-.07-2.33C2.3 7.11 3 6.32 3.87 5.82c.58-1.69 2.21-2.82 4-2.7c1.6-1.5 4.05-1.66 5.83-.37c.42-.11.86-.17 1.3-.17c1.36-.03 2.65.57 3.5 1.64c2.04.53 3.5 2.35 3.58 4.47c.05 1.11-.25 2.2-.86 3.13c.07.36.11.72.11 1.09m-5-1.41c.57.07 1.02.5 1.02 1.07a1 1 0 0 1-1 1h-.63c-.32.9-.88 1.69-1.62 2.29c.25.09.51.14.77.21c5.13-.07 4.53-3.2 4.53-3.25a2.592 2.592 0 0 0-2.69-2.49a1 1 0 0 1-1-1a1 1 0 0 1 1-1c1.23.03 2.41.49 3.33 1.3c.05-.29.08-.59.08-.89c-.06-1.24-.62-2.32-2.87-2.53c-1.25-2.96-4.4-1.32-4.4-.4c-.03.23.21.72.25.75a1 1 0 0 1 1 1c0 .55-.45 1-1 1c-.53-.02-1.03-.22-1.43-.56c-.48.31-1.03.5-1.6.56c-.57.05-1.04-.35-1.07-.9a.968.968 0 0 1 .88-1.1c.16-.02.94-.14.94-.77c0-.66.25-1.29.68-1.79c-.92-.25-1.91.08-2.91 1.29C6.75 5 6 5.25 5.45 7.2C4.5 7.67 4 8 3.78 9c1.08-.22 2.19-.13 3.22.25c.5.19.78.75.59 1.29c-.19.52-.77.78-1.29.59c-.73-.32-1.55-.34-2.3-.06c-.32.27-.32.83-.32 1.27c0 .74.37 1.43 1 1.83c.53.27 1.12.41 1.71.4c-.15-.26-.28-.53-.39-.81a1.038 1.038 0 0 1 1.96-.68c.4 1.14 1.42 1.92 2.62 2.05c1.37-.07 2.59-.88 3.19-2.13c.23-1.38 1.34-1.5 2.56-1.5m2 7.47l-.62-1.3l-.71.16l1 1.25zm-4.65-8.61a1 1 0 0 0-.91-1.03c-.71-.04-1.4.2-1.93.67c-.57.58-.87 1.38-.84 2.19a1 1 0 0 0 1 1c.57 0 1-.45 1-1c0-.27.07-.54.23-.76c.12-.1.27-.15.43-.15c.55.03 1.02-.38 1.02-.92"/></svg></div>
                </div>
            </div>
        </div>
    </div>

    <!-- contenedores -->
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                <p id="titulo-conferencia" class="title-conferencia"></p>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <iframe id="link_transmision" class="link_transmision" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="top:0;left:0;width:100%;height:100%;"></iframe>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <iframe id="votacion" allowfullscreen="" id="web2" name="web2" height="500" src="http://localhost/conferencias/user/votacion" width="100%" border="0" frameborder="0"></iframe>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 text-center" style="margin-top: 20px;">
                <div class="card-chat">
                    <div class="card-body-titulo">chat grupal</div>
                    <div id="card-body-mensaje" class="card-body-mensaje table-responsive">
                    </div>
                    <div class="card-body-form text-center">
                        <form id="form-new-comentario" action="javascript:;" name="form-comentario" class="needs-validation" novalidate="post">
                            <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo isset($_SESSION['id_usuario-' . constant('Sistema')]) ? $_SESSION['id_usuario-' . constant('Sistema')] : ''; ?>">
                            <input type="hidden" id="idSesion" name="idSesion">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <input type="text" class="form-control" name="comentario" id="comentario" aria-label="comentario" required>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <button data-formulario="form-new-comentario" type="button" class="btn btn-warning btn-publicar">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 text-center" style="margin-top: 20px;">
                <div class="card-pregunta">
                    <div class="card-pregunta-titulo">
                        Escribe una pregunta al ponente
                    </div>
                    <div class="card-body-caja">
                        <div class="card-body-panel">
                            <div class="card-form">
                                <form id="form-new-pregunta" action="javascript:;" name="form-pregunta" class="needs-validation" novalidate="post">
                                    <input type="hidden" id="idUsuarioPregunta" name="idUsuarioPregunta" value="<?php echo isset($_SESSION['id_usuario-' . constant('Sistema')]) ? $_SESSION['id_usuario-' . constant(('Sistema'))] : ''; ?>">
                                    <input type="hidden" id="idSesionPregunta" name="idSesionPregunta">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <input type="text" class="form-control" name="pregunta" id="pregunta" aria-label="pregunta" required>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <button data-formulario="form-new-pregunta" type="button" class="btn btn-success btn-preguntar">ENVIAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <p class="mb-0 text-secondary">
                        Copyright ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> <a href="https://grupolahe.com/" target="_blank">Grupo Lahe</a> | <a href="https://www.linkedin.com/in/francisco-arenal-g" target="_blank">Francisco Arenal</a>.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <script>
        let servidor = '<?= constant('URL') ?>';
    </script>
    <!-- comprobar estatus -->
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js" integrity="sha384-2huaZvOR9iDzHqslqwpR87isEmrfxqyWOF7hr7BY6KG0+hVKLoEXMPUJw3ynWuhO" crossorigin="anonymous"></script>
    <!-- loader -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?= constant("URL") ?>public/js/paginas/loader.js"></script>
    <!-- script generales  -->
    <script src="<?= constant("URL") ?>public/js/scoket.js/scoket.io.js"></script>
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
    <script src="<?= constant('URL') ?>public/js/paginas/user/home.transmision.js"></script>
    <script src="<?= constant('URL') ?>public/js/particulas-lib/particles.js"></script>
    <script src="<?= constant('URL') ?>public/js/particulas-lib/app.js"></script>
    <script src="<?= constant('URL') ?>public/js/particulas-lib/stats.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="<?= constant('URL') ?>public/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
</body>

</html>