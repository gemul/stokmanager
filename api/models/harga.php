<?php
namespace models;

use sys\models;

class harga extends models {
    protected $tabel = "harga";
    function listAll(){
        return DB::run("SELECT * FROM harga where deleted is null")->fetchAll();
    }
}
?>