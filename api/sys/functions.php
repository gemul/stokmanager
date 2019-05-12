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

function Fuser(){
    
}

function FprintLabel($data){
    //print
    include('printer/template_label.php');
}