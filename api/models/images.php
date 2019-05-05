<?php
namespace models;

use sys\models;

class images extends models {
    protected $tabel = "images";
    function listAll(){
        return DB::run("SELECT * FROM images where deleted is null")->fetchAll();
    }
}
?>