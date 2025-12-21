<?php
$x = $_GET['x'];
$y = $_GET['y'];
$id_zoocriadero = $_GET['id_zoocriadero'];
$nombre_zoocriadero = $_GET['nombre_zoocriadero'];
$direccion_zoocriadero = $_GET['direccion_zoocriadero'];
$telefono_zoocriadero = $_GET['telefono_zoocriadero'];
$correo_zoocriadero = $_GET['correo_zoocriadero'];
$barrio = $_GET['barrio'];
$comuna = $_GET['comuna'];
$documento_responsable = $_GET['documento_responsable'];

$id_estado_zoocriadero = 1;

$sql = "
INSERT INTO zoocriadero (
    id_zoocriadero,
    nombre_zoocriadero,
    direccion_zoocriadero,
    telefono_zoocriadero,
    correo_zoocriadero,
    barrio,
    comuna,
    documento_responsable,
    id_estado_zoocriadero,
    coordenadas
) VALUES (
    $id_zoocriadero,
    '$nombre_zoocriadero',
    '$direccion_zoocriadero',
    $telefono_zoocriadero,
    '$correo_zoocriadero',
    '$barrio',
    '$comuna',
    '$documento_responsable',
    $id_estado_zoocriadero,
    ST_SetSRID(ST_MakePoint($x, $y), 4326)
)";
//echo $sql;

?>