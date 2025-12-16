<?php
// descargar_pdf.php

// Obtener el tipo de manual solicitado
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Definir la ruta de los PDFs según el tipo
$archivos = array(
    'usuario' => 'C:/ms4w/Apache/htdocs/GEOSALUD/documentos/Manual_Usuario.pdf',
    'sistema' => 'C:/ms4w/Apache/htdocs/GEOSALUD/documentos/Manual_Sistema.pdf',
    'instalacion' => 'C:/ms4w/Apache/htdocs/GEOSALUD/documentos/Manual_Instalacion.pdf'
);

// Definir los nombres de descarga
$nombresDescarga = array(
    'usuario' => 'Manual_de_Usuario_GEOSALUD.pdf',
    'sistema' => 'Manual_del_Sistema_GEOSALUD.pdf',
    'instalacion' => 'Manual_de_Instalacion_GEOSALUD.pdf'
);

// Validar que el tipo exista
if (!isset($archivos[$tipo])) {
    die('Tipo de manual no válido');
}

$archivo = $archivos[$tipo];
$nombreDescarga = $nombresDescarga[$tipo];

// Verificar que el archivo existe
if (!file_exists($archivo)) {
    die('El archivo PDF no existe. Por favor contacte al administrador.');
}

// Configurar headers para la descarga
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $nombreDescarga . '"');
header('Content-Length: ' . filesize($archivo));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Limpiar buffer de salida
ob_clean();
flush();

// Leer y enviar el archivo
readfile($archivo);
exit;
?>
```

## **Estructura de carpetas necesaria:**

Crea esta estructura:
```
GEOSALUD/
├── view/
│   └── videoManual/
│       └── videoManual.php (el código HTML de arriba)
├── documentos/
│   ├── Manual_Usuario.pdf
│   ├── Manual_Sistema.pdf
│   └── Manual_Instalacion.pdf
└── descargar_pdf.php