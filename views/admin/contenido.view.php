<?php require('views/headervertical.view.php'); ?>
<style>
    /* From Uiverse.io by Yaya12085 */
    .form {
        background-color: #fff;
        box-shadow: 0 10px 60px rgb(218, 229, 255);
        border: 1px solid rgb(159, 159, 160);
        border-radius: 20px;
        padding: 2rem .7rem .7rem .7rem;
        text-align: center;
        font-size: 1.125rem;
        max-width: 320px;
    }

    .form-title {
        color: #000000;
        font-size: 1.8rem;
        font-weight: 500;
    }

    .form-paragraph {
        margin-top: 10px;
        font-size: 0.9375rem;
        color: rgb(105, 105, 105);
    }

    .drop-container {
        background-color: #fff;
        position: relative;
        display: flex;
        gap: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 10px;
        margin-top: 2.1875rem;
        border-radius: 10px;
        border: 2px dashed rgb(171, 202, 255);
        color: #444;
        cursor: pointer;
        transition: background .2s ease-in-out, border .2s ease-in-out;
    }

    .drop-container:hover {
        background: rgba(0, 140, 255, 0.164);
        border-color: rgba(17, 17, 17, 0.616);
    }

    .drop-container:hover .drop-title {
        color: #222;
    }

    .drop-title {
        color: #444;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        transition: color .2s ease-in-out;
    }

    #file-input {
        width: 350px;
        max-width: 100%;
        color: #444;
        padding: 2px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid rgba(8, 8, 8, 0.288);
    }

    #file-input::file-selector-button {
        margin-right: 20px;
        border: none;
        background: #084cdf;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
        cursor: pointer;
        transition: background .2s ease-in-out;
    }

    #file-input::file-selector-button:hover {
        background: #0d45a5;
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Asignacion de contenido</h3>
            <button class="btn btn-success btn-agregar-inspeccion" data-bs-target="#modalContenido" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-contenido"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script>
    let id_espacio = '<?= $this->asignacion_contenido; ?>';
    let tipo = '<?= $this->tipo; ?>';
</script>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.contenido.js"></script>
<div class="modal fade" id="modalContenido" aria-hidden="true" aria-labelledby="modalContenidoLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalContenidoLabel">Dar de alta a un salon</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="registro">
                <form id="form-new-contenido" action="javascript:;" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="tipo" id="tipo" value="nuevo">
                    <input type="hidden" name="id_contenido" id="id_contenido">
                    <input type="hidden" name="tipo_contenido" id="tipo_contenido">
                    <input type="hidden" name="id_espacio" id="id_espacio">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="mostrar-imagen"></div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="registro">
                            <label for="file-input" class="drop-container">
                                <span class="drop-title">Ingresa la imagen</span>
                                <input type="file" accept="image/*" required="" id="file-input" name="ubicacion" style="color: #000;">
                            </label>
                            <div class="invalid-feedback">
                                Ingrese un nombre valido, por favor.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body" id="actualizar"></div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button data-formulario="form-new-contenido" type="button" class="btn btn-primary btn-contenido">Guardar</button>
            </div>
        </div>
    </div>
</div>