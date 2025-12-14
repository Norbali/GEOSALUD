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
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            echo "Error al registrar el tipo de tanque";
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
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            echo "Error al actualizar el tipo de tanque";
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
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            echo "Error al inhabilitar el tipo de tanque";
        }
    }
       public function postHabilitar() {
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_POST['id_tipo_tanque'];
        
        $sql = "UPDATE tipo_tanque 
                SET id_estado_tipo_tanque = 1
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $obj->update($sql);
        
        if ($ejecutar) {
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            echo "Error al habilitar el tipo de tanque";
        }
    }
}

?>
}

?>