<?php
session_start();
header('Content-Type: application/json');
require_once("sys/config.php");
require_once("sys/db.php");
require_once("sys/models.php");
require_once("sys/functions.php");
if(isset($_GET['mod'])){
    if($_GET['mod']==""){
        echo json_encode(["status"=>"0","message"=>"undefined module"]);
    }else{
        if(strpos($_GET['mod'],"/")===false){
            //get module
            $modulepath=explode(".",$_GET['mod'],4);
            $modulepath="modules/".implode("/",$modulepath).".php";
            if(file_exists($modulepath)){
                include($modulepath);
            }else{
                echo json_encode(["status"=>"0","message"=>"module not found"]);
            }
        }else{
            echo json_encode(["status"=>"0","message"=>"invalid module name"]);
        }
    }
}else{
    echo json_encode(["status"=>"0","message"=>"no module specified"]);
}
?>