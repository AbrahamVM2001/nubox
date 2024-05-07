<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Logeo</h3>
        </div>
        <diV class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 1%;">
            <p>En esta sección, podemos visualizar los usuarios que han iniciado sesión, junto con su información de perfil, modelo, dirección,
                y las opciones para expulsarlos y denegarles el acceso a la sección nuevamente (esta función se puede habilitar y deshabilitar
                en cualquier momento).</p>
        </diV>
        <div class="card-body">
            <div class="row table-responsive" id="container-logeo"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.logeo.js"></script>