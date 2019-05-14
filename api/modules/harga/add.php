<?php
FloadModels(['harga']);
use models\harga;

$harga = new harga();
$ins=$harga->insert([ 
         'idbarang' => $_POST['idbarang'],
         'idsupplier' => $_POST['idsupplier'],
         'nominalharga' => $_POST['nominalharga'],
         'urlharga' => $_POST['urlharga'],
      ]);
if($ins->lastInsertId()){
   echo json_encode(['status'=>'1','message'=>"Harga ditambahkan"]);
}else{
   echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
}