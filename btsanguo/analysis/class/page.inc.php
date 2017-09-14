<?php

/*
* @param Int $count 记录总数
* @param Int $currentPage 当前页面
* @param Int $pageSize 每页显示数
* @param String $order 排序方式
* @param String $by 升降序(desc,asc)
* @param Int $return 返回方式(0 echo,1 return)
* @param String $tourl 实际跳转到的页面(用于静态页)
* @return 分页输出
*/
function page($count,$currentPage=1,$pageSize=null,$order=null,$by=null,$return=0,$tourl=""){
    if($order){
        $order="&order=$order";
    }
    if($by){
        $by="&by=$by";
    }
    if($pageSize){
        $size="&pageSize=$pageSize";
    }
    if(!$pageSize){
        $pageSize=15;
    }
    $pageCount=ceil($count/$pageSize);//总页数
    $currentPage=ceil($currentPage);
    if($_SERVER["QUERY_STRING"] == ""){//无?后内容
        $url="?";
    }
    else{//?后有内容,去掉旧信息
        $url_query=ereg_replace("(^|&)currentPage=".$currentPage,"",$_SERVER["QUERY_STRING"]);//清空currentPage
        $url_query=ereg_replace("(^|&)pageSize=".$pageSize,"",$url_query);//清空pageSize
        if($order)$url_query=ereg_replace($order,"",$url_query);//清空order
        if($by)$url_query=ereg_replace($by,"",$url_query);//清空by
        $url_query=ereg_replace("(^|&)currentPage=","",$url_query);
        if($url_query)//如果存在其他url信息
            $url="?".$url_query."&";
        else
            $url="?";
    }
    $url=$tourl.$url;
    $outpage="";
    $outpage.="
<div class='fenyeiWrap clearfix'>
  	<div class='whichPageWrap'>
  		<span class='total'>共<span class='total_page'>".$pageCount."</span>页<span class='total_page'>".$count."</span>条记录</span>
		<span id='perPageWrap'>
			<span>每页显示:</span>
			<input name='pageSize' id='perPageBox' class='perPageBox' type='text' value='".$pageSize."' />
			<span>条</span>
		</span>
		<span class='span3'>到</span>
		<input name='whickPage' id='whickPage' class='whickPage' type='text' />
		<span class='span3'>页</span>
		<span class='qd'>
			<input class='' type='button' onclick='window.location=\"".$url."currentPage=\"+document.getElementById(\"whickPage\").value+\"&pageSize=\"+document.getElementById(\"perPageBox\").value+\"".$order.$by."\"' value='确 定'/>
		</span>
	</div>
	<ul class='navWrap' title='点击翻页不爽？试试键盘\"←\"、\"→\"键吧~'>";
    $msp= $pageCount<5 ? $pageCount : 5;
    $outpage.= $currentPage-1>0 ?
        "<li><a href='".$url."currentPage=".($currentPage-1).$order.$by.$size."' class='prevBtn'>上一页</a></li>"
        :"<li><a class='prevBtn no-hover'>上一页</a></li>";
    $outpage.= $currentPage==1 ?
        "<li><a class='firstPage no-hover'>首 页</a></li> "
        :"<li><a class='firstPage' href='".$url."currentPage=1'>首 页</a></li> ";
    if($currentPage<5){
        for($i=0;$i<$msp;$i++){//开头4页1234
            $outpage.= (($i+1)==$currentPage) ?
                "<li><a class='current'>".$currentPage."</a></li> "
                :"<li><a href='".$url."currentPage=".($i+1).$order.$by.$size."'>".($i+1)."</a></li> ";
        }
    }elseif($pageCount-$currentPage>=2){//56七89
        for($i=-2;$i<3;$i++){
            $outpage.= (($i+$currentPage)==$currentPage) ?
                "<li><a class='current'>".($currentPage+$i)."</a></li> "
                :"<li><a href='".$url."currentPage=".($currentPage+$i).$order.$by.$size."'>".($currentPage+$i)."</a></li> ";
        }
    }elseif($pageCount-$currentPage<=5){//567八9最后2页
        for($i=-4;$i<1;$i++){
            $outpage.= (($i+$pageCount)==$currentPage) ?
                "<li><a class='current'>".($i+$pageCount)."</a></li> "
                :"<li><a href='".$url."currentPage=".($i+$pageCount).$order.$by.$size."'>".($i+$pageCount)."</a></li> ";
        }
    }
    $outpage.= $currentPage==$pageCount ?
        "<li><a class='lastPage no-hover'>末 页</a></li>"
        :"<li><a class='lastPage' href='".$url."currentPage=".$pageCount.$order.$by.$size."'>末 页</a></li>";
    $outpage.= (($currentPage+1)<=$pageCount) ?
        "<li><a href='".$url."currentPage=".($currentPage+1).$order.$by.$size."' class='nextBtn'>下一页</a></li></ul>"
        :"<li><a class='nextBtn no-hover'>下一页</a></li></ul>";
    $outpage.="</div>";
    $outpage.="<script type='text/javascript'>";
    $outpage.="document.body.onkeydown=function(e){";
    $outpage.="if(!e)e=event;";
    $outpage.="var obj=document.activeElement;";
    $outpage.=$currentPage-1>0 ? "if(e.keyCode==37 && (obj.type!='text' && obj.tagName!='TEXTAREA'))window.location='".$url."currentPage=".($currentPage-1).$order.$by.$size."';" : "";
    $outpage.=($currentPage+1)<=$pageCount ? "if(e.keyCode==39 && (obj.type!='text' && obj.tagName!='TEXTAREA'))window.location='".$url."currentPage=".($currentPage+1).$order.$by.$size."';" : "";
    $outpage.="}";
    $outpage.="</script>";
    if($return==0)echo $outpage;
    elseif($return==1)return $outpage;
}
/* //$url="page.php";
$count=200;
$currentPage=$_REQUEST["currentPage"]?$_REQUEST["currentPage"]:1;
$pageSize=$_REQUEST['pageSize'];
page($count,$currentPage,$pageSize); */
?>