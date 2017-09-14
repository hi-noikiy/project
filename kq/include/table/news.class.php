<?php
class news extends getList {
		
        public function __construct(){
                $this->tableName = '_web_news';
                $this->key = 'id';
                $this->wheres = $this->tableName.'.systag=0';
                $this->orders = 'descno desc';
                $this->pageReNum = 15;
                $this->eventFuncName = 'showNews';
        }
        public function setKw($_GET){
        	$this->setNT($_GET["ntype"]);
        }
        public function setTab($tab=null,$tabid=0){
        	if($tab) $this->setWhere("tab='".$tab."'");
        	if($tabid) $this->setWhere("tabid='".$tabid."'");
        }
        
        public function setNT($val){
        	$this->setWhere("ntype='".$val."'");
        }
}

function showNews($ary){
	global $rooturl;
	if(is_array($ary)){
		$ary['link']=$rooturl.'newsInfo.php?id='.$ary['id'];
	}
	return $ary;
}
?>