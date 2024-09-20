<?php require('views/headervertical.view.php'); ?>
<div id="precesar">
    <div class="procesando">
        <span>Procesando...</span>
    </div>
</div>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Nubox</h3>
            <button class="btn btn-success btn-generar-reporte" data-bs-target="#modalReporte" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-eventos"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/user/home.index.js"></script>
<div class="modal fade" id="modalReporte" aria-hidden="true" aria-labelledby="modalReporteLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalReporteLabel">General Reporte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-new-reporte" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="id_usuario" id="id_usuario" style="color: #000;">
                    <input type="hidden" name="correo" id="correo" style="color: #000;">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="titulo" style="color: #000;">Titulo del reporte <span>*</span></label>
                            <input type="text" style="color: #000;" class="form-control" name="titulo" id="titulo" placeholder="Reporte por indisiplina" required>
                            <div class="invalid-feedback">
                                Ingresa el titulo del reporte, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="descripcion" style="color: #000;">Descripción <span>*</span></label>
                            <textarea name="desc" style="color: #000;" id="desc" class="form-control" rows="10" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sed felis sapien. Suspendisse est dolor, pretium eget convallis in, pharetra eget elit. Nulla scelerisque ornare ante, in aliquam nulla cursus eu. Curabitur quis mi pretium, bibendum nisl vel, consectetur libero. Sed ac tincidunt dui. Nulla pretium et justo quis feugiat. Donec nisi justo, vulputate feugiat faucibus in, faucibus ac nisl. Nam quis erat neque. Nunc ac quam pulvinar, convallis neque at, maximus mauris. Aliquam sit amet leo arcu. Maecenas eget sem rutrum mauris porttitor luctus. Aliquam id faucibus sapien. Vestibulum tristique leo a turpis finibus sollicitudin." required></textarea>
                            <div class="invalid-feedback">
                                Ingresa una descripcion valida, por favor.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-reporte" type="button" class="btn btn-primary btn-reporte">Enviar reporte</button>
            </div>
        </div>
    </div>
</div>