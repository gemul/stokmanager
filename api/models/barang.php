<?php
namespace models;

use sys\models;

class barang extends models {
    protected $tabel = "barang";
    function listAll(){
        return DB::run("SELECT * FROM barang where deleted is null")->fetchAll();
    }
}
?>