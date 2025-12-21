<?php
header('Content-Type: application/json');

require_once dirname(__DIR__) . '../lib/helpers.php';
require_once dirname(__DIR__) . '../model/Mapa/MapaModel.php';

$url = getUrl('Mapa','Mapa','registrarZoocriadero');
// puedes usar helpers aquí sin problema
?>