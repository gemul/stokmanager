<?php
FloadModels(['barang']);
use models\barang;
$id=abs($_GET['id']);
if($id==0){
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
}else{
   //valid id
   $barang = new barang();
   $barang->filterQuery([['idbarang', '=', $id]]);
   $data = $barang->query(
                     "SELECT * FROM barang 
                     INNER JOIN etalase USING(idetalase)
                     " . $barang->getFilter() . "
                     ",
      $barang->getData()
   )->fetchAll();
   $count=count($data);
   echo json_encode(['status'=> '1', 'message' => "success. ".$count." data returned" ,'data'=>$data]);
}