<div style="position: relative; top: -70px;">
    <style>
        body {
            min-height: 100vh;
        }

        .main-title {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            color: #1f2937;
        }

        /* TARJETAS DE MANUALES */
        .manuales-container {
            max-width: 1200px;
            margin: 0 auto 50px;
        }

        .manual-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }

        .manual-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .manual-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .manual-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .manual-description {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 25px;
            min-height: 60px;
        }

        .btn-download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-download:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-download i {
            margin-right: 8px;
        }

        /* VIDEO SECTION */
        .video-section {
            max-width: 1200px;
            margin: 0 auto;
        }

        .titulo-video {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            color: #1f2937;
        }

        .video-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%; 
            height: 0;
            overflow: hidden;
            border-radius: 15px;
            background: #000;
        }

        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            object-fit: contain;
        }

        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            margin: 60px auto;
            max-width: 800px;
        }
    </style>
<div class="container-fluid px-4">

    <h1 class="main-title">Documentación del Sistema</h1>

    <!-- SECCIÓN DE MANUALES -->
    <div class="manuales-container px-3">
        <div class="row g-4">
            
            <!-- Manual de Usuario -->
            <div class="col-lg-4 col-md-6">
                <div class="manual-card">
                    <div class="manual-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3 class="manual-title">Manual de<br> Usuario</h3>
                    <a href="/view/documentos/ManualDeUsuario.pdf" class="btn btn-download" download="ManualDeUsuario.pdf" target="_blank">
                        <i class="fas fa-download"></i>
                        Descargar PDF
                    </a>
                </div>
            </div>

            <!-- Manual del Sistema -->
            <div class="col-lg-4 col-md-6">
                <div class="manual-card">
                    <div class="manual-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="manual-title">Manual de<br>Sistema</h3>
                    <a href="/view/documentos/ManualDelSistema.pdf" class="btn btn-download" download="ManualDelSistema.pdf" target="_blank">
                        <i class="fas fa-download"></i>
                        Descargar PDF
                    </a>
                </div>
            </div>

            <!-- Manual de Instalación -->
            <div class="col-lg-4 col-md-6">
                <div class="manual-card">
                    <div class="manual-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h3 class="manual-title">Manual de Instalación</h3>
                    <a href="/view/documentos/ManualDeInstalación.pdf" class="btn btn-download" download="ManualDeInstalación.pdf" target="_blank">
                        <i class="fas fa-download"></i>
                        Descargar PDF
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- DIVISOR -->
    <div class="divider"></div>

    <!-- SECCIÓN DE VIDEO -->
    <div class="video-section px-3">
        <h2 class="titulo-video">Video Manual</h2>

        <div class="video-card">
            <div class="video-container">
                <video controls controlsList="nodownload">
                    <source src="view/documentos/VideoManualGEOSALUD.mp4" type="video/mp4">
                    Tu navegador no soporta la reproducción de video.
                </video>
            </div>
        </div>
    </div>
    </div>
</div>