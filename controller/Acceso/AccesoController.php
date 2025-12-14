<?php

    include_once '../model/Acceso/AccesoModel.php';

    class AccesoController{

        public function login(){
          
            $obj = new AccesoModel();

            $documento = $_POST['documento'];
            $contraseña = $_POST['contraseña'];

            $validaciones = true;
            $validacionDocumento = $this->validarDocumento($documento); 
            $validacionesContraseña = $this->validarContrasena($contraseña);

            if($validacionDocumento!="true"){
                $validaciones=false;
                $_SESSION['error'] = $validacionDocumento;
            }

            if($validacionesContraseña!="true"){
                $validaciones=false;
                $_SESSION['error'] = $validacionesContraseña;
            }
            if($validaciones){

                if(!$this->validarCredencialDocumento($documento)){
                    $_SESSION['error'] = "El número documento no está asociado a una cuenta.";
                    redirect("login.php");
                    return;
                }

                if(!$this->validarCredencialContraseña($documento, $contraseña)){
                    $_SESSION['error'] = "La contraseña incorrecta.";
                    redirect("login.php");
                    return;
                }

                $idRol = $this->obtenerValorCampo($documento, "id_rol");

                $_SESSION['documento'] = $documento;
                $_SESSION['auth'] = "ok";
                
                $nombre = $this->obtenerValorCampo($documento, "nombre");
                $apellido = $this->obtenerValorCampo($documento, "apellido");
                $_SESSION['nombreCompleto'] =$nombre." ".$apellido;
                 $_SESSION['nombre'] =$nombre;

                $_SESSION['rol'] = $idRol;
                $_SESSION['nombreRol'] = $this->obtenerNombreRol($idRol);

                // CARGAR PERMISOS
                $permisos = $this->cargarPermisos($idRol);
                $_SESSION['permisos'] = $permisos;

                redirect("index.php");

            }else{
                redirect("login.php");
            }        
        }

        public function logout(){
            session_destroy();
            redirect("login.php");
        }

        public function cargarPermisos($idRol) {
            $obj = new AccesoModel();

            $sql = "
                SELECT m.nombre_modulo, a.nombre_accion
                FROM permisos p
                INNER JOIN modulo m ON p.id_modulo = m.id_modulo
                INNER JOIN acciones a ON p.id_accion = a.id_accion
                WHERE p.id_rol = $idRol
            ";

            $result = $obj->select($sql);

            $permisos = array();

            while ($row = pg_fetch_assoc($result)) {
                if (!isset($permisos[$row['nombre_modulo']])) {
                    $permisos[$row['nombre_modulo']] = array();
                }

                array_push($permisos[$row['nombre_modulo']], $row['nombre_accion']);
            }

            return $permisos;
        }


        //VALIDAR CAMPOS
        function validarContrasena($contrasena) {
            $mensaje = "";

            if (strlen($contrasena) < 8) {
                $mensaje = "La contrase&ntilde;a debe tener m&iacute;nimo 8 caracteres.";
            } elseif (
                !preg_match('/[A-Z]/', $contrasena) ||
                !preg_match('/[a-z]/', $contrasena) ||
                !preg_match('/\d/', $contrasena) ||
                !preg_match('/[\W_]/', $contrasena)
            ) {
                $mensaje = "La contrase&ntilde;a debe contener may&uacute;scula, min&uacute;scula, n&uacute;mero y car&aacute;cter especial.";
            } else {
                $mensaje = "true";
            }

            return $mensaje;
        }

        function validarDocumento($documento) {
            $mensaje = "";
            if (empty($documento)) {
                $mensaje = "El documento no puede estar vacío.";
            } elseif (
                !preg_match('/^[0-9]+$/', $documento) || strlen($documento) < 9 || strlen($documento) > 10
            ) {
                $mensaje = "El documento debe contener solo n&uacute;meros enteros y tener entre 9 y 10 d&iacute;gitos.";
            } else {
                $mensaje = "true";
            }

            return $mensaje;
        }

        //validar credenciales
        public function validarCredencialDocumento($documento){
            $obj = new AccesoModel();

            $sql = "SELECT * FROM usuarios WHERE documento = '$documento'";
            $usuario = $obj->select($sql);

            if(pg_num_rows($usuario)>0){
                return true;
            }else{
                return false;
            }
        }

        public function validarCredencialContraseña($documento, $contrasena){
            $obj = new AccesoModel();
            $hash = sha1($contrasena);

            $sql = "SELECT * FROM usuarios WHERE documento = '$documento' AND contrasena = '$hash'";
            $usuario = $obj->select($sql);

            if(pg_num_rows($usuario)>0){
                return true;
            }else{
                return false;
            }
        }

        public function obtenerValorCampo($documento, $campo) {
            $obj = new AccesoModel();

            $sql = "SELECT $campo FROM usuarios WHERE documento = '$documento'";
            $usuario = $obj->select($sql);

            if (pg_num_rows($usuario) > 0) {
                $row = pg_fetch_assoc($usuario);
                return $row[$campo];  
            } else {
                return false;
            }
        }

        public function obtenerNombreRol($idRol) {
            $obj = new AccesoModel();
            $sql = "SELECT nombre_rol FROM rol WHERE id_rol = $idRol";

            $resultado = $obj->select($sql);

            if (pg_num_rows($resultado) > 0) {
                $row = pg_fetch_assoc($resultado);
                return $row['nombre_rol'];
            } else {
                return null; 
            }
        }

    }
?>