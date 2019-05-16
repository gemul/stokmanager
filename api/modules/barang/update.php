<?php
FloadModels(['barang']);
use models\barang;

$id = abs($_POST['idbarang']);
if ($id == 0) {
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //data
   $idetalase = $_POST['idetalase'];
   $namabarang = $_POST['namabarang'];
   $kodebarang = $_POST['kodebarang'];
   $catatan = $_POST['catatan'];
   $berat = $_POST['berat'];
   $deskripsi = $_POST['deskripsi'];
   $statusbarang = $_POST['statusbarang'];
   //update
   $kategori = new barang();
   $update=$kategori->update(
      [ 
         'idetalase' => $idetalase ,
         'namabarang' => $namabarang ,
         'kodebarang' => $kodebarang ,
         'catatan' => $catatan ,
         'berat' => $berat ,
         'deskripsi' => $deskripsi ,
         'statusbarang' => $statusbarang ,
      ],
      [['idbarang','=',$id]]
   );
   if($update){
      echo json_encode(['status'=>'1','message'=>"Kategori diupdate"]);
   }else{
      echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
   }
}