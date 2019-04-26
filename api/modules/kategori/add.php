<?php
FloadModels(['etalase']);

$kategori = new etalase();
$ins=$kategori->insert([ 'namaetalase' => 'test1']);
var_dump( $ins->lastInsertId() );