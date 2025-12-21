<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles y Permisos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .tabs-header {
            background: white;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
        }

        .tab-button {
            flex: 1;
            padding: 20px;
            background: white;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .tab-button:hover {
            background: #f8f9fa;
            color: #667eea;
        }

        .tab-button.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: #f8f9ff;
        }

        .tab-content {
            display: none;
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .tab-content.active {
            display: block;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 5px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Tabla de permisos */
        .permissions-section {
            margin-top: 30px;
        }

        .section-title {
            font-size: 1.1em;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-bottom: 30px;
        }

        .permissions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            min-width: 1200px;
        }

        .permissions-table thead {
            background: #f8f9fa;
        }

        .permissions-table th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e0e0e0;
            font-size: 0.85em;
        }

        .permissions-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        .permissions-table tbody tr:hover {
            background: #fafbfc;
        }

        .checkbox-cell {
            text-align: center;
        }

        .checkbox-cell input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .action-label {
            font-weight: 500;
            color: #555;
        }

        .module-header {
            text-align: center;
            font-weight: 600;
            color: #667eea;
            background: #f8f9ff;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .permisos-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .permisos-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
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

        .permisos-table {
            width: 100%;
            border-collapse: collapse;
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
        }

        .permisos-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
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

        .estado-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
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

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .tabs-header {
                flex-direction: column;
            }

            .tab-button {
                border-bottom: 1px solid #e0e0e0;
                border-left: 3px solid transparent;
            }

            .tab-button.active {
                border-bottom-color: #e0e0e0;
                border-left-color: #667eea;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="tabs-header">
            <button class="tab-button active" onclick="switchTab('registro')">
                <i class="fas fa-user-plus"></i>
                Registro de Roles
            </button>
            <button class="tab-button" onclick="switchTab('consulta')">
                <i class="fas fa-shield-alt"></i>
                Consultar Permisos
            </button>
        </div>

        <div class="form-header">
            <h1>Registro Roles</h1>
        </div>

        <form id="roleForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre_rol"
                        placeholder="Auxiliar"
                        required
                        maxlength="50"
                        pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                        title="Solo se permiten letras y espacios (sin números ni caracteres especiales)"
                        oninput="validarSoloLetras(this)"
                        onpaste="return false">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" id="descripcion" placeholder="Ingrese la descripción">
                </div>
            </div>

            <div class="permissions-section">
                <div class="section-title">Acción/Módulo</div>
                <div class="table-wrapper">
                    <table class="permissions-table">
                        <thead>
                            <tr>
                                <th style="width: 200px;">Acción</th>
                                <th class="module-header">Mapa</th>
                                <th class="module-header">Tanques</th>
                                <th class="module-header">Seguimiento de tanques</th>
                                <th class="module-header">Tipos de Actividades</th>
                                <th class="module-header">Tipos de Tanques</th>
                                <th class="module-header">Permisos roles</th>
                                <th class="module-header">Registrar usuarios</th>
                                <th class="module-header">Reportes</th>
                                <th class="module-header">Ajustes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="action-label">Registrar</td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="mapa-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tanques-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="seguimiento-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="actividades-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tipos-tanques-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="permisos-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="usuarios-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="reportes-registrar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="ajustes-registrar">
                                </td>
                            </tr>
                            <tr>
                                <td class="action-label">Consultar</td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="mapa-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tanques-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="seguimiento-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="actividades-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tipos-tanques-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="permisos-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="usuarios-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="reportes-consultar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="ajustes-consultar">
                                </td>
                            </tr>
                            <tr>
                                <td class="action-label">Editar</td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="mapa-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tanques-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="seguimiento-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="actividades-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tipos-tanques-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="permisos-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="usuarios-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="reportes-editar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="ajustes-editar">
                                </td>
                            </tr>
                            <tr>
                                <td class="action-label">Eliminar</td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="mapa-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tanques-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="seguimiento-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="actividades-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="tipos-tanques-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="permisos-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="usuarios-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="reportes-eliminar">
                                </td>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="permisos" value="ajustes-eliminar">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                Registrar
            </button>
        </form>
    </div>

    <div id="consulta" class="tab-content">
        <div class="permisos-header">
            <h1>
                <i class="fas fa-shield-alt"></i>
                Gestión de Permisos de Roles
            </h1>
            <p>Consulta los permisos y accesos del sistema por rol</p>
        </div>

        <div id="alertContainer"></div>

        <h2 class="section-title">Selecciona un Rol</h2>
        <div class="roles-grid" id="rolesGrid"></div>

        <h2 class="section-title">
            Permisos de: <span id="rolNombre">...</span>
        </h2>

        <table class="permisos-table">
            <thead>
                <tr>
                    <th><i class="fas fa-cube"></i> Módulo</th>
                    <th><i class="fas fa-key"></i> Permisos Asignados</th>
                    <th><i class="fas fa-chart-pie"></i> Estado</th>
                </tr>
            </thead>
            <tbody id="permisosTableBody">
                <tr>
                    <td colspan="3" style="text-align: center; padding: 40px; color: #999;">
                        Selecciona un rol para ver sus permisos
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>

    <script>
        // Validar que solo se escriban letras
        function validarSoloLetras(input) {
            // Remover números y caracteres especiales en tiempo real
            let valor = input.value;

            // Eliminar números
            valor = valor.replace(/[0-9]/g, '');

            valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');

            valor = valor.replace(/\s+/g, ' ');

            input.value = valor;
        }


        let roles = [{
                id: 1,
                nombre: 'Administrador',
                descripcion: 'Acceso total al sistema'
            },
            {
                id: 2,
                nombre: 'Auxiliar',
                descripcion: 'Acceso limitado'
            }
        ];

        let permisos = {
            1: { // Administrador
                'Mapa': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Tanques': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Seguimiento de tanques': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Tipos de Actividades': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Tipos de Tanques': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Permisos roles': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Registrar usuarios': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Reportes': ['Registrar', 'Consultar', 'Editar', 'Eliminar'],
                'Ajustes': ['Registrar', 'Consultar', 'Editar', 'Eliminar']
            },
            2: { // Editor
                'Mapa': ['Consultar', 'Editar'],
                'Tanques': ['Registrar', 'Consultar', 'Editar'],
                'Seguimiento de tanques': ['Consultar', 'Editar'],
                'Tipos de Actividades': ['Consultar'],
                'Tipos de Tanques': ['Consultar'],
                'Permisos roles': ['Consultar'],
                'Registrar usuarios': ['Consultar'],
                'Reportes': ['Consultar'],
                'Ajustes': ['Consultar']
            },
            3: { // Auxiliar
                'Mapa': ['Consultar'],
                'Tanques': ['Consultar'],
                'Seguimiento de tanques': ['Consultar'],
                'Tipos de Actividades': ['Consultar'],
                'Tipos de Tanques': ['Consultar'],
                'Permisos roles': [],
                'Registrar usuarios': [],
                'Reportes': ['Consultar'],
                'Ajustes': []
            }
        };

        const totalAcciones = 4;

        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            event.target.closest('.tab-button').classList.add('active');
            document.getElementById(tabName).classList.add('active');

            if (tabName === 'consulta') {
                cargarRoles();
            }
        }

        document.getElementById('roleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value;
            const checkboxes = document.querySelectorAll('input[name="permisos"]:checked');

            if (nombre.length < 3) {
                alert('El nombre del rol debe tener al menos 3 caracteres');
                return;
            }

            if (/[0-9]/.test(nombre)) {
                alert('El nombre del rol no puede contener números');
                return;
            }

            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/.test(nombre)) {
                alert('El nombre del rol solo puede contener letras y espacios');
                return;
            }

            const permisosSeleccionados = Array.from(checkboxes).map(cb => cb.value);

            const nuevoId = roles.length + 1;
            roles.push({
                id: nuevoId,
                nombre: nombre,
                descripcion: descripcion
            });

            permisos[nuevoId] = {};
            permisosSeleccionados.forEach(permiso => {
                const [modulo, accion] = permiso.split('-');
                const moduloNombre = modulo.charAt(0).toUpperCase() + modulo.slice(1);
                const accionNombre = accion.charAt(0).toUpperCase() + accion.slice(1);

                if (!permisos[nuevoId][moduloNombre]) {
                    permisos[nuevoId][moduloNombre] = [];
                }
                permisos[nuevoId][moduloNombre].push(accionNombre);
            });

            mostrarAlerta('Rol registrado exitosamente', 'success');

            // Limpiar formulario
            this.reset();

            // Cambiar a pestaña de consulta
            setTimeout(() => {
                document.querySelectorAll('.tab-button')[1].click();
            }, 1500);
        });

        // Cargar roles en la vista de consulta
        function cargarRoles() {
            const grid = document.getElementById('rolesGrid');
            grid.innerHTML = '';

            roles.forEach((rol, index) => {
                const card = document.createElement('div');
                card.className = 'role-card' + (index === 0 ? ' active' : '');
                card.innerHTML = `
                    <h3>${rol.nombre}</h3>
                    <p>${rol.descripcion}</p>
                `;
                card.onclick = () => mostrarPermisos(rol.id, rol.nombre, card);
                grid.appendChild(card);
            });

            if (roles.length > 0) {
                mostrarPermisos(roles[0].id, roles[0].nombre);
            }
        }

        // Mostrar permisos de un rol
        function mostrarPermisos(idRol, nombreRol, elemento) {
            if (elemento) {
                document.querySelectorAll('.role-card').forEach(card => {
                    card.classList.remove('active');
                });
                elemento.classList.add('active');
            }

            document.getElementById('rolNombre').textContent = nombreRol;

            const tbody = document.getElementById('permisosTableBody');
            tbody.innerHTML = '';

            const permisosRol = permisos[idRol];

            if (!permisosRol || Object.keys(permisosRol).length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-lock" style="font-size: 2em; margin-bottom: 10px;"></i>
                            <div>Este rol no tiene permisos asignados</div>
                        </td>
                    </tr>
                `;
                return;
            }

            for (const [modulo, acciones] of Object.entries(permisosRol)) {
                const numAcciones = acciones.length;
                let estadoBadge = '';

                if (numAcciones === 0) {
                    estadoBadge = '<span class="estado-badge estado-sin-acceso">Sin acceso</span>';
                } else if (numAcciones === totalAcciones) {
                    estadoBadge = '<span class="estado-badge estado-completo">Acceso completo</span>';
                } else {
                    estadoBadge = '<span class="estado-badge estado-parcial">Acceso parcial</span>';
                }

                const permisosHTML = acciones.map(accion =>
                    `<span class="permiso-badge"><i class="fas fa-check"></i> ${accion}</span>`
                ).join('');

                const row = `
                    <tr>
                        <td><strong>${modulo}</strong></td>
                        <td><div class="permisos-badges">${permisosHTML}</div></td>
                        <td>${estadoBadge}</td>
                    </tr>
                `;

                tbody.innerHTML += row;
            }
        }

        // Mostrar alerta
        function mostrarAlerta(mensaje, tipo) {
            const container = document.getElementById('alertContainer');
            const icon = tipo === 'success' ? 'check-circle' : 'exclamation-triangle';

            container.innerHTML = `
                <div class="alert-message alert-${tipo}">
                    <i class="fas fa-${icon}"></i>
                    ${mensaje}
                </div>
            `;

            setTimeout(() => {
                container.innerHTML = '';
            }, 3000);
        }
    </script>
</body>

</html>