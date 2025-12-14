<?php
session_start();

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

include_once '../model/Tanques/TanquesModel.php';

class TanquesController {

    private $model;

    public function __construct() {
        $this->model = new TanquesModel();
    }

    /* ============================
       LISTAR TANQUES
    ============================ */
    public function getList() {

        $sql = "SELECT 
                    t.id_tanque,
                    t.nombre_tanque,
                    t.medida_alto,
                    t.medida_ancho,
                    t.medida_profundidad,
                    t.cantidad_peces,
                    t.id_estado_tanque,
                    t.id_tipo_tanque,
                    tt.nombre_tipo_tanque
                FROM tanque t
                JOIN tipo_tanque tt ON t.id_tipo_tanque = tt.id_tipo_tanque";

        $tanques = $this->model->select($sql);

        $tipos = $this->model->select("SELECT * FROM tipo_tanque");

        include_once "../view/tanques/listRegistroTanques.php";
    }

    /* ============================
       REGISTRAR
    ============================ */
    public function postCreate() {

        $id = $this->model->autoIncrement("tanque", "id_tanque");

        // Obtener id_zoocriadero (primero intenta de sesión, si no existe lo consulta)
        if (isset($_SESSION['id_zoocriadero']) && !empty($_SESSION['id_zoocriadero'])) {
            $id_zoocriadero = (int)$_SESSION['id_zoocriadero'];
        } else {
            // Consultar el id_zoocriadero del documento_responsable en la tabla zoocriadero
            $sqlZoo = "SELECT id_zoocriadero FROM zoocriadero WHERE documento_responsable = '".$_SESSION['documento']."'";
            $resultZoo = $this->model->select($sqlZoo);
            
            if ($resultZoo && pg_num_rows($resultZoo) > 0) {
                $rowZoo = pg_fetch_assoc($resultZoo);
                $id_zoocriadero = (int)$rowZoo['id_zoocriadero'];
            } else {
                // Si no hay zoocriadero asignado, usar el ID 1 por defecto
                $id_zoocriadero = 1;
            }
        }

        $sql = "INSERT INTO tanque (
                    id_tanque, 
                    nombre_tanque, 
                    medida_alto, 
                    medida_ancho,
                    medida_profundidad, 
                    id_tipo_tanque, 
                    cantidad_peces,
                    id_zoocriadero,
                    id_estado_tanque
                ) VALUES (
                    $id,
                    '".pg_escape_string($_POST['nombre_tanque'])."',
                    ".(int)$_POST['medida_alto'].",
                    ".(int)$_POST['medida_ancho'].",
                    ".(int)$_POST['medida_profundidad'].",
                    ".(int)$_POST['id_tipo_tanque'].",
                    ".(int)$_POST['cantidad_peces'].",
                    $id_zoocriadero,
                    1
                )";

        $this->model->insert($sql);
        
        echo "<script>window.location.href='".getUrl("Tanques","Tanques","getList")."';</script>";
        exit;
    }

    /* ============================
       EDITAR
    ============================ */
    public function postUpdate() {

        $sql = "UPDATE tanque SET
                    nombre_tanque='".pg_escape_string($_POST['nombre_tanque'])."',
                    medida_alto=".(int)$_POST['medida_alto'].",
                    medida_ancho=".(int)$_POST['medida_ancho'].",
                    medida_profundidad=".(int)$_POST['medida_profundidad'].",
                    id_tipo_tanque=".(int)$_POST['id_tipo_tanque'].",
                    cantidad_peces=".(int)$_POST['cantidad_peces']."
                WHERE id_tanque=".(int)$_POST['id_tanque'];

        $this->model->update($sql);
        
        echo "<script>window.location.href='".getUrl("Tanques","Tanques","getList")."';</script>";
        exit;
    }

    /* ============================
       INHABILITAR / ACTIVAR
    ============================ */
    public function updateStatus() {

        $id = (int)$_GET['id_tanque'];
        $estado = (int)$_GET['estado'];

        $sql = "UPDATE tanque SET id_estado_tanque=$estado WHERE id_tanque=$id";
        $this->model->update($sql);

        echo "<script>window.location.href='".getUrl("Tanques","Tanques","getList")."';</script>";
        exit;
    }
}