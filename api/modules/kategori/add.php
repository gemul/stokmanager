<?php
FloadModels(['etalase']);

$kategori = new etalase();
$kategori->insert([ 'a' => 'b', 'c' => 'd']);