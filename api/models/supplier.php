<?php
namespace models;

use sys\models;

class supplier extends models {
    protected $tabel = "supplier";
    function listAll(){
        return DB::run("SELECT * FROM supplier where deleted is null")->fetchAll();
    }
}
?>