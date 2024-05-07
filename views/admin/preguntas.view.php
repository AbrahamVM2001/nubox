<?php require('views/headervertical.view.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Votaciones</h3>
            <button class="btn btn-success btn-agregar-votacion">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <diV class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 10px;">
            <p>En esta sección, puedes crear una votación haciendo clic en 'Agregar', lo que abrirá un modal donde puedes ingresar los datos.
                Para agregar opciones, simplemente haz clic en el botón verde '+'. Si deseas eliminar una opción, utiliza el botón rojo con el
                icono de basura. Una vez completado, haz clic en 'Guardar'. Las acciones disponibles incluyen habilitar, deshabilitar, ver el
                conteo, modificar y eliminar. Si deseas activar una pregunta, haz clic en 'Activar'. Por lo general, el botón estará en rojo si
                está deshabilitado; simplemente haz clic para activarlo. Esto abrirá una nueva pantalla con el conteo, que se cerrará
                automáticamente después de 30 segundos y la pregunta se deshabilitará automáticamente después de 20 segundos. Para modificar
                una opción, simplemente llena el campo correspondiente y haz clic en el botón verde. Para agregar una nueva opción, completa
                los campos y haz clic en el botón verde. Para eliminar una opción, haz clic en el botón rojo con el icono de basura. El botón
                verde de 'Conteo' muestra la vista proyectada de la votación.</p>
        </diV>
        <div class="card-body">
            <div class="row table-responsive" id="container-votacion"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.preguntas.js"></script>
<div class="modal fade" id="modalVotacion" aria-hidden="true" aria-labelledby="modalVotacionLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalVotacionLabel">Crear una nueva votacion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body-crear">
                <form id="form-new-votacion" action="javascript:;" class="needs-validation" novalidate method="post">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="">Pregunta</label>
                            <input type="text" class="form-control" name="pregunta" id="pregunta" placeholder="Titulo de la pregunta...." required>
                            <div class="invalid-feedback">
                                Ingrese el titulo de la pregunta, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label for="">Sesión</label>
                            <select title="Sesión" class="form-select" aria-label="Seleccionar la sesión..." name="sesion" id="sesion">
                                <option></option>
                            </select>
                            <div class="invalid-feedback">
                                Ingrese la fecha y hora de incio de la sesión, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <p>Opcion</p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <button type="button" class="btn btn-success btn-agregar-opcion rounded-circle"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <div id="opciones-container">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body-mostrar">
                <div id="container-preguntas"></div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-votacion" type="button" class="btn btn-primary btn-votacion">Guardar</button>
            </div>
        </div>
    </div>
</div>