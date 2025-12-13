<<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Actividades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .main-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        .breadcrumb-text {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .table thead th {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem 0.75rem;
        }
        .table tbody tr {
            border-bottom: 1px solid #f1f3f5;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        .badge-estado-progreso {
            background-color: #cfe2ff;
            color: #084298;
        }
        .badge-estado-completada {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .badge-estado-pendiente {
            background-color: #fff3cd;
            color: #997404;
        }
        .badge-prioridad-alta {
            background-color: #f8d7da;
            color: #842029;
        }
        .badge-prioridad-media {
            background-color: #fff3cd;
            color: #997404;
        }
        .badge-prioridad-baja {
            background-color: #e9ecef;
            color: #495057;
        }
        .badge-habilitado {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .badge-inhabilitado {
            background-color: #f8d7da;
            color: #842029;
        }
        .btn-action {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-view {
            background-color: #0dcaf0;
            color: white;
        }
        .btn-view:hover {
            background-color: #0aa2c0;
        }
        .btn-edit {
            background-color: #ffc107;
            color: white;
        }
        .btn-edit:hover {
            background-color: #d39e00;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #bb2d3b;
        }
        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="header-card">
            <h1 class="h3 fw-bold mb-2">
                <i class="bi bi-list"></i> Gestión de Actividades
            </h1>
            <p class="breadcrumb-text mb-0">
                <i class="bi bi-square"></i> Inicio / Actividades / Listado
            </p>
        </div>

        <div class="main-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0">
                    <i class="bi bi-square"></i> Lista de Actividades
                </h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaActividad">
                    <i class="bi bi-plus-lg"></i> Nueva Actividad
                </button>
            </div>

            <div class="table-responsive">
                <table class="table" id="tablaActividades">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ACTIVIDAD</th>
                            <th>RESPONSABLE</th>
                            <th>ESTADO</th>
                            <th>PRIORIDAD</th>
                            <th>FECHA</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="fw-semibold">Desarrollo de API REST</td>
                            <td>Juan Pérez</td>
                            <td><span class="badge badge-estado-progreso">En progreso</span></td>
                            <td><span class="badge badge-prioridad-alta">Alta</span></td>
                            <td>10/12/2024</td>
                            <td><span class="badge badge-habilitado">Habilitado</span></td>
                            <td>
                                <button class="btn-action btn-view" onclick="verActividad(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" onclick="editarActividad(1)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="eliminarActividad(1)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td class="fw-semibold">Diseño de interfaz</td>
                            <td>María García</td>
                            <td><span class="badge badge-estado-completada">Completada</span></td>
                            <td><span class="badge badge-prioridad-media">Media</span></td>
                            <td>08/12/2024</td>
                            <td><span class="badge badge-habilitado">Habilitado</span></td>
                            <td>
                                <button class="btn-action btn-view" onclick="verActividad(2)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" onclick="editarActividad(2)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="eliminarActividad(2)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td class="fw-semibold">Testing y QA</td>
                            <td>Carlos López</td>
                            <td><span class="badge badge-estado-pendiente">Pendiente</span></td>
                            <td><span class="badge badge-prioridad-alta">Alta</span></td>
                            <td>12/12/2024</td>
                            <td><span class="badge badge-habilitado">Habilitado</span></td>
                            <td>
                                <button class="btn-action btn-view" onclick="verActividad(3)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" onclick="editarActividad(3)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="eliminarActividad(3)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td class="fw-semibold">Documentación técnica</td>
                            <td>Ana Martínez</td>
                            <td><span class="badge badge-estado-progreso">En progreso</span></td>
                            <td><span class="badge badge-prioridad-baja">Baja</span></td>
                            <td>11/12/2024</td>
                            <td><span class="badge badge-inhabilitado">Inhabilitado</span></td>
                            <td>
                                <button class="btn-action btn-view" onclick="verActividad(4)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" onclick="editarActividad(4)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="eliminarActividad(4)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td class="fw-semibold">Deploy a producción</td>
                            <td>Luis Rodríguez</td>
                            <td><span class="badge badge-estado-pendiente">Pendiente</span></td>
                            <td><span class="badge badge-prioridad-alta">Alta</span></td>
                            <td>15/12/2024</td>
                            <td><span class="badge badge-habilitado">Habilitado</span></td>
                            <td>
                                <button class="btn-action btn-view" onclick="verActividad(5)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" onclick="editarActividad(5)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="eliminarActividad(5)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNuevaActividad" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Actividad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevaActividad">
                        <div class="mb-3">
                            <label class="form-label">Actividad</label>
                            <input type="text" class="form-control" name="actividad" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Responsable</label>
                            <input type="text" class="form-control" name="responsable" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado" required>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En progreso">En progreso</option>
                                <option value="Completada">Completada</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prioridad</label>
                            <select class="form-select" name="prioridad" required>
                                <option value="Baja">Baja</option>
                                <option value="Media">Media</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="habilitado" required>
                                <option value="Habilitado">Habilitado</option>
                                <option value="Inhabilitado">Inhabilitado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarActividad()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function verActividad(id) {
            console.log('Ver actividad:', id);
            alert('Ver actividad ID: ' + id);
        }

        function editarActividad(id) {
            console.log('Editar actividad:', id);
            alert('Editar actividad ID: ' + id);
        }

        function eliminarActividad(id) {
            if (confirm('¿Está seguro de eliminar esta actividad?')) {
                console.log('Eliminar actividad:', id);
                alert('Actividad ' + id + ' eliminada');
            }
        }

        function guardarActividad() {
            const form = document.getElementById('formNuevaActividad');
            if (form.checkValidity()) {
                alert('Actividad guardada correctamente');
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaActividad'));
                modal.hide();
                form.reset();
            } else {
                form.reportValidity();
            }
        }
    </script>
</body>
</html>