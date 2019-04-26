<?php
use model\etalase;
include "../../sys/model.php";
// FloadModels(['etalase']);

$kategori = new etalase();
echo json_encode($kategori->get() );
// echo json_encode($kategori->countData(['idetalase is not null']));
