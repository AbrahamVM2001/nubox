<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Espacios</h3>
            <button class="btn btn-success btn-agregar-inspeccion" data-bs-target="#modalSalon" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-salon"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.espacios.js"></script>
<div class="modal fade" id="modalSalon" aria-hidden="true" aria-labelledby="modalSalonLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalSalonLabel">Dar de alta a un salon</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="registro">
                <form id="form-new-salon" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="tipo" id="tipo" value="nuevo">
                    <input type="hidden" name="id_espacio" id="id_espacio">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="nombre" style="color: #000;">Nombre <span>*</span></label>
                            <input style="color: #000;" type="text" class="form-control" name="nombre" id="nombre" placeholder="example..." required>
                            <div class="invalid-feedback">
                                Ingrese un nombre valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="tipo_espacio" style="color: #000;">Tipo de espacio <span>*</span></label>
                            <select name="tipo_espacio" id="tipo_espacio" class="form-control" required style="color: #000;">
                                <option value="1">Salón</option>
                                <option value="2">Oficina</option>
                            </select>
                            <div class="invalid-feedback">
                                Ingrese el tipo de espacio valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="descripcion" style="color: #000;">Descripción <span>*</span></label>
                            <textarea style="color: #000;" name="desc" id="desc" class="form-control" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas porttitor felis nunc, in euismod ligula sodales ut." required></textarea>
                            <div class="invalid-feedback">
                                Ingresa una descripción valida, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="pais" style="color: #000;">País <span>*</span></label>
                            <select name="id_pais" id="id_pais" class="form-control" required style="color: #000;">
                                <option></option>
                            </select>
                            <div class="invalid-feedback">
                                Ingrese el país valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="estado" style="color: #000;">Estado <span>*</span></label>
                            <select name="id_estado" id="id_estado" class="form-control" required style="color: #000;">
                                <option></option>
                            </select>
                            <div class="invalid-feedback">
                                Ingrese el estado valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="cordenadas" style="color: #000;">Dirección <span>*</span></label>
                            <input style="color: #000;" type="text" name="direccion" id="direccion" class="form-control" required placeholder="Calle Ficticia 123, Colonia Imaginaria, Ciudad Virtual, CP 12345, México.">
                            <div class="invalid-feedback">
                                Ingrese las Dirección valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="latitud" style="color: #000;">Latitud <span>*</span></label>
                            <input style="color: #000;" type="number" step="any" name="latitud" id="latitud" class="form-control" required placeholder="45.123456">
                            <div class="invalid-feedback">
                                Ingrese la latitud valida, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="longitud" style="color: #000;">Longitud <span>*</span></label>
                            <input style="color: #000;" type="number" step="any" name="longitud" id="longitud" class="form-control" required placeholder="-93.654321">
                            <div class="invalid-feedback">
                                Ingrese una longitud valida, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="precio" style="color: #000;">Precio por hora <span>*</span></label>
                            <input style="color: #000;" type="number" step="any" name="precio" id="precio" class="form-control" placeholder="1000.00" required>
                            <div class="invalid-feedback">
                                Ingrese un precio valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="portada" style="color: #000;">Portada <span>*</span></label>
                            <input type="file" class="form-control" name="ubicacion" id="ubicacion">
                            <div class="invalid-feedback">
                                Ingresa la portada, por favor.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body" id="actualizar"></div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-salon" type="button" class="btn btn-primary btn-espacios">Guardar</button>
            </div>
        </div>
    </div>
</div>