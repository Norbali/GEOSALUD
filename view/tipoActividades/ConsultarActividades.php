<?php
    include_once '../lib/helpers.php';
    $permisos = $_SESSION['permisos'];

    echo '<pre>';

    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        background-color: #f4f5f7;
    }
    
    .page-content-fix {
        position: relative;
        top: 0 !important;
        transform: none !important;
        margin-top: 0 !important;
        padding-top: 1rem;
    }

    .main-panel,
    .content,
    .container-fluid {
        overflow: visible !important;
    }

    .page-header {
        display: block !important;
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

    

    .table thead th {
        cursor: pointer;
        user-select: none;
    }

    .table thead th:hover {
        background-color: #e9ecef;
    }

    .sortable {
        cursor: pointer;
    }

   
</style>

<!-- Alertas de éxito o error -->
<?php if (!empty($alert)) { ?>
    <div class="container-fluid px-4 pt-3">
        <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
            <strong><?= $alert['message'] ?></strong>
            <?php if (!empty($alert['error'])) { ?>
                <br>
                <small><?= htmlspecialchars($alert['error']) ?></small>
            <?php } ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php } ?>

<div class="container-fluid px-4 py-4">
    <div class="page-content-fix">

        <div class="page-header">
            <h1 class="h3 mb-2">
                <i class="fas fa-tasks text-primary"></i> Gestión de Actividades
            </h1>
        </div>

        <!-- Card con Tabla -->
         <form action="<?php echo getUrl("TipoActividades","ConsultarTipoDeActividades","getConsulta") ?>" method="POST">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list text-primary"></i> Lista de Actividades
                </h5>

                <div class="d-flex gap-2">
                    <!-- ORDENAR -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle sortable" type="button" data-bs-toggle="dropdown" aria-expanded="false"onclick="sortTable('id')" data-column="id" data-direction="<?php echo $order; ?>">
                <i class="bi bi-sort-down"></i> Ordenar por ID
            </button>
                
                    </div>

                    <!-- NUEVA ACTIVIDAD -->
                        <?php if (in_array('actualizar', $permisos['TiposDeActividades'])) { ?> 
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                        <i class="fas fa-plus"></i> Nueva Actividad
                    </button>
                    <?php } ?>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaActividades">
                        <thead class="table-light">
                            <tr>
                                <th onclick="sortTable('id')">ID</th>
                                <th onclick="sortTable('nombre')">Nombre</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $listaActividades = array();
                            if ($actividades) {
                                while ($row = pg_fetch_assoc($actividades)) {
                                    $listaActividades[] = $row;
                                }
                            }

                            foreach ($listaActividades as $actividad) { ?>
                                <tr>
                                    <td><?= $actividad['id_actividad'] ?></td>
                                    <td><?= $actividad['nombre_actividad'] ?></td>
                                    <td>
                                        <?php if ($actividad['nombre_estado_actividades'] == 'Activo') { ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <?php if ($actividad['nombre_estado_actividades'] === 'Activo') { ?>
                                                
                                               <?php 
                                                //Acceso a acciones segun permisos de rol 
                                                if (in_array('actualizar', $permisos['TiposDeActividades'])) { ?>
                                                <button type="button"
                                                    class="btn btn-warning btn-sm"
                                                    onclick="editarActividad(
                                                       <?= $actividad['id_actividad'] ?>,
                                                       '<?= addslashes($actividad['nombre_actividad']) ?>',
                                                      '<?= $actividad['id_estado_actividad'] ?>'
                                                        )">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                                 <?php } ?> 
                                            <?php } else { ?>
                                                 <?php
                                                 if (in_array('actualizar', $permisos['TiposDeActividades'])) { ?> 
                                                <button type="button"
                                                    class="btn btn-warning btn-sm"
                                                    onclick="actividadInhabilitada()">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                             <?php } ?> 
                                              <?php } ?>

                                                <?php if (in_array('inhabilitar', $permisos['TiposDeActividades'])) { ?> 
                                            <?php if ($actividad['nombre_estado_actividades'] === 'Activo') { ?>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="eliminarActividad(<?= $actividad['id_actividad']; ?>)">
                                                    <i class="fas fa-trash"></i> Inhabilitar
                                                </button>
                                                <?php } ?> 
                                            <?php } else { ?>

                                                <?php if (in_array('inhabilitar', $permisos['TiposDeActividades'])) { ?>
                                                <button type="button"
                                                    class="btn btn-secondary btn-sm"
                                                    disabled>
                                                    <i class="fas fa-ban"></i> Inhabilitada
                                                </button>
                                                <?php } ?>
                                            <?php } ?>
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

    <!-- MODAL NUEVO -->
    <div class="modal fade" id="modalNuevo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formNuevaActividad" action="<?php echo getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'postCreate'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva Actividad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text"
                                class="form-control"
                                name="nombre_actividad"
                                id="nombreActividad">
                        </div>

                        <?php
                        $listaEstados = array();
                        if ($estados) {
                            while ($row = pg_fetch_assoc($estados)) {
                                $listaEstados[] = $row;
                            }
                        }
                        ?>

                        <div class="mb-3">
                            <label class="form-label">Estado *</label>
                            <select class="form-select"
                                name="id_estado_actividad"
                                id="estadoActividad">
                                <option value="">Seleccione</option>
                                <?php foreach ($listaEstados as $estado) { ?>
                                    <option value="<?= $estado['id_estado_actividades'] ?>">
                                        <?= $estado['nombre_estado_actividades'] ?>
                                    </option>
                                <?php } ?>
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

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST"
                    action="<?php echo getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'postUpdate'); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Actividad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- ID -->
                        <input type="hidden" name="id_actividad" id="editId">

                        <!-- NOMBRE  -->
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre de la Actividad</label>
                            <input type="text" class="form-control" id="editNombre" required>
                        </div>

                        <!-- ESTADO  -->
                        <div class="mb-3">
                            <label for="editEstado" class="form-label">Estado</label>
                            <select class="form-select" id="editEstado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>

                            <!-- EL ESTADO QUE SE ENVÍA -->
                            <input type="hidden"
                                name="id_estado_actividad"
                                id="editEstadoHidden">

                            <small class="text-muted">
                                El estado no se puede editar.
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </button>
                        <button type="submit"
                            class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Variable global para controlar el ordenamiento
let currentSort = { column: null, direction: 'asc' };

function sortTable(column, direction) {
    const table = document.getElementById('tablaActividades');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Si no se especifica dirección, alternar
    if (!direction) {
        if (currentSort.column === column) {
            direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            direction = 'asc';
        }
    }
    
    currentSort = { column, direction };
    
    // Determinar el índice de la columna
    let columnIndex;
    switch(column) {
        case 'id': columnIndex = 0; break;
        case 'nombre': columnIndex = 1; break;
        default: return;
    }
    
    // Ordenar filas
    rows.sort((a, b) => {
        let aValue = a.cells[columnIndex].textContent.trim();
        let bValue = b.cells[columnIndex].textContent.trim();
        
        // Si es ID, convertir a número
        if (column === 'id') {
            aValue = parseInt(aValue);
            bValue = parseInt(bValue);
        }
        
        if (aValue < bValue) return direction === 'asc' ? -1 : 1;
        if (aValue > bValue) return direction === 'asc' ? 1 : -1;
        return 0;
    });
    
    // Limpiar y re-agregar filas
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
    
    // Actualizar indicadores visuales
    document.querySelectorAll('.sortable').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
    });
}
</script>

<script src="assets/js/funcionesModalTipoActividades.js"></script>
