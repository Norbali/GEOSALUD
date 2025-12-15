<?php 

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