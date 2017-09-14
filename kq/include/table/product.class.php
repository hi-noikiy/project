<?php 
class product extends getList {
	 
     public function __construct(){
             $this->tableName = '_web_product';
             $this->key = 'id';
             $this->wheres = "1";
             
             $this->orders = 'id desc';
             $this->pageReNum = 3;
     }
     
     public function edit($array,$id){
     	global $rootpath;
     	$img=$this->uploadImg("upload_img");
     	if($img){
     		$info=$this->getInfo($id);
     		if($info["img"]) unlink($rootpath.$info["img"]);
     		if($info["small_img"]) unlink($rootpath.$info["small_img"]);
     		$array["img"]=$img["url"];
     		$array["small_img"]=$img["surl"];
     	}
     	
     	$this->editData($array,$id);	
     }
     
     public function add($array){
     	$img=$this->uploadImg("upload_img");
     	if($img){
     		$array["img"]=$img["url"];
     		$array["small_img"]=$img["surl"];
     	}
    	 
     	$this->addData($array);
     }
     
     public function uploadImg($name){
     	return upload::img($name);
     }
}
?>