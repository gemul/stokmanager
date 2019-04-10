<?php
FloadModels(['barang']);
$barang=new barang();
var_dump(
    $barang->get([
            ['kodebarang','=','cbb']
        ]
    )
);