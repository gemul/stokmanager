<?php
use models\etalase;
FloadModels(['etalase']);

$kategori = new etalase();
echo json_encode($kategori->get() );
// echo json_encode($kategori->countData(['idetalase is not null']));
