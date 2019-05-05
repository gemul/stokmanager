<?php
FloadModels(['harga']);
use models\harga;

$id = abs($_POST['idharga']);
if ($id == 0) {
   //invalid id
   echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
   //data
   $idbarang = $_POST['idbarang'];
   $idsupplier = $_POST['idsupplier'];
   $nominalharga = $_POST['nominalharga'];
   //update
   $kategori = new harga();
   $update=$kategori->update(
      [ 
         'idbarang' => $idbarang ,
         'idsupplier' => $idsupplier ,
         'nominalharga' => $nominalharga ,
      ],
      [['idharga','=',$id]]
   );
   if($update){
      echo json_encode(['status'=>'1','message'=>"Kategori diupdate"]);
   }else{
      echo json_encode(['status'=>'2','message'=>"Kesalahan saat menyimpan"]);
   }
}