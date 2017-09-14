<?php
class department extends getList {
    public function __construct(){
            $this->tableName = '_web_department';
            $this->key = 'id';
            $this->wheres="1=1";
            $this->orders = 'id desc';
            $this->pageReNum = 15;
    }
    public function hasSub($id){
    	global $webdb;
    	$rs=$webdb->getValue("select count(*) as total from ".$this->tableName." where pid=".$id);
    	return $rs["total"];
    }
    
    public function setKw($array){
     		if($array['name'])
     		$this->wheres .= " and name like '%".$array['name']."%' ";
     }
}

?>