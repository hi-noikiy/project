<?php
class unread extends getList {

        public function __construct(){
                $this->tableName = '_sys_admin';
                $this->key = 'id';
                $this->wheres = ' 1=1 ';
                $this->orders = 'id desc';
                $this->pageReNum = 15;
                $this->unread = '';
        }

        public function getUnreadStr($id){
        	$this->unread = $this->getInfo($id, 'unread','pass');
        }

        public function getUnreadList($id,$type=''){
        	$this->getUnreadStr($id);
                preg_match_all('/\?'.$type.'#([0-9]*)/',$this->unread,$out);
                return $out;
        }
}
?>