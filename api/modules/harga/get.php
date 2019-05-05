<?php
FloadModels(['harga']);
use models\harga;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
}else{
   //valid id
   $harga = new harga();
   $harga->filterQuery([['idharga', '=', $id]]);
   $data = $harga->query(
                     "SELECT * FROM harga 
                     INNER JOIN barang USING(idbarang)
                     INNER JOIN supplier USING(idsupplier)
                     " . $harga->getFilter() . "
                     ",
      $harga->getData()
   )->fetchAll();
   $count=count($data);
   echo json_encode(['status'=> '1', 'message' => "success. ".$count." data returned" ,'data'=>$data]);
}