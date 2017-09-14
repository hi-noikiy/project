<?php 
class job extends getList {
	 
     public function __construct(){
             $this->tableName = '_web_job';
             $this->key = 'id';
             $this->wheres = "1";
             
             $this->orders = 'id desc';
             $this->pageReNum = 15;
     }     
}
?>