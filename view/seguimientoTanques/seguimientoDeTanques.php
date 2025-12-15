<?php session_start(); ?>
<div class="container">
<div class="page-inner">
<h4 class="fw-bold mb-3">Registro de Actividades</h4>


<?php if(isset($_GET['msg'])): ?>
<div class="alert alert-info"><?= $_GET['msg'] ?></div>
<?php endif; ?>


<form method="POST" action="../controller/ActividadController.php">


<div class="form-group">
<label>Tipo de Actividad *</label>
<select name="tipo_actividad" class="form-control" required>
<option value="">Seleccione</option>
<option value="TANQUE">Registro de tanques</option>
<option value="ALIMENTACION">Alimentación</option>
<option value="RECOLECCION">Recolección peces muertos/nacidos</option>
<option value="NIVEL_AGUA">Nivel de agua</option>
<option value="LAVADO">Lavado</option>
</select>
</div>


<div class="form-group">
<label>No. Tanque *</label>
<input type="number" name="no_tanque" class="form-control" required>
</div>


<div class="row">
<div class="col-md-6">
<label>Temperatura *</label>
<input type="number" step="0.1" name="temperatura" class="form-control" required>
</div>
<div class="col-md-6">
<label>pH *</label>
<input type="number" step="0.1" name="ph" class="form-control" required>
</div>
</div>


<div class="row mt-3">
<div class="col-md-4">
<label>Alevines</label>
<input type="number" name="alevines" class="form-control">
</div>
<div class="col-md-4">
<label>Muertes machos</label>
<input type="number" name="muertes_machos" class="form-control">
</div>
<div class="col-md-4">
<label>Muertes hembras</label>
<input type="number" name="muertes_hembras" class="form-control">
</div>
</div>


<div class="form-group mt-3">
<label>Actividades realizadas</label>
<textarea name="actividades" class="form-control"></textarea>
</div>


<div class="form-group">
<label>Observaciones</label>
<textarea name="observaciones" class="form-control"></textarea>
</div>


<button class="btn btn-success mt-3" type="submit">Guardar</button>


</form>
</div>
</div>