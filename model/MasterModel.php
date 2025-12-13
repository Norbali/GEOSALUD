<?php
include_once("../lib/conf/connection.php");

class MasterModel extends Connection {

    public function select($sql){
        $result = pg_query($this->getConnect(), $sql);

        if(!$result){
            echo "Error: Could not execute query";
            return array(); // PHP 5 compatible
        }

        // Crear array con los datos
        $data = array();
        while ($row = pg_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function insert($sql){
        return pg_query($this->getConnect(), $sql);
    }

    public function update($sql){
        return pg_query($this->getConnect(), $sql);
    }

    public function delete($sql){
        return pg_query($this->getConnect(), $sql);
    }

    public function findOne($table, $fields, $condition){
        $sql = "SELECT $fields FROM $table WHERE $condition";
        $result = pg_query($this->getConnect(), $sql);

        if(pg_num_rows($result) > 0){
            return $result;
        } else {
            return false;
        }
    }

    public function autoincrement($table, $field){
        $sql = "SELECT MAX($field) FROM $table";
        $result = pg_query($this->getConnect(), $sql);

        $max_id = pg_fetch_array($result);
        return $max_id[0] + 1;
    }
}
?>
