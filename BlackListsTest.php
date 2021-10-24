<?php
require_once "./vendor/autoload.php";
require_once "Database_worker.php";
require_once "Blacklists.php";

class BlackListsTest extends PHPUnit\Framework\TestCase
{
    public function test_validate_string(){
        $black_list = new Blacklists();
        $params = [
            ["got"=>"p1,s1", "want"=>["status"=>0]],
            ["got"=>"p22,s32", "want"=>["status"=>0]],
            ["got"=>"p2,p1", "want"=>["status"=>3,"message"=>"Blacklist format error"]],
            ["got"=>"s1,s1", "want"=>["status"=>3,"message"=>"Blacklist format error"]],
            ["got"=>"p,s1", "want"=>["status"=>3,"message"=>"Blacklist format error"]],
            ["got"=>"p1,s", "want"=>["status"=>3,"message"=>"Blacklist format error"]],
        ];
        foreach($params as $row){
            $this->assertEquals($row['want'],  $black_list->validate_string($row["got"]));
        }
    }

    public function test_get(){
        $black_list = new Blacklists();
        $params = [
            ["got"=>5, "want"=>['status'=>0,"content"=>'p48,s21,p42,s48']],
            ["got"=>1000, "want"=>['status'=>0,"content"=>""]],
        ];
        foreach($params as $row){
            $this->assertEquals($row['want'],  $black_list->get($row["got"]));
        }
    }

    public function test_check_advertiser_exist(){
        $black_list = new Blacklists();
        $params = [
            ["got"=>5, "want"=>['status'=>0]],
            ["got"=>1000, "want"=>['status'=>2,"message"=>"Not exist"]],
            ["got"=>"abc", "want"=>['status'=>1,"message"=>"Format error"]],
        ];

        foreach($params as $row){
            $this->assertEquals($row['want'],  $black_list->check_advertiser_exist($row["got"]));
        }
    }

    public function test_check_publisher_exist(){
        $black_list = new Blacklists();
        $params = [
            ["got"=>5, "want"=>['status'=>0]],
            ["got"=>1000, "want"=>['status'=>2,"message"=>"Not exist"]],
            ["got"=>"abc", "want"=>['status'=>1,"message"=>"Format error"]],
        ];

        foreach($params as $row){
            $this->assertEquals($row['want'],  $black_list->check_publisher_exist($row["got"]));
        }
    }




}