<?php
use models\etalase;
FloadModels(['etalase']);

$kategori = new etalase();

$return = Array();
$data = $kategori->get();

$fdata=Array();
foreach($data as $item){
    $fdata[]=[$item['idetalase'],$item['namaetalase'], "<button class='btn btn-primary btn-sm iface-edit' onclick='kategoriEdit(" . $item['idetalase'] . ")'><i class='fa fa-edit'></i></button> <button class='btn btn-warning btn-sm iface-delete' onclick='kategoriDelete(" . $item['idetalase'] . ")'><i class='fa fa-trash'></i></button> "];
}

$return['draw']=$_GET['draw'];
$return['recordsTotal']=1;
$return['recordsFiltered']=1;
$return['data']=$fdata;


echo json_encode($return);