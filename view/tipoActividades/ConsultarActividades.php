<?php
    session_start();
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        // importante para que no se repita la alerta
        unset($_SESSION['alert']);
    }
    ?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Actividades - KaiAdmin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .btn-morado {
            background-color: #6f63ff;
            color: #fff;
            border: none;
            border-radius: 8px;
        }

        .btn-morado:hover {
            background-color: #5a50e5;
            color: #fff;
        }

        .btn-morado:focus {
            box-shadow: 0 0 0 0.2rem rgba(111, 99, 255, 0.4);
        }

        

        
    </style>
</head>

<body>
    <!-- Alertas de éxito o error -->
    <?php
    session_start();
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        // importante para que no se repita la alerta
        unset($_SESSION['alert']);
    }
    ?>

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

        <!-- CARD y TABLA -->

        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list text-primary"></i> Lista de Actividades
                </h5>

                <div class="d-flex gap-2">
                    
                <!-- ORDENAR -->
                    <button class="btn btn-morado dropdown-toggle"
                        type="button"
                        id="btnOrdenar">
                        <i class="fas fa-sort me-1"></i> Ordenar
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="ordenar('id_asc')">ID Ascendiente</a></li>
                        <li><a class="dropdown-item" href="#" onclick="ordenar('id_desc')">ID Descendiente</a></li>
                        <li><a class="dropdown-item" href="#" onclick="ordenar('nom_asc')">Nombre Ascendiente</a></li>
                        <li><a class="dropdown-item" href="#" onclick="ordenar('nom_desc')">Nombre Descendiente</a></li>
                    </ul>

                    <!-- NUEVA ACTIVIDAD -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                        <i class="fas fa-plus"></i> Nueva Actividad
                    </button>


                </div>
            </div>
  
            <form method="POST" action="<?php echo getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'postConsulta'); ?>">

            </div>
  
            <form method="POST" action="<?php echo getUrl('TipoActividades', 'ConsultarTipoDeActividades', 'postConsulta'); ?>">

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
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
                                                    <button type="button"
                                                        class="btn btn-warning btn-sm"
                                                        onclick="editarActividad(
                                                           <?= $actividad['id_actividad'] ?>,
                                                           '<?= addslashes($actividad['nombre_actividad']) ?>',
                                                          '<?= $actividad['id_estado_actividad'] ?>'
                                                            )">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button"
                                                        class="btn btn-warning btn-sm"
                                                        onclick="actividadInhabilitada()">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                <?php } ?>



                                                <?php if ($actividad['nombre_estado_actividades'] === 'Activo') { ?>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="eliminarActividad(<?= $actividad['id_actividad']; ?>)">
                                                        <i class="fas fa-trash"></i> Inhabilitar
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button"
                                                        class="btn btn-secondary btn-sm"
                                                        disabled>
                                                        <i class="fas fa-ban"></i> Inhabilitada
                                                    </button>
                                                <?php } ?>




                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </form>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <i class="fas fa-times"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary"> <i class="fas fa-save"></i> Guardar</button>


                    </div>

                </form>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR -->
    <div class="modal fade" id="modalEditar" tabindex="-1">
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
                            <label class="form-label">Nombre *</label>
                            <input type="text"
                                class="form-control"
                                id="editNombre"
                                name="nombre_actividad">
                        </div>

                        <!-- ESTADO  -->

                        <!-- ESTADO  -->
                        <div class="mb-3">
                            <label class="form-label">Estado</label>

                            <select class="form-select"
                                id="editEstado"
                                readonly
                                style="pointer-events:none; background:#e9ecef;">
                                <?php foreach ($listaEstados as $estado) { ?>
                                    <option value="<?= $estado['id_estado_actividades'] ?>">
                                        <?= $estado['nombre_estado_actividades'] ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <!-- EL ESTADO QUE SE ENVÍA -->
                            <input type="hidden"
                                name="id_estado_actividad"
                                id="editEstadoHidden">

                            <small class="text-muted">
                                El estado no se puede editar.
                            </small>

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





</div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //OBLIGAR DROPDOWN BOOTSTRAP A FUNCIONAR CON BOTON
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('btnOrdenar');
            const dropdown = new bootstrap.Dropdown(btn);

            btn.addEventListener('click', function() {
                dropdown.toggle();
            });
        });
    </script>

    <script>
        // FUNCION PARA ORDENAR
        function ordenar(tipo) {
            const url = new URL(window.location.href);
            url.searchParams.set('orden', tipo);
            window.location.href = url.toString();
        }
    </script>

    <script>
        //OBLIGAR DROPDOWN BOOTSTRAP A FUNCIONAR CON BOTON
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('btnOrdenar');
            const dropdown = new bootstrap.Dropdown(btn);

            btn.addEventListener('click', function() {
                dropdown.toggle();
            });
        });
    </script>

    <script>
        // FUNCION PARA ORDENAR
        function ordenar(tipo) {
            const url = new URL(window.location.href);
            url.searchParams.set('orden', tipo);
            window.location.href = url.toString();
        }
    </script>

    <script src="assets/js/funcionesModalTipoActividades.js"></script>



</body>

</html>