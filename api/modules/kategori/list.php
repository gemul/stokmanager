<?php
use models\etalase;
FloadModels(['etalase']);

$kategori = new etalase();

$return = Array();
$start=0;
$limit=10;
$data = $kategori->get(["*"], [], [], $start, $limit);
$count = $kategori->countData([]);
$countAll = $kategori->countData([]);

$fdata=Array();
foreach($data as $item){
    $fdata[]=[$item['idetalase'],$item['namaetalase'], "<div id='act-kategori-" . $item['idetalase'] . "'><button class='btn btn-primary btn-sm iface-edit' onclick='kategoriEdit(" . $item['idetalase'] . ")'><i class='fa fa-edit'></i></button> <button class='btn btn-warning btn-sm iface-delete' onclick='kategoriDelete(" . $item['idetalase'] . ")'><i class='fa fa-trash'></i></button></div> "];
}

$return['draw']=$_GET['draw'];
$return['recordsFiltered']=$count;
$return['recordsTotal']=$countAll;
$return['data']=$fdata;


echo json_encode($return);