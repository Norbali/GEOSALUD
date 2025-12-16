<?php
session_start();

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
        
        // Iniciar sesión
        @session_start();
        
        // Capturar mensajes de sesión
        $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;
        $tipo_mensaje = isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : null;
        
        // Limpiar mensajes de sesión
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
        
        include_once '../view/tipoTanques/Consultartipodetanque.php';
    }
    
    // Registrar nuevo tipo de tanque
    public function postCrear() {
        @session_start();
        
        $obj = new TipoTanquesModel();
        
        $nombre_tipo_tanque = $_POST['nombre_tipo_tanque'];
        $id_estado_tipo_tanque = $_POST['id_estado_tipo_tanque'];
        
        $sql = "INSERT INTO tipo_tanque (nombre_tipo_tanque, id_estado_tipo_tanque)
                VALUES ('$nombre_tipo_tanque', '$id_estado_tipo_tanque')";
        
        $ejecutar = $obj->insert($sql);
        
        if ($ejecutar) {
            $_SESSION['mensaje'] = "El tipo de tanque \"$nombre_tipo_tanque\" ha sido registrado exitosamente.";
            $_SESSION['tipo_mensaje'] = "success";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            $_SESSION['mensaje'] = "Error al registrar el tipo de tanque.";
            $_SESSION['tipo_mensaje'] = "error";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        }
    }
    
    // Actualizar tipo de tanque
    public function postActualizar() {
        @session_start();
        
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_POST['id_tipo_tanque'];
        $nombre_tipo_tanque = $_POST['nombre_tipo_tanque'];
        $id_estado_tipo_tanque = $_POST['id_estado_tipo_tanque'];
        
        // VALIDAR QUE EL TANQUE ESTÉ ACTIVO ANTES DE EDITAR
        $sqlValidar = "SELECT tt.nombre_tipo_tanque, ett.nombre_estado_tipo_tanques 
                       FROM tipo_tanque tt
                       JOIN estado_tipo_tanques ett ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
                       WHERE tt.id_tipo_tanque = $id_tipo_tanque";
        
        $resultado = $obj->select($sqlValidar);
        
        if ($resultado && pg_num_rows($resultado) > 0) {
            $row = pg_fetch_assoc($resultado);
            
            // SI EL TANQUE ESTÁ INACTIVO, NO PERMITIR EDICIÓN
            if ($row['nombre_estado_tipo_tanques'] != 'activo') {
                $_SESSION['mensaje'] = "Este tanque \"" . $row['nombre_tipo_tanque'] . "\" no se puede editar porque no está activo.";
                $_SESSION['tipo_mensaje'] = "warning";
                redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
                return; // IMPORTANTE: detener ejecución
            }
        }
        
        // Si está activo, proceder con la actualización
        $sql = "UPDATE tipo_tanque 
                SET nombre_tipo_tanque = '$nombre_tipo_tanque',
                    id_estado_tipo_tanque = $id_estado_tipo_tanque
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $obj->update($sql);
        
        if ($ejecutar) {
            $_SESSION['mensaje'] = "La edición del tipo de tanque \"$nombre_tipo_tanque\" ha sido exitosa.";
            $_SESSION['tipo_mensaje'] = "success";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el tipo de tanque.";
            $_SESSION['tipo_mensaje'] = "error";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        }
    }
    
    // Inhabilitar tipo de tanque
    public function postInhabilitar() {
        @session_start();
        
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_POST['id_tipo_tanque'];
        
        // Obtener el nombre del tanque antes de inhabilitar
        $sqlNombre = "SELECT nombre_tipo_tanque FROM tipo_tanque WHERE id_tipo_tanque = $id_tipo_tanque";
        $resultadoNombre = $obj->select($sqlNombre);
        $nombreTanque = "";
        
        if ($resultadoNombre && pg_num_rows($resultadoNombre) > 0) {
            $row = pg_fetch_assoc($resultadoNombre);
            $nombreTanque = $row['nombre_tipo_tanque'];
        }
        
        $sql = "UPDATE tipo_tanque 
                SET id_estado_tipo_tanque = 2
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $obj->update($sql);
        
        if ($ejecutar) {
            $_SESSION['mensaje'] = "El tipo de tanque \"$nombreTanque\" ha sido inhabilitado exitosamente.";
            $_SESSION['tipo_mensaje'] = "success";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            $_SESSION['mensaje'] = "Error al inhabilitar el tipo de tanque.";
            $_SESSION['tipo_mensaje'] = "error";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        }
    }
    
    public function postHabilitar() {
        @session_start();
        
        $obj = new TipoTanquesModel();
        
        $id_tipo_tanque = $_POST['id_tipo_tanque'];
        
        // Obtener el nombre del tanque antes de habilitar
        $sqlNombre = "SELECT nombre_tipo_tanque FROM tipo_tanque WHERE id_tipo_tanque = $id_tipo_tanque";
        $resultadoNombre = $obj->select($sqlNombre);
        $nombreTanque = "";
        
        if ($resultadoNombre && pg_num_rows($resultadoNombre) > 0) {
            $row = pg_fetch_assoc($resultadoNombre);
            $nombreTanque = $row['nombre_tipo_tanque'];
        }
        
        $sql = "UPDATE tipo_tanque 
                SET id_estado_tipo_tanque = 1
                WHERE id_tipo_tanque = $id_tipo_tanque";
        
        $ejecutar = $obj->update($sql);
        
        if ($ejecutar) {
            $_SESSION['mensaje'] = "El tipo de tanque \"$nombreTanque\" ha sido activado exitosamente.";
            $_SESSION['tipo_mensaje'] = "success";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        } else {
            $_SESSION['mensaje'] = "Error al activar el tipo de tanque.";
            $_SESSION['tipo_mensaje'] = "error";
            redirect(getUrl("TipoTanques", "TipoTanques", "getConsultar"));
        }
    }

    public function filtro($nombre) {
        $obj = new TipoTanquesModel();
        $sql = "SELECT 
                    tt.id_tipo_tanque,
                    tt.nombre_tipo_tanque,
                    ett.nombre_estado_tipo_tanques
                FROM tipo_tanque tt
                JOIN estado_tipo_tanques ett
                    ON tt.id_estado_tipo_tanque = ett.id_estado_tipo_tanques
                WHERE tt.nombre_tipo_tanque ILIKE '%$nombre%'
                ORDER BY tt.id_tipo_tanque";
        $tiposTanques = $obj->select($sql);
        if (pg_num_rows($tiposTanques) == 0) {
            $_SESSION['sinResultadosTipoTanque'] = "No se encontraron resultados para la búsqueda \"$nombre\".";
        }   
        include_once '../view/tipoTanques/filtroTipoTanques.php';
    }
}

?>