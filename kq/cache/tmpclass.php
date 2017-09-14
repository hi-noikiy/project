<?php
class leave_filing extends getList {
		var $errMsg='';
		
        public function __construct(){
                $this->tableName = 'leave_filing';
                $this->key = 'id';
                $this->wheres = '1';
                $this->orders = 'id';
                $this->pageReNum = 15;
        }
}
?>