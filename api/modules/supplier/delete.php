<?php
FloadModels(['supplier']);
use models\supplier;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //valid id
   $supplier = new supplier();
   $data=$supplier->softDelete([['idsupplier','=',$id]]);
   echo json_encode(['status'=> '1', 'message' => "success. data deleted"]);
}