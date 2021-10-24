<?php
class Database_worker
{

    private $hostname = 'server155.hosting.reg.ru';
    private $username = 'u0507831_test';
    private $password = 'jM3yJ6eI6hrB9h';
    private $database = 'u0507831_test';

    public function __construct() {
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            echo "Ошибка подключения к БД";
            die();
        }
    }

    public function reconnect(){
        if(!mysqli_ping($this->conn)){
            $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        }
    }

    public function do_sql($sql, $to_array = FALSE) {
        //$this->conn->query("SET NAMES utf8");
        $result = $this->conn->query($sql);
        if (!$result) {
            return FALSE;
        }
        $result_obj_arr = [];
        if ($result->num_rows != 0) {
            if ($to_array) {
                while ($data = $result->fetch_assoc()) {
                    $result_obj_arr[] = $data;
                }
            } else {
                while ($data = $result->fetch_object()) {
                    $result_obj_arr[] = $data;
                }
            }
        }
        return $result_obj_arr;
    }

    public function insert($table_name, $insert_arr) {
        $sql = "INSERT IGNORE INTO $table_name SET ";
        $tmp_arr = [];
        foreach ($insert_arr as $key => $data) {
            $tmp_arr[] = "$key='$data' ";
        }
        $tmp_arr = implode(",", $tmp_arr);
        $sql .= $tmp_arr;
        $result = $this->conn->query($sql);
        if (!$result) {
            $result = FALSE;
        } else {
            $result = $this->conn->insert_id;
        }
        return $result;
    }

    /*
     public function insert_batch($table_name, $insert_arr){
         $sql = "INSERT INTO $table_name (`link`,`goods_id`,`name`,`value`) VALUES";
         $tmp_arr = [];
         foreach ($insert_arr as $key => $data) {
             mysql
             $tmp_arr[] = "('{$data['link']}','{$data['goods_id']}','{$data['name']}','{$data['value']}')";
         }
         $tmp_arr = implode(",", $tmp_arr);
         $sql .= $tmp_arr;
         
         $result = $this->conn->query($sql);
         if (!$result) {
             $result = FALSE;
         } else {
             $result = FALSE;
         }
         return $result;
     }
     */

    public function update($table_name, $update_arr, $where_arr) {
        $sql = "UPDATE $table_name SET ";
        $tmp_arr = [];
        foreach ($update_arr as $key => $data) {
            $tmp_arr[] = "$key='$data' ";
        }
        $tmp_arr = implode(",", $tmp_arr);
        $sql .= $tmp_arr;
        $sql .= " WHERE id=" . $where_arr;
        $result = $this->conn->query($sql);
        //var_dump($result);
        //echo $sql;

        if (!$result) {
            echo $this->conn->error;
            $result = FALSE;
        } else {
            $result = TRUE;
        }
        return $result;
    }


    public function update_chat_id($table_name, $update_arr, $where_arr) {
        $sql = "UPDATE $table_name SET ";
        $tmp_arr = [];
        foreach ($update_arr as $key => $data) {
            $tmp_arr[] = "$key='$data' ";
        }
        $tmp_arr = implode(",", $tmp_arr);
        $sql .= $tmp_arr;
        $sql .= " WHERE chat_id=" . $where_arr;
        $result = $this->conn->query($sql);
        //var_dump($result);
        //echo $sql;

        if (!$result) {
            echo $this->conn->error;
            $result = FALSE;
        } else {
            $result = TRUE;
        }
        return $result;
    }

}