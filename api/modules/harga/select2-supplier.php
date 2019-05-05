<?php
FloadModels(['supplier']);
use models\supplier;

if( !isset($_GET['search']) ){
    $search="";
}else{
    $search=$_GET['search'];
}
//valid id
$kategori = new supplier();
$data = $kategori->get(['*'], [['namasupplier', 'like', "%".$search."%"], ['deleted is null']]);
$count = count($data);
$result=[];
foreach($data as $item){
    $result[]=[
        'id'=>$item['idsupplier'],
        'text'=> $item['idsupplier']." ".$item['namasupplier'],
    ];
}
echo json_encode(['status' => '1', 'pagination'=>['more'=>false], 'message' => "success. " . $count . " data returned", 'results' => $result]);
