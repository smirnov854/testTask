<?php
require_once "Database_worker.php";
$db = new Database_worker();
for($i=1;$i<100;$i++){
    $db->insert("advertisers",["id"=>$i,"description"=>"Advertiser ".$i]);
    $db->insert("publisher",["id"=>$i,"description"=>"Publisher ".$i]);
    $db->insert("sites",["id"=>$i,"description"=>"Site ".$i]);
}
$file_handle = fopen("./input_dir/1.txt","w");

for($i=0;$i<100;$i++){
    fwrite($file_handle,"p".rand(1,100).", s".rand(1,100).", ");
}
fclose($file_handle);