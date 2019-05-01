<?php
FloadModels(['etalase']);
use models\etalase;

$id = abs($_POST['idetalase']);
if ($id == 0) {
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //data
   $namaetalase = $_POST['namaetalase'];
   //update
   $kategori = new etalase();
   $update=$kategori->update([ 'namaetalase' => $namaetalase ],[['idetalase','=',$id]]);
   if($update){
      echo json_encode(['status'=>'1','message'=>"Kategori diupdate"]);
   }else{
      echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
   }
}