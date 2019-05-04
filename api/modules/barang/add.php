<?php
FloadModels(['barang']);
use models\barang;

$barang = new barang();
$ins=$barang->insert([ 
         'idetalase' => $_POST['idetalase'],
         'namabarang' => $_POST['namabarang'],
         'kodebarang' => $_POST['kodebarang'],
         'statusbarang' => $_POST['statusbarang'],
      ]);
if($ins->lastInsertId()){
   echo json_encode(['status'=>'1','message'=>"Barang ditambahkan"]);
}else{
   echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
}