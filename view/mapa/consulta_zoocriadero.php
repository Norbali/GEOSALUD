<?php
// NO NECESITA INCLUDE DE CONTROLADORES
header('Content-Type: application/json; charset=UTF-8');

$host = "localhost";
$dbname = "bd_geosalud";
$user = "postgres";
$password = "tu_contraseña_aqui"; // ⚠️ CAMBIA ESTO

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $x = isset($_GET['x']) ? floatval($_GET['x']) : 0;
    $y = isset($_GET['y']) ? floatval($_GET['y']) : 0;
    
    if ($x == 0 || $y == 0) {
        echo json_encode(array('error' => 'Coordenadas inválidas'));
        exit;
    }
    
    // Buscar con transformación de coordenadas
    try {
        $sql = "SELECT 
                    id_zoocriadero,
                    nombre_zoocriadero,
                    direccion_zoocriadero,
                    telefono_zoocriadero,
                    correo_zoocriadero,
                    barrio,
                    comuna,
                    documento_responsable,
                    id_estado_zoocriadero,
                    ST_AsText(coordenadas) as coordenadas,
                    ST_Distance(
                        ST_Transform(coordenadas, 3116),
                        ST_Transform(ST_SetSRID(ST_MakePoint(:x, :y), 3116), 4326)
                    ) as distancia
                FROM public.zoocriadero
                WHERE ST_DWithin(
                    ST_Transform(coordenadas, 3116),
                    ST_Transform(ST_SetSRID(ST_MakePoint(:x2, :y2), 3116), 4326),
                    100
                )
                ORDER BY distancia
                LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':x', $x);
        $stmt->bindParam(':y', $y);
        $stmt->bindParam(':x2', $x);
        $stmt->bindParam(':y2', $y);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo json_encode($resultado);
        } else {
            echo json_encode(array('error' => 'No se encontró ningún zoocriadero cerca'));
        }
        
    } catch (PDOException $e) {
        // Intentar sin transformación
        $sql = "SELECT 
                    id_zoocriadero,
                    nombre_zoocriadero,
                    direccion_zoocriadero,
                    telefono_zoocriadero,
                    correo_zoocriadero,
                    barrio,
                    comuna,
                    documento_responsable,
                    id_estado_zoocriadero,
                    ST_AsText(coordenadas) as coordenadas
                FROM public.zoocriadero
                WHERE ST_DWithin(
                    coordenadas,
                    ST_SetSRID(ST_MakePoint(:x, :y), 4326),
                    0.001
                )
                LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':x', $x);
        $stmt->bindParam(':y', $y);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo json_encode($resultado);
        } else {
            echo json_encode(array('error' => 'No se encontró ningún zoocriadero'));
        }
    }
    
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Error: ' . $e->getMessage()));
}

$conn = null;
?>