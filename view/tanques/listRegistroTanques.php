<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Tanques</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    background-color: #f5f5f5;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.alert-custom {
    background-color: #f8d7da;
    border: none;
    border-radius: 8px;
    color: #721c24;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.list-card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.list-header h5 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    padding: 12px;
    cursor: pointer;
    user-select: none;
}

.table tbody td {
    padding: 15px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.badge {
    padding: 6px 12px;
    font-weight: 500;
    font-size: 0.875rem;
}

.btn-action {
    padding: 6px 16px;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 6px;
    margin-right: 5px;
    white-space: nowrap;
}

.btn-ver {
    background-color: #0d6efd;
    border: none;
    color: white;
}

.btn-ver:hover {
    background-color: #0b5ed7;
    color: white;
}

.btn-edit {
    background-color: #ffc107;
    border: none;
    color: #000;
}

.btn-edit:hover {
    background-color: #e0a800;
    color: #000;
}

.btn-disable {
    background-color: #dc3545;
    border: none;
    color: white;
}

.btn-disable:hover {
    background-color: #c82333;
    color: white;
}

.btn-enable {
    background-color: #28a745;
    border: none;
    color: white;
}

.btn-enable:hover {
    background-color: #218838;
    color: white;
}

/* Estilos para formularios */
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

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.5rem 0;
    font-size: 1rem;
    border: none;
    border-bottom: 1px solid #dee2e6;
    background-color: transparent;
    outline: none;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    border-bottom: 2px solid #0d6efd;
}

.form-group select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    padding-right: 2rem;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-lg {
    max-width: 800px;
}

.btn-close {
    font-size: 0.875rem;
}
</style>
</head>

<body>

<div class="container mt-4">

<!-- HEADER -->
<div class="header-card">
    <h2><i class="fas fa-water text-primary "></i> Gesti&oacute;n de Tanques</h2>
</div>

<!-- LISTA -->
<div class="list-card">
    <div class="list-header">
        <h5><i class="fas fa-list text-primary"></i> Lista de Tanques</h5>
        <div class="header-actions">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i  class="sortable" onclick="sortTable('id')" data-column="id" data-direction="<?php echo $order; ?>">Ordenar por ID</i>
                </button>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                <i class="fas fa-plus"></i> Nuevo Tanque
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="tablaTanques">
        <thead>
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>TIPO</th>
            <th>RESPONSABLE</th>
            <th>ESTADO</th>
            <th style="min-width: 280px;">ACCIONES</th>
        </tr>
        </thead>

        <tbody>
        <?php while ($t = pg_fetch_assoc($tanques)) { ?>
        <tr>
        <td><?php echo $t['id_tanque']; ?></td>
        <td><?php echo $t['nombre_tanque']; ?></td>
        <td><?php echo $t['nombre_tipo_tanque']; ?></td>
        <td><?php echo $_SESSION['nombreCompleto']; ?></td>

        <td>
        <?php if ($t['id_estado_tanque'] == 1) { ?>
        <span class="badge bg-success">Activo</span>
        <?php } else { ?>
        <span class="badge bg-secondary">Inactivo</span>
        <?php } ?>
        </td>

        <td style="white-space: nowrap;">
        <!-- VER -->
        <button class="btn btn-action btn-ver"
        data-bs-toggle="modal"
        data-bs-target="#modalVer"
        data-nombre="<?php echo $t['nombre_tanque']; ?>"
        data-tipo="<?php echo $t['nombre_tipo_tanque']; ?>"
        data-alto="<?php echo $t['medida_alto']; ?>"
        data-ancho="<?php echo $t['medida_ancho']; ?>"
        data-profundidad="<?php echo $t['medida_profundidad']; ?>"
        data-cantidad="<?php echo $t['cantidad_peces']; ?>"
        data-documento="<?php echo $_SESSION['documento']; ?>"
        data-responsable="<?php echo $_SESSION['nombreCompleto']; ?>"
        data-rol="<?php echo $_SESSION['nombreRol']; ?>">
        <i class="fas fa-eye"></i> Ver
        </button>

        <!-- EDITAR -->
        <button class="btn btn-action btn-edit"
        data-bs-toggle="modal"
        data-bs-target="#modalEditar"
        data-id="<?php echo $t['id_tanque']; ?>"
        data-nombre="<?php echo $t['nombre_tanque']; ?>"
        data-alto="<?php echo $t['medida_alto']; ?>"
        data-ancho="<?php echo $t['medida_ancho']; ?>"
        data-profundidad="<?php echo $t['medida_profundidad']; ?>"
        data-cantidad="<?php echo $t['cantidad_peces']; ?>"
        data-tipo="<?php echo $t['id_tipo_tanque']; ?>">
        <i class="fas fa-edit"></i> Editar
        </button>

        <!-- INHABILITAR / HABILITAR -->
        <?php if ($t['id_estado_tanque'] == 1) { ?>
        <a class="btn btn-action btn-disable"
        href="<?php echo getUrl(
            'Tanques',
            'Tanques',
            'updateStatus',
            array(
                'id_tanque' => $t['id_tanque'],
                'estado' => 2
            )
        ); ?>">
        <i class="fas fa-trash"></i> Inhabilitar
        </a>
        <?php } else { ?>
        <a class="btn btn-action btn-enable"
        href="<?php echo getUrl(
            'Tanques',
            'Tanques',
            'updateStatus',
            array(
                'id_tanque' => $t['id_tanque'],
                'estado' => 1
            )
        ); ?>">
        <i class="fas fa-check"></i> Activar
        </a>
        <?php } ?>
        </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</div>

