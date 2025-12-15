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

        $nombre = isset($_POST['nombre_tanque']) ? trim($_POST['nombre_tanque']) : '';
        $alto = isset($_POST['medida_alto']) ? trim($_POST['medida_alto']) : '';
        $ancho = isset($_POST['medida_ancho']) ? trim($_POST['medida_ancho']) : '';
        $profundidad = isset($_POST['medida_profundidad']) ? trim($_POST['medida_profundidad']) : '';
        $tipo = isset($_POST['id_tipo_tanque']) ? trim($_POST['id_tipo_tanque']) : '';
        $cantidad = isset($_POST['cantidad_peces']) ? trim($_POST['cantidad_peces']) : '';

        // VALIDAR CAMPOS OBLIGATORIOS
        if (!$this->camposObligatorios($nombre, $alto, $ancho, $profundidad, $tipo, $cantidad)) {
            $this->alerta(
                'danger',
                'Debe completar todos los campos antes de guardar el tanque'
            );
            return;
        }

        // VALIDAR QUE LAS MEDIDAS SEAN NÚMEROS POSITIVOS
        if (!$this->validarNumeroPositivo($alto) || !$this->validarNumeroPositivo($ancho) || 
            !$this->validarNumeroPositivo($profundidad) || !$this->validarNumeroPositivo($cantidad)) {
            $this->alerta(
                'danger',
                'Las medidas y cantidad de peces deben ser números positivos'
            );
            return;
        }

        // VALIDAR QUE EL NOMBRE SOLO CONTENGA TEXTO Y NÚMEROS
        if (!$this->validarTexto($nombre)) {
            $this->alerta(
                'danger',
                'El nombre del tanque contiene caracteres no permitidos'
            );
            return;
        }

        // VALIDAR DUPLICADOS
        if ($this->existeTanque($nombre)) {
            $this->alerta(
                'danger',
                'Ya existe un tanque con este nombre, por favor ingresa un nombre diferente'
            );
            return;
        }

        // OBTENER ID_ZOOCRIADERO
        if (isset($_SESSION['id_zoocriadero']) && !empty($_SESSION['id_zoocriadero'])) {
            $id_zoocriadero = (int)$_SESSION['id_zoocriadero'];
        } else {
            $sqlZoo = "SELECT id_zoocriadero FROM zoocriadero WHERE documento_responsable = '".$_SESSION['documento']."'";
            $resultZoo = $this->model->select($sqlZoo);
            
            if ($resultZoo && pg_num_rows($resultZoo) > 0) {
                $rowZoo = pg_fetch_assoc($resultZoo);
                $id_zoocriadero = (int)$rowZoo['id_zoocriadero'];
            } else {
                $id_zoocriadero = 1;
            }
        }

        $id = $this->model->autoIncrement("tanque", "id_tanque");

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
                    '".pg_escape_string($nombre)."',
                    ".(float)$alto.",
                    ".(float)$ancho.",
                    ".(float)$profundidad.",
                    ".(int)$tipo.",
                    ".(int)$cantidad.",
                    $id_zoocriadero,
                    1
                )";

        if ($this->model->insert($sql)) {
            $this->alerta(
                'success',
                'Tanque registrado correctamente'
            );
        } else {
            $this->alerta(
                'danger',
                'No se pudo registrar el tanque'
            );
        }
    }

    /* ============================
       EDITAR
    ============================ */
    public function postUpdate() {

        $id = isset($_POST['id_tanque']) ? trim($_POST['id_tanque']) : '';
        $nombre = isset($_POST['nombre_tanque']) ? trim($_POST['nombre_tanque']) : '';
        $alto = isset($_POST['medida_alto']) ? trim($_POST['medida_alto']) : '';
        $ancho = isset($_POST['medida_ancho']) ? trim($_POST['medida_ancho']) : '';
        $profundidad = isset($_POST['medida_profundidad']) ? trim($_POST['medida_profundidad']) : '';
        $tipo = isset($_POST['id_tipo_tanque']) ? trim($_POST['id_tipo_tanque']) : '';
        $cantidad = isset($_POST['cantidad_peces']) ? trim($_POST['cantidad_peces']) : '';

        // VALIDAR CAMPOS OBLIGATORIOS
        if ($id === '' || !$this->camposObligatorios($nombre, $alto, $ancho, $profundidad, $tipo, $cantidad)) {
            $this->alerta(
                'danger',
                'Debe completar todos los campos antes de guardar los cambios'
            );
            return;
        }

        // VALIDAR QUE LAS MEDIDAS SEAN NÚMEROS POSITIVOS
        if (!$this->validarNumeroPositivo($alto) || !$this->validarNumeroPositivo($ancho) || 
            !$this->validarNumeroPositivo($profundidad) || !$this->validarNumeroPositivo($cantidad)) {
            $this->alerta(
                'danger',
                'Las medidas y cantidad de peces deben ser numeros positivos'
            );
            return;
        }

        // VALIDAR QUE EL NOMBRE SOLO CONTENGA TEXTO Y NÚMEROS
        if (!$this->validarTexto($nombre)) {
            $this->alerta(
                'danger',
                'El nombre del tanque contiene caracteres no permitidos'
            );
            return;
        }

        // VALIDAR DUPLICADOS (EXCLUYENDO EL ACTUAL)
        $sqlDuplicado = "
            SELECT 1
            FROM tanque
            WHERE LOWER(nombre_tanque) = LOWER('".pg_escape_string($nombre)."')
            AND id_tanque <> ".(int)$id;

        if (pg_num_rows($this->model->select($sqlDuplicado)) > 0) {
            $this->alerta(
                'danger',
                'Ya existe un tanque con este nombre, por favor ingresa un nombre diferente'
            );
            return;
        }

        $sql = "UPDATE tanque SET
                    nombre_tanque='".pg_escape_string($nombre)."',
                    medida_alto=".(float)$alto.",
                    medida_ancho=".(float)$ancho.",
                    medida_profundidad=".(float)$profundidad.",
                    id_tipo_tanque=".(int)$tipo.",
                    cantidad_peces=".(int)$cantidad."
                WHERE id_tanque=".(int)$id;

        if ($this->model->update($sql)) {
            $this->alerta(
                'success',
                'Tanque actualizado correctamente'
            );
        } else {
            $this->alerta(
                'danger',
                'No se pudo actualizar el tanque'
            );
        }
    }

    /* ============================
       INHABILITAR / ACTIVAR
    ============================ */
    public function updateStatus() {

        $id = isset($_GET['id_tanque']) ? (int)$_GET['id_tanque'] : 0;
        $estado = isset($_GET['estado']) ? (int)$_GET['estado'] : 0;

        // VALIDAR ID
        if ($id <= 0) {
            $this->alerta('danger', 'ID invalido');
            return;
        }

        // VERIFICAR QUE EL TANQUE EXISTE
        $sqlCheck = "SELECT id_estado_tanque FROM tanque WHERE id_tanque = $id";
        $result = $this->model->select($sqlCheck);

        if (pg_num_rows($result) === 0) {
            $this->alerta('danger', 'El tanque no existe');
            return;
        }

        $row = pg_fetch_assoc($result);

        // VALIDAR SI YA ESTÁ EN ESE ESTADO
        if ($row['id_estado_tanque'] == $estado) {
            $mensaje = $estado == 1 ? 'El tanque ya está activo' : 'El tanque ya está inactivo';
            $this->alerta('danger', $mensaje);
            return;
        }

        $sql = "UPDATE tanque SET id_estado_tanque=$estado WHERE id_tanque=$id";
        
        if ($this->model->update($sql)) {
            $mensaje = $estado == 1 ? 'Tanque activado correctamente' : 'Tanque inhabilitado correctamente';
            $this->alerta('success', $mensaje);
        } else {
            $this->alerta('danger', 'No se pudo cambiar el estado del tanque');
        }
    }

    /* ============================
       MÉTODOS DE VALIDACIÓN
    ============================ */

    // CAMPOS OBLIGATORIOS
    private function camposObligatorios($nombre, $alto, $ancho, $profundidad, $tipo, $cantidad) {
        return $nombre !== '' && $alto !== '' && $ancho !== '' && 
               $profundidad !== '' && $tipo !== '' && $cantidad !== '';
    }

    // VALIDAR TEXTO (LETRAS, NÚMEROS Y ESPACIOS)
    private function validarTexto($texto) {
        return preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/u', $texto);
    }

    // VALIDAR NÚMERO POSITIVO
    private function validarNumeroPositivo($numero) {
        return is_numeric($numero) && $numero > 0;
    }

    // VALIDAR DUPLICADOS
    private function existeTanque($nombre) {
        $sql = "
            SELECT 1
            FROM tanque
            WHERE LOWER(nombre_tanque) = LOWER('".pg_escape_string($nombre)."')
        ";
        $result = $this->model->select($sql);
        return pg_num_rows($result) > 0;
    }

    // ALERTA Y REDIRECCIÓN
    private function alerta($tipo, $mensaje) {
        $_SESSION['alert'] = array(
            'type' => $tipo,
            'message' => $mensaje
        );

        echo "<script>window.location.href='".getUrl("Tanques","Tanques","getList")."';</script>";
        exit;
    }
}