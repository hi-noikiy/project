<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 问题反馈
* ==============================================
* @date: 2016-6-14
* @author: Administrator
* @return:
*/
include("./inc/config.php");
include("./inc/function.php");
define('SCENES_IMG__PATH', './upload/feedback/');

$title = trim($_POST['title']);
$phone = $_POST['phone'];
$sign = strtolower(trim($_POST['sign']));


$mySign = strtolower(md5($phone.$title.$appKey));
if($sign != $mySign)
	exit(json_encode(array('status'=>4, 'msg'=>'sign error.')));

$imagesArr = array();
if(is_array($_FILES['filepath']['name'])){ //多图上传
	for ($i = 0; $i< count($_FILES['filepath']['name']); $i++){
		$arr = array();
		$arr['name'] = $_FILES['filepath']['name'][$i];
		$arr['type'] = $_FILES['filepath']['type'][$i];
		$arr['tmp_name'] = $_FILES['filepath']['tmp_name'][$i];
		$arr['size'] = $_FILES['filepath']['size'][$i];

		$upload = new upload($arr, array('jpg','gif','png', 'jpeg'), 2048, SCENES_IMG__PATH);
		$up = $upload->UploadFile();
		if($up)
			$imagesArr[] = $up;
	}
} else {
	$upload = new upload('filepath', array('jpg','gif','png', 'jpeg'), 2048, SCENES_IMG__PATH);
	$up = $upload->UploadFile();
	if($up)
		$imagesArr[] = $up;
}

$images = json_encode($imagesArr);
$rs = feedback($title, $images, $phone);
if($rs)
	exit(json_encode(array('status'=>1, 'msg'=>'success')));
exit(json_encode(array('status'=>0, 'msg'=>'fail')));
function feedback($title, $images, $phone){
	$conn = SetConn(999);
	$addtime = time();
	$sql = "insert into feedback(title, images, phone, addtime) values ('$title', '$images', '$phone', '$addtime')";
	$msg = (false == mysqli_query($conn, $sql)) ? false : true;
	@mysqli_close($conn);
	return $msg;
}

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
	public function UploadFile($imagename=false) {
		if ($this->File_Name == '') 
			return false;
		$this->SetFile();
		$this->GetFileSize();
		$this->GetFileType();
		$SaveName = $imagename != false ? $imagename : $this->SaveName();

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

	public function SaveName(){
		$SaveName=explode(".",$this->File_Name);
		$SaveName=date("ymdHis").rand(10,99).".".$SaveName[1];
		return $SaveName;
	}
}