<div style="position: relative; top: -70px;">
    <style>
        body {
            background-color: #f4f5f7;
        }

        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .table-responsive {
            border-radius: 0 0 0.5rem 0.5rem;
        }
        .main-title {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            color: #1f2937;
        }
    </style>
</head>

<body>
  
        <h1 class="main-title">Gestión de Tipo de Tanques</h1>
        <!-- Card y su tabla -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list text-primary"></i> Lista de Tipos de Tanques
                </h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                    <i class="fas fa-plus"></i> Nuevo Tipo de Tanque
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre del Tipo de Tanque</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <?php
                        // convertir el resultado en array 
                        $listaTiposTanques = array();

                        if ($tiposTanques) {
                            while ($row = pg_fetch_assoc($tiposTanques)) {
                                $listaTiposTanques[] = $row;
                            }
                        }
                        ?>
                        <tbody>
                            <?php
                            foreach ($listaTiposTanques as $tipoTanque) { ?>
                                <tr>
                                    <td><?php echo $tipoTanque['id_tipo_tanque']; ?></td>
                                    <td><?php echo $tipoTanque['nombre_tipo_tanque']; ?></td>
                                    <td>
                                        <?php
                                        if ($tipoTanque['nombre_estado_tipo_tanques'] == 'activo') {
                                            echo '<span class="badge bg-success">Activo</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Inactivo</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <?php if ($tipoTanque['nombre_estado_tipo_tanques'] == 'activo') { ?>
                                                <button class="btn btn-warning btn-sm" 
                                                    onclick="confirmarEdicion('<?php echo $tipoTanque['id_tipo_tanque']; ?>', '<?php echo addslashes($tipoTanque['nombre_tipo_tanque']); ?>')">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                                
                                                <button class="btn btn-danger btn-sm" 
                                                    onclick="confirmarInhabilitacion('<?php echo $tipoTanque['id_tipo_tanque']; ?>', '<?php echo addslashes($tipoTanque['nombre_tipo_tanque']); ?>')">
                                                    <i class="fas fa-ban"></i> Inhabilitar
                                                </button>
                                            <?php } else { ?>
                                                <span class="text-muted">Sin acciones disponibles</span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Editar individual -->
                                <div class="modal fade" id="modalEditar<?php echo $tipoTanque['id_tipo_tanque']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form id="formEditar<?php echo $tipoTanque['id_tipo_tanque']; ?>" 
                                                action="<?php echo getUrl("TipoTanques", "TipoTanques", "postActualizar"); ?>" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-edit text-warning"></i> Editar Tipo de Tanque
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_tipo_tanque" value="<?php echo $tipoTanque['id_tipo_tanque']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nombre del Tipo de Tanque *</label>
                                                        <input type="text" class="form-control" name="nombre_tipo_tanque" 
                                                            id="nombreEditar<?php echo $tipoTanque['id_tipo_tanque']; ?>"
                                                            value="<?php echo $tipoTanque['nombre_tipo_tanque']; ?>" required>
                                                    </div>
                                                    <!-- SE ELIMINÓ EL SELECTOR DE ESTADO EN EDICIÓN -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times"></i> Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save"></i> Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Tipo de Tanque -->
    <div class="modal fade" id="modalNuevo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formNuevo" action="<?php echo getUrl("TipoTanques", "TipoTanques", "postCrear"); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle text-primary"></i> Nuevo Tipo de Tanque
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Tipo de Tanque *</label>
                            <input type="text" class="form-control" name="nombre_tipo_tanque" id="nombreTanqueNuevo" required>
                        </div>
                        <!-- SE ELIMINÓ EL SELECTOR DE ESTADO -->
                        <!-- El estado será ACTIVO por defecto en el backend -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para limpiar espacios múltiples mientras el usuario escribe
        function limpiarEspacios(input) {
            // Eliminar espacios al inicio
            input.value = input.value.replace(/^\s+/, '');
            // Reemplazar múltiples espacios por uno solo
            input.value = input.value.replace(/\s{2,}/g, ' ');
        }

        // Aplicar limpieza de espacios a los inputs al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Input del modal nuevo
            const inputNuevo = document.getElementById('nombreTanqueNuevo');
            if (inputNuevo) {
                inputNuevo.addEventListener('input', function() {
                    limpiarEspacios(this);
                });
            }

            // Inputs de los modales de edición
            document.querySelectorAll('[id^="nombreEditar"]').forEach(function(input) {
                input.addEventListener('input', function() {
                    limpiarEspacios(this);
                });
            });
        });

        // Mostrar mensajes
        <?php if (isset($mensaje) && isset($tipo_mensaje)) { ?>
            Swal.fire({
                icon: '<?php echo $tipo_mensaje == "success" ? "success" : ($tipo_mensaje == "warning" ? "warning" : "error"); ?>',
                title: '<?php echo $tipo_mensaje == "success" ? "¡Éxito!" : ($tipo_mensaje == "warning" ? "Advertencia" : "Error"); ?>',
                text: '<?php echo $mensaje; ?>',
                confirmButtonColor: '#3085d6',
                timer: 3000,
                timerProgressBar: true
            });
        <?php } ?>

        // Confirmar edición con SweetAlert2
        function confirmarEdicion(idTanque, nombreTanque) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas editar el tipo de tanque '" + nombreTanque + "'?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, editar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar' + idTanque));
                    modalEditar.show();
                }
            });
        }

        // Confirmar inhabilitación
        function confirmarInhabilitacion(idTanque, nombreTanque) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas inhabilitar el tipo de tanque '" + nombreTanque + "'? Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, inhabilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?php echo getUrl("TipoTanques", "TipoTanques", "postInhabilitar"); ?>';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id_tipo_tanque';
                    input.value = idTanque;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</div>
