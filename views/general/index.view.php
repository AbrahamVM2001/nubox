<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= constant('SOCIEDAD') ?></title>
    <?php require('views/estilos.view.php'); ?>
</head>

<body>
    <nav>
        <div class="wrapper">
            <div class="logo"><a href="#"><img src="<?= constant('URL') ?>public/img/logo.png" alt="logo"></a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">
                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="#">Home</a></li>
                <li><a href="#">Mejores</a></li>
                <li><a href="#">Salon</a></li>
                <li><a href="#">Oficinas</a></li>
                <li>
                    <a href="#" class="desktop-item"><i class="fa-solid fa-user"></i></a>
                    <input type="checkbox" id="showDrop">
                    <label for="showDrop" class="mobile-item"><i class="fa-solid fa-user"></i></label>
                    <ul class="drop-menu">
                        <li>
                            <button class="Btn" data-bs-target="#modalIniciar" data-bs-toggle="modal">
                                <div class="sign"><svg viewBox="0 0 512 512">
                                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                                    </svg></div>
                                <div class="text">Iniciar</div>
                            </button>
                        </li>
                        <li>
                            <button class="button">
                            </button>
                        </li>
                    </ul>
                </li>
                <li><a href="#">
                        <label class="ui-switch">
                            <input type="checkbox">
                            <div class="slider">
                                <div class="circle"></div>
                            </div>
                        </label>
                    </a>
                </li>
            </ul>
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>

    <!-- modal -->

    <div class="modal fade" id="modalIniciar" aria-hidden="true" aria-labelledby="modalIniciarLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalIniciarLabel">Iniciar sesión</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-new-iniciar" action="javascript:;" class="needs-validation" novalidate method="post">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="">Correo</label>
                                <input type="email" class="form-control" name="mail" id="mail" placeholder="Corre..." required>
                                <div class="invalid-feedback">
                                    Ingrese tu correo, por favor.
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="">Contraseña</label>
                                <input type="password" class="form-control" name="pass" id="pass" placeholder="Contraseña..." required>
                                <div class="invalid-feedback">
                                    Ingrese tu contraseña, por favor.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button data-formulario="form-new-conferencia" type="button" class="btn btn-primary btn-iniciar">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- header -->

    <div class="body-text">
        <div class="title">Necesitas una oficina o salon.</div>
        <div class="sub-title">solo escoge tu gusto pagas y listo.</div>
    </div>

    <div class="container">
        <div class="row"></div>
    </div>
    <?php require('views/footer.view.php'); ?>
    <script src="<?= constant('URL') ?>public/js/paginas/main.js"></script>
    <script src="<?= constant('URL') ?>public/js/sweetalert.min.js"></script>
</body>

</html>