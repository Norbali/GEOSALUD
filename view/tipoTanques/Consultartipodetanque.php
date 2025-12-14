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
                                                <button class="btn btn-info btn-sm"
                                                    onclick="verDetalle(<?php echo $tipoTanque['id_tipo_tanque']; ?>)">
                                                    <i class="fas fa-eye"></i> Ver
                                                </button>

                                                <button class="btn btn-warning btn-sm"
                                                    onclick="editar(<?php echo $tipoTanque['id_tipo_tanque']; ?>)">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>

                                                <button class="btn btn-danger btn-sm"
                                                    onclick="eliminar(<?php echo $tipoTanque['id_tipo_tanque']; ?>)">
                                                    <i class="fas fa-trash"></i> Inhabilitar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal Nuevo Tipo de Tanque -->
    <div class="modal fade" id="modalNuevo" tabindex="-1" aria-labelledby="modalNuevoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoLabel">
                        <i class="fas fa-plus-circle text-primary"></i> Nuevo Tipo de Tanque
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevo">
                        <div class="mb-3">
                            <label for="nuevoNombre" class="form-label">Nombre del Tipo de Tanque</label>
                            <input type="text" class="form-control" id="nuevoNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="nuevoEstado" class="form-label">Estado</label>
                            <select class="form-select" id="nuevoEstado">
                                <option value="1" selected>Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="guardarNuevo()">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ver Detalle -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalleLabel">
                        <i class="fas fa-info-circle text-info"></i> Detalle de Tipo de Tanque
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">ID:</div>
                        <div class="col-8" id="detalleId">-</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Nombre:</div>
                        <div class="col-8" id="detalleNombre">-</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Estado:</div>
                        <div class="col-8" id="detalleEstado">
                            <span class="badge bg-success">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">
                        <i class="fas fa-edit text-warning"></i> Editar Tipo de Tanque
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre del Tipo de Tanque</label>
                            <input type="text" class="form-control" id="editNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEstado" class="form-label">Estado</label>
                            <select class="form-select" id="editEstado">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicion()">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/funcionesModalTipoTanques.js"></script>
</body>

</html>