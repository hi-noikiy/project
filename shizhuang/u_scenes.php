<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 用户头像上次
 * ==============================================
 * @date: 2015-10-30
 * @author: Administrator
 * @return:
 * 	"2"      参数异常
 *   '3'      sql异常
 *   "4"      验证出错
 *   "0"
 *   "1"      成功
 */
include("./inc/config.php");
include("./inc/function.php");

define('SCENES_IMG__PATH', './upload/scenes/');
$accountid = intval($_POST['accountid']);
$post = serialize($_POST);
$file = serialize($_FILES);
write_log("log","scenes_info_"," post=$post, file=$file,".date("Y-m-d H:i:s")."\r\n");
if(!$accountid)
	exit('2');


$conn = SetConn('81');
$sql = "select id from account where id = '$accountid' limit 1";
$query=mysqli_query($conn, $sql);
if($query == false){
	write_log("log","scenes_error_"," sql=$sql".date("Y-m-d H:i:s")."\r\n");
	exit('3');
}
$result=mysqli_fetch_assoc($query);
/* if(!$result)
	exit('4'); */
//上传小图标
$upload = new upload('filepath', array('jpg','gif','png', 'jpeg'), 2048, SCENES_IMG__PATH);
$upload->UploadFile();
exit('1');

class upload {
	public function __construct($UploadName,$SetType,$SetSize,$SavePath) {
		$this->UploadFile=$_FILES[$UploadName]["tmp_name"];//获取服务器临时文件
		$this->File_Name=$_FILES[$UploadName]["name"];//获取文件名
		$this->File_Type=$_FILES[$UploadName]["type"];//获取文件类型
		$this->File_Size=$_FILES[$UploadName]["size"];//获取文件大小
		$this->SetSize=$SetSize;
		$this->Set_Type=$SetType;
		$this->SavePath=$SavePath;
	}

	public function UploadFile($imagename = false) {
		if ($this->File_Name=="") return false;
		$this->SetFile();
		$this->GetFileSize();
		$this->GetFileType();
		$SaveName = $this->File_Name;
		
		move_uploaded_file($this->UploadFile,$this->SavePath.$SaveName);
		return $SaveName;
	}

	public function GetFileSize() {
		if($this->File_Size/1024 > $this->SetSize) {
			//$msg = "文件大小不能超过".$this->SetSize."KB";
			//echo"<script>alert('".$msg."');history.go(-1);</script>";
			exit('999');
		}
	}

	public function GetFileType() {
		$fileType = str_replace('image/', '', $this->File_Type);
		if(!in_array($fileType, $this->Set_Type) && $this->File_Type != 'application/octet-stream'){
			//$msg = "不能上传此类格式的文件";
			//echo"<script>alert('".$msg."');history.go(-1);</script>";
			exit('998');
		}
	}

	public function SetFile() {
		if(!isset($this->File_Name)) {
			//echo "没有接收到文件";
			exit('997');
		}
	}

	/* public function SaveName(){
		$SaveName=explode(".",$this->File_Name);
		$SaveName=date("ymdHis").rand(10,99).".".$SaveName[1];
		return $SaveName;
	} */
}
