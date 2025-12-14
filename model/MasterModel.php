<?php
include_once("../lib/conf/connection.php");

class MasterModel extends Connection
{

    public function select($sql)
    {
        $result = pg_query($this->getConnect(), $sql);
        return $result;
    }

    public function insert($sql)
    {
        $conn = $this->getConnect();
        $result = pg_query($conn, $sql);

        if (!$result) {
            echo "<pre>Error PostgreSQL: " . pg_last_error($conn) . "</pre>";
            return false;
        }

        return true;
    }


    public function update($sql)
    {
        $result = pg_query($this->getConnect(), $sql);
        return $result;
    }

    public function delete($sql)
    {
        $result = pg_query($this->getConnect(), $sql);
        return $result;
    }

    public function findOne($table, $fields, $condition)
    {
        $sql = "SELECT $fields FROM $table WHERE $condition";

        $result = pg_query($this->getConnect(), $sql);

        if (pg_num_rows($result) > 0) {
            return $result;
        } else {
            return "No se encontro ningun registro";
        }
    }

    public function autoincrement($table, $field)
    {
        $sql = "SELECT MAX($field) FROM $table";

        $result = pg_query($this->getConnect(), $sql);

        $max_id = pg_fetch_array($result);

        return $max_id[0] + 1;
    }
}
