<?php
FloadModels(['etalase']);
use models\etalase;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
}else{
   //valid id
   $kategori = new etalase();
   $data=$kategori->get(['*'],[['idetalase','=',$id]]);
   $count=count($data);
   echo json_encode(['status'=> '1', 'message' => "success. ".$count." data returned" ,'data'=>$data]);
}