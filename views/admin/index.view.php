<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Nubox</h3>
            <button class="btn btn-success btn-agregar-inspeccion" data-bs-target="#modalInspeccion" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-inspeccion"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.index.js"></script>
<div class="modal fade" id="modalInspeccion" aria-hidden="true" aria-labelledby="modalInspeccionLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalInspeccionLabel">Crear una nueva inspección</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="registro">
                <form id="form-new-inspeccion" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="tipo" id="tipo" value="nuevo">
                    <input type="hidden" name="id_inspeccion" id="id_inspeccion">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Seleccionar cliente">Selecciona el cliente</label>
                            <select class="form-select" aria-placeholder="Seleccionar el cliente..." name="cliente" id="cliente" required>
                                <option></option>
                            </select>
                            <div class="invalid-feedback">
                                Seleccione el cliente, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Fecha de incio">Fecha de inico</label>
                            <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" required>
                            <div class="invalid-feedback">
                                Seleccione la fecha de inicio, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Fecha de finalizacion">Fecha de finalizacion</label>
                            <input type="date" class="form-control" name="fechaFinalizacion" id="fechaFinalizacion" required>
                            <div class="invalid-feedback">
                                Seleccione la fecha de finalización, por favor.
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-body" id="actualizar"></div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-inspeccion" type="button" class="btn btn-primary btn-inspeccion">Guardar</button>
            </div>
        </div>
    </div>
</div>