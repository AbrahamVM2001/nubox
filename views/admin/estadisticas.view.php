<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Estadisticas</h3>
        </div>
        <diV class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 1%;">
            <p>En la sección de estadísticas, podemos obtener información detallada sobre los usuarios por país, estado, votación y conectividad.
                Simplemente haz clic en el botón azul para acceder a los datos estadísticos. Si deseas copiar la información, solo necesitas presionar
                el botón azul y copiar los datos de la tabla. Si prefieres exportar los datos <br>a un documento Excel, haz clic en el botón verde que dice
                'Excel'. Para obtener un archivo PDF, haz clic en el botón rojo 'PDF'. Ten en cuenta que al hacerlo, se abrirá una ventana con el PDF;
                si deseas guardarlo, selecciona la opción de impresión, luego cambia el destino a 'Guardar como PDF', y finalmente haz clic en 'Guardar'
                para descargar el PDF en la ubicación deseada.</p>
        </diV>
        <div class="card-body">
            <div class="row table-responsive" id="container-estadisticas"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.estadisticas.js"></script>
<div class="modal fade" id="modalEstadisticas" aria-hidden="true" aria-labelledby="modalEstadisticasLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEstadisticasLabel">Estadisticas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                Total de asistentes: <span id="total-asistentes" class="text-success" style="font-size: 24px;"></span>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row table-responsive" id="container-pais"></div>
                </div>
                <div class="card-body">
                    <div class="row table-responsive" id="container-estado"></div>
                </div>
                <div class="card-body">
                    <div class="row table-responsive" id="container-logeos"></div>
                </div>
                <div class="card-body">
                    <div class="row table-responsive" id="container-votaciones"></div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>