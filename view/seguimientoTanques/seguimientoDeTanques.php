<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Tanques - Zoocriadero</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .main-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border: none;
        }
        
        .section-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .section-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        
        .btn-secondary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }
        
        .icon-label {
            color: #667eea;
            margin-right: 5px;
        }
        
        textarea.form-control {
            min-height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="card-header-custom">
                <h2 class="mb-0">
                    <i class="fas fa-fish me-2"></i>
                    Seguimiento de Tanques - Zoocriadero
                </h2>
                <p class="mb-0 mt-2">Módulo de registro y control de actividades</p>
            </div>
            
            <!-- Form Body -->
            <div class="card-body p-4">
                <form id="formTanques" method="POST" action="">
                    
                    <!-- Sección: Tipo de Actividad -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-clipboard-list icon-label"></i>
                            Tipo de Actividad
                        </h5>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="tipoActividad" class="form-label">Seleccione el tipo de actividad a registrar</label>
                                <select class="form-select" id="tipoActividad" name="tipo_actividad" required>
                                    <option value="">-- Seleccione una opción --</option>
                                    <option value="registro_tanques">Registro de tanques</option>
                                    <option value="alimentacion">Registro de actividad de Alimentación</option>
                                    <option value="recoleccion">Registro de actividad de Recolección de peces muertos y nacidos</option>
                                    <option value="nivel_agua">Registro de actividad de Nivel de agua</option>
                                    <option value="lavado">Registro de actividad de Lavado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección: Información del Tanque -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-water icon-label"></i>
                            Información del Tanque
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="noTanque" class="form-label">No. Tanque</label>
                                <input type="text" class="form-control" id="noTanque" name="no_tanque" placeholder="Ej: T-001" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipoTanque" class="form-label">Tipo de Tanque</label>
                                <select class="form-select" id="tipoTanque" name="tipo_tanque" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="reproduccion">Reproducción</option>
                                    <option value="alevines">Alevines</option>
                                    <option value="engorde">Engorde</option>
                                    <option value="cuarentena">Cuarentena</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección: Parámetros Fisicoquímicos -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-flask icon-label"></i>
                            Parámetros Fisicoquímicos
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="ph" class="form-label">pH</label>
                                <input type="number" step="0.1" class="form-control" id="ph" name="ph" placeholder="Ej: 7.5" min="0" max="14">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                <input type="number" step="0.1" class="form-control" id="temperatura" name="temperatura" placeholder="Ej: 25.5">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cloro" class="form-label">Cloro (ppm)</label>
                                <input type="number" step="0.01" class="form-control" id="cloro" name="cloro" placeholder="Ej: 0.5">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección: Registro de Población -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-chart-line icon-label"></i>
                            Registro de Población
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="numeroAlevines" class="form-label">Número de Alevines (Nacimientos)</label>
                                <input type="number" class="form-control" id="numeroAlevines" name="numero_alevines" placeholder="0" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="muertesMachos" class="form-label">Número de Muertes - Machos</label>
                                <input type="number" class="form-control" id="muertesMachos" name="muertes_machos" placeholder="0" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="muertesHembras" class="form-label">Número de Muertes - Hembras</label>
                                <input type="number" class="form-control" id="muertesHembras" name="muertes_hembras" placeholder="0" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección: Actividades y Observaciones -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-tasks icon-label"></i>
                            Actividades y Observaciones
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="actividadesRealizadas" class="form-label">Actividades Realizadas</label>
                                <select class="form-select" id="actividadesRealizadas" name="actividades_realizadas" multiple size="4">
                                    <option value="limpieza">Limpieza</option>
                                    <option value="aspirado">Aspirado</option>
                                    <option value="ajuste_agua">Ajuste nivel de agua</option>
                                </select>
                                <small class="text-muted">Mantenga presionada la tecla Ctrl (Cmd en Mac) para seleccionar múltiples opciones</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="4" placeholder="Ingrese observaciones adicionales sobre el estado del tanque, comportamiento de los peces, etc."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección: Responsable -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-user icon-label"></i>
                            Responsable
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nombreResponsable" class="form-label">Nombre del Responsable</label>
                                <input type="text" class="form-control" id="nombreResponsable" name="nombre_responsable" placeholder="Nombre completo del responsable" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de Acción -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary-custom me-2">
                            <i class="fas fa-save me-2"></i>Guardar Registro
                        </button>
                        <button type="reset" class="btn btn-secondary-custom">
                            <i class="fas fa-redo me-2"></i>Limpiar Formulario
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Validación y manejo del formulario
        document.getElementById('formTanques').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Aquí puedes agregar la lógica para enviar los datos al servidor
            const formData = new FormData(this);
            
            // Ejemplo de mensaje de éxito
            alert('Registro guardado exitosamente');
            
            // Para enviar datos al servidor, descomenta y ajusta:
            // fetch('tu_archivo_php.php', {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => {
            //     console.log('Success:', data);
            // })
            // .catch((error) => {
            //     console.error('Error:', error);
            // });
        });
    </script>
</body>
</html>