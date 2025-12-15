<div style="position: relative; top: -70px;">
<?php
session_start();

if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>
<style>
body {
    background-color: #f5f5f5;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.header-card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.header-card h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.info-card {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.info-card h5 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 25px;
}

.info-item {
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 5px;
}

.info-value {
    font-size: 1rem;
    color: #333;
}

.badge {
    padding: 6px 12px;
    font-weight: 500;
    font-size: 0.875rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    font-weight: 400;
}

.form-group input {
    width: 100%;
    padding: 0.5rem 0;
    font-size: 1rem;
    border: none;
    border-bottom: 1px solid #dee2e6;
    background-color: transparent;
    outline: none;
    transition: border-color 0.2s;
}

.form-group input:focus {
    border-bottom: 2px solid #0d6efd;
}

.profile-icon {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    border: 4px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.profile-icon i {
    font-size: 4rem;
    color: white;
}

.main-title {
    font-size: 3rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: #1f2937;
}
</style>

<div class="container mt-4">

<!-- ALERTAS -->
<?php if (!empty($alert)) { ?>
    <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible fade show" role="alert">
        <strong><?php echo $alert['message']; ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- HEADER -->
<h1 class="main-title"><i class="fas fa-user text-primary"></i> Información Personal</h1>

<!-- INFORMACIÓN DEL USUARIO -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="info-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="fas fa-id-card text-primary"></i> Datos Personales</h5>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </div>

            <?php if ($datosUsuario) { ?>
            
            <!-- ÍCONO DE PERFIL -->
            <div class="profile-icon">
                <i class="fas fa-user"></i>
            </div>

            <div class="info-item">
                <div class="info-label">DOCUMENTO</div>
                <div class="info-value"><?php echo $datosUsuario['documento']; ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">NOMBRE COMPLETO</div>
                <div class="info-value"><?php echo $datosUsuario['nombre'] . ' ' . $datosUsuario['apellido']; ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">TELÉFONO</div>
                <div class="info-value"><?php echo $datosUsuario['telefono']; ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">CORREO ELECTRÓNICO</div>
                <div class="info-value"><?php echo $datosUsuario['correo']; ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">ROL</div>
                <div class="info-value">
                    <span class="badge bg-primary">
                        <?php echo isset($datosUsuario['nombre_rol']) ? $datosUsuario['nombre_rol'] : 'Sin rol asignado'; ?>
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalContrasena">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </button>
            </div>

            <?php } else { ?>
            <div class="alert alert-warning">
                No se encontró información del usuario.
            </div>
            <?php } ?>
        </div>
    </div>
</div>

</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Editar Información Personal</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<form action="<?php echo getUrl('InformacionPersonal','InformacionPersonal','postUpdate'); ?>" method="post">

<div class="form-group">
    <label for="nombre">Nombre *</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($datosUsuario['nombre']) ? $datosUsuario['nombre'] : ''; ?>" required>
</div>

<div class="form-group">
    <label for="apellido">Apellido *</label>
    <input type="text" id="apellido" name="apellido" value="<?php echo isset($datosUsuario['apellido']) ? $datosUsuario['apellido'] : ''; ?>" required>
</div>

<div class="form-group">
    <label for="telefono">Teléfono *</label>
    <input type="text" id="telefono" name="telefono" value="<?php echo isset($datosUsuario['telefono']) ? $datosUsuario['telefono'] : ''; ?>" maxlength="10" required>
</div>

<div class="form-group">
    <label for="correo">Correo Electrónico *</label>
    <input type="email" id="correo" name="correo" value="<?php echo isset($datosUsuario['correo']) ? $datosUsuario['correo'] : ''; ?>" required>
</div>

<button type="submit" class="btn btn-primary w-100 mt-3">Guardar Cambios</button>
</form>
</div>

</div>
</div>
</div>

<!-- MODAL CAMBIAR CONTRASEÑA -->
<div class="modal fade" id="modalContrasena" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Cambiar Contraseña</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<form action="<?php echo getUrl('InformacionPersonal','InformacionPersonal','postCambiarContrasena'); ?>" method="post">

<div class="form-group">
    <label for="contrasena_actual">Contraseña Actual *</label>
    <input type="password" id="contrasena_actual" name="contrasena_actual" required>
</div>

<div class="form-group">
    <label for="contrasena_nueva">Contraseña Nueva *</label>
    <input type="password" id="contrasena_nueva" name="contrasena_nueva" minlength="6" required>
</div>

<div class="form-group">
    <label for="contrasena_confirmar">Confirmar Contraseña Nueva *</label>
    <input type="password" id="contrasena_confirmar" name="contrasena_confirmar" minlength="6" required>
</div>

<button type="submit" class="btn btn-primary w-100 mt-3">Cambiar Contraseña</button>
</form>
</div>

</div>
</div>
</div>

</div>