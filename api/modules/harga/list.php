<?php
use models\harga;
FloadModels(['harga']);

$harga = new harga();

$return = Array();
$start= $_GET['start'];
$limit= $_GET['length'];

$cols=[ "idharga", "namabarang", "namasupplier","nominalharga"];

//global filter
$filter=[["harga.deleted is null"]];
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
$harga->filterQuery($filter);
$harga->sortQuery($sort);

$data = $harga->query("SELECT * FROM harga 
                        INNER JOIN barang USING(idbarang)
                        INNER JOIN supplier USING(idsupplier)
                     " . $harga->getFilter() . "
                     " . $harga->getSort() . " 
                     LIMIT $start , $limit
                     "
                     ,
                     $harga->getData()
                    );
$count = $harga->query("SELECT count(*) as num FROM harga 
                        INNER JOIN barang USING(idbarang)
                        INNER JOIN supplier USING(idsupplier)
                     " . $harga->getFilter() . "
                     "
                     ,
                     $harga->getData()
                    );
$count=$count->fetchAll()[0]['num'];
// $data = $harga->get(["*"], $filter, $sort, $start, $limit);
$countAll = $harga->countData();

$fdata=Array();
foreach($data as $item){
    $fdata[]=[$item['idharga'], $item['namabarang'], $item['namasupplier'], $item['nominalharga'], "<div id='act-harga-" . $item['idharga'] . "'><button class='btn btn-primary btn-sm iface-edit' onclick='hargaEdit(" . $item['idharga'] . ")'><i class='fa fa-edit'></i></button> <button class='btn btn-warning btn-sm iface-delete' onclick='hargaDelete(" . $item['idharga'] . ")'><i class='fa fa-trash'></i></button></div> "];
}

$return['draw']=$_GET['draw'];
$return['recordsFiltered']=$count;
$return['recordsTotal']=$countAll;
$return['data']=$fdata;


echo json_encode($return);