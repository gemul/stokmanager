<?php
use models\mutasi;
FloadModels(['mutasi']);

$mutasi = new mutasi();

$return = Array();
$start= $_GET['start'];
$limit= $_GET['length'];

$cols=[ "waktu", "namabarang", "masuk","keluar","nominalmasuk","nominalkeluar"];

//global filter
$filter=[["mutasistok.deleted is null"]];
if(!empty($_GET['search']['value'])){
    $filter=[
        ["namamutasi","like","%". $_GET['search']['value']."%"]
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
$mutasi->filterQuery($filter);
$mutasi->sortQuery($sort);

$data = $mutasi->query("SELECT * FROM mutasistok 
                        INNER JOIN barang USING(idbarang)
                        INNER JOIN etalase USING(idetalase)
                     " . $mutasi->getFilter() . "
                     " . $mutasi->getSort() . " 
                     LIMIT $start , $limit
                     "
                     ,
                     $mutasi->getData()
                    );
$count = $mutasi->query("SELECT count(*) as num FROM mutasistok 
                        INNER JOIN barang USING(idbarang)
                        INNER JOIN etalase USING(idetalase)
                     " . $mutasi->getFilter() . "
                     "
                     ,
                     $mutasi->getData()
                    );
$count=$count->fetchAll()[0]['num'];
// $data = $mutasi->get(["*"], $filter, $sort, $start, $limit);
$countAll = $mutasi->countData();

$fdata=Array();
foreach($data as $item){
    $fdata[]=[$item['waktu'], $item['namabarang'], $item['masuk'], $item['keluar'], $item['nominalmasuk'], $item['nominalkeluar'], "<div id='act-mutasi-" . $item['idmutasistok'] . "'> <button class='btn btn-warning btn-sm iface-detail' onclick=\"loadPage('frontend/pages/detail-mutasi.php?id=" . $item['idmutasistok'] . "')\"><i class='fa fa-eye'></i></button> <button class='btn btn-primary btn-sm iface-edit' onclick='mutasiEdit(" . $item['idmutasistok'] . ")'><i class='fa fa-edit'></i></button> <button class='btn btn-warning btn-sm iface-delete' onclick='mutasiDelete(" . $item['idmutasistok'] . ")'><i class='fa fa-trash'></i></button></div> "];
}

$return['draw']=$_GET['draw'];
$return['recordsFiltered']=$count;
$return['recordsTotal']=$countAll;
$return['data']=$fdata;


echo json_encode($return);