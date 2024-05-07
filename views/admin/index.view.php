<?php require('views/headervertical.view.php'); ?>
<div class="container">
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h3>CONFERENCIAS</h3>
      <button class="btn btn-success btn-agregar-conferencia" data-bs-target="#modalConferencias" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
    </div>
    <diV class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 1%;">
      <p>En la sección de conferencias, puedes crear nuevas conferencias, habilitar o deshabilitarlas, añadir enlaces y replicarlas. Para hacer
        cambios, simplemente haz clic en el botón verde 'Agregar' con el signo de más, completa todos los campos y pulsa 'Guardar'. Para habilitar
        o deshabilitar una conferencia, haz clic en el botón con el icono<br> de encendido/apagado; si el botón es verde, significa que está habilitado,
        si es rojo, está deshabilitado. En el botón azul puedes ver los enlaces, y los botones morados <br>sirven para replicar la conferencia. Es importante
        tener en cuenta que si la fecha es anterior a la actual, no podrás crearla; las fechas deben ser futuras para poder crear<br> la conferencia. Una
        vez que hayas terminado, haz clic en el botón 'Guardar'. Si necesitas hacer algún cambio, simplemente haz doble clic en el campo de la tabla
        que <br>deseas modificar y luego haz clic en 'Guardar'.</p>
    </diV>
    <div class="card-body">
      <div class="row table-responsive" id="container-conferencia"></div>
    </div>
  </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.conferencia.js"></script>
<div class="modal fade" id="modalConferencias" aria-hidden="true" aria-labelledby="modalConferenciasLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalConferenciasLabel">Crear nueva conferencia</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-new-conferencia" action="javascript:;" class="needs-validation" novalidate method="post">
          <input type="hidden" name="tipo" id="tipo" value="nuevo">
          <input type="hidden" name="id_conferencia" id="id_conferencia">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <label for="">Tema de la sesión</label>
              <input type="text" class="form-control" name="tema_sesion" id="tema_sesion" placeholder="Tema de la sesión..." required>
              <div class="invalid-feedback">
                Ingrese un tema de la sesión, por favor.
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
              <label for="fechaHora_inicio">Fecha y Hora de Inicio</label>
              <input type="datetime-local" class="form-control" name="fechaHora_inicio" id="fechaHora_inicio" placeholder="Fecha y Hora de inicio..." required>
              <div class="invalid-feedback">
                Ingrese la fecha y hora de inicio de la sesión, por favor.
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
              <label for="fechaHoraFinalizacion">Fecha y Hora de Finalización</label>
              <input type="datetime-local" class="form-control" name="fechaHoraFinalizacion" id="fechaHoraFinalizacion" required>
              <div class="invalid-feedback">
                Ingrese la fecha y hora de finalización de la sesión, por favor.
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <label for="">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
              <div class="invalid-feedback">
                Ingrese la descripción de la sesión, por favor.
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <label for="">Enlace de transmisión nativo</label>
              <input type="url" class="form-control" name="linkTransmision_nativo" id="linkTransmision_nativo" required>
              <div class="invalid-feedback">
                Ingrese el enlace de la transmisión nativa, por favor.
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <label for="">Enlace de transmisión traducida</label>
              <input type="url" class="form-control" name="linkTransmision_traducida" id="linkTransmision_traducida" required>
              <div class="invalid-feedback">
                Ingrese el enlace de la transmisión traduciada, por favor.
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
              <label for="estatus">Estatus <small class="text-danger">*</small></label>
              <select title="Ingresa el estatus del usuario." class="form-select" aria-label="Estatus" name="estatus" id="estatus">
                <option value="0">Deshabilitado</option>
                <option value="1">Habilitado</option>
              </select>
              <div class="invalid-feedback">
                Ingrese el estado de la sesión, por favor.
              </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
              <label for="permiso">Permiso de entrada</label>
              <select title="Escoge las opciones de entrada" class="form-select" aria-label="Permisos" name="permiso" id="permiso">
                <option value="0">Entrada antes de 15 min</option>
                <option value="1">Entrada en todos momentos</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button data-formulario="form-new-conferencia" type="button" class="btn btn-primary btn-conferencia">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalLinks" aria-hidden="true" aria-labelledby="modalLinksLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalLinksLabel">Crear nueva conferencia</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="container-links"></div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>