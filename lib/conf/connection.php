<?php
// Se crean los mismos atributos de la configuracion solo que se le añadio el atributo "link".
    class Connection{
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        private $link;
        // El constructor se utiliza dos guiones bajos separado de la funcion 
        function __construct(){
            $this->setConnection(); // asigna los parametros de conexion.
            $this->connect(); // conecta con la base de datos.
        }
        // require requiere el archivo configuracion.php para poder ejecutar. 
        // Si el archivo no esta no exite te lanza el error.
        // Para sacar las comillas simple es al lado del cero.
        private function setConnection(){
            require 'conf.php';
            $this->server = $server;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;
            $this->port = $port;
        }

        private function connect(){
        //Se debe de poner en el orden en el cual esta el archivo 'configuracion.php' de lo contrario
        //saldra un error.
        
            $this->link = pg_connect("host={$this->server} port={$this->port} dbname={$this->database} user={$this->user} password={$this->password}");

        // el if se hace para ver si la conexion existe.
            if (!$this->link){
                die(pg_last_error($this->link)); //Mostrara cual es el error o se puede tambien escribir un mensaje diciendo que en la conexion hubo un error.

            }
            // Establece la codificación de caracteres a UTF-8 para la conexión a la base de datos Y así manejar correctamente caracteres especiales.
            pg_set_client_encoding($this->link, "UTF8");
           
        }
        public function getConnect(){ // o getConnect
            return $this->link;
        }
        public function close(){
            pg_close($this->link);
        }
    }
 

?>