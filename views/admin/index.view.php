<?php require('views/headervertical.view.php'); ?>
<div class="container">
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h3>Estadisticas</h3>
    </div>
    <div class="card-body">
      <div class="row table-responsive" id="container-conferencia"></div>
    </div>
  </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.main.js"></script>