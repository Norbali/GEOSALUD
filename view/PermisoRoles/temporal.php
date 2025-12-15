
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permisos de Roles</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }
        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .roles-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        .role-card {
            flex: 1;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .role-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        .role-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .role-card h3 {
            font-size: 1.2em;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background: #f5f5f5;
            font-weight: 600;
            color: #667eea;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .permiso-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 5px 10px 5px 0;
        }
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            margin-top: 20px;
            margin-left: 10px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Gestión de Permisos de Roles</h1>
            <p>Administra los permisos y accesos del sistema</p>
        </div>
        
        <div class="content">
            <h2>Selecciona un Rol</h2>
            <div class="roles-selector">
                <div class="role-card active" onclick="cambiarRol(1, this)">
                    <h3>Administrador</h3>
                    <p>Acceso total al sistema</p>
                </div>
                <div class="role-card" onclick="cambiarRol(2, this)">
                    <h3>Editor</h3>
                    <p>Puede crear y editar contenido</p>
                </div>
                <div class="role-card" onclick="cambiarRol(3, this)">
                    <h3>Usuario</h3>
                    <p>Acceso básico de lectura</p>
                </div>
            </div>

            <h2>Permisos de: <span id="rolActual">Administrador</span></h2>
            
            <table id="tablaPermisos">
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th>Permisos</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTabla">
                </tbody>
            </table>

            <div>
                <button class="btn btn-secondary" onclick="resetear()">Cancelar</button>
                <button class="btn btn-primary" onclick="guardar()">Guardar Cambios</button>
            </div>
        </div>
    </div>

    <script>