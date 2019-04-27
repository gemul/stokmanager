<?php
namespace models;

use sys\models;

class user extends models {
    protected $tabel = "user";
    function listAll(){
        return DB::run("SELECT * FROM barang where deleted is null")->fetchAll();
    }
}
?>