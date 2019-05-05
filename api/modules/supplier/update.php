<?php
FloadModels(['supplier']);
use models\supplier;

$id = abs($_POST['idsupplier']);
if ($id == 0) {
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //data
   $namasupplier = $_POST['namasupplier'];
   $url = $_POST['url'];
   $source = $_POST['source'];
   //update
   $supplier = new supplier();
   $update=$supplier->update([
            'namasupplier' => $namasupplier,
            'url' => $url,
            'source' => $source,
         ],[['idsupplier','=',$id]]);
   if($update){
      echo json_encode(['status'=>'1','message'=>"Supplier diupdate"]);
   }else{
      echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
   }
}