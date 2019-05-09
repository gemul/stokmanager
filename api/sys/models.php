<?php
namespace sys;
use sys\DBC;

/**
 * model for database wrapper
 *
 * @copyright  2019 Gema Ulama Putra
 * @version    Release: @package_version@
 * @link       http://github.com/gemul/stokmanager
 */ 
class models extends DBC{
    protected $tabel = "";
    public $data=[];
    public $filter="";
    public $sort="";

    /**
     * Get records
     *
     * @param Array   $cols Associative array of select columns
     * @param Array   $filters Associative array of filters
     * @param Array   $sorting Associative array of sort(order)
     * @param Integer   $start start of record
     * @param Integer   $limit number of record
     * 
     * @throws PDO_Exception 
     * @author Gema Ulama Putra <gemul.putra@gmail.com>
     * @return Array Array containing records
     */
    public function get($cols=[],$filters=[],$sorting=[],$start=0,$limit=512){
        $filterCount=count($filters);
        $filter=($filterCount==0)?"":" WHERE ";
        $i=1;
        $filterVal=Array();
        foreach($filters as $cond) {
            //glue
            if( $i > 1 ){
                $filter .= " AND ";
            }

            //condition
            if(count($cond)==1){
                $filter.=" ".$cond[0]." ";
            }else{
                $filter.=" ".$cond[0]." ".$cond[1]." :".$cond[0]." ";
                $filterVal[ $cond[0] ] = $cond[2];
            }
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

    //filter getter setter
    public function filterQuery( $filters = []){
        $filterCount = count($filters);
        $filter = ($filterCount == 0) ? "" : " WHERE ";
        $i = 1;
        $filterVal = array();
        foreach ($filters as $cond) {
            //glue
            if ($i > 1) {
                $filter .= " AND ";
            }

            //condition
            if (count($cond) == 1) {
                $filter .= " " . $cond[0] . " ";
            } else {
                $filter .= " " . $cond[0] . " " . $cond[1] . " :" . $cond[0] . " ";
                $filterVal[$cond[0]] = $cond[2];
            }
            $i++;
        }
        $this->addData($filterVal);
        $this->filter=$filter;
    }
    public function getFilter(){
        return $this->filter;
    }

    //data getter setter
    public function setData($data){
        $this->data = $data;
    }
    public function addData($data){
        $this->data = array_merge($this->data,$data);
    }
    public function clearData(){
        $this->data=[];
    }
    public function removeData($key){
        unset( $this->data[$key] );
    }
    public function getData(){
        return $this->data;
    }

    //sort getter setter
    public function sortQuery( $sorting = []){
        $i = 1;
        $sortCount = count($sorting);
        $sort = ($sortCount == 0) ? "" : " ORDER BY ";
        foreach ($sorting as $col => $srt) {
            $sort .= $col . " " . $srt;
            $sort .= ($i < $sortCount) ? " , " : "";
            $i++;
        }
        $this->sort=$sort;
    }
    public function getSort(){
        return $this->sort;
    }

    public function countData($filters=[]){
        $res=$this->get(['count(*) as cnt'],$filters);
        return $res[0]['cnt'];
    }

    /**
     * Insert record
     *
     * @param Array   $data Associative array for data
     * 
     * @throws PDO_Exception 
     * @author Gema Ulama Putra <gemul.putra@gmail.com>
     * @return dbc
     */
    public function insert($data){
        $query="INSERT INTO ".$this->tabel." ";
        $cols="(";
        $values="(";
        foreach($data as $key=>$val){
            $cols.="`".$key."`,";
            if($key=="NULL"){
                $values.="NULL,";
            }elseif($key=="NOW()"){
                $values.="NOW(),";
            }else{
                $values.=":".$key.",";
            }
        }
        $cols=substr($cols,0,-1).")";
        $values=substr($values,0,-1).")";
        $query.= $cols . " VALUES " . $values;

        $stmt = $this->dbc->prepare($query);
        $stmt->execute($data);
        return $this->dbc;
    }

    /**
     * Update record
     *
     * @param Array   $formdata Associative array of form data
     * @param Array   $filters Associative array of filters
     * 
     * @throws PDO_Exception 
     * @author Gema Ulama Putra <gemul.putra@gmail.com>
     * @return dbc
     */ 
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
                //glue
                if ($i > 1) {
                    $filter .= " AND ";
                }

                //condition
                if (count($cond) == 1) {
                    $filter .= " " . $cond[0] . " ";
                } else {
                    $filter .= " " . $cond[0] . " " . $cond[1] . " :" . $cond[0] . " ";
                    $data[$cond[0]] = $cond[2];
                }
                $i++;
            }
        }

        $query.= $dataval . $filter;

        $stmt = $this->dbc->prepare($query);
        $stmt->execute($data);
        return $this->dbc;
    }

    /**
     * Remove record
     *
     * @param Array   $filters Associative array of filters
     * 
     * @throws PDO_Exception 
     * @author Gema Ulama Putra <gemul.putra@gmail.com>
     * @return dbc
     */
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
                //glue
                if ($i > 1) {
                    $filter .= " AND ";
                }

                //condition

                if (count($cond) == 1) {
                    $filter .= " " . $cond[0] . " ";
                } else {
                    $filter .= " " . $cond[0] . " " . $cond[1] . " :" . $cond[0] . " ";
                    $data[$cond[0]] = $cond[2];
                }
                $i++;
            }
            $query .= $filter;

            $stmt = $this->dbc->prepare($query);
            $stmt->execute($data);
            return $this->dbc;
        }
    }

    /**
     * Soft delete record
     *
     * @param Array   $filters Associative array of filters
     * 
     * @throws PDO_Exception 
     * @author Gema Ulama Putra <gemul.putra@gmail.com>
     * @return dbc
     */
    public function softDelete($filters)
    {
        $query = "UPDATE " . $this->tabel . " SET deleted = now() ";

        $filterCount = count($filters);
        if ($filterCount == 0) {
            return json_encode(['status' => 'DBERR', 'message' => 'Update need at least 1 condition.']);
        } else {
            $filter = ($filterCount == 0) ? "" : " WHERE ";
            $i = 1;
            $filterVal = array();
            foreach ($filters as $cond) {
                //glue
                if ($i > 1) {
                    $filter .= " AND ";
                }

                //condition

                if (count($cond) == 1) {
                    $filter .= " " . $cond[0] . " ";
                } else {
                    $filter .= " " . $cond[0] . " " . $cond[1] . " :" . $cond[0] . " ";
                    $data[$cond[0]] = $cond[2];
                }
                $i++;
            }
            $query .= $filter;

            $stmt = $this->dbc->prepare($query);
            $stmt->execute($data);
            return $this->dbc;
        }
    }

    /**
     * Do a database query
     *
     * @param String   $query Query string
     * @param Array   $data Associative array of data for execution
     * 
     * @throws PDO_Exception 
     * @author Gema Ulama Putra <gemul.putra@gmail.com>
     * @return dbc
     */
    function query($query,$data){
        $stmt = $this->dbc->prepare($query);
        $stmt->execute($data);
        return $stmt;
    }
}