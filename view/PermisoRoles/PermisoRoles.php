<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Permisos de Roles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .permisos-container {
            background: #f5f7fa;
            padding: 20px;
            min-height: 100vh;
        }

        .permisos-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .permisos-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .permisos-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .permisos-content {
            padding: 30px;
        }

        .section-title {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .role-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-card:hover {
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .role-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .role-card h3 {
            font-size: 1.3em;
            margin-bottom: 8px;
        }

        .role-card p {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 20px;
        }

        .permisos-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .permisos-table thead {
            background: #f8f9fa;
        }

        .permisos-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            border-bottom: 2px solid #e0e0e0;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .permisos-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }

        .permisos-table tbody tr:hover {
            background: #f8f9fa;
        }

        .permisos-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .permiso-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .permiso-badge i {
            font-size: 0.9em;
        }

        .estado-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            display: inline-block;
        }

        .estado-completo {
            background: #d4edda;
            color: #155724;
        }

        .estado-parcial {
            background: #fff3cd;
            color: #856404;
        }

        .estado-sin-acceso {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-message i {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-message h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .permisos-header h1 {
                font-size: 1.5em;
            }

            .permisos-content {
                padding: 20px;
            }

            .roles-grid {
                grid-template-columns: 1fr;
            }

            .permisos-table {
                font-size: 0.9em;
            }

            .permisos-table th,
            .permisos-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="permisos-container">
        <div class="permisos-wrapper">
            <div class="permisos-header">
                <h1>
                    <i class="fas fa-shield-alt"></i>
                    Gestión de Permisos de Roles
                </h1>
                <p>Consulta los permisos y accesos del sistema por rol</p>
            </div>

            <div class="permisos-content">
                <?php if ($mensaje): ?>
                    <div class="alert-message alert-<?php echo $tipo_mensaje; ?>">
                        <i class="fas fa-<?php echo $tipo_mensaje == 'success' ? 'check-circle' : ($tipo_mensaje == 'error' ? 'times-circle' : 'exclamation-triangle'); ?>"></i>
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <h2 class="section-title">Selecciona un Rol</h2>
                
                <div class="roles-grid" id="rolesGrid">
                    <?php 
                    $rolesArray = array();
                    if ($roles && pg_num_rows($roles) > 0): 
                        $first = true;
                        while($rol = pg_fetch_assoc($roles)): 
                            $rolesArray[] = $rol;
                    ?>
                        <div class="role-card <?php echo $first ? 'active' : ''; ?>" 
                             onclick="mostrarPermisos(<?php echo $rol['id_rol']; ?>, '<?php echo htmlspecialchars($rol['nombre_rol'], ENT_QUOTES); ?>', this)">
                            <h3><?php echo htmlspecialchars($rol['nombre_rol']); ?></h3>
                            <p>Ver permisos de este rol</p>
                        </div>
                    <?php 
                            $first = false;
                        endwhile; 
                    else: 
                    ?>
                        <div class="empty-message">
                            <i class="fas fa-users-slash"></i>
                            <h3>No hay roles registrados</h3>
                        </div>
                    <?php endif; ?>
                </div>

                <h2 class="section-title">
                    Permisos de: <span id="rolNombre"><?php echo !empty($rolesArray) ? htmlspecialchars($rolesArray[0]['nombre_rol']) : '...'; ?></span>
                </h2>

                <div class="table-wrapper">
                    <table class="permisos-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-cube"></i> Módulo</th>
                                <th><i class="fas fa-key"></i> Permisos Asignados</th>
                                <th><i class="fas fa-chart-pie"></i> Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 40px; color: #667eea;">
                                    <i class="fas fa-spinner fa-spin"></i> Cargando permisos...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        var rolesData = <?php echo json_encode($rolesArray); ?>;

        var modulosData = <?php 
            $modulosArray = array();
            if ($modulos && pg_num_rows($modulos) > 0) {
                while($mod = pg_fetch_assoc($modulos)) {
                    $modulosArray[] = $mod;
                }
            }
            echo json_encode($modulosArray); 
        ?>;

        var accionesData = <?php 
            $accionesArray = array();
            if ($acciones && pg_num_rows($acciones) > 0) {
                while($acc = pg_fetch_assoc($acciones)) {
                    $accionesArray[] = $acc;
                }
            }
            echo json_encode($accionesArray); 
        ?>;

        var permisosData = <?php 
            $permisosArray = array();
            if ($permisos && pg_num_rows($permisos) > 0) {
                while($perm = pg_fetch_assoc($permisos)) {
                    $permisosArray[] = $perm;
                }
            }
            echo json_encode($permisosArray); 
        ?>;

        window.addEventListener('DOMContentLoaded', function() {
            if (rolesData.length > 0) {
                mostrarPermisos(rolesData[0].id_rol, rolesData[0].nombre_rol);
            }
        });

        function mostrarPermisos(idRol, nombreRol, elemento) {
            if (elemento) {
                var cards = document.querySelectorAll('.role-card');
                for (var i = 0; i < cards.length; i++) {
                    cards[i].classList.remove('active');
                }
                elemento.classList.add('active');
            }

            document.getElementById('rolNombre').textContent = nombreRol;

            var permisosRol = permisosData.filter(function(p) {
                return p.id_rol == idRol;
            });

            var permisosPorModulo = {};
            
            for (var i = 0; i < permisosRol.length; i++) {
                var permiso = permisosRol[i];
                if (!permisosPorModulo[permiso.id_modulo]) {
                    permisosPorModulo[permiso.id_modulo] = {
                        nombre: permiso.nombre_modulo,
                        acciones: []
                    };
                }
                permisosPorModulo[permiso.id_modulo].acciones.push(permiso.nombre_accion);
            }

            var tbody = document.getElementById('tablaBody');
            tbody.innerHTML = '';

            var moduloKeys = Object.keys(permisosPorModulo);
            
            if (moduloKeys.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="empty-message"><i class="fas fa-lock"></i><h3>Este rol no tiene permisos asignados</h3></td></tr>';
                return;
            }

            for (var i = 0; i < moduloKeys.length; i++) {
                var idModulo = moduloKeys[i];
                var modulo = permisosPorModulo[idModulo];
                var totalAcciones = accionesData.length;
                var accionesAsignadas = modulo.acciones.length;

                var estadoBadge = '';
                if (accionesAsignadas === 0) {
                    estadoBadge = '<span class="estado-badge estado-sin-acceso">Sin acceso</span>';
                } else if (accionesAsignadas === totalAcciones) {
                    estadoBadge = '<span class="estado-badge estado-completo">Acceso completo</span>';
                } else {
                    estadoBadge = '<span class="estado-badge estado-parcial">Acceso parcial</span>';
                }

                var permisosHTML = '';
                for (var j = 0; j < modulo.acciones.length; j++) {
                    permisosHTML += '<span class="permiso-badge"><i class="fas fa-check"></i> ' + modulo.acciones[j] + '</span>';
                }

                if (permisosHTML === '') {
                    permisosHTML = '<span style="color: #999;">Sin permisos</span>';
                }

                var fila = '<tr>' +
                    '<td><strong>' + modulo.nombre + '</strong></td>' +
                    '<td><div class="permisos-badges">' + permisosHTML + '</div></td>' +
                    '<td>' + estadoBadge + '</td>' +
                    '</tr>';

                tbody.innerHTML += fila;
            }
        }
    </script>
</body>
</html>