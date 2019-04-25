<?php
class etalase extends models {
    protected $tabel = "etalase";
    function listAll(){
        return DB::run("SELECT * FROM etalase where deleted is null")->fetchAll();
    }
}
?>