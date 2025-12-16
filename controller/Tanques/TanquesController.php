<?php
session_start();

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

        // Capturar búsqueda
        $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

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

        // Agregar filtro de búsqueda si existe
        if ($busqueda !== '') {
            $sql .= " WHERE LOWER(t.nombre_tanque) LIKE LOWER('%".pg_escape_string($busqueda)."%')";
        }

        $sql .= " ORDER BY t.id_tanque DESC";

        $tanques = $this->model->select($sql);

        $tipos = $this->model->select("SELECT * FROM tipo_tanque");

        // Obtener zoocriaderos para el select
        $zoocriaderos = $this->model->select("SELECT * FROM zoocriadero WHERE id_estado_zoocriadero = 1");

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
        
        // OBTENER ID_ZOOCRIADERO - Ajusta según tu caso:
        // Opción 1: Desde el formulario (si agregaste el select)
        $id_zoocriadero = isset($_POST['id_zoocriadero']) ? trim($_POST['id_zoocriadero']) : '';
        
        // Opción 2: Desde la sesión (si el usuario tiene un zoocriadero asignado)
        if ($id_zoocriadero === '' && isset($_SESSION['id_zoocriadero'])) {
            $id_zoocriadero = $_SESSION['id_zoocriadero'];
        }
        
        // Opción 3: Valor por defecto (si solo hay un zoocriadero)
        if ($id_zoocriadero === '') {
            $id_zoocriadero = 1; // Cambia esto según tu necesidad
        }

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

        $id = $this->model->autoIncrement("tanque", "id_tanque");

        $sql = "INSERT INTO tanque (
                    id_tanque, 
                    nombre_tanque, 
                    medida_alto, 
                    medida_ancho,
                    medida_profundidad, 
                    id_tipo_tanque, 
                    cantidad_peces,
                    id_estado_tanque,
                    id_zoocriadero
                ) VALUES (
                    $id,
                    '".pg_escape_string($nombre)."',
                    ".(float)$alto.",
                    ".(float)$ancho.",
                    ".(float)$profundidad.",
                    ".(int)$tipo.",
                    ".(int)$cantidad.",
                    1,
                    ".(int)$id_zoocriadero."
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
            $this->alerta('danger', 'ID inválido');
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