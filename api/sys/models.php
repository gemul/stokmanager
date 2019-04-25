<?php
class models extends DB{
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
    function insert($data){
        $query="INSERT INTO ".$this->tabel." ";
        $cols="(";
        $values="(";
        foreach($data as $key=>$val){
            $cols.="`".$key."`,";
            $values.=":".$key.",";
        }
        $cols=substr($cols,0,-1).")";
        $values=substr($values,0,-1).")";
        $query.= $cols . " VALUES " . $values;

        $stmt = $this->dbc->prepare($query);
        $stmt->execute($data);
    }
}