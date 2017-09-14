<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$TabName="news_data";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];

$NewsTitle=CheckStr($_REQUEST["NewsTitle"]);
$NewsFrom=CheckStr($_REQUEST["NewsFrom"]);
$NewsKey=CheckStr($_REQUEST["NewsKey"]);
$NewsType=$_REQUEST["NewsType"];
$TitleColor=$_REQUEST["TitleColor"];
$Content=$_REQUEST["Content"];
$wapContent=CheckStr($_REQUEST["wapContent"]);
$Add_Time=$_REQUEST["Add_Time"];
$IsHot=$_REQUEST["IsHot"];
$IsComm=$_REQUEST["IsComm"];
$IsPic=$_REQUEST["IsPic"];
$IsHide=$_REQUEST["IsHide"];
$HitCount=$_REQUEST["HitCount"];

if($IsHot=="") $IsHot=0;
if($IsComm=="") $IsComm=0;
if($IsPic=="") $IsPic=0;
if($IsHide=="") $IsHide=0;

if ($Action=="Add"){
    setcookie('nTypeTwo' , $NewsType);//用于下次新增，自动选择下拉框

    //获取多个提交的图片
    $upClass1=new upload("NewsFile1","jpg|gif|bmp","500",$NewsPath);
    $ImgPath1=$upClass1->UploadFile();//开始上传
    $upClass2=new upload("NewsFile2","jpg|gif|bmp","500",$NewsPath);
    $ImgPath2=$upClass2->UploadFile();//开始上传
    $upClass3=new upload("NewsFile3","jpg|gif|bmp","500",$NewsPath);
    $ImgPath3=$upClass3->UploadFile();//开始上传
    $upClass4=new upload("NewsFile4","jpg|gif|bmp","500",$NewsPath);
    $ImgPath4=$upClass4->UploadFile();//开始上传
    $upClass5=new upload("NewsFile5","jpg|gif|bmp","500",$NewsPath);
    $ImgPath5=$upClass5->UploadFile();//开始上传

    $img_arr = mysql_escape_string(serialize(array($ImgPath1,$ImgPath2,$ImgPath3,$ImgPath4,$ImgPath5)));
    $sql="insert into ".$TabName."(NewsTitle,NewsType,NewsFrom,NewsKey,TitleColor,IsHot,IsComm,IsPic,IsHide,HitCount,img_arr,Content,wapContent,AddOper,Add_Time)
    values('$NewsTitle',$NewsType,'$NewsFrom','$NewsKey','$TitleColor','$IsHot','$IsComm','$IsPic','$IsHide',$HitCount,'$img_arr','$Content','$wapContent','$AdminName','$Add_Time')";
    mysql_query($sql);

}
if ($Action=="Edit"){

    //    $IsFile=$_FILES["NewsFile"]["name"];
    //    if ($IsFile != ""){
    //        $result=mysql_query("select ImgPath from ".$TabName." Where ID=$ID");
    //        $ImgPath=mysql_result($result,0);
    //        //检测是否存在图片
    //        if ($ImgPath!=""){
    //            ErrMsg("请先删除图片，再上传！");
    //            exit;
    //        }else{
    //            $upClass=new upload("NewsFile","jpg|gif|bmp","500",$NewsPath);
    //            $newPath=$upClass->UploadFile();//开始上传
    //            $sqlImg=", ImgPath='$newPath'";
    //        }
    //    }

    //获取多个提交的图片
    $upClass1=new upload("NewsFile1","jpg|gif|bmp","500",$NewsPath);
    $ImgPath1=$upClass1->UploadFile();//开始上传
    $upClass2=new upload("NewsFile2","jpg|gif|bmp","500",$NewsPath);
    $ImgPath2=$upClass2->UploadFile();//开始上传
    $upClass3=new upload("NewsFile3","jpg|gif|bmp","500",$NewsPath);
    $ImgPath3=$upClass3->UploadFile();//开始上传
    $upClass4=new upload("NewsFile4","jpg|gif|bmp","500",$NewsPath);
    $ImgPath4=$upClass4->UploadFile();//开始上传
    $upClass5=new upload("NewsFile5","jpg|gif|bmp","500",$NewsPath);
    $ImgPath5=$upClass5->UploadFile();//开始上传

    if($ImgPath1||$ImgPath2||$ImgPath3||$ImgPath4||$ImgPath5){//只有当存在图片上传的时候，生成序列化
        $img_arr = mysql_escape_string(serialize(array($ImgPath1,$ImgPath2,$ImgPath3,$ImgPath4,$ImgPath5)));

        //存在新上传的图片的情况，删除原有的图片文件
        $result=mysql_query("select img_arr from ".$TabName." Where ID=$ID");
        $img_arr_old=unserialize(mysql_result($result,0));
        for($i=0;$i<count($img_arr_old);$i++){
            if(file_exists($NewsPath.$img_arr_old[$i])){
                @unlink($NewsPath.$img_arr_old[$i]);
            }
        }
    }
    
    $sql="Update ".$TabName." Set NewsTitle='$NewsTitle', NewsType=$NewsType, NewsFrom='$NewsFrom', NewsKey='$NewsKey'";
    $sql=$sql.", TitleColor='$TitleColor', IsHot='$IsHot', IsComm='$IsComm', IsPic='$IsPic', IsHide='$IsHide', HitCount=$HitCount";
    if($img_arr){
        $sql=$sql.", img_arr ='".$img_arr."', Content='$Content', wapContent='$wapContent', Add_Time='$Add_Time' Where ID=$ID";
    }else{
        $sql=$sql.", Content='$Content', wapContent='$wapContent', Add_Time='$Add_Time' Where ID=$ID";
    }
    
    mysql_query($sql);

    //修改文章后，删除pc站的静态文件
    include("inc/web_config.php");
    file_get_contents(WEB_URL.'404.php?action=del_new&new_id='.$ID);
}
header("Location: News_List.php");
?>