<?php
require_once dirname(dirname(dirname(__FILE__))) . '/lib/helpers.php';

$x = $_GET['x'];
$y = $_GET['y'];

$id_zoocriadero = $_GET['id_zoocriadero'];
$nombre = $_GET['nombre_zoocriadero'];

// aquí llamas al modelo o al controlador
echo getUrl('Mapa','Mapa','registrarZoocriadero');
