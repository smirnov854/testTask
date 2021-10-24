<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once "Database_worker.php";
require_once "Blacklists.php";
$black_list = new Blacklists();
$input_dir = "./input_dir/";
$dir_handle = opendir($input_dir);


while (($file_name = readdir($dir_handle)) !== FALSE) {
    if ($file_name == "." || $file_name == "..") {
        continue;
    }
    $file_handle = fopen($input_dir.$file_name, "r");
    $buffer = "";
    $count = 0;
    while (($data = fgetc($file_handle)) !==FALSE) {
        if ($count == 2) {
            $res = $black_list->save(trim($buffer,","),rand(1,100));
            $buffer = "";
            $count = 0;
            continue;
        }
        $buffer .= $data;
        if ($data == ",") {
            $count++;
            continue;
        }
    }
    fclose($file_handle);
}

$result = $black_list->get(5);
if($result['status'] !=0){
    echo $result['message'];
}else{
    echo $result['content'];
}


