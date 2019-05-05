<?php
use models\barang;
FloadModels(['barang']);

$barang = new barang();

$return = Array();
$start= $_GET['start'];
$limit= $_GET['length'];

$cols=[ "idbarang", "namaetalase", "namabarang","kodebarang","statusbarang"];

//global filter
$filter=[["barang.deleted is null"]];
if(!empty($_GET['search']['value'])){
    $filter=[
        ["namabarang","like","%". $_GET['search']['value']."%"]
    ];
}

//column filter
if (isset($_GET['columns'])) {
    foreach ($_GET['columns'] as $col) {
        if(!empty($col['search']['value'])){
            // array_push($filter,[ $cols[$col['data']] . " like " . "'%" . $col['search']['value'] . "%'"]);
            array_push($filter,[ $cols[$col['data']] , "like", "%" . $col['search']['value'] . "%"]);
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
$barang->filterQuery($filter);
$barang->sortQuery($sort);

$data = $barang->query("SELECT * FROM barang 
                        INNER JOIN etalase USING(idetalase)
                     " . $barang->getFilter() . "
                     " . $barang->getSort() . " 
                     LIMIT $start , $limit
                     "
                     ,
                     $barang->getData()
                    );
$count = $barang->query("SELECT count(*) as num FROM barang 
                        INNER JOIN etalase USING(idetalase)
                     " . $barang->getFilter() . "
                     "
                     ,
                     $barang->getData()
                    );
$count=$count->fetchAll()[0]['num'];
// $data = $barang->get(["*"], $filter, $sort, $start, $limit);
$countAll = $barang->countData();

$fdata=Array();
foreach($data as $item){
    $fdata[]=[$item['idbarang'], $item['namaetalase'], $item['namabarang'], $item['kodebarang'], $item['statusbarang'], "<div id='act-barang-" . $item['idbarang'] . "'> <button class='btn btn-warning btn-sm iface-detail' onclick=\"loadPage('frontend/pages/detail-barang.php?id=" . $item['idbarang'] . "')\"><i class='fa fa-eye'></i></button> <button class='btn btn-primary btn-sm iface-edit' onclick='barangEdit(" . $item['idbarang'] . ")'><i class='fa fa-edit'></i></button> <button class='btn btn-warning btn-sm iface-delete' onclick='barangDelete(" . $item['idbarang'] . ")'><i class='fa fa-trash'></i></button></div> "];
}

$return['draw']=$_GET['draw'];
$return['recordsFiltered']=$count;
$return['recordsTotal']=$countAll;
$return['data']=$fdata;


echo json_encode($return);