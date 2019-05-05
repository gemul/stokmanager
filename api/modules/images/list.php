<?php
FloadModels(['images']);
use models\images;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
}else{
   //valid id
   $images = new images();
   $images->filterQuery([['idbarang', '=', $id]]);
   $data = $images->query(
                     "SELECT * FROM images 
                     INNER JOIN barang USING(idbarang)
                     " . $images->getFilter() . "
                     ",
                     $images->getData()
   )->fetchAll();
   $count=count($data);
   echo json_encode(['status'=> '1', 'message' => "success. ".$count." data returned" ,'data'=>$data]);
}