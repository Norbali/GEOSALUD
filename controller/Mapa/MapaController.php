<?php

class MapaController {

    private $host = "localhost";
    private $dbname = "bd_geosalud";
    private $user = "postgres";
    private $password = "12345"; // ⚠️ CAMBIA ESTO
    
    public function vistaIndex() {
        include_once '../view/Mapa/indexMapa.php';
    }

    public function vistaMapa() {
        include_once '../view/Mapa/visorCaliMapa.php';
    }

    public function registrarZoocriadero() {
        header('Content-Type: text/html; charset=UTF-8');
        
        try {
            $conn = new PDO("pgsql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
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
            
            // Validar
            if (empty($nombre) || empty($direccion) || $x == 0 || $y == 0) {
                echo "❌ Error: Faltan campos obligatorios";
                return;
            }
            
            // Intentar insertar con transformación
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
                echo "✅ Zoocriadero '$nombre' registrado exitosamente";
                
            } catch (PDOException $e) {
                // Intentar sin transformación
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
                echo "✅ Zoocriadero '$nombre' registrado exitosamente (4326)";
            }
            
        } catch (PDOException $e) {
            echo "❌ Error: " . $e->getMessage();
        }
    }
    
    public function consultarZoocriadero() {
        header('Content-Type: application/json; charset=UTF-8');
        
        try {
            $conn = new PDO("pgsql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $x = isset($_GET['x']) ? floatval($_GET['x']) : 0;
            $y = isset($_GET['y']) ? floatval($_GET['y']) : 0;
            
            if ($x == 0 || $y == 0) {
                echo json_encode(array('error' => 'Coordenadas inválidas'));
                return;
            }
            
            $sql = "SELECT * FROM public.zoocriadero 
                    WHERE ST_DWithin(coordenadas, ST_SetSRID(ST_MakePoint(:x, :y), 4326), 0.001)
                    LIMIT 1";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':x', $x);
            $stmt->bindParam(':y', $y);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado) {
                echo json_encode($resultado);
            } else {
                echo json_encode(array('error' => 'No se encontró zoocriadero'));
            }
            
        } catch (PDOException $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
}

?>