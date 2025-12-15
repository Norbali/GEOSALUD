<?php if (isset($_SESSION['alert'])): ?>
<div class="alert alert-<?= $_SESSION['alert']['type'] ?>">
    <?= $_SESSION['alert']['message'] ?>
</div>
<?php unset($_SESSION['alert']); endif; ?>

<h4>Seguimiento de Tanques</h4>

<form method="POST" action="<?= getUrl('SeguimientoDeTanques','SeguimientoDeTanques','postCreate') ?>">

<label>No. Tanque *</label>
<input type="number" name="id_tanque" class="form-control" required>

<label class="mt-2">Tipo de Actividad *</label>
<select name="id_actividad" class="form-control" required>
<option value="">Seleccione</option>
<?php foreach ($actividades as $act): ?>
<option value="<?= $act['id_actividad'] ?>">
    <?= $act['nombre_actividad'] ?>
</option>
<?php endforeach; ?>
</select>

<div class="row mt-3">
<div class="col-md-4">
<label>pH *</label>
<input type="number" step="0.1" name="ph" class="form-control" required>
</div>

<div class="col-md-4">
<label>Temperatura *</label>
<input type="number" step="0.1" name="temperatura" class="form-control" required>
</div>

<div class="col-md-4">
<label>Cloro</label>
<input type="number" step="0.1" name="cloro" class="form-control">
</div>
</div>

<div class="row mt-3">
<div class="col-md-4">
<label>Alevines</label>
<input type="number" name="num_alevines" class="form-control">
</div>

<div class="col-md-4">
<label>Muertes machos</label>
<input type="number" name="num_machos" class="form-control">
</div>

<div class="col-md-4">
<label>Muertes hembras</label>
<input type="number" name="num_hembras" class="form-control">
</div>
</div>

<label class="mt-3">Observaciones</label>
<textarea name="observaciones" class="form-control"></textarea>

<label class="mt-3">Responsable</label>
<input type="text" class="form-control"
value="<?= $_SESSION['nombre'].' '.$_SESSION['apellido']; ?>" readonly>

<button class="btn btn-success mt-3">Guardar</button>

</form>
