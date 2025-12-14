<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Tipo de Tanques - KaiAdmin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </style>
</head>

<body>
    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="h3 mb-2">
                <i class="fas fa-water text-primary"></i> Gestion de Tipo de Tanques
            </h1>
        </div>

        <!-- Card con Tabla -->
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
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#modalDetalle<?php echo $tipoTanque['id_tipo_tanque']; ?>">
                                                <i class="fas fa-eye"></i> Ver Detalles
                                            </button>

                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#modalEditar<?php echo $tipoTanque['id_tipo_tanque']; ?>">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>

                                            <?php if ($tipoTanque['nombre_estado_tipo_tanques'] == 'activo') { ?>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#modalInhabilitar<?php echo $tipoTanque['id_tipo_tanque']; ?>">
                                                    <i class="fas fa-ban"></i> Inhabilitar
                                                </button>
                                            <?php } else { ?>
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#modalActivar<?php echo $tipoTanque['id_tipo_tanque']; ?>">
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
                                            <form action="<?php echo getUrl("TipoTanques", "TipoTanques", "postActualizar"); ?>" method="POST">
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

                                <!-- Modal Eliminar individual -->
                                <div class="modal fade" id="modalInhabilitar<?php echo $tipoTanque['id_tipo_tanque']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?php echo getUrl("TipoTanques", "TipoTanques", "postInhabilitar"); ?>" method="POST">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle"></i> Confirmar Inhabilitación
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_tipo_tanque" value="<?php echo $tipoTanque['id_tipo_tanque']; ?>">
                                                    <p>¿Está seguro que desea inhabilitar el tipo de tanque <strong><?php echo $tipoTanque['nombre_tipo_tanque']; ?></strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times"></i> Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-ban"></i> Inhabilitar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Activar individual -->
                                <div class="modal fade" id="modalActivar<?php echo $tipoTanque['id_tipo_tanque']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?php echo getUrl("TipoTanques", "TipoTanques", "postHabilitar"); ?>" method="POST">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-check-circle"></i> Confirmar Activación
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_tipo_tanque" value="<?php echo $tipoTanque['id_tipo_tanque']; ?>">
                                                    <p>¿Está seguro que desea activar el tipo de tanque <strong><?php echo $tipoTanque['nombre_tipo_tanque']; ?></strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times"></i> Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check-circle"></i> Activar
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
                <form action="<?php echo getUrl("TipoTanques", "TipoTanques", "postCrear"); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle text-primary"></i> Nuevo Tipo de Tanque
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Tipo de Tanque</label>
                            <input type="text" class="form-control" name="nombre_tipo_tanque" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="id_estado_tipo_tanque">
                                <option value="1" selected>Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>