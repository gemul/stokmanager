<?php
FloadModels(['images']);
use models\images;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //valid id
   $images = new images();
   $data= $images->get(['*'],[['idimages', '=', $id]]);
   $images->delete([['idimages','=',$id]]);
   echo json_encode(['status'=> '1', 'message' => "success. data deleted"]);
   unlink( "../contents/product/".$data[0]['filename']);
}