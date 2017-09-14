<?
//消息提示
function ErrMsg($msg){
	echo"<script>alert('".$msg."');history.go(-1);</script>";
	exit;
}

function goMsg($msg,$url){
	echo"<script>alert('".$msg."');location.href='".$url."';</script>";
	exit;
}

//根据数字串取时间
function getIntTime($in_time){
	if (substr($in_time,0,1)=='9') {
		$y = "200".substr($in_time,0,1);
		$m = substr($in_time,1,2);
		$d = substr($in_time,3,2);
		$h = substr($in_time,5,2);
		$mi = substr($in_time,7,2);
		$pay_time = $y."-".$m."-".$d." ".$h.":".$mi;
		$str=$pay_time;
	}else{
	$str=date("Y-m-d H:i:s",strtotime(substr(date("Y"),0,2).$in_time));
	}
	return $str;
}
//获取当前路径
function getPath(){
	$PathName=basename($_SERVER['PHP_SELF']);
	return $PathName;
}
//作用：下拉菜单显示结果,如果输入参数值相等，则返回selected
function SeleObject($CurrValue,$ObjeValue){
	if ($CurrValue==$ObjeValue){
	$SelectStr="selected";
	}else{
	$SelectStr="";
	}
	return $SelectStr;
}
//作用：获取小时下拉表
function getHours($curval='',$t=''){
    echo "<select name='hour$t'>";
    for($i=0;$i<=23;$i++)
    {
        $str = '';
        if($i<10)$str = '0';
        echo "<option value='".$str.$i."'".($i==$curval?' selected':'').">$str$i</option>";
    }
    echo "</select>时";
}
//作用：单选按钮显示结果,如果输入参数值相等，则返回checked
function ChecObject($CurrValue,$ObjeValue){
	if ($CurrValue==$ObjeValue){
	$CheckStr="checked";
	}else{
	$CheckStr="";
	}
	return $CheckStr;
}
//作用：多选按钮显示结果,如果输入参数值相等，则返回checked
//ObjeValue=表单值，FlagValue=当前数值
function MuchObject($ObjeValue,$FlagValue){
	$ArrayNum = explode(',',$FlagValue);
	$N=count($ArrayNum);
	for ($I=0; $I<=$N-1; $I++) {
		if ($ObjeValue ==$ArrayNum[$I]){
			$str="checked";
			break;
		}else{
			$str="";
		}
	}
	return $str;
}

//过虑非法字符
function CheckStr($str){
	if ($str !=""){
	$str = trim($str);
	//$str = str_replace("&","&amp;",$str);
	//$str = str_replace("<","&lt;",$str);
	//$str = str_replace(">","&gt;",$str);
	$str = str_replace("'","&#39;",$str);
	return $str;
	}
}
//wap输出内容
function WapStr($str){
	if ($str !=""){
	$str = str_replace("&","&amp;",$str);
	return $str;
	}
}

/*演示
$up=new upload("upfile","jpg","10","UploadFiles");
$up->UploadFile();
*/
class upload {
	function __construct($UploadName,$SetType,$SetSize,$SavePath) {
		$this->UploadFile=$_FILES[$UploadName]["tmp_name"];//获取服务器临时文件
		$this->File_Name=$_FILES[$UploadName]["name"];//获取文件名
		$this->File_Type=$_FILES[$UploadName]["type"];//获取文件类型
		$this->File_Size=$_FILES[$UploadName]["size"];//获取文件大小
		$this->SetSize=$SetSize;
		$this->Set_Type=$SetType;
		$this->SavePath=$SavePath;
	}

	function UploadFile() {
		if ($this->File_Name=="") return false;
		$this->SetFile();
		$this->GetFileSize();
        $this->GetFileType();
		$SaveName=$this->SaveName();
		move_uploaded_file($this->UploadFile,$this->SavePath.$SaveName);
		//echo $this->UploadFile."  ".$this->SavePath."/".$SaveName;
		return $SaveName;
	}

	function GetFileSize() {
		if($this->File_Size/1024 > $this->SetSize) {
			echo "文件大小不能超过".$this->SetSize."KB";
			exit;
		}
	}

	function GetFileType() {
		if(!ereg($this->Set_Type,$this->File_Name)) {
			echo "不能上传此类格式的文件";
			exit;
		}
	}

	function SetFile() {
		if(!isset($this->File_Name)) {
			echo "没有接收到文件";
			exit;
		}
	}

