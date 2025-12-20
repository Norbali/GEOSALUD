<?php
// Configuración de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=UTF-8');

// CONFIGURACIÓN - AJUSTA ESTOS VALORES
$host = "localhost";
$port = "5432"; // Puerto por defecto de PostgreSQL
$dbname = "bd_geosalud";
$user = "postgres";
$password = "postgres"; // ⚠️ CAMBIA ESTO por tu contraseña

// Log de inicio
file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Inicio de inserción\n", FILE_APPEND);

try {
    // Verificar que PDO esté disponible
    if (!extension_loaded('pdo_pgsql')) {
        throw new Exception("❌ La extensión pdo_pgsql no está habilitada. Edita php.ini y habilita: extension=pdo_pgsql");
    }
    
    // Conectar a PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Conexión exitosa\n", FILE_APPEND);
    
    // Recibir parámetros
    $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
    $direccion = isset($_GET['direccion']) ? trim($_GET['direccion']) : '';
    $telefono = isset($_GET['telefono']) ? trim($_GET['telefono']) : null;
    $correo = isset($_GET['correo']) ? trim($_GET['correo']) : null;
    $barrio = isset($_GET['barrio']) ? trim($_GET['barrio']) : null;
    $comuna = isset($_GET['comuna']) ? trim($_GET['comuna']) : null;
    $documento = isset($_GET['documento']) ? trim($_GET['documento']) : null;
    $estado = isset($_GET['estado']) ? intval($_GET['estado']) : 1;
    $x = isset($_GET['x']) ? floatval($_GET['x']) : 0;
    $y = isset($_GET['y']) ? floatval($_GET['y']) : 0;
    
    file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Datos recibidos: $nombre, X=$x, Y=$y\n", FILE_APPEND);
    
    // Validar campos obligatorios
    if (empty($nombre) || empty($direccion) || $x == 0 || $y == 0) {
        echo "❌ Error: Faltan campos obligatorios (Nombre: '$nombre', Dirección: '$direccion', X: $x, Y: $y)";
        exit;
    }
    
    // Verificar que la tabla existe
    $checkTable = $conn->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_name = 'zoocriadero'");
    if ($checkTable->fetchColumn() == 0) {
        throw new Exception("❌ La tabla 'zoocriadero' no existe en la base de datos. Ejecuta el script SQL de creación.");
    }
    
    // Intentar insertar (probar diferentes variantes de SRID)
    $inserted = false;
    $lastError = '';
    
    // Intento 1: Con transformación de 3116 a 4326
    try {
        $sql = "INSERT INTO public.zoocriadero 
                (nombre_zoocriadero, direccion_zoocriadero, telefono_zoocriadero, 
                 correo_zoocriadero, barrio, comuna, documento_responsable, 
                 id_estado_zoocriadero, coordenadas) 
                VALUES 
                (:nombre, :direccion, :telefono, :correo, :barrio, :comuna, 
                 :documento, :estado, ST_Transform(ST_SetSRID(ST_MakePoint(:x, :y), 3116), 4326))";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':barrio', $barrio);
        $stmt->bindParam(':comuna', $comuna);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':x', $x);
        $stmt->bindParam(':y', $y);
        
        $stmt->execute();
        $inserted = true;
        echo "✅ Zoocriadero '$nombre' registrado exitosamente (SRID 3116→4326)";
        file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Éxito con SRID 3116\n", FILE_APPEND);
        
    } catch (PDOException $e1) {
        $lastError = $e1->getMessage();
        file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Error SRID 3116: $lastError\n", FILE_APPEND);
        
        // Intento 2: Directamente en 4326
        try {
            $sql = "INSERT INTO public.zoocriadero 
                    (nombre_zoocriadero, direccion_zoocriadero, telefono_zoocriadero, 
                     correo_zoocriadero, barrio, comuna, documento_responsable, 
                     id_estado_zoocriadero, coordenadas) 
                    VALUES 
                    (:nombre, :direccion, :telefono, :correo, :barrio, :comuna, 
                     :documento, :estado, ST_SetSRID(ST_MakePoint(:x, :y), 4326))";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':barrio', $barrio);
            $stmt->bindParam(':comuna', $comuna);
            $stmt->bindParam(':documento', $documento);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':x', $x);
            $stmt->bindParam(':y', $y);
            
            $stmt->execute();
            $inserted = true;
            echo "✅ Zoocriadero '$nombre' registrado exitosamente (SRID 4326)";
            file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Éxito con SRID 4326\n", FILE_APPEND);
            
        } catch (PDOException $e2) {
            $lastError = $e2->getMessage();
            file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Error SRID 4326: $lastError\n", FILE_APPEND);
            
            // Intento 3: Sin especificar SRID
            try {
                $sql = "INSERT INTO public.zoocriadero 
                        (nombre_zoocriadero, direccion_zoocriadero, telefono_zoocriadero, 
                         correo_zoocriadero, barrio, comuna, documento_responsable, 
                         id_estado_zoocriadero, coordenadas) 
                        VALUES 
                        (:nombre, :direccion, :telefono, :correo, :barrio, :comuna, 
                         :documento, :estado, ST_MakePoint(:x, :y))";
                
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':direccion', $direccion);
                $stmt->bindParam(':telefono', $telefono);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':barrio', $barrio);
                $stmt->bindParam(':comuna', $comuna);
                $stmt->bindParam(':documento', $documento);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':x', $x);
                $stmt->bindParam(':y', $y);
                
                $stmt->execute();
                $inserted = true;
                echo "✅ Zoocriadero '$nombre' registrado exitosamente (sin SRID)";
                file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - Éxito sin SRID\n", FILE_APPEND);
                
            } catch (PDOException $e3) {
                throw new Exception("❌ Error al insertar en todos los intentos: " . $e3->getMessage());
            }
        }
    }
    
} catch (PDOException $e) {
    $error = "❌ Error de base de datos: " . $e->getMessage();
    echo $error;
    file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - $error\n", FILE_APPEND);
    
    // Dar pistas según el error
    if (strpos($e->getMessage(), 'could not find driver') !== false) {
        echo "\n\n🔧 SOLUCIÓN: Edita php.ini y habilita: extension=pdo_pgsql";
        echo "\n   Luego reinicia Apache.";
    } elseif (strpos($e->getMessage(), 'could not connect') !== false) {
        echo "\n\n🔧 SOLUCIÓN: Verifica que PostgreSQL esté corriendo.";
        echo "\n   Revisa el usuario, contraseña y nombre de base de datos.";
    } elseif (strpos($e->getMessage(), 'does not exist') !== false) {
        echo "\n\n🔧 SOLUCIÓN: La tabla o base de datos no existe.";
        echo "\n   Ejecuta el script SQL de creación.";
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
    echo $error;
    file_put_contents('log_insertar.txt', date('Y-m-d H:i:s') . " - $error\n", FILE_APPEND);
}

$conn = null;
?>