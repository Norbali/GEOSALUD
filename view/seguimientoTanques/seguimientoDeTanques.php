<?php
session_start();
require_once '../config/conexion.php';

if (!isset($_SESSION['auth'])) {
    header("Location: ../login.php");
    exit;
}
?>

<div class="container">
<h4 class="fw-bold mb-3">Seguimiento de Tanques</h4>

<?php if (isset($_GET['msg'])) { ?>
<div class="alert alert-info"><?= $_GET['msg']; ?></div>
<?php } ?>


<form method="POST" action="<?php echo getUrl('SeguimientoDeTanques', 'SeguimientoDeTanques', 'postCreate'); ?>">



<!-- No Tanque -->
<label>No. Tanque *</label>
<input type="number" name="id_seguimiento" class="form-control" required>

<!-- Tipo Actividad -->
<label class="mt-2">Tipo de Actividad *</label>
<select name="id_actividad" class="form-control" required>
<option value="">Seleccione</option>
<?php
$sql = "SELECT id_actividad, nombre FROM actividad";
foreach ($pdo->query($sql) as $row) {
    echo "<option value='{$row['id_actividad']}'>{$row['nombre']}</option>";
}
?>
</select>

<!-- Parámetros -->
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

<!-- Conteos -->
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

<!-- Observaciones -->
<label class="mt-3">Observaciones</label>
<textarea name="observaciones" class="form-control"></textarea>

<!-- Responsable -->
<label class="mt-3">Responsable</label>
<input type="text" class="form-control" value="<?= $_SESSION['nombreCompleto']; ?>" readonly>

<!-- Botón con permiso -->
<?php if (in_array('crear', $_SESSION['permisos']['SeguimientoTanques'])) { ?>
<button class="btn btn-success mt-3">Guardar</button>
<?php } ?>

</form>
</div>
