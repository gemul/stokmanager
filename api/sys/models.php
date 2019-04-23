<?php
class models {
    protected $tabel = "";
    function get($filters,$start=0,$limit=512){
        $filterCount=count($filters);
        $filter=($filterCount==0)?"":" AND ";
        $i=1;
        foreach($filters as $cond){
            $filter.=" ".$cond[0]." ".$cond[1]." '".$cond[2]."' ";
            $filter.=($i<$filterCount)?" AND ":"";
            $i++;
        }
        return DB::run("SELECT * FROM ".$this->tabel." 
            where deleted is null
            $filter
            "
            )->fetchAll();
    }
}