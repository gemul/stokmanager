<?php
use models\supplier;
FloadModels(['supplier']);

$supplier = new supplier();

$return = Array();
$start= $_GET['start'];
$limit= $_GET['length'];

$cols=["idsupplier","namasupplier","url","source"];

//global filter
$filter = [["deleted is null"]];
if(!empty($_GET['search']['value'])){
    $filter=[
        ["namasupplier","like","%". $_GET['search']['value']."%"]
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
$data = $supplier->get(["*"], $filter, $sort, $start, $limit);
$count = $supplier->countData($filter);
$countAll = $supplier->countData();

$fdata=Array();
foreach($data as $item){
    $fdata[]=[$item['idsupplier'], $item['namasupplier'], $item['url'], $item['source'], "<div id='act-supplier-" . $item['idsupplier'] . "'><button class='btn btn-primary btn-sm iface-edit' onclick='supplierEdit(" . $item['idsupplier'] . ")'><i class='fa fa-edit'></i></button> <button class='btn btn-warning btn-sm iface-delete' onclick='supplierDelete(" . $item['idsupplier'] . ")'><i class='fa fa-trash'></i></button></div> "];
}

$return['draw']=$_GET['draw'];
$return['recordsFiltered']=$count;
$return['recordsTotal']=$countAll;
$return['data']=$fdata;


echo json_encode($return);