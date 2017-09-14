<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 图片上传类
* ==============================================
* @date: 2016-7-23
* @author: Administrator
* @return:
*/
class upload {
	protected $uploadFile;
	protected $fileName;
	protected $fileType;
	protected $fileSize;
	protected $savePath;
	protected $setType = array('image/jpg','image/gif','image/png', 'image/jpeg', 'application/octet-stream');
	protected $setSize = 1024;
	
	public function __construct($uploadName,$savePath) {
		if(empty($_FILES[$uploadName]))
			return array('status'=>1, 'msg'=>'params should not empty');
		
		$this->uploadFile = $_FILES[$uploadName]["tmp_name"];//获取服务器临时文件
		$this->fileName = $_FILES[$uploadName]["name"];//获取文件名
		$this->fileType = $_FILES[$uploadName]["type"];//获取文件类型
		$this->fileSize = $_FILES[$uploadName]["size"];//获取文件大小
		
		$this->savePath = $savePath;
	}

	public function uploadFile($imagename = false) {
		
		$this->setFile();
		$this->getFileSize();
		$this->getFileType();
		$saveName = $this->SaveName($imagename);
		$moveUpload = move_uploaded_file($this->uploadFile,$this->savePath.$saveName);
		if($moveUpload)
			return array('status'=>0, 'msg'=>"success");
		else 
			return array('status'=>1, 'msg'=>"fail");
	}

	public function getFileSize() {
		if($this->fileSize/1024 > $this->setSize)
			return array('status'=>1, 'msg'=>"File size can not exceed {$this->setSize}KB");
	}

	public function getFileType() {
		if(!in_array($this->fileType, $this->setType))
			return array('status'=>1, 'msg'=>"File format wrong.{$this->fileType}");
	}

	public function setFile() {
		if($this->fileName == '')
			return array('status'=>1, 'msg'=>'file should not empty');
	}

	public function saveName($imagename){
		$fieleName = explode(".",$this->fileName);
		$saveName = ($imagename == false) ? date("ymdHis").rand(100,999) : $imagename;
		$saveName .= '.'.$fieleName[1];

		return $saveName;
	}
}