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

    $sql = "SELECT * FROM tipo_tanque";
    $tipos = $this->model->select($sql);

    include_once "../view/tanques/createRegistroTanques.php";
}

    /* ============================
       GUARDAR TANQUE
    ============================ */
    public function postCreate() {

    $nombre   = $_POST['nombre_tanque'];
    $alto     = $_POST['medida_alto'];
    $ancho    = $_POST['medida_ancho'];
    $prof     = $_POST['medida_profundidad'];
    $tipo     = $_POST['id_tipo_tanque'];
    $cantidad = $_POST['cantidad_peces'];

    // Usuario responsable (siempre debe existir)
    $user = isset($_SESSION['usuario']->documento)? $_SESSION['usuario']->documento : 1;

    // ID autoincremental
    $id = $this->model->autoIncrement("tanque", "id_tanque");

    $sql = "INSERT INTO tanque
            (id_tanque, nombre_tanque, medida_alto, medida_ancho, medida_profundidad, 
             id_tipo_tanque, cantidad_peces, id_estado_tanque, id_user_responsable)
            VALUES 
            ($id, '$nombre', $alto, $ancho, $prof, $tipo, $cantidad, 1, $user)";

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
    public function getList() {

    $sql = "SELECT t.*, 
                   tt.nombre_tipo_tanque,
                   e.nombre_estado_tanques
            FROM tanque t
            INNER JOIN tipo_tanque tt 
                ON t.id_tipo_tanque = tt.id_tipo_tanque
            INNER JOIN estado_tanques e
                ON t.id_estado_tanque = e.id_estado_tanques";

    $tanques = $this->model->select($sql);

    include_once "../view/tanques/listRegistroTanques.php";
}


    /* ============================
      DETALLES DEL TANQUE
============================ */
    public function getDetails() {

        $id = $_GET['id_tanque'];

    // Consulta con todos los JOIN necesarios
        $sql = "
        SELECT t.*, 
               tt.nombre_tipo_tanque,
               et.nombre_estado_tanques AS estado_nombre,
               u.documento AS user_doc,
               CONCAT(u.nombre, ' ', u.apellido) AS user_nombre,
               r.nombre_rol AS user_rol
        FROM tanque t
        LEFT JOIN tipo_tanque tt ON t.id_tipo_tanque = tt.id_tipo_tanque
        LEFT JOIN estado_tanques et ON t.id_estado_tanque = et.id_estado_tanques
        LEFT JOIN usuarios u ON t.id_user_responsable = u.documento
        LEFT JOIN rol r ON u.id_rol = r.id_rol
        WHERE t.id_tanque = $id
        LIMIT 1
    ";

    $detalle = $this->model->select($sql);

    include_once "../view/tanques/details.php";
}

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

    $id       = $_POST['id_tanque'];
    $nombre   = $_POST['nombre_tanque'];
    $alto     = $_POST['medida_alto'];
    $ancho    = $_POST['medida_ancho'];
    $prof     = $_POST['medida_profundidad'];
    $tipo     = $_POST['id_tipo_tanque'];
    $cantidad = $_POST['cantidad_peces'];

    // usuario responsable actualizado
    $user = isset($_SESSION['usuario']->documento) ? $_SESSION['usuario']->documento : 1;

    $sql = "UPDATE tanque SET
            nombre_tanque='$nombre',
            medida_alto=$alto,
            medida_ancho=$ancho,
            medida_profundidad=$prof,
            id_tipo_tanque=$tipo,
            cantidad_peces=$cantidad,
            id_user_responsable=$user
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

    $sql = "UPDATE tanque 
            SET id_estado_tanque = 2 
            WHERE id_tanque = $id";

    $ejecutar = $this->model->update($sql);

    if ($ejecutar) {
        echo "<script>
                alert('Tanque desactivado correctamente');
                window.location='".getUrl("Tanques","Tanque","list")."';
              </script>";
    } else {
        echo "<script>alert('Error al desactivar');</script>";
    }
}


    /* ============================
       ACTIVAR TANQUE
    ============================ */
    public function updateStatus() {

    $id = $_GET['id_tanque'];

    $sql = "UPDATE tanque 
            SET id_estado_tanque = 1 
            WHERE id_tanque = $id";

    $ejecutar = $this->model->update($sql);

    if ($ejecutar) {
        echo "<script>
                alert('Tanque activado correctamente');
                window.location='".getUrl("Tanques","Tanque","list")."';
              </script>";
    } else {
        echo "<script>alert('Error al activar');</script>";
    }
}

}

?>
