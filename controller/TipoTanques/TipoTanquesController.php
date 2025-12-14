<?php

include_once '../model/TipoTanques/TipoTanquesModel.php';

class TipoTanquesController {
    
    // Consultar todos los tipos de tanques
    public function getConsultar() {
        $obj = new TipoTanquesModel();
        $sql = "SELECT 
                    tt.id_tipo_tanque,
                    tt.nombre_tipo_tanque,
                    ett.nombre_estado_tipo_tanques
                FROM tipo_tanque tt
                JOIN estado_tipo_tanques ett
                    ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
                ORDER BY tt.id_tipo_tanque";
        
        $tiposTanques = $obj->select($sql);
        include_once '../view/tipoTanques/Consultartipodetanque.php';
    }
    
    // Registrar nuevo tipo de tanque
    public function postCrear() {
        $obj = new TipoTanquesModel();
        
        $nombre_tipo_tanque = $_POST['nombre_tipo_tanque'];
        $id_estado_tipo_tanque = $_POST['id_estado_tipo_tanque'];
        
        $sql = "INSERT INTO tipo_tanque (nombre_tipo_tanque, id_estado_tipo_tanque)
                VALUES ('$nombre_tipo_tanque', '$id_estado_tipo_tanque')";
        
        $ejecutar = $obj->insert($sql);
        
        if ($ejecutar) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Tipo de tanque registrado exitosamente'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error al registrar el tipo de tanque'
            ));
        }
    }
    
    // Obtener un tipo de tanque por ID (para ver detalle)
    public function getDetalle() {
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_GET['id'];
        
        $sql = "SELECT 
                    tt.id_tipo_tanque,
                    tt.nombre_tipo_tanque,
                    ett.nombre_estado_tipo_tanques,
                    tt.id_estado_tipo_tanque
                FROM tipo_tanque tt
                JOIN estado_tipo_tanques ett
                    ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
                WHERE tt.id_tipo_tanque = $id_tipo_tanque";
        
        $resultado = $obj->select($sql);
        
        if ($resultado) {
            $tipoTanque = pg_fetch_assoc($resultado);
            echo json_encode(array(
                'success' => true,
                'data' => $tipoTanque
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Tipo de tanque no encontrado'
            ));
        }
    }
    
    // Actualizar tipo de tanque
    public function postActualizar() {
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_POST['id_tipo_tanque'];
        $nombre_tipo_tanque = $_POST['nombre_tipo_tanque'];
        $id_estado_tipo_tanque = $_POST['id_estado_tipo_tanque'];
        
        $sql = "UPDATE tipo_tanque 
                SET nombre_tipo_tanque = '$nombre_tipo_tanque',
                    id_estado_tipo_tanque = $id_estado_tipo_tanque
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $obj->update($sql);
        
        if ($ejecutar) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Tipo de tanque actualizado exitosamente'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error al actualizar el tipo de tanque'
            ));
        }
    }
    
    // Inhabilitar tipo de tanque (cambiar estado a Inactivo)
    public function postInhabilitar() {
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_POST['id_tipo_tanque'];
        
        $sql = "UPDATE tipo_tanque 
                SET id_estado_tipo_tanque = 2
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $obj->update($sql);
        
        if ($ejecutar) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Tipo de tanque inhabilitado exitosamente'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error al inhabilitar el tipo de tanque'
            ));
        }
    }
    
    // Habilitar tipo de tanque (cambiar estado a Activo)
    public function postHabilitar() {
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_POST['id_tipo_tanque'];
        
        $sql = "UPDATE tipo_tanque 
                SET id_estado_tipo_tanque = 1
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $obj->update($sql);
        
        if ($ejecutar) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Tipo de tanque habilitado exitosamente'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error al habilitar el tipo de tanque'
            ));
        }
    }
}

?>