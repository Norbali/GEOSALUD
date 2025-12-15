<?php

include_once "Conexion.php";

$dir1 = $_GET['x']; 
$dir2 = $_GET['y'];  
$sql = "
    SELECT nombre, ST_AsText(geom) AS geom
    FROM puntos
    ORDER BY geom <-> ST_SetSRID(GeometryFromText('POINT($dir1 $dir2)'), 4326)
    LIMIT 1
";

$query = pg_query($link, $sql);

if (!$query) {
    echo "Error en la consulta.";
    exit;
}

$datos = pg_fetch_assoc($query);

if ($datos) {
    echo "Nombre: " . $datos['nombre'] . " | Coordenadas: " . $datos['geom'];
} else {
    echo "No se encontró ningún punto cerca.";
}

?>
