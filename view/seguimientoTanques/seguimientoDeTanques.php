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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .main-card {
            background: white;
            border-radius: 12px;
            border: 2px solid #7c3aed;
        }


        .card-header-custom {
            background: #7c3aed;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .header-icon {
            font-size: 3rem;
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
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        .button-group {
            text-align: center;
            margin-top: 30px;
        }

        .btn-primary-custom {
            background: #7c3aed;
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
        }

        .btn-secondary-custom {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
        }


        #alerta-sistema {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
            border-left: 6px solid;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .15);
        }


        #alerta-sistema.alert-danger {
            border-left-color: #dc2626;
        }

        #alerta-sistema.alert-success {
            border-left-color: #22c55e;
        }
    </style>
   </head>
        <?php if (!empty($_SESSION['errores_formulario'])) { ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    mostrarAlerta(`<?php echo implode(' | ', $_SESSION['errores_formulario']); ?>`);
                });
            </script>
        <?php unset($_SESSION['errores_formulario']);
        } ?>

        <?php if (!empty($_SESSION['exito'])) { ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    mostrarAlerta('<?php echo $_SESSION['exito']; ?>', 'success');
                });
            </script>
        <?php unset($_SESSION['exito']);
        } ?>


<body>

    <div class="container-fluid">
        <div class="main-card">

            <!-- HEADER -->
            <div class="card-header-custom">
                <i class="fas fa-fish header-icon"></i>
                <h2>Seguimiento de Tanques - Zoocriadero</h2>
                <p>Módulo de registro y control de actividades</p>
            </div>

            <div id="alerta-sistema" class="alert d-none align-items-center">
                <span id="alerta-texto"></span>
            </div>
            <!-- FORMULARIO -->
            <div class="card-body">
                <form method="POST" novalidate action="<?php echo getUrl('SeguimientoDeTanques', 'SeguimientoDeTanques', 'postCreate'); ?>">

                    <!-- TANQUE -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-water"></i> Tanque *
                        </h5>
                        <select class="form-select" name="id_tanque">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($tanques as $t) { ?>
                                <option value="<?php echo $t['id_tanque']; ?>">
                                    <?php echo $t['nombre_tanque']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- ACTIVIDAD -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-clipboard-list"></i> Actividad *
                        </h5>
                        <select class="form-select" name="id_actividad">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($actividades as $a) { ?>
                                <option value="<?php echo $a['id_actividad']; ?>">
                                    <?php echo $a['nombre_actividad']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- PARÁMETROS -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-flask"></i> Parámetros Fisicoquímicos
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label ">pH *</label>
                                <input class="form-control" name="ph" type="number" step="0.1">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label ">Temperatura (°C) *</label>
                                <input class="form-control" name="temperatura" type="number" step="0.1">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label ">Cloro (ppm) *</label>
                                <input class="form-control" name="cloro" type="number" step="0.01">
                            </div>
                        </div>

                    </div>

                    <!-- PECES -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-chart-line"></i> Registro de Peces
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Número de Alevines *</label>
                                <input class="form-control" name="num_alevines" type="number">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label ">Muertes Machos *</label>
                                <input class="form-control" name="num_machos" type="number">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Muertes Hembras *</label>
                                <input class="form-control" name="num_hembras" type="number">
                            </div>
                        </div>

                    </div>

                    <!-- OBSERVACIONES -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <label class="form-label ">Observaciones* </label>
                            <textarea class="form-control" name="observaciones" rows="4"></textarea>

                    </div>

                    <!-- BOTONES -->
                    <div class="button-group">
                       <?php if (in_array('registrar', $permisos['SeguimientoDeTanques'])) { ?>
                        <button type="submit" class="btn btn-primary-custom me-2">
                            <i class="fas fa-save me-2"></i> Guardar
                        </button>
                        <?php } ?>
                        <button type="reset" class="btn btn-secondary-custom">
                            <i class="fas fa-redo me-2"></i> Limpiar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function mostrarAlerta(mensaje, tipo = 'danger') {
            const alerta = document.getElementById('alerta-sistema');
            const texto = document.getElementById('alerta-texto');

            alerta.classList.remove('d-none', 'alert-success', 'alert-danger');
            alerta.classList.add(`alert-${tipo}`);
            texto.textContent = mensaje;

            setTimeout(() => alerta.classList.add('d-none'), 3000);
        }

        document.querySelector('form').addEventListener('submit', function(e) {


            // VALIDAR CAMPOS VACÍOS

            const campos = [
                'id_tanque',
                'id_actividad',
                'ph',
                'temperatura',
                'cloro',
                'num_alevines',
                'num_machos',
                'num_hembras',
                'observaciones'
            ];

            for (let campo of campos) {
                const input = document.querySelector(`[name="${campo}"]`);
                if (!input || !input.value.trim()) {
                    mostrarAlerta('Todos los campos son obligatorios');
                    e.preventDefault();
                    return;
                }
            }


            // OBTENER INPUTS PARA VALIDAR

            const phInput = document.querySelector('[name="ph"]');
            const tempInput = document.querySelector('[name="temperatura"]');
            const cloroInput = document.querySelector('[name="cloro"]');

            const alevinesInput = document.querySelector('[name="num_alevines"]');
            const machosInput = document.querySelector('[name="num_machos"]');
            const hembrasInput = document.querySelector('[name="num_hembras"]');


            // VALIDAR CARACTERES

            const decimal = /^[0-9]+(\.[0-9]+)?$/;
            const decimalNeg = /^-?[0-9]+(\.[0-9]+)?$/;
            const entero = /^[0-9]+$/;

            if (!decimal.test(phInput.value)) {
                mostrarAlerta('El pH solo admite números y decimales');
                e.preventDefault();
                return;
            }

            if (!decimal.test(cloroInput.value)) {
                mostrarAlerta('El cloro solo admite números y decimales');
                e.preventDefault();
                return;
            }

            if (!decimalNeg.test(tempInput.value)) {
                mostrarAlerta('La temperatura solo admite números');
                e.preventDefault();
                return;
            }

            if (
                !entero.test(alevinesInput.value) ||
                !entero.test(machosInput.value) ||
                !entero.test(hembrasInput.value)
            ) {
                mostrarAlerta('Los datos de peces solo admiten números enteros');
                e.preventDefault();
                return;
            }

            const alevines = parseInt(alevinesInput.value);
            const machos = parseInt(machosInput.value);
            const hembras = parseInt(hembrasInput.value);


        });
    </script>

</body>
</html>