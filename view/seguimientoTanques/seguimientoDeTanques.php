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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #ffffff;
            min-height: 100vh;
            padding: 0;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .main-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 0 2px #7c3aed;
            margin-bottom: 0;
            border: 2px solid #7c3aed;
        }
        
        .card-header-custom {
            background: #7c3aed;
            color: white;
            padding: 30px 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .header-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .header-icon {
            font-size: 3rem;
            flex-shrink: 0;
        }
        
        .header-text h2 {
            font-size: 2rem;
            margin: 0 0 5px 0;
            font-weight: 700;
        }
        
        .header-text p {
            font-size: 1rem;
            margin: 0;
            opacity: 0.95;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .section-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid #7c3aed;
        }
        
        .section-title {
            color: #7c3aed;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.15rem;
        }
        
        .icon-label {
            color: #7c3aed;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
            outline: none;
        }
        
        textarea.form-control {
            min-height: 100px;
        }
        
        .btn-primary-custom {
            background: #7c3aed;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            color: white;
            font-size: 0.95rem;
        }
        
        .btn-primary-custom:hover {
            background: #6d28d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(124, 58, 237, 0.4);
        }
        
        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            color: white;
            font-size: 0.95rem;
        }
        
        .btn-secondary-custom:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }
        
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .card-header-custom {
                padding: 20px 15px;
            }
            
            .header-icon {
                font-size: 2.5rem;
            }
            
            .header-text h2 {
                font-size: 1.5rem;
            }
            
            .header-text p {
                font-size: 0.9rem;
            }
            
            .section-card {
                padding: 20px 15px;
            }
            
            .section-title {
                font-size: 1rem;
            }
            
            .button-group {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                padding: 12px 20px;
            }
        }
        
        @media (max-width: 576px) {
            .header-icon {
                font-size: 2rem;
            }
            
            .header-text h2 {
                font-size: 1.3rem;
            }
            
            .form-label {
                font-size: 0.9rem;
            }
            
            .form-control, .form-select {
                font-size: 0.9rem;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-card">
            <!-- Header -->
            <div class="card-header-custom">
                <div class="header-content">
                    <i class="fas fa-fish header-icon"></i>
                    <div class="header-text">
                        <h2>Seguimiento de Tanques - Zoocriadero</h2>
                        <p>Módulo de registro y control de actividades</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Body -->
            <div class="card-body">
<form method="POST" action="<?php echo getUrl('SeguimientoDeTanques','SeguimientoDeTanques','postCreate'); ?>">

    <!-- Información del Tanque -->
    <div class="section-card">
        <h5 class="section-title">
            <i class="fas fa-water icon-label"></i>
            Información del Tanque
        </h5>

        <div class="mb-3">
            <label class="form-label">Tanque</label>
           <select class="form-select" name="id_tanque" required>
    <option value="">-- Seleccione --</option>
    <?php foreach ($tanques as $t) { ?>
        <option value="<?php echo $t['id_tanque']; ?>">
            <?php echo $t['nombre_tanque']; ?>
        </option>
    <?php } ?>
</select>

        </div>
    </div>

    <!-- Tipo de Actividad -->
    <div class="section-card">
        <h5 class="section-title">
            <i class="fas fa-clipboard-list icon-label"></i>
            Tipo de Actividad
        </h5>

        <div class="mb-3">
            <label class="form-label">Actividad</label>
           <select class="form-select" name="id_actividad" required>
    <option value="">-- Seleccione --</option>
    <?php foreach ($actividades as $a) { ?>
        <option value="<?php echo $a['id_actividad']; ?>">
            <?php echo $a['nombre_actividad']; ?>
        </option>
    <?php } ?>
</select>

        </div>
    </div>

    <!-- Parámetros Fisicoquímicos -->
    <div class="section-card">
        <h5 class="section-title">
            <i class="fas fa-flask icon-label"></i>
            Parámetros Fisicoquímicos
        </h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">pH</label>
                <input type="number" step="0.1" class="form-control" name="ph">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Temperatura (°C)</label>
                <input type="number" step="0.1" class="form-control" name="temperatura">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Cloro (ppm)</label>
                <input type="number" step="0.01" class="form-control" name="cloro">
            </div>
        </div>
    </div>

    <!-- Registro de Población -->
    <div class="section-card">
        <h5 class="section-title">
            <i class="fas fa-chart-line icon-label"></i>
            Registro de Población
        </h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Alevines</label>
                <input type="number" class="form-control" name="num_alevines" value="0">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Muertes Machos</label>
                <input type="number" class="form-control" name="num_machos" value="0">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Muertes Hembras</label>
                <input type="number" class="form-control" name="num_hembras" value="0">
            </div>
        </div>
    </div>

    <!-- Observaciones -->
    <div class="section-card">
        <h5 class="section-title">
            <i class="fas fa-tasks icon-label"></i>
            Observaciones
        </h5>

        <textarea class="form-control" name="observaciones" rows="4"></textarea>
    </div>

    <div class="section-card">
    <h5 class="section-title">
        <i class="fas fa-user icon-label"></i>
        Responsable
    </h5>

    <div class="mb-3">
        <label class="form-label">Responsable</label>
        <input type="text"
               class="form-control"
               value="<?php echo $nombreResponsable; ?>"
               readonly>
    </div>
</div>



    <!-- Botones -->
    <div class="button-group">
        <button type="submit" class="btn btn-primary-custom me-2">
            <i class="fas fa-save me-2"></i>Guardar Registro
        </button>
        <button type="reset" class="btn btn-secondary-custom">
            <i class="fas fa-redo me-2"></i>Limpiar
        </button>
    </div>

</form>

            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    
</body>
</html>