<?php
FloadModels(['barang']);
use models\barang;
if(
   !isset( $_POST['idetalase'] )
){
   echo json_encode(['status' => '2', 'message' => "Data salah"]);
   return false;
}
$barang = new barang();
$ins=$barang->insert([ 
         'idetalase' => $_POST['idetalase'],
         'namabarang' => $_POST['namabarang'],
         'kodebarang' => $_POST['kodebarang'],
         'catatan' => $_POST['catatan'],
         'statusbarang' => $_POST['statusbarang'],
      ]);
if($ins->lastInsertId()){
   echo json_encode(['status'=>'1','message'=>"Barang ditambahkan"]);
}else{
   echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
}