<link href="<?= constant('URL') ?>public/css/style.css" rel="stylesheet">
<?php require('views/estilos.view.php'); ?>
<div id="particles-js"></div>
<div class="container" style="background-color: #fff;">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
            <h1>PREGUNTAS PARA EL PONENTE</h1>
            <div class="row table-responsive" id="container-preguntas-ponente"></div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
            <h1>COMENTARIOS CHAT</h1>
            <div class="row table-responsive" id="container-comentarios-chat"></div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
            <h1>TOTAL POR PAIS</h1>
            <div class="row table-responsive" id="container-total-pais"></div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
            <h1>TOTAL POR ESTADO</h1>
            <div class="row table-responsive" id="container-total-estado"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script>
    let id_sesion = '<?= $this->conferencia; ?>';
</script>
<script src="<?= constant('URL') ?>public/js/particulas-lib/particles.js"></script>
<script src="<?= constant('URL') ?>public/js/particulas-lib/app.js"></script>
<script src="<?= constant('URL') ?>public/js/particulas-lib/stats.js"></script>
<script src="<?= constant('URL') ?>public/js/paginas/admin/borrar.js"></script>