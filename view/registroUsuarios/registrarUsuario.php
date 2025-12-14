<?php 
    include_once '../lib/helpers.php';
?>
    <?php if (!empty($_SESSION['success'])){ ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php } ?>

    <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>
    <div style="position: relative; top: -70px;">

        <div class="card mx-auto mt-1" style="max-width: 800px; margin-top:-30px;">
            <div class="card-header mt-1">
                <div class="card-title">Registro de Usuario</div>
            </div>

            <form id="registroForm" class="" action="<?php echo getUrl("RegistroUsuarios","RegistroUsuarios","postCreate") ?>" method="POST">
                <div class="card-body">

                    <div class="form-group">
                        <label for="documento">Documento*</label>
                        <input type="text" class="form-control" id="documento" name="documento" placeholder="Ingrese un n&uacute;mero de documento" minlength="9" maxlength="10" required>
                        <small id="error-documento" class="text-danger"></small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre*</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese un nombre" minlength="2" maxlength="20" required>
                                <small id="error-nombre" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido">Apellido*</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese un apellido" minlength="2" maxlength="20" required>
                                <small id="error-apellido" class="text-danger"></small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Tel&eacute;fono*</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese un n&uacute;mero de tel&eacute;fono" minlength="9" maxlength="10" required>
                        <small id="error-telefono" class="text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo*</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese un correo electronico" required>
                        <small id="error-correo" class="text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="contrasena">Contrase&ntilde;a*</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese una contrase&ntilde;a" minlength="8" maxlength="16" required>
                        <small id="error-contrasena" class="text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="id_rol">Rol*</label>
                        <select class="form-control" id="id_rol" name="id_rol" required>
                            <option value="">Seleccione un rol</option>
                            <?php while ($rol = pg_fetch_assoc($roles)) { ?>
                                <option value="<?php echo $rol['id_rol']; ?>">
                                    <?php echo $rol['nombre_rol']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <small id="error-id_rol" class="text-danger"></small>
                    </div>

                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
<script>
document.getElementById('registroForm').addEventListener('submit', function (event) {
    event.preventDefault(); // SIEMPRE detenemos primero

    // Limpiar errores
    document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

    let valido = true;

    const documento  = document.getElementById('documento').value.trim();
    const nombre     = document.getElementById('nombre').value.trim();
    const apellido   = document.getElementById('apellido').value.trim();
    const telefono   = document.getElementById('telefono').value.trim();
    const correo     = document.getElementById('correo').value.trim();
    const contrasena = document.getElementById('contrasena').value;
    const rol        = document.getElementById('id_rol').value;

    /* ===== DOCUMENTO ===== */
    if (!/^\d{9,10}$/.test(documento)) {
        document.getElementById('error-documento').textContent =
            'El documento debe tener 9 o 10 dígitos numéricos.';
        valido = false;
    }

    /* ===== NOMBRE ===== */
    if (!/^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{2,20}$/.test(nombre)) {
        document.getElementById('error-nombre').textContent =
            'El nombre debe tener entre 2 y 20 letras.';
        valido = false;
    }

    /* ===== APELLIDO ===== */
    if (!/^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{2,20}$/.test(apellido)) {
        document.getElementById('error-apellido').textContent =
            'El apellido debe tener entre 2 y 20 letras.';
        valido = false;
    }

    /* ===== TELÉFONO ===== */
    if (!/^\d{9,10}$/.test(telefono)) {
        document.getElementById('error-telefono').textContent =
            'El teléfono debe tener entre 9 y 10 dígitos.';
        valido = false;
    }

    /* ===== CORREO ===== */
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
        document.getElementById('error-correo').textContent =
            'El correo no tiene un formato válido.';
        valido = false;
    }

    /* ===== CONTRASEÑA ===== */
    if (
        contrasena.length < 8 ||
        contrasena.length > 16 ||
        !/[A-Z]/.test(contrasena) ||
        !/[a-z]/.test(contrasena) ||
        !/[0-9]/.test(contrasena) ||
        !/[!@#$%^&*(),.?":{}|<>_\-+=`[\]\\;/]/.test(contrasena)
    ) {
        document.getElementById('error-contrasena').textContent =
            'La contraseña debe tener 8 a 16 caracteres, una mayúscula, un número y un símbolo.';
        valido = false;
    }

    /* ===== ROL ===== */
    if (rol === '') {
        document.getElementById('error-id_rol').textContent =
            'Debe seleccionar un rol.';
        valido = false;
    }

    /* ===== RESULTADO FINAL ===== */
    if (valido) {
        // SOLO aquí se envía el formulario
        this.submit();
    }
});
</script>
