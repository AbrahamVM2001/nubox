<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Salones</h3>
            <button class="btn btn-success btn-agregar-salon" data-bs-target="#modalSalon" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-salon"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.salon.js"></script>

<div class="modal fade" id="modalSalon" aria-hidden="true" aria-labelledby="modalSalonLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalSalonLabel">Publicar un salón</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-new-salon" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="tipo" id="tipo" value="nuevo">
                    <input type="hidden" name="salon" id="salon">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Nombre">Nombre del salón.</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del salón..." required>
                            <div class="invalid-feedback">
                                Ingrese el nombre del salón, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Descripcion">Descripción del salon.</label>
                            <textarea class="form-control" aria-label="Decripción del salón..." name="decripcion" id="decripcion" placeholder="Precio del salón..." required></textarea>
                            <div class="invalid-feedback">
                                Ingrese la descripción del salón, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Caracteristicas">Caracteristicas del salón.</label>
                            <textarea class="form-control" aria-label="Caracteristicas del salón..." name="caracteristicas" id="caracteristicas" placeholder="Caracteristicas del salón..." required></textarea>
                            <div class="invalid-feedback">
                                Ingrese las caractetisticas del salón, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Ubicación">Ubicación del salón.</label>
                            <textarea class="form-control" aria-label="Ubicación del salón..." name="ubicacion" id="ubicacion" placeholder="Ubicación del salón..." required></textarea>
                            <div class="invalid-feedback">
                                Ingrese la ubicación del salón, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="Aforo">Aforo.</label>
                            <input type="number" min="0" max="100000000" class="form-control" name="aforo" id="aforo" placeholder="Aforo del salón..." required>
                            <div class="invalid-feedback">
                                Ingrese el aforo del salón, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="Precio">Precio.</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" aria-label="Precio del salón..." name="precio" id="precio" placeholder="Precio del salón..." required>
                                <span class="input-group-text">mxn</span>
                            </div>
                            <div class="invalid-feedback">
                                Ingrese el Precio del salón, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="Titulo">Titulo de la imagen.</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Titulo de la imagen..." required>
                            <div class="invalid-feedback">
                                Ingrese el titulo de la imagen, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="Archivo_imagen">Imagen.</label>
                            <input class="form-control" type="file" id="imagen" name="imagen" aria-label="Imagen del salón..." required>
                            <div class="invalid-feedback">
                                Ingrese el archivo de la imagen, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Estatus">Estatus.</label>
                            <select title="Ingresa el estatus del salon." class="form-select" aria-label="Estatus" name="estatus" id="estatus">
                                <option value="0">Deshabilitado</option>
                                <option value="1">Habilitado</option>
                            </select>
                            <div class="invalid-feedback">
                                Ingrese el estatus del salón, por favor.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-salon" type="button" class="btn btn-primary btn-salon">Guardar</button>
            </div>
        </div>
    </div>
</div>