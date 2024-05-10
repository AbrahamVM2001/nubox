<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Empleados</h3>
            <button class="btn btn-success btn-agregar-empleados" data-bs-target="#modalEmpleado" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-empleado"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.index.js"></script>

<div class="modal fade" id="modalEmpleado" aria-hidden="true" aria-labelledby="modalEmpleadoLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEmpleadoLabel">Dar de alta nuevo empleado</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-new-empleado" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="tipo" id="tipo" value="nuevo">
                    <input type="hidden" name="empleado" id="empleado">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Nombre">Nombre del empleado.</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese el nombre del empleado, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Apellidos">Apellidos del empleado.</label>
                            <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese el apellido del empleado, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="Fecha_nacimiento">Fecha de nacimiento del empleado.</label>
                            <input type="date" class="form-control" name="nacimiento" id="nacimiento" placeholder="Fecha de nacimiento del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese la fecha de nacimiento del empleado, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="Edad">Edad del empleado.</label>
                            <input type="number" min="18" max="65" class="form-control" name="edad" id="edad" placeholder="Edad del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese la edad del empleado, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Telefono">Telefono del empleado.</label>
                            <input type="number" class="form-control" name="telefono" id="telefono" placeholder="Telefono del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese el telefono del empleado, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Curp">Curp del empleado.</label>
                            <input type="text" class="form-control" name="curp" id="curp" placeholder="Curp del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese el curp del empleado, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Correo">Correo del empleado.</label>
                            <input type="mail" class="form-control" name="mail" id="mail" placeholder="Correo del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese el correo del empleado, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="password">Contraseña del empleado.</label>
                            <input type="password" class="form-control" name="pass" id="pass" placeholder="Contraseña del empleado..." required>
                            <div class="invalid-feedback">
                                Ingrese la contraseña del empleado, por favor.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-empleado" type="button" class="btn btn-primary btn-empleado">Guardar</button>
            </div>
        </div>
    </div>
</div>