</div>

<!-- ================= MODAL VER ================= -->
<div class="modal fade" id="modalVer" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Detalles del Tanque</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<h6 class="text-muted mb-3">Responsable</h6>
<p><strong>Documento:</strong> <span id="v_documento"></span></p>
<p><strong>Nombre:</strong> <span id="v_responsable"></span></p>
<p><strong>Rol:</strong> <span id="v_rol"></span></p>

<hr>

<h6 class="text-muted mb-3">Datos del Tanque</h6>
<p><strong>Nombre:</strong> <span id="v_nombre"></span></p>
<p><strong>Tipo:</strong> <span id="v_tipo"></span></p>
<p><strong>Alto:</strong> <span id="v_alto"></span></p>
<p><strong>Ancho:</strong> <span id="v_ancho"></span></p>
<p><strong>Profundidad:</strong> <span id="v_profundidad"></span></p>
<p><strong>Cantidad peces:</strong> <span id="v_cantidad"></span></p>
</div>

</div>
</div>
</div>

<!-- ================= MODAL NUEVO ================= -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Registrar Tanque</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<form action="<?php echo getUrl('Tanques','Tanques','postCreate'); ?>" method="post">

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre_tanque" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="alto">Alto *</label>
            <input type="number" step="0.01" id="alto" name="medida_alto" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="ancho">Ancho *</label>
            <input type="number" step="0.01" id="ancho" name="medida_ancho" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="profundidad">Profundidad *</label>
            <input type="number" step="0.01" id="profundidad" name="medida_profundidad" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="tipo">Tipo *</label>
            <select id="tipo" name="id_tipo_tanque" required>
                <option value="">Seleccione...</option>
                <?php 
                pg_result_seek($tipos, 0);
                while ($tp = pg_fetch_assoc($tipos)) { 
                ?>
                <option value="<?php echo $tp['id_tipo_tanque']; ?>">
                <?php echo $tp['nombre_tipo_tanque']; ?>
                </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="cantidad">Cantidad peces *</label>
            <input type="number" id="cantidad" name="cantidad_peces" required>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary w-100 mt-3">Guardar</button>
</form>
</div>

</div>
</div>
</div>

<!-- ================= MODAL EDITAR ================= -->
<div class="modal fade" id="modalEditar" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Editar Tanque</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<form action="<?php echo getUrl('Tanques','Tanques','postUpdate'); ?>" method="post">

<input type="hidden" id="e_id_tanque" name="id_tanque">

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="e_nombre">Nombre *</label>
            <input type="text" id="e_nombre" name="nombre_tanque" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="e_alto">Alto *</label>
            <input type="number" step="0.01" id="e_alto" name="medida_alto" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="e_ancho">Ancho *</label>
            <input type="number" step="0.01" id="e_ancho" name="medida_ancho" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="e_profundidad">Profundidad *</label>
            <input type="number" step="0.01" id="e_profundidad" name="medida_profundidad" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="e_tipo">Tipo *</label>
            <select id="e_tipo" name="id_tipo_tanque" required>
                <option value="">Seleccione...</option>
                <?php 
                pg_result_seek($tipos, 0);
                while ($tp = pg_fetch_assoc($tipos)) { 
                ?>
                <option value="<?php echo $tp['id_tipo_tanque']; ?>">
                <?php echo $tp['nombre_tipo_tanque']; ?>
                </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="e_cantidad">Cantidad peces *</label>
            <input type="number" id="e_cantidad" name="cantidad_peces" required>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-warning w-100 mt-3">Actualizar</button>
</form>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Modal Ver
document.getElementById('modalVer').addEventListener('show.bs.modal', function (e) {
var b = e.relatedTarget;
document.getElementById('v_nombre').innerHTML = b.getAttribute('data-nombre');
document.getElementById('v_tipo').innerHTML = b.getAttribute('data-tipo');
document.getElementById('v_alto').innerHTML = b.getAttribute('data-alto');
document.getElementById('v_ancho').innerHTML = b.getAttribute('data-ancho');
document.getElementById('v_profundidad').innerHTML = b.getAttribute('data-profundidad');
document.getElementById('v_cantidad').innerHTML = b.getAttribute('data-cantidad');
document.getElementById('v_documento').innerHTML = b.getAttribute('data-documento');
document.getElementById('v_responsable').innerHTML = b.getAttribute('data-responsable');
document.getElementById('v_rol').innerHTML = b.getAttribute('data-rol');
});

// Modal Editar
document.getElementById('modalEditar').addEventListener('show.bs.modal', function (e) {
var b = e.relatedTarget;
document.getElementById('e_id_tanque').value = b.getAttribute('data-id');
document.getElementById('e_nombre').value = b.getAttribute('data-nombre');
document.getElementById('e_alto').value = b.getAttribute('data-alto');
document.getElementById('e_ancho').value = b.getAttribute('data-ancho');
document.getElementById('e_profundidad').value = b.getAttribute('data-profundidad');
document.getElementById('e_cantidad').value = b.getAttribute('data-cantidad');
document.getElementById('e_tipo').value = b.getAttribute('data-tipo');
});

// Función de ordenamiento
let currentSort = { column: null, direction: 'asc' };

function sortTable(column, direction) {
    const table = document.getElementById('tablaTanques');
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
        case 'tipo': columnIndex = 2; break;
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

</body>
</html>