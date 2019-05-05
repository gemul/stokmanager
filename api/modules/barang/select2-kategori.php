<?php
FloadModels(['etalase']);
use models\etalase;

if( !isset($_GET['search']) ){
    $search="";
}else{
    $search=$_GET['search'];
}
//valid id
$kategori = new etalase();
$data = $kategori->get(['*'], [['namaetalase', 'like', "%".$search."%"]]);
$count = count($data);
$result=[];
foreach($data as $item){
    $result[]=[
        'id'=>$item['idetalase'],
        'text'=> $item['idetalase']." ".$item['namaetalase'],
    ];
}
echo json_encode(['status' => '1', 'pagination'=>['more'=>false], 'message' => "success. " . $count . " data returned", 'results' => $result]);
