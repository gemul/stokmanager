<?php
namespace models;

use sys\models;

class mutasi extends models {
    protected $tabel = "mutasistok";
    function listAll(){
        return DB::run("SELECT * FROM mutasistok where deleted is null")->fetchAll();
    }
}
?>