<?php
FloadModels(['barang']);
use models\barang;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //valid id
   $kategori = new barang();
   $data=$kategori->softDelete([['idbarang','=',$id]]);
   echo json_encode(['status'=> '1', 'message' => "success. data deleted"]);
}