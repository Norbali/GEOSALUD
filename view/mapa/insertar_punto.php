<?php 

// OJO SOLO ES UN EJMPLO DE COMO INSERTAR UN PUNTO EN POSTGIS DESDE PHP, ALGUNAS CISAS DEBEN APLICARLAS DIFERENTE EN EL CONTROLADOR
//esto es un ejemplo de como insertar un punto en postgis desde php, se debe traer las coordenadas x y y por get desde el mapa
// $link es la conexion a la base de datos y basica, en el controlador la utilizan con model

include_once "Conexion.php";

$dir1 = $_GET['x'];
$dir2 = $_GET['y'];
//$id = $_GET['cedu'];

$nombre = $_GET['nombre'];

$sql = "INSERT INTO puntos (nombre, geom) VALUES ('".$nombre."',
ST_SetSRID(GeometryFromText('POINT(".$dir1." ".$dir2.")'), 4326))";
$query = pg_query($link,$sql);
if($query == false){
    echo "<alert> 'No se inserto' </alert>";
}else{
    echo "<alert> 'Si se inserto' </alert>";
}

?>