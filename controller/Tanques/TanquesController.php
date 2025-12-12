<?php

include_once '../model/Tanques/TanquesModel.php';

class TanquesController {
    private $model;
    public function __construct() {
        $this->model = new TanquesModel();
    }

    /* ============================
       FORMULARIO DE CREACIÓN
    ============================ */
    public function getCreate() {

        // Traer tipos de tanque desde la BD
        $sql = "SELECT * FROM tipo_tanque";
        $tipos = $this->model->select($sql);

        include_once "../view/tanques/createRegistroTanques.php";
    }

    /* ============================
       GUARDAR TANQUE
    ============================ */
    public function postCreate() {

        $nombre  = $_POST['nombre_tanque'];
        $alto    = $_POST['medida_alto'];
        $ancho   = $_POST['medida_ancho'];
        $prof    = $_POST['medida_profundidad'];
        $tipo    = $_POST['id_tipo_tanque'];
        $cantidad = $_POST['cantidad_peces'];

        // ID AUTOINCREMENTAL MANUAL (SI LO USAS)
        $id = $this->model->autoIncrement("tanque", "id_tanque");

        // INSERT CORRECTO
        $sql = "INSERT INTO tanque 
                (id_tanque, nombre_tanque, medida_alto, medida_ancho, medida_profundidad, id_tipo_tanque, cantidad_peces, id_estado_tanque)
                VALUES ($id, '$nombre', $alto, $ancho, $prof, $tipo, $cantidad, 1)";

        $ejecutar = $this->model->insert($sql);

        if ($ejecutar) {
            echo "<script>
                    alert('Tanque registrado con éxito');
                    window.location='".getUrl("Tanques","Tanque","list")."';
                  </script>";
        } else {
            echo "<script>alert('Error al registrar el tanque');</script>";
        }
    }

    /* ============================
       LISTAR TANQUES
    ============================ */
    public function list() {

        $sql = "SELECT * FROM tanque";
        $tanques = $this->model->select($sql);

        include_once "../view/tanques/list.php";
    }

    /* ============================
       FORMULARIO DE ACTUALIZACIÓN
    ============================ */
    public function getUpdate() {

        $id = $_GET['id_tanque'];

        // Traer tanque
        $sql = "SELECT * FROM tanque WHERE id_tanque = $id";
        $tanque = $this->model->select($sql);

        // Traer tipos
        $sqlTipos = "SELECT * FROM tipo_tanque";
        $tipos = $this->model->select($sqlTipos);

        include_once "../view/tanques/update.php";
    }

    /* ============================
       ACTUALIZAR TANQUE
    ============================ */
    public function postUpdate() {

        $id      = $_POST['id_tanque'];
        $nombre  = $_POST['nombre_tanque'];
        $alto    = $_POST['medida_alto'];
        $ancho   = $_POST['medida_ancho'];
        $prof    = $_POST['medida_profundidad'];
        $tipo    = $_POST['id_tipo_tanque'];
        $cantidad = $_POST['cantidad_peces'];

        $sql = "UPDATE tanque SET
                nombre_tanque='$nombre',
                medida_alto=$alto,
                medida_ancho=$ancho,
                medida_profundidad=$prof,
                id_tipo_tanque=$tipo,
                cantidad_peces=$cantidad
                WHERE id_tanque=$id";

        $ejecutar = $this->model->update($sql);

        if ($ejecutar) {
            echo "<script>
                    alert('Tanque actualizado con éxito');
                    window.location='".getUrl("Tanques","Tanque","list")."';
                  </script>";
        } else {
            echo "<script>alert('Error al actualizar');</script>";
        }
    }

    /* ============================
       DESACTIVAR TANQUE
    ============================ */
    public function getDelete() {

        $id = $_GET['id_tanque'];

        $sql = "UPDATE tanque SET id_estado_tanque = 2 WHERE id_tanque = $id";
        $ejecutar = $this->model->update($sql);

        if ($ejecutar) {
            echo "<script>
                    alert('Tanque desactivado');
                    window.location='".getUrl("Tanques","Tanque","list")."';
                  </script>";
        } else {
            echo "<script>alert('Error al eliminar');</script>";
        }
    }

    /* ============================
       ACTIVAR TANQUE
    ============================ */
    public function updateStatus() {

        $id = $_GET['id_tanque'];

        $sql = "UPDATE tanque SET id_estado_tanque = 1 WHERE id_tanque = $id";
        $ejecutar = $this->model->update($sql);

        if ($ejecutar) {
            echo "<script>
                    alert('Tanque activado');
                    window.location='".getUrl("Tanques","Tanque","list")."';
                  </script>";
        } else {
            echo "<script>alert('Error al activar');</script>";
        }
    }
}

?>
