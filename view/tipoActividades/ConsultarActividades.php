<?php
include_once '../lib/helpers.php';

if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>

<style>
    body {
        background-color: #f4f5f7;
    }

    .main-title {
        font-size: 3rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
        color: #1f2937;
    }

    .card {
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
    }

    .btn-action-full {
        width: 100%;
        background-color: #ffb347;
        border: none;
        color: #000;
        font-weight: 600;
        padding: 14px;
        border-radius: 6px;
    }

    .btn-action-full:hover {
        background-color: #ffa733;
    }

    .table thead th {
        cursor: pointer;
    }

    .search-input {
        max-width: 280px;
    }

    .acciones-btns {
        white-space: nowrap;
    }

    @media (max-width: 576px) {
        .acciones-btns button {
            padding: 4px 8px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="container-fluid px-4 py-4">

    <h1 class="main-title">Gestión de Actividades</h1>

    <?php if (!empty($alert)) { ?>
        <div id="alertaAuto" class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show"
            role="alert">
            <?= $alert['message'] ?>
        </div>
    <?php } ?>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row align-items-start">

                <!--  TITULO Y BUSCADOR -->
                <div class="col-md-6 col-12">
                    <h5 class="mb-2">
                        <i class="fas fa-list text-primary"></i> Lista de Actividades
                    </h5>

                    <input
                        type="text"
                        id="buscadorNombre"
                        class="form-control search-input"
                        placeholder="Buscar actividad...">
                </div>

                <!-- BOTONES DE NUEVA ACTIVIDAD, ORDEN POR ID Y FECHA) -->
                <div class="col-md-6 col-12">
                    <div class="d-flex justify-content-md-end align-items-center gap-1 mt-5">
                       <?php if (in_array('registrar', $permisos['TiposDeActividades'])) { ?>
                        <button
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalNuevo">
                            <i class="fas fa-plus"></i> Nueva Actividad
                        </button>
                        <?php } ?>

                        <button
                            class="btn btn-outline-primary"
                            onclick="ordenarTabla(0)">
                            <i class="fas fa-sort-numeric-down"></i> ID
                        </button>

                        <button
                            class="btn btn-outline-primary"
                            onclick="ordenarTabla(2)">
                            <i class="fas fa-calendar-alt"></i> Fecha
                        </button>

                    </div>
                </div>

            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tablaActividades">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($actividades) {
                        while ($actividad = pg_fetch_assoc($actividades)) { ?>
                            <tr>
                                <td><?= $actividad['id_actividad'] ?></td>
                                <td><?= $actividad['nombre_actividad'] ?></td>
                                <td><?= date('Y-m-d', strtotime($actividad['fecha_creacion'])) ?></td>
                                <td>
                                    <?php if ($actividad['nombre_estado_actividades'] === 'Activo') { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($actividad['nombre_estado_actividades'] === 'Activo') { ?>
                                        <div class="d-flex justify-content-center gap-2 flex-nowrap acciones-btns">
                                         <?php if (in_array('actualizar', $permisos['TiposDeActividades'])) { ?>
                                            <button class="btn btn-warning btn-sm"
                                                onclick="editarActividad(
                                                 <?= $actividad['id_actividad'] ?>,
                                                '<?= addslashes($actividad['nombre_actividad']) ?>',
                                                <?= $actividad['id_estado_actividad'] ?>
                                                )">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <?php } ?>

                                             <?php if (in_array('inhabilitar', $permisos['TiposDeActividades'])) { ?>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="eliminarActividad(<?= $actividad['id_actividad'] ?>)">
                                                <i class="fas fa-trash"></i> Inhabilitar
                                            </button>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <span class="text-muted fst-italic">
                                            Sin acciones disponibles
                                        </span>
                                    <?php } ?>
                                </td>

                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--  MODAL NUEVO -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <form method="POST"
                action="<?php echo getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'postCreate'); ?>">

                <div class="modal-header">
                    <i class="fas fa-plus-circle text-primary"></i> Nuevo Tipo de Tanque
                </div>

                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Nombre *</label>
                        <input type="text"
                            class="form-control"
                            name="nombre_actividad">
                    </div>

                    <!-- ESTADO ACTIVO -->
                    <input type="hidden" name="id_estado_actividad" value="1">
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

<!--  MODAL EDITAR  -->
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <form method="POST"
                action="<?php echo getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'postUpdate'); ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Editar Actividad</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_actividad" id="editId">

                    <div class="mb-4">
                        <label class="form-label">Nombre *</label>
                        <input type="text"
                            class="form-control"
                            name="nombre_actividad"
                            id="editNombre">
                    </div>

                    <input type="hidden"
                        name="id_estado_actividad"
                        id="editEstadoHidden">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerta = document.getElementById('alertaAuto');
        if (alerta) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alerta);
                bsAlert.close();
            }, 3000);
        }
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/funcionesModalTipoActividades.js"></script>