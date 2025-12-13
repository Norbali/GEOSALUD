<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tanques</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f5f7;
        }

        .page-header {
            background: #fff;
            padding: 1.5rem;
            border-radius: .5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }
    </style>
</head>

<body>

<div class="container-fluid px-4 py-4">

    <!-- HEADER -->
    <div class="page-header">
        <h1 class="h3 mb-0">
            <i class="fas fa-water text-primary"></i> Gestión de Tanques
        </h1>
    </div>

    <!-- CARD LISTA -->
    <div class="card">

        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">
                <i class="fas fa-list text-primary"></i> Lista de Tanques
            </h5>

            <!-- BOTÓN NUEVO TANQUE -->
            <a href="<?php echo getUrl('Tanques','Tanque','getCreate'); ?>" 
               class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Tanque
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo de Tanque</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($tanques as $tanque) { ?>
                            <tr>
                                <td><?php echo $tanque['id_tanque']; ?></td>
                                <td><?php echo $tanque['nombre_tanque']; ?></td>
                                <td><?php echo $tanque['nombre_tipo_tanque']; ?></td>

                                <td>
                                    <?php if ($tanque['id_estado_tanque'] == 1) { ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">

                                        <!-- VER DETALLES -->
                                        <a href="<?php echo getUrl(
                                            'Tanques',
                                            'Tanque',
                                            'getDetails',
                                            array('id_tanque' => $tanque['id_tanque'])
                                        ); ?>" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>

                                        <!-- EDITAR -->
                                        <a href="<?php echo getUrl(
                                            'Tanques',
                                            'Tanque',
                                            'getUpdate',
                                            array('id_tanque' => $tanque['id_tanque'])
                                        ); ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>

                                        <!-- ELIMINAR / ACTIVAR -->
                                        <?php if ($tanque['id_estado_tanque'] == 1) { ?>

                                            <a href="<?php echo getUrl(
                                                'Tanques',
                                                'Tanque',
                                                'getDelete',
                                                array('id_tanque' => $tanque['id_tanque'])
                                            ); ?>" 
                                               class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </a>

                                        <?php } else { ?>

                                            <a href="<?php echo getUrl(
                                                'Tanques',
                                                'Tanque',
                                                'updateStatus',
                                                array('id_tanque' => $tanque['id_tanque'])
                                            ); ?>" 
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Activar
                                            </a>

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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
