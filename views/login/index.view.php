<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= constant('SOCIEDAD') ?></title>
    <?php require('views/estilos.view.php'); ?>
</head>

<body>
    <a href="https://front.codes/" class="logo" target="_blank">
        <img src="<?= constant('LOGOTIPO') ?>" alt="Logotipo">
    </a>

    <input class="menu-icon" type="checkbox" id="menu-icon" name="menu-icon" />
    <label for="menu-icon"></label>
    <nav class="nav">
        <ul class="pt-10">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#salones">Salones</a></li>
            <li><a href="#oficinas">Oficinas</a></li>
            <li><a class="#" data-bs-toggle="modal" data-bs-target="#iniciarModal">Iniciar sesion</a></li>
        </ul>
    </nav>

    <!-- modal -->

    <div class="modal fade" id="modalIniciar" aria-hidden="true" aria-labelledby="modalIniciarLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalIniciarLabel">Crear nueva conferencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-new-iniciar" action="javascript:;" class="needs-validation" novalidate method="post">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="">Correo</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="correo..." required>
                                <div class="invalid-feedback">
                                    Ingrese tu correo, por favor.
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="">Contraseña</label>
                                <input type="text" class="form-control" name="pass" id="pass" placeholder="contraseña..." required>
                                <div class="invalid-feedback">
                                    Ingrese tu contraseña, por favor.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button data-formulario="form-new-iniciar" type="button" class="btn btn-primary btn-iniciar">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- seccion del home -->

    <section id="home">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 d-flex justify-content-center align-items-center">
                    <h1>NUBOX RENTA TU OFICINA O TU SALÓN DE EVENTOS EN LA CDMX</h1>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 d-flex justify-content-center align-items-center">
                    <img src="public/img/oficina.jpg" alt="Introduccion" class="intro">
                </div>
            </div>
        </div>
    </section>


    <!-- seccion del about -->

    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <h2>¿QUIENES SOMOS?</h2>
                    <p>Nubox: tu espacio, nuestras soluciones. Somos una empresa Mexicana en la cdmx, especializada en ofrecer oficinas y
                        salones para eventos. Creando entornos inspiradores, flexibles y completamente equipados, estamos aquí para hacer
                        tus reuniones y eventos inolvidables. ¡Con Nubox, tus ideas se vuelven realidad!</p>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <img src="public/img/salon intro.jpg" class="intro_about">
                </div>
            </div>
        </div>
    </section>


    <!-- seccion de salones -->

    <section id="salones">
    </section>

    <?php require('views/footer.view.php'); ?>
    <script src="<?= constant('URL') ?>public/js/paginas/main.js"></script>
    <script src="<?= constant('URL') ?>public/js/sweetalert.min.js"></script>
</body>

</html>