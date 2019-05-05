<?php
FloadModels(['supplier']);
use models\supplier;

$supplier = new supplier();
$ins=$supplier->insert([ 
   'namasupplier' => $_POST['namasupplier'],
   'url' => $_POST['url'],
   'source' => $_POST['source'],
   ]);
if($ins->lastInsertId()){
   echo json_encode(['status'=>'1','message'=>"Supplier ditambahkan"]);
}else{
   echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
}