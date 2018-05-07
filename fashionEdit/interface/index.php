<?php
include_once 'config.php';
error_reporting(0);
header("Access-Control-Allow-Origin: *");
$ex1 = explode('/',explode('/index.php/', $_SERVER['PHP_SELF'])[1]);
$_controller = ucfirst($ex1[0]);
include $_controller.'.php';
$_action = $ex1[1];
$data = $_REQUEST;
$controller = new $_controller($data);
$controller->$_action($data);

class upload {
	public function __construct($UploadName, $SetType, $SetSize, $SavePath) {
		if(is_array($UploadName)){
			$this->UploadFile = $UploadName["tmp_name"];//获取服务器临时文件
			$this->File_Name = $UploadName["name"];//获取文件名
			$this->File_Type = $UploadName["type"];//获取文件类型
			$this->File_Size = $UploadName["size"];//获取文件大小
		} else {
			$this->UploadFile = $_FILES[$UploadName]["tmp_name"];//获取服务器临时文件
			$this->File_Name = $_FILES[$UploadName]["name"];//获取文件名
			$this->File_Type = $_FILES[$UploadName]["type"];//获取文件类型
			$this->File_Size = $_FILES[$UploadName]["size"];//获取文件大小
		}
		$this->SetSize = $SetSize;
		$this->Set_Type = $SetType;
		$this->SavePath = $SavePath;
	}
	public function UploadFile($imagename = false) {
		$this->SetFile();
		$this->GetFileSize();
		$this->GetFileType();
		$SaveName = $this->SaveName($imagename);

		$rs = move_uploaded_file($this->UploadFile,$this->SavePath.$SaveName);

		$back = $rs ? $SaveName : false;
		return $back;
	}
	public function GetFileSize() {
		if($this->File_Size/1024 > $this->SetSize)
			exit(json_encode(array('status'=>6, 'msg'=>'picture too large.')));
	}

	public function GetFileType() {
		$fileType = str_replace('image/', '', $this->File_Type);
		if(!in_array($fileType, $this->Set_Type) && $this->File_Type != 'application/octet-stream')
			exit(json_encode(array('status'=>6, 'msg'=>'format wrong.')));
	}

	public function SetFile() {
		if(!isset($this->File_Name))
			exit(json_encode(array('status'=>6, 'msg'=>'filename is not exist.')));
	}

	public function SaveName($imagename){
		if($imagename)
			$name = $imagename.'.png';
		else
			$name = date("ymdHis").rand(10,99).".png";
		return $name;
	}
}
