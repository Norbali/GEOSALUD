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
                $_SESSION['nombre'] = $this->obtenerValorCampo($documento, "nombre");
                $_SESSION['rol'] = $idRol;

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
                SELECT m.nombre_modulo, a.nombre_accion FROM permisos p
                INNER JOIN modulo m ON p.id_modulo = m.id_modulo
                INNER JOIN acciones a ON p.id_accion = a.id_accion
                WHERE p.id_rol = $idRol
            ";

            $result = $obj->select($sql);

            $permisos = [];

            while ($row = pg_fetch_assoc($result)) {
                $modulo = $row['nombre_modulo'];
                $accion = $row['nombre_accion'];
                $permisos[$row['nombre_modulo']][] = $row['nombre_accion'];
            }

            return $permisos;
            
        }

        //VALIDAR CAMPOS
        function validarContrasena($contrasena) {
            $mensaje = "";

            if (strlen($contrasena) < 8) {
                $mensaje = "Debe tener mínimo 8 caracteres.";
            } elseif (!preg_match('/[A-Z]/', $contrasena)) {
                $mensaje = "Debe contener al menos una mayúscula.";
            } elseif (!preg_match('/[a-z]/', $contrasena)) {
                $mensaje = "Debe contener al menos una minúscula.";
            } elseif (!preg_match('/\d/', $contrasena)) {
                $mensaje = "Debe contener al menos un número.";
            } elseif (!preg_match('/[\W_]/', $contrasena)) {
                $mensaje = "Debe contener al menos un carácter especial.";
            } else {
                $mensaje = "true";
            }
            return $mensaje;
        }

        function validarDocumento($documento) {
            $mensaje = "";
            if (empty($documento)) {
                $mensaje = "El documento no puede estar vacío.";
            }
            
            elseif (!preg_match('/^[0-9]+$/', $documento)) {
                $mensaje = "El documento solo debe contener números.";
            }

            elseif (strlen($documento) < 9) {
                $mensaje = "El documento debe tener mínimo 9 dígitos.";
            }

            elseif (strlen($documento) > 10) {
                $mensaje = "El documento debe tener máximo 10 dígitos.";
            }
            else {
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

        public function validarCredencialContraseña($documento, $contraseña){
            $obj = new AccesoModel();

            $sql = "SELECT * FROM usuarios WHERE documento = '$documento' AND contrasena = '$contraseña'";
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


    }

?>