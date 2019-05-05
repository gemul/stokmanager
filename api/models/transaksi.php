<?php
namespace models;

use sys\models;

class transaksi extends models {
    protected $tabel = "transaksi";
    function listAll(){
        return DB::run("SELECT * FROM transaksi where deleted is null")->fetchAll();
    }
}
?>