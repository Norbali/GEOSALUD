<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Video Manual</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            max-width: 900px;
            margin: auto;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%; 
            height: 0;
            overflow: hidden;
            border-radius: 15px;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid px-5 mt-4">

    <h1 class="titulo-video">Video Manual</h1>

    <div class="video-card">
        <div class="video-container">
            <!-- PEGA AQUÍ TU VIDEO -->
            <iframe 
                src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                allowfullscreen>
            </iframe>
        </div>
    </div>

</div>

</body>
</html>
