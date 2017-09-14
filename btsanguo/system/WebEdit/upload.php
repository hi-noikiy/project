<?
date_default_timezone_set('Asia/Shanghai');
$AllowExt="gif|jpg|jpeg|bmp";
//上传大小
$AllowSize=500;
//上传路径
$UploadDir="../../NewsFile/".date("ym")."/";
//编辑器显示路径
$ContentPath="/NewsFile/".date("ym")."/";

$Action=$_REQUEST["Action"];
if ($Action=="save"){
	$UploadFile=$_FILES["uploadfile"]["tmp_name"];//获取服务器临时文件
	$File_Name=$_FILES["uploadfile"]["name"];//获取文件名
	$File_Type=$_FILES["uploadfile"]["type"];//获取文件类型
	$File_Size=$_FILES["uploadfile"]["size"];//获取文件大小
	if ($File_Name=="") return false;//没有上传退出
	if($File_Size/1024 > $AllowSize) {
		echo "文件大小不能超过".$AllowSize."KB";
		exit;
	}
	//取扩展名
	$File_Ext=explode(".",$File_Name);
	//定义文件名
	$RandFile=date("ymdHis").rand(10,99).".".$File_Ext[1];
	
	if (!is_dir($UploadDir)) mkdir($UploadDir, 0777);//检查文件是否存在
	//开始上传
	move_uploaded_file($UploadFile,$UploadDir.$RandFile);
	$str="<script language=javascript>";
	$str=$str."parent.UploadSaved('" .$ContentPath.$RandFile. "');history.back()</script>";
	//$str=$str."var obj=parent.dialogArguments.dialogArguments;if (!obj) obj=parent.dialogArguments;";
	//$str=$str."try{ obj.addUploadFile('" .$File_Name. "', '" .$RandFile. "', '" .$ContentPath.$RandFile. "');}";
	//$str=$str."catch(e){};history.back()</script>";
	echo $str;
}
?>
<HTML>
<HEAD>
<TITLE>文件上传</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style type="text/css">
body, a, table, div, span, td, th, input, select{font:9pt;font-family: "宋体", Verdana, Arial, Helvetica, sans-serif;}
body {padding:0px;margin:0px}
</style>
<script language="JavaScript" src="dialog/dialog.js"></script>
</head>
<body bgcolor=menu>
<form action="upload.php?Action=save" method=post name=myform enctype="multipart/form-data">
<input type=file name=uploadfile size=1 style="width:100%" onChange="getfile()">
<input type=hidden name=originalfile value="">
<input type="submit" name="button" id="button" value="提交">
</form>
<script language=javascript>
function InsertImg(sFromUrl){
	var sHTML = '';
	sHTML = '<img id=eWebEditor_TempElement_Img src="'+sFromUrl+'" border=0>';
	dialogArguments.insertHTML(sHTML);
	
	var oTempElement = dialogArguments.eWebEditor.document.getElementById("eWebEditor_TempElement_Img");
	oTempElement.src = sFromUrl;
	oTempElement.removeAttribute("id");
	window.close();
}

function getfile(){
myform.originalfile.value=myform.uploadfile.value;
//parent.tdPreview.innerHTML = "<img border=0 src='"+myform.uploadfile.value+"' width='100' height='100'>";
}

var sAllowExt = "<?=$AllowExt?>";
// 检测上传表单
function CheckUploadForm() {
	if (!IsExt(document.myform.uploadfile.value,sAllowExt)){
		parent.UploadError("提示：请选择一个有效的文件，\n支持的格式有（"+sAllowExt+"）！");
		return false;
	}
	return true
}

// 提交事件加入检测表单
var oForm = document.myform ;
oForm.attachEvent("onsubmit", CheckUploadForm) ;
if (! oForm.submitUpload) oForm.submitUpload = new Array() ;
oForm.submitUpload[oForm.submitUpload.length] = CheckUploadForm ;
if (! oForm.originalSubmit) {
	oForm.originalSubmit = oForm.submit ;
	oForm.submit = function() {
		if (this.submitUpload) {
			for (var i = 0 ; i < this.submitUpload.length ; i++) {
				this.submitUpload[i]() ;
			}
		}
		this.originalSubmit() ;
	}
}

// 上传表单已装入完成
try {
	parent.UploadLoaded();
}
catch(e){
}
</script>
</body>
</html>