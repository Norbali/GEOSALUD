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

.profile-photo-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 30px;
}

.profile-photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.profile-icon {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.profile-icon i {
    font-size: 4rem;
    color: white;
}

.change-photo-btn {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #0d6efd;
    color: white;
    border: 3px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.change-photo-btn:hover {
    background: #0b5ed7;
    transform: scale(1.1);
}

.change-photo-btn i {
    font-size: 1rem;
}

#foto_input {
    display: none;
}

.preview-container {
    text-align: center;
    margin-top: 15px;
}

.preview-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 10px;
    margin-top: 10px;
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
<div class="header-card">
    <h4><i class="fas fa-user text-primary"></i> Informaci&oacute;n Personal</h4>
</div>

<!-- INFORMACI&oacute;N DEL USUARIO -->
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
            
            <!-- FOTO DE PERFIL -->
            <div class="profile-photo-container">
                <?php if (!empty($datosUsuario['foto_perfil']) && file_exists("C:/ms4w/Apache/htdocs/GEOSALUD/uploads/fotos_perfil/".$datosUsuario['foto_perfil'])) { ?>
                    <img src="/GEOSALUD/uploads/fotos_perfil/<?php echo $datosUsuario['foto_perfil']; ?>" 
                         alt="Foto de perfil" 
                         class="profile-photo" 
                         id="profile_photo">
                <?php } else { ?>
                    <div class="profile-icon" id="profile_icon">
                        <i class="fas fa-user"></i>
                    </div>
                <?php } ?>
                
                <label for="foto_input" class="change-photo-btn" title="Cambiar foto">
                    <i class="fas fa-camera"></i>
                </label>
                
                <form id="form_foto" action="<?php echo getUrl('InformacionPersonal','InformacionPersonal','postActualizarFoto'); ?>" method="post" enctype="multipart/form-data" style="display: none;">
                    <input type="file" id="foto_input" name="foto_perfil" accept="image/*">
                </form>
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
                <div class="info-label">TEL&Eacute;FONO</div>
                <div class="info-value"><?php echo $datosUsuario['telefono']; ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">CORREO ELECTR&Oacute;NICO</div>
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
                    <i class="fas fa-key"></i> Cambiar Contrase&ntilde;a
                </button>
            </div>

            <?php } else { ?>
            <div class="alert alert-warning">
                No se encontr&oacute; informaci&oacute;n del usuario.
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
<h5 class="modal-title">Editar Informaci&oacute;n Personal</h5>
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
    <label for="correo">Correo Electr&oacute;nico *</label>
    <input type="email" id="correo" name="correo" value="<?php echo isset($datosUsuario['correo']) ? $datosUsuario['correo'] : ''; ?>" required>
</div>

<button type="submit" class="btn btn-primary w-100 mt-3">Guardar Cambios</button>
</form>
</div>

</div>
</div>
</div>

<!-- MODAL CAMBIAR CONTRASE&ntilde;A -->
<div class="modal fade" id="modalContrasena" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Cambiar Contrase&ntilde;a</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<form action="<?php echo getUrl('InformacionPersonal','InformacionPersonal','postCambiarContrasena'); ?>" method="post">

<div class="form-group">
    <label for="contrasena_actual">Contrase&ntilde;a Actual *</label>
    <input type="password" id="contrasena_actual" name="contrasena_actual" required>
</div>

<div class="form-group">
    <label for="contrasena_nueva">Contrase&ntilde;a Nueva *</label>
    <input type="password" id="contrasena_nueva" name="contrasena_nueva" minlength="6" required>
</div>

<div class="form-group">
    <label for="contrasena_confirmar">Confirmar Contrase&ntilde;a Nueva *</label>
    <input type="password" id="contrasena_confirmar" name="contrasena_confirmar" minlength="6" required>
</div>

<button type="submit" class="btn btn-primary w-100 mt-3">Cambiar Contrase&ntilde;a</button>
</form>
</div>

</div>
</div>
</div>
<script>
// Auto-submit del formulario cuando se selecciona una foto
document.getElementById('foto_input').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        // Validar tamaño
        if (this.files[0].size > 5242880) {
            alert('La imagen no debe superar 5MB');
            this.value = '';
            return;
        }
        
        // Validar tipo
        var validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(this.files[0].type)) {
            alert('Solo se permiten imágenes JPG, PNG o GIF');
            this.value = '';
            return;
        }
        
        // Enviar formulario
        if (confirm('¿Desea cambiar su foto de perfil?')) {
            document.getElementById('form_foto').submit();
        }
    }
});
</script>