	function SaveName(){
		$SaveName=explode(".",$this->File_Name);
		$SaveName=date("ymdHis").rand(10,99).".".$SaveName[1];
		return $SaveName;
	}
}
/****************************************
图片处理类：
功能：生成文字图片，加水印，调整图片大小
作者：追风剑  qq:285837593
$imgctrl=new images("../NewsFile/2009011615522470.jpg");//创建图片处理类
$srcimg=$imgctrl->textimages("漂亮","40",$fontfamily,"#b5815b");//调用生成文字图片函数
$imgctrl->resizeimage(200,150);
$img=$imgctrl->copyimages($srcimg,100,"leftbottom");//把文字加在图片上
imagejpeg($img,"../NewsFile/sd.gif",100);
****************************************/
class images{
	function __construct($path) {
		$imgtype=strtolower(strrchr($path,"."));//获得扩展名
		switch($imgtype) {//根据扩展名确定图片格式并创建图片
	    case ".jpeg":
		case ".jpg":
			$img=imagecreatefromjpeg($path);
		    break;
		case ".gif":
			$img=imagecreatefromgif($path);
		    break;
		case ".png":
			$img=imagecreatefrompng($path);
		    break;
		default:
			echo $img="不支持的图片格式！";
		    exit;
		}
		$this->img=$img;
		$this->path=$path;
		$this->imgwidth=imagesx($img);
		$this->imghight=imagesy($img);
	}

//将文字生成图片 $text:文字内容  $fontsize:字号大小(默认24)  $fontfamily:字体路径(默认黑体)  $fontcolor:字体颜色
	function textimages($text,$fontsize,$fontfamily,$fontcolor) {
		if(!isset($fontsize)) {
			 $fontsize=24;
		}
		if(!isset($fontfamily)) {
			 $fontfamily="C:/WINDOWS/Fonts/SIMHEI.TTF";//默认黑体
		}
        $textimg=imagecreate(strlen($text)*$fontsize-strlen($text)*$fontsize*0.34,$fontsize+8);//创建一个空白图
		$RGB=$this->torgb($fontcolor);
		$black=imagecolorallocate($textimg,$RGB[0],$RGB[1],$RGB[2]);//设置文字图片背景颜色
		imagecolortransparent($textimg,$black);//设置背景透明
        $fontcolor=imagecolorallocate($textimg,$RGB[0],$RGB[1],$RGB[2]);
		$text=iconv("GB2312","UTF-8",$text);
        imagettftext($textimg, $fontsize, 0, 0, $fontsize+2, $fontcolor, $fontfamily, $text);
		//$textimg=imagegif($textimg);
        return $textimg;
	}

/*给图片加水印  $srcimg:水印图片 $imagepct:水印透明度
$align:水印对齐方式可选五种:"center","rightbottom","leftbottom","righttop","lefttop"
*/
	function copyimages($srcimg,$imagepct,$align) {
		switch($align) {//5种对齐方式
		case "center":
			$x=$this->imgwidth/2-imagesx($srcimg)/2;
		    $y=$this->imghight/2-imagesy($srcimg);
			break;
		case "rightbottom":
			$x=$this->imgwidth-imagesx($srcimg);
		    $y=$this->imghight-imagesy($srcimg);
			break;
		case "leftbottom":
			$x=0;
		    $y=$this->imghight-imagesy($srcimg);
			break;
		case "righttop":
			$x=$this->imgwidth-imagesx($srcimg);
		    $y=0;
			break;
		case "lefttop":
			$x=0;
		    $y=0;
			break;
		}
        imagecopymerge($this->img,$srcimg,$x,$y,0,0,imagesx($srcimg),imagesy($srcimg),$imagepct);//拷贝水印
		header   ("Content-type: image/gif");//输出头文件信息
        $outimg=imagegif($this->img);
		return $this->img;
	}

	//改变图片大小  $rew:宽  $reh:高
    function resizeimage($rew,$reh) {
		$reimg=imagecreatetruecolor ($rew,$reh);
		imagecopyresized($reimg,$this->img, 0, 0, 0, 0,$rew, $reh, $this->imgwidth,$this->imghight );
		//$reimg=imagejpeg($reimg);
        $this->img=$reimg;
		return $reimg;
    }
   //16进制的颜色转换为10进制的RGB
	function torgb($color) {
          $color = eregi_replace ("^#","",$color);//去掉#号
		  $rgb=array();
          $r = $color[0].$color[1];
          $rgb[0] = hexdec($r);//16进制转换成10进制
          $b = $color[2].$color[3];
          $rgb[1] = hexdec($b);
          $g = $color[4].$color[5];
          $rgb[2] = hexdec($g);
          return $rgb;
	}

}

function getIP(){
	$ip=getenv('REMOTE_ADDR');
	$ip_ = getenv('HTTP_X_FORWARDED_FOR');
	if (($ip_ != "") && ($ip_ != "unknown"))
	{
	$ip=$ip_;
	}
	return $ip;
}
function getColor($Tcolor){
	$ColorStr="#FF0000,#0000FF,#FF00FF,#008080,#008000,#800000";
	$ColorArre=explode(",",$ColorStr);
	$N=count($ColorArre);
	$str="<select size=1 name=TitleColor>";
	$str=$str."<option value=''>默认颜色</option>";
	for ($I=0; $I<=$N-2; $I++) {
	$str=$str."<option ".SeleObject($Tcolor,$ColorArre[$I])." value=".$ColorArre[$I]." style=\"background-color:".$ColorArre[$I]."\"></option>\n";
	}
	echo $str."</select>";
}
//权限控制
//CurrValue:当前访问权限值，FlagValue:拥有权限
function getFlag($CurrValue,$FlagValue){
	$ArrayNum = explode(',',$FlagValue);
	$N=count($ArrayNum);
	for ($I=0; $I<=$N-1; $I++) {
		if ($CurrValue ==$ArrayNum[$I]){
			$str=True;
			break;
		}else{
			$str=False;
		}
	}
	return $str;
}
//设置IP访问权限
function ipLimit($IP_File){
	if(file_exists($IP_File)){
		//读取IP配置文件
		$ipStr="";
		$myFile = file($IP_File);
		for($index = 0; $index < count($myFile); $index++) {	
			$txtStr=$myFile[$index];
			$ipStr=$ipStr.$txtStr;
		}
		$ipStr=str_replace("\r\n",",",$ipStr);
		$IP=getenv ('REMOTE_ADDR');
		//判断IP是否存在
		if (strstr($ipStr,$IP)) {
			$str=True;
		}else{
			$str=False;
		}
	}else{
	   echo "找不到登录IP配置文件";
	   exit;
	}
	return $str;
}
?>