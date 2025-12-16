
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
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" 
                    data-bs-target="#modalDetalle<?php echo $tipoTanque['id_tipo_tanque']; ?>">
                    <i class="fas fa-eye"></i> Ver Detalles
                </button>

                <?php if ($tipoTanque['nombre_estado_tipo_tanques'] == 'activo') { ?>
                    <button class="btn btn-warning btn-sm" 
                        onclick="confirmarEdicion('<?php echo $tipoTanque['id_tipo_tanque']; ?>', '<?php echo $tipoTanque['nombre_tipo_tanque']; ?>')">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                <?php } else { ?>
                    <button class="btn btn-warning btn-sm" 
                        onclick="mostrarAlertaInactivo('<?php echo $tipoTanque['nombre_tipo_tanque']; ?>')">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                <?php } ?>

                <?php if ($tipoTanque['nombre_estado_tipo_tanques'] == 'activo') { ?>
                    <button class="btn btn-danger btn-sm" 
                        onclick="confirmarInhabilitacion('<?php echo $tipoTanque['id_tipo_tanque']; ?>', '<?php echo $tipoTanque['nombre_tipo_tanque']; ?>')">
                        <i class="fas fa-ban"></i> Inhabilitar
                    </button>
                <?php } else { ?>
                    <button class="btn btn-success btn-sm" 
                        onclick="confirmarActivacion('<?php echo $tipoTanque['id_tipo_tanque']; ?>', '<?php echo $tipoTanque['nombre_tipo_tanque']; ?>')">
                        <i class="fas fa-check-circle"></i> Activar
                    </button>
                <?php } ?>
            </div>
        </td>
    </tr>

    <!-- Modal Ver Detalle individual -->
    <div class="modal fade" id="modalDetalle<?php echo $tipoTanque['id_tipo_tanque']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle text-info"></i> Detalle de Tipo de Tanque
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">ID:</div>
                        <div class="col-8"><?php echo $tipoTanque['id_tipo_tanque']; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Nombre:</div>
                        <div class="col-8"><?php echo $tipoTanque['nombre_tipo_tanque']; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Estado:</div>
                        <div class="col-8">
                            <?php
                            if ($tipoTanque['nombre_estado_tipo_tanques'] == 'activo') {
                                echo '<span class="badge bg-success">Activo</span>';
                            } else {
                                echo '<span class="badge bg-danger">Inactivo</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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
                            <label class="form-label">Nombre del Tipo de Tanque</label>
                            <input type="text" class="form-control" name="nombre_tipo_tanque" 
                                id="nombreEditar<?php echo $tipoTanque['id_tipo_tanque']; ?>"
                                value="<?php echo $tipoTanque['nombre_tipo_tanque']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="id_estado_tipo_tanque">
                                <option value="1" <?php echo ($tipoTanque['nombre_estado_tipo_tanques'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                                <option value="2" <?php echo ($tipoTanque['nombre_estado_tipo_tanques'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
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
