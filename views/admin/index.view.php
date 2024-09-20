<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Nubox</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 text-center" id="container-ventas-totales">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6" id="container-espacios">
                    <canvas id="chartEspacios"></canvas>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6" id="container-clientes-sin-asignar">
                    <canvas id="chartClientes"></canvas>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 text-center" id="container-clientes">
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.index.js"></script>