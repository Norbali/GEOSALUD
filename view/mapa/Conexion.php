<?php

$server = "localhost";
$port = "5432";
$database = "prueba";
$user = "postgres";
$password = "12345";

$link = pg_connect("host={$server} port={$port} dbname={$database} user={$user} password={$password}");

if ($link === false) {
    die("ERROR: No se pudo conectar. " . pg_last_error());
} else {
}
?>