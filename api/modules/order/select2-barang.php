<?php
FloadModels(['barang']);
use models\barang;

if( !isset($_GET['search']) ){
    $search="";
}else{
    $search=$_GET['search'];
}
//valid id
$barang = new barang();
$data = $barang->get(['*'], [
    ['namabarang', 'like', "%".$search."%"],
    ['deleted is null'],
    ]);
$count = count($data);
$result=[];
foreach($data as $item){
    $result[]=[
        'id'=>$item['idbarang'],
        'text'=> $item['idbarang']." ".$item['namabarang'],
    ];
}
echo json_encode(['status' => '1', 'pagination'=>['more'=>false], 'message' => "success. " . $count . " data returned", 'results' => $result]);
