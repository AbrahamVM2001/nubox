<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Usuario</h3>
            <button class="btn btn-success btn-agregar-inspeccion" data-bs-target="#modalUsuario" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-usuario"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.usuario.js"></script>
<div class="modal fade" id="modalUsuario" aria-hidden="true" aria-labelledby="modalUsuarioLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalUsuarioLabel">Dar de alta a un usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="registro">
                <form id="form-new-usuario" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="tipo" id="tipo" value="nuevo">
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="usuario" style="color: #000;">Nombre usuario <span>*</span></label>
                            <input style="color: #000;" type="text" name="nombre" id="nombre" class="form-control" placeholder="nombre..." required>
                            <div class="invalid-feedback">
                                Ingresa un nombre o nombres validos, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="APaterno" style="color: #000;">Apellido paterno <span>*</span></label>
                            <input style="color: #000;" type="text" name="apaterno" id="apaterno" class="form-control" placeholder="Apellido paterno..." required>
                            <div class="invalid-feedback">
                                Ingresa un Apellido paterno valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="AMaterno" style="color: #000;">Apellido materno <span>*</span></label>
                            <input style="color: #000;" type="text" name="amaterno" id="amaterno" class="form-control" placeholder="Apellido materno...">
                            <div class="invalid-feedback">
                                Ingresa un Apellido Materno, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="tipo_usuario" style="color: #000;">Tipo de usuario <span>*</span></label>
                            <select class="form-control" name="tipo_usuario" id="tipo_usuario" required style="color: #000;">
                                <option value="">Seleccione un tipo de usuario..</option>
                                <option value="1">Administrador</option>
                                <option value="2">Usuario</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="correo" style="color: #000;">Correo <span>*</span></label>
                            <input style="color: #000;" type="email" name="correo" id="correo" class="form-control" placeholder="example@nubox.com" required>
                            <div class="invalid-feedback">
                                Ingresa un correo valido, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Caracteristicas" style="color: #000;">Ingresa una contraseña mayor a 8 pero menos a 32 con letras, caracteres y números.</label><br>
                            <label for="Contraseña" style="color: #000;">Contraseña <span>*</span></label>
                            <input style="color: #000;" type="password" name="pass" id="pass" class="form-control" placeholder="Contraseña..." required>
                            <p style="font-size: 10px; color: #000;"><span class="verificar-mayusculas"></span> Mayúsculas <span>ABCDFG</span></p>
                            <p style="font-size: 10px; color: #000;"><span class="verificar-minusculas"></span> Minúscula <span>abcdfg</span></p>
                            <p style="font-size: 10px; color: #000;"><span class="verificar-numeros"></span> Números <span>12345</span></p>
                            <p style="font-size: 10px; color: #000;"><span class="verificar-caracteres"></span> Caracteres <span>@?¡!&%</span></p>
                            <p style="font-size: 10px; color: #000;"><span class="verificar-meno"></span> Es mayor a 8 caracteres</p>
                            <p style="font-size: 10px; color: #000;"><span class="verificar-mayor"></span> Es menos a 32 caracteres</p>
                            <div class="invalid-feedback">
                                Ingresa una contraseña válida, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="Confirmacion" style="color: #000;">Confirmación Contraseña</label>
                            <input style="color: #000;" type="password" class="form-control" name="pass_confir" id="pass_confir" placeholder="Contraseña..." required>
                            <p style="font-size: 10px; color: #000;"><span class="verificar-similar"></span> Similar</p>
                            <div class="invalid-feedback">
                                Ingresa una contraseña válida, por favor.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body" id="actualizar"></div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-usuario" type="button" class="btn btn-primary btn-usuario">Guardar</button>
            </div>
        </div>
    </div>
</div>