<?php

class Blacklists extends Database_worker
{


    public function save($publisher_site_str, $advertiser_id)
    {
        try {
            if (empty($advertiser_id)) {
                throw new Exception("Empty advertiser id", 2);
            }

            $check_status = $this->validate_string($publisher_site_str);
            if($check_status['status']!=0){
                throw new Exception("Error input string ".$check_status['message'],1);
            }

            $exploded = explode(",", $publisher_site_str);
            $publisher_id = preg_replace('/[^0-9]/', '', $exploded[0]);
            $site_id = preg_replace('/[^0-9]/', '', $exploded[1]);

            $exist_status = $this->check_publisher_exist($publisher_id);
            if ($exist_status['status'] != 0) {
                throw new Exception("Publisher error " . $exist_status['message'], 3);
            }

            $exist_status = $this->check_site_exist($site_id);
            if ($exist_status['status'] != 0) {
                throw new Exception("Site error " . $exist_status['message'], 3);
            }

            $exist_status = $this->check_advertiser_exist($advertiser_id);
            if ($exist_status['status'] != 0) {
                throw new Exception("Site error " . $exist_status['message'], 3);
            }

            $add_res = $this->insert("blacklists", [
                    "site_id" => $site_id,
                    "publisher_id" => $publisher_id,
                    "advertiser_id" => $advertiser_id
                ]
            );

            if(empty($add_res)){
                throw new Exception("Database add error",3);
            }

            $result = [
                "status" => 200,
                "message" => "Success inserted"
            ];
        } catch (Exception $ex) {
            $result = [
                "message" => $ex->getMessage(),
                "status" => $ex->getCode()
            ];
        }
        return $result;
    }

    public function validate_string($publisher_site_str){
        try{
            if (empty($publisher_site_str)) {
                throw new Exception("Empty balcklist string", 1);
            }

            $exploded = explode(",", $publisher_site_str);

            if (count($exploded) != 2) {
                throw new Exception("Blacklist format error", 3);
            }
            $publisher_id = trim($exploded[0]);
            if (empty($publisher_id)) {
                throw new Exception("Blacklist format error", 3);
            }

            if (stripos($publisher_id, "p") === FALSE || stripos($publisher_id, "p") !== 0 || strlen($publisher_id) === 1) {
                throw new Exception("Blacklist format error", 3);
            }


            $site_id = trim($exploded[1]);
            if (stripos($site_id, "s") === FALSE || stripos($site_id, "s") !== 0 || strlen($site_id) === 1) {
                throw new Exception("Blacklist format error", 3);
            }

            preg_replace('/[^0-9]/', '', $site_id);
            if (empty($site_id)) {
                throw new Exception("Blacklist format error", 3);
            }

            $result['status']=0;
        } catch (Exception $ex) {
            $result = [
                "message" => $ex->getMessage(),
                "status" => $ex->getCode()
            ];
        }
        return $result;
    }

    public function check_publisher_exist($publisher_id)
    {
        try {
            if (!is_numeric($publisher_id)) {
                throw new Exception("Format error", 1);
            }
            $sql = "SELECT id FROM publisher WHERE id =$publisher_id";
            $res = $this->do_sql($sql);
            if (empty($res)) {
                throw new Exception("Not exist", 2);
            }
            $result = [
                "status" => 0
            ];
        } catch (Exception $ex) {
            $result = [
                "message" => $ex->getMessage(),
                "status" => $ex->getCode()
            ];
        }
        return $result;

    }

    public function check_site_exist($site_id)
    {
        try {
            if (!is_numeric($site_id)) {
                throw new Exception("Format error", 1);
            }
            $sql = "SELECT id FROM sites WHERE id =$site_id";
            $res = $this->do_sql($sql);
            if (empty($res)) {
                throw new Exception("Not exist", 2);
            }
            $result = [
                "status" => 0
            ];
        } catch (Exception $ex) {
            $result = [
                "message" => $ex->getMessage(),
                "status" => $ex->getCode()
            ];
        }
        return $result;
    }

    public function check_advertiser_exist($advertiser_id)
    {
        try {
            if (!is_numeric($advertiser_id)) {
                throw new Exception("Format error", 1);
            }
            $sql = "SELECT id FROM advertisers WHERE id =$advertiser_id";
            $res = $this->do_sql($sql);
            if (empty($res)) {
                throw new Exception("Not exist", 2);
            }
            $result = [
                "status" => 0
            ];
        } catch (Exception $ex) {
            $result = [
                "message" => $ex->getMessage(),
                "status" => $ex->getCode()
            ];
        }
        return $result;
    }

    public function get($advertiser_id){
        try {
            if (!is_numeric($advertiser_id)) {
                throw new Exception("", 1);
            }
            $sql = "SELECT GROUP_CONCAT(CONCAT('p',publisher_id,',','s',site_id) SEPARATOR ',') as result 
                    FROM blacklists 
                    WHERE advertiser_id=$advertiser_id
                    GROUP BY advertiser_id";
            $res = $this->do_sql($sql);
            $content = "";
            if (!empty($res)) {
                $content = $res[0]->result;
            }

            $result = [
               "status" => 0,
               "content"=>$content
            ];
        } catch (Exception $ex) {
            $result = [
                "message" => $ex->getMessage(),
                "status" => $ex->getCode()
            ];
        }
        return $result;
    }
}