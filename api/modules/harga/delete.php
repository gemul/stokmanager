<?php
FloadModels(['harga']);
use models\harga;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //valid id
   $kategori = new harga();
   $data=$kategori->softDelete([['idharga','=',$id]]);
   echo json_encode(['status'=> '1', 'message' => "success. data deleted"]);
}