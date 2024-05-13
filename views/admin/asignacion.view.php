<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Asignacion</h3>
            <button class="btn btn-success btn-agregar-asignacion" data-bs-target="#modalAsignacion" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-asignacion"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.asignacion.js"></script>

<div class="modal fade" id="modalAsignacion" aria-hidden="true" aria-labelledby="modalOficinaLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAsignacionLabel">Asignacion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-new-asignacion" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="tipo" id="tipo" value="nuevo">
                    <input type="hidden" name="asignacion" id="asignacion">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Nombre">Nombre de la oficina.</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre de la oficina..." required>
                            <div class="invalid-feedback">
                                Ingrese el nombre de la oficina, por favor.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-asignacion" type="button" class="btn btn-primary btn-asignacion">Guardar</button>
            </div>
        </div>
    </div>
</div>