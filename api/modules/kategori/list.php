<?php
use models\etalase;
FloadModels(['etalase']);

$kategori = new etalase();

$return = Array();
$start= $_GET['start'];
$limit= $_GET['length'];

$cols=["idetalase","namaetalase"];

//global filter
$filter = [["deleted is null"]];
if(!empty($_GET['search']['value'])){
    $filter=[
        ["namaetalase","like","%". $_GET['search']['value']."%"]
    ];
}

//column filter
if (isset($_GET['columns'])) {
    foreach ($_GET['columns'] as $col) {
        if(!empty($col['search']['value'])){
            array_push($filter,[ $cols[$col['data']] . " like " . "'%" . $col['search']['value'] . "%'"]);
            // array_push($filter,[ $cols[$col['data']] , "like", "%" . $col['search']['value'] . "%"]);
        }
    }
}

//sorting
$sort=[];
if (isset($_GET['order'])) {
    foreach( $_GET['order'] as $ord){
        $sort[ $cols[$ord['column']] ] = $ord['dir'];

    }
}
$data = $kategori->get(["*"], $filter, $sort, $start, $limit);
$count = $kategori->countData($filter);
$countAll = $kategori->countData();

$fdata=Array();
foreach($data as $item){
    $fdata[]=[$item['idetalase'],$item['namaetalase'], "<div id='act-kategori-" . $item['idetalase'] . "'><button class='btn btn-primary btn-sm iface-edit' onclick='kategoriEdit(" . $item['idetalase'] . ")'><i class='fa fa-edit'></i></button> <button class='btn btn-warning btn-sm iface-delete' onclick='kategoriDelete(" . $item['idetalase'] . ")'><i class='fa fa-trash'></i></button></div> "];
}

$return['draw']=$_GET['draw'];
$return['recordsFiltered']=$count;
$return['recordsTotal']=$countAll;
$return['data']=$fdata;


echo json_encode($return);