<?php
session_start();
include_once '../model/TipoTanques/TipoTanquesModel.php';

class TipoTanquesController {
    
    private $model;

    public function __construct() {
        $this->model = new TipoTanquesModel();
    }

  
    public function getConsultar() {
        $sql = "SELECT 
                    tt.id_tipo_tanque,
                    tt.nombre_tipo_tanque,
                    ett.nombre_estado_tipo_tanques
                FROM tipo_tanque tt
                JOIN estado_tipo_tanques ett
                    ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
                ORDER BY tt.id_tipo_tanque";
        
        $tiposTanques = $this->model->select($sql);
        
        @session_start();
        
        $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;
        $tipo_mensaje = isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : null;
        
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
        
        include_once '../view/tipoTanques/Consultartipodetanque.php';
    }
    
   
    public function postCrear() {
        @session_start();
        
        $nombre_tipo_tanque = isset($_POST['nombre_tipo_tanque']) ? trim($_POST['nombre_tipo_tanque']) : '';
        
        $nombre_tipo_tanque = preg_replace('/\s+/', ' ', $nombre_tipo_tanque);
        $nombre_tipo_tanque = trim($nombre_tipo_tanque);
        
        $id_estado_tipo_tanque = 1;
        
        
        if ($nombre_tipo_tanque === '') {
            $this->alerta('warning', 'Debe ingresar el nombre del tipo de tanque');
            return;
        }
        
        
        if (strlen($nombre_tipo_tanque) === 0) {
            $this->alerta('warning', 'El nombre del tipo de tanque no puede estar vacío o contener solo espacios');
            return;
        }
        
        if (!$this->validarTexto($nombre_tipo_tanque)) {
            $this->alerta('warning', 'El nombre del tipo de tanque contiene caracteres no permitidos. Solo se permiten letras, números y espacios');
            return;
        }
        
        if (strlen($nombre_tipo_tanque) < 3) {
            $this->alerta('warning', 'El nombre del tipo de tanque debe tener al menos 3 caracteres');
            return;
        }
        
        if ($this->existeTipoTanque($nombre_tipo_tanque)) {
            $this->alerta('warning', 'Ya existe un tipo de tanque con este nombre, por favor ingresa un nombre diferente');
            return;
        }
        
        $sql = "INSERT INTO tipo_tanque (nombre_tipo_tanque, id_estado_tipo_tanque)
                VALUES ('".pg_escape_string($nombre_tipo_tanque)."', ".$id_estado_tipo_tanque.")";
        
        $ejecutar = $this->model->insert($sql);
        
        if ($ejecutar) {
            $this->alerta('success', "El tipo de tanque \"$nombre_tipo_tanque\" ha sido registrado exitosamente");
        } else {
            $this->alerta('error', 'Error al registrar el tipo de tanque. Por favor, intenta nuevamente');
        }
    }
    
