<?php
FloadModels(['etalase']);
use models\etalase;

$kategori = new etalase();
$ins=$kategori->insert([ 'namaetalase' => $_POST['namaetalase']]);
if($ins->lastInsertId()){
   echo json_encode(['status'=>'1','message'=>"Kategori ditambahkan"]);
}else{
   echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
}