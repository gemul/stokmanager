<?php
namespace sys;
use sys\DBC;

class models extends DBC{
    protected $tabel = "";
    public function get($cols=[],$filters=[],$sorting=[],$start=0,$limit=512){
        $filterCount=count($filters);
        $filter=($filterCount==0)?"":" WHERE ";
        $i=1;
        $filterVal=Array();
        foreach($filters as $cond){
            if(count($cond)==1){
                $filter.=" ".$cond[0]." ";
            }else{
                $filter.=" ".$cond[0]." ".$cond[1]." :".$cond[0]." ";
                $filterVal[ $cond[0] ] = $cond[2];
            }
            $filter.=($i<$filterCount)?" AND ":"";
            $i++;
        }

        $i = 1;
        $sortCount = count($sorting);
        $sort = ($sortCount == 0) ? "" : " ORDER BY ";
        foreach($sorting as $col=>$srt){
            $sort .= $col . " " . $srt ;
            $sort .= ($i < $sortCount) ? " , " : "";
            $i++;
        }

        $i = 1;
        $columnCount = count($cols);
        $column = ($columnCount == 0) ? " * " : "";
        foreach($cols as $col){
            $column .= $col ;
            $column .= ($i < $columnCount) ? " , " : "";
            $i++;
        }
        
        
        $stmt= $this->dbc->prepare(
            "SELECT $column FROM " . $this->tabel . " 
            $filter
            $sort
            LIMIT
            $start , $limit
            "
        );
        $stmt->execute($filterVal);
        return $stmt->fetchAll();
    }
    public function countData($filters=[]){
        $res=$this->get(['count(*) as cnt'],$filters);
        return $res[0]['cnt'];
    }
    public function insert($data){
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
        return $this->dbc;
    }
    public function update($formdata,$filters){
        $query="UPDATE ".$this->tabel." SET ";
        $dataval="";
        foreach($formdata as $key=>$val){
            $dataval.="`".$key. "`=:" . $key . ",";
            $data[$key]=$val;
        }
        $dataval=substr($dataval,0,-1)."";

        $filterCount = count($filters);
        if($filterCount == 0){
            return json_encode(['status'=>'DBERR','message'=>'Update need at least 1 condition.']);
            exit;
        }else{
            $filter = ($filterCount == 0) ? "" : " WHERE ";
            $i = 1;
            $filterVal = array();
            foreach ($filters as $cond) {
                if (count($cond) == 1) {
                    $filter .= " " . $cond[0] . " ";
                } else {
                    $filter .= " " . $cond[0] . " " . $cond[1] . " :" . $cond[0] . " ";
                    $data[$cond[0]] = $cond[2];
                }
                $filter .= ($i < $filterCount) ? " AND " : "";
                $i++;
            }
        }

        $query.= $dataval . $filter;

        $stmt = $this->dbc->prepare($query);
        $stmt->execute($data);
        return $this->dbc;
    }
    public function delete($filters)
    {
        $query = "DELETE FROM " . $this->tabel . " ";

        $filterCount = count($filters);
        if ($filterCount == 0) {
            return json_encode(['status' => 'DBERR', 'message' => 'Update need at least 1 condition.']);
        } else {
            $filter = ($filterCount == 0) ? "" : " WHERE ";
            $i = 1;
            $filterVal = array();
            foreach ($filters as $cond) {
                if (count($cond) == 1) {
                    $filter .= " " . $cond[0] . " ";
                } else {
                    $filter .= " " . $cond[0] . " " . $cond[1] . " :" . $cond[0] . " ";
                    $data[$cond[0]] = $cond[2];
                }
                $filter .= ($i < $filterCount) ? " AND " : "";
                $i++;
            }
            $query .= $filter;

            $stmt = $this->dbc->prepare($query);
            $stmt->execute($data);
            return $this->dbc;
        }

    }
    function query($query,$data){
        $stmt = $this->dbc->prepare($query);
        $stmt->execute($data);
        return $stmt;
    }
}