    public function postActualizar() {
        @session_start();
        
        $id_tipo_tanque = isset($_POST['id_tipo_tanque']) ? trim($_POST['id_tipo_tanque']) : '';
        $nombre_tipo_tanque = isset($_POST['nombre_tipo_tanque']) ? trim($_POST['nombre_tipo_tanque']) : '';
        
        $nombre_tipo_tanque = preg_replace('/\s+/', ' ', $nombre_tipo_tanque);
        $nombre_tipo_tanque = trim($nombre_tipo_tanque);
        
        if ($id_tipo_tanque === '' || $nombre_tipo_tanque === '') {
            $this->alerta('warning', 'Debe completar todos los campos antes de guardar los cambios');
            return;
        }
        
        if (strlen($nombre_tipo_tanque) === 0) {
            $this->alerta('warning', 'El nombre del tipo de tanque no puede estar vacío o contener solo espacios');
            return;
        }
        
        // VALIDAR QUE EL NOMBRE SOLO CONTENGA TEXTO Y NÚMEROS
        if (!$this->validarTexto($nombre_tipo_tanque)) {
            $this->alerta('warning', 'El nombre del tipo de tanque contiene caracteres no permitidos. Solo se permiten letras, números y espacios');
            return;
        }
        
        if (strlen($nombre_tipo_tanque) < 3) {
            $this->alerta('warning', 'El nombre del tipo de tanque debe tener al menos 3 caracteres');
            return;
        }
        
        $sqlValidar = "SELECT tt.nombre_tipo_tanque, ett.nombre_estado_tipo_tanques 
                       FROM tipo_tanque tt
                       JOIN estado_tipo_tanques ett ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
                       WHERE tt.id_tipo_tanque = ".(int)$id_tipo_tanque;
        
        $resultado = $this->model->select($sqlValidar);
        
        if ($resultado && pg_num_rows($resultado) > 0) {
            $row = pg_fetch_assoc($resultado);
            
            if ($row['nombre_estado_tipo_tanques'] != 'activo') {
                $this->alerta('warning', "Este tipo de tanque \"" . $row['nombre_tipo_tanque'] . "\" no se puede editar porque está inhabilitado");
                return;
            }
        } else {
            $this->alerta('error', 'El tipo de tanque no existe');
            return;
        }
        
        $sqlDuplicado = "
            SELECT 1
            FROM tipo_tanque
            WHERE LOWER(nombre_tipo_tanque) = LOWER('".pg_escape_string($nombre_tipo_tanque)."')
            AND id_tipo_tanque <> ".(int)$id_tipo_tanque;

        if (pg_num_rows($this->model->select($sqlDuplicado)) > 0) {
            $this->alerta('warning', 'Ya existe un tipo de tanque con este nombre, por favor ingresa un nombre diferente');
            return;
        }
        
        $sql = "UPDATE tipo_tanque 
                SET nombre_tipo_tanque = '".pg_escape_string($nombre_tipo_tanque)."'
                WHERE id_tipo_tanque = ".(int)$id_tipo_tanque;
        
        $ejecutar = $this->model->update($sql);
        
        if ($ejecutar) {
            $this->alerta('success', "La edición del tipo de tanque \"$nombre_tipo_tanque\" ha sido exitosa");
        } else {
            $this->alerta('error', 'Error al actualizar el tipo de tanque. Por favor, intenta nuevamente');
        }
    }

    public function postInhabilitar() {
        @session_start();
        
        $id_tipo_tanque = isset($_POST['id_tipo_tanque']) ? (int)$_POST['id_tipo_tanque'] : 0;
        
        if ($id_tipo_tanque <= 0) {
            $this->alerta('error', 'ID inválido');
            return;
        }
        
        $sqlValidar = "SELECT tt.nombre_tipo_tanque, ett.nombre_estado_tipo_tanques 
                       FROM tipo_tanque tt
                       JOIN estado_tipo_tanques ett ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
                       WHERE tt.id_tipo_tanque = $id_tipo_tanque";
        $resultado = $this->model->select($sqlValidar);
        
        if (!$resultado || pg_num_rows($resultado) === 0) {
            $this->alerta('error', 'El tipo de tanque no existe');
            return;
        }
        
        $row = pg_fetch_assoc($resultado);
        $nombreTanque = $row['nombre_tipo_tanque'];
        
        if ($row['nombre_estado_tipo_tanques'] != 'activo') {
            $this->alerta('warning', "El tipo de tanque \"$nombreTanque\" ya está inhabilitado");
            return;
        }
        
        $sql = "UPDATE tipo_tanque 
                SET id_estado_tipo_tanque = 2
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $this->model->update($sql);
        
        if ($ejecutar) {
            $this->alerta('success', "El tipo de tanque \"$nombreTanque\" ha sido inhabilitado exitosamente");
        } else {
            $this->alerta('error', 'Error al inhabilitar el tipo de tanque. Por favor, intenta nuevamente');
        }
    }
    
    private function validarTexto($texto) {
        return preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/u', $texto);
    }
    
    private function existeTipoTanque($nombre) {
        $sql = "
            SELECT 1
            FROM tipo_tanque
            WHERE LOWER(nombre_tipo_tanque) = LOWER('".pg_escape_string($nombre)."')
        ";
        $result = $this->model->select($sql);
        return pg_num_rows($result) > 0;
    }
    
    private function alerta($tipo, $mensaje) {
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipo_mensaje'] = $tipo;
        
        redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        exit;
    }

    public function filtro(){
        $buscar = $_GET['buscar']; 
        
        $sql = "
            SELECT 
            tt.id_tipo_tanque,
            tt.nombre_tipo_tanque,
            ett.nombre_estado_tipo_tanques
        FROM tipo_tanque tt
        JOIN estado_tipo_tanques ett
            ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
        WHERE tt.nombre_tipo_tanque ILIKE '%$buscar%'
        ORDER BY tt.id_tipo_tanque;

        ";

        $tiposTanques = $this->model->select($sql); 
       
        include_once '../view/tipoTanques/filtroTipoTanques.php';
    }
    
}

?>