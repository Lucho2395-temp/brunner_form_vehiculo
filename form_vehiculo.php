<?php

include 'header.php';

if (!isset($_SESSION['mov_trabajador'])) {
    header("Location: "._SERVER_."index.php");
    exit;
}
$trab = $_SESSION['mov_trabajador'];

?>

    <div class="card-body">
      <div class="alert alert-info">
        <b>Trabajador:</b> <?= htmlspecialchars($trab['nombres']) ?> <br>
        <b>DNI:</b> <?= htmlspecialchars($trab['dni']) ?>
      </div>

      <form method="POST" action="models/guardar_vehiculo.php" enctype="multipart/form-data" autocomplete="off">
        <div class="form-row">
          <div class="form-group col-md-12">
            <label>Tipo de vehículo</label>
            <select name="tipo_vehiculo" class="form-control" required>
              <option value="">Seleccione</option>
              <option value="Auto">Auto</option>
              <option value="Moto">Moto</option>
              <option value="Mototaxi">Mototaxi</option>
              <option value="Bicicleta">Bicicleta</option>
              <option value="Otro">Otro</option>
            </select>
          </div>

          <div class="form-group col-md-12">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control" maxlength="30" required placeholder="Ejem. Honda">
          </div>

          <div class="form-group col-md-12">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" maxlength="30" required placeholder="Ejem. CBF150ME">
          </div>

          <div class="form-group col-md-12">
            <label>Placa</label>
            <input type="text" name="placa" class="form-control" maxlength="20" required placeholder="Ejem. 12345L">
          </div>

          <div class="form-group col-md-12">
            <label>Propietario</label>
            <select name="propietario" class="form-control" required>
              <option value="">Seleccione</option>
              <option value="Propio">Propio</option>
              <option value="Familiar">Familiar</option>
              <option value="Tercero">Tercero</option>
            </select>
          </div>
        </div>

        <div class="form-group col-md-12">
          <label>¿Licencia vigente?</label>
          <select name="licencia_vigente" class="form-control" required>
            <option value="">Seleccione</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label>¿SOAT vigente?</label>
          <select name="soat_vigente" id="soat_vigente" class="form-control" onchange="select_soat()" required>
            <option value="">Seleccione</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
          </select>
        </div>

        <div class="form-group col-md-12" id="div_fecha_vencimiento">
          <label>Fecha de Vencimiento</label>
          <input type="date" name="soat_fecha_nacimiento" id="soat_fecha_nacimiento" class="form-control">
        </div>

        <button class="btn btn-success btn-block">Guardar</button>
        <a href="index.php" class="btn btn-link btn-block">Volver</a>
      </form>
   

<?php

include 'footer.php';
?>
  
<script>

  $(document).ready(function(){
        select_soat();
    });

  function select_soat(){
    $('#soat_fecha_nacimiento').val('')
    let soat_vigente = $('#soat_vigente').val()
    if(soat_vigente==1){
      $('#div_fecha_vencimiento').show();
    }else{
      $('#div_fecha_vencimiento').hide();
    }
  }
</script>