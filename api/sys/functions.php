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

function FprintLabel($id=0){

    //DATA
    $data = array(
        'sku' => "EMB0142",
        'id' => "000089284718",
        'name' => "Ard.Nano v3 CH350",
        'desc' => "Arduino Nano v3 5v clone with CH350 usb chipset and mini USB interface",
    );

    //print
    include('printer/template_label.php');
}