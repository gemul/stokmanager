<?php
function FloadModels($models){
    foreach($models as $model){
        if(file_exists("models/".$model.".php")){
            include("models/".$model.".php");
        }else{
            die("model $model not found");
        }
    }
}