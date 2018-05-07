<?php
/*
 * 自动加载类
 */
function __autoload($class_name){
	global $rootpath;
	$file = $rootpath.'include/table/'.$class_name.'.class.php';
	if(!file_exists($file)){
		$file = $rootpath.'include/class/'.$class_name.'.class.php';
		if(!file_exists($file)){
			$file = $rootpath.'include/class/'.$class_name.'/'.$class_name.'.class.php';
			if(!file_exists($file)){
				$str=file_get_contents($rootpath.'include/template.php');
				$str=str_replace('CLASSNAME',$class_name,$str);
				fileWrite($rootpath.'cache/','tmpclass.php',$str);
				$file=$rootpath.'cache/'.'tmpclass.php';
			}
		}
	}
	include_once ($file);
}
/*
 * 递归某个表,获得Html
 */
function dgHtml($tab,$html,$ex='&nbsp;&nbsp;',$pid=0,$pf='pid',$kf='id',$where='',$nowex=''){
	global $webdb;
	$sql="select * from ".$tab." where ".$pf."='".$pid."' ";
	if($where) $sql.=$where;
	$res=$webdb->getList($sql);
	!$res && $res=array();
	foreach($res as $val){
		$str=$html;
		$str=str_replace('%ex',$nowex,$str);
		foreach($val as $k=>$v){
			$str=str_replace('%'.$k,$v,$str);
		}
		$reAry[]=$str;
		$reAry=array_merge($reAry,dgHtml($tab,$html,$ex,$val[$kf],$pf,$kf,$where,$nowex.$ex));
	}
	!$reAry && $reAry=array();
	return $reAry;
}
/*
 * 递归某个表,获得下拉菜单
 */
function dgAry($tab,$where='',$pid=0,$ex='',$nf='name',$pf='pid',$kf='id'){
	global $webdb;
	$sql="select ".$kf.",".$nf." from ".$tab." where ".$pf."='".$pid."' ";
	if($where) $sql.=$where;
        
	$res=$webdb->getList($sql);
	!$res && $res=array();
	foreach($res as $val){
		$val['dicval']=$val[$kf];
		$val['name']=$ex.$val[$nf];
		$reAry[]=$val;
		$reAry=array_merge($reAry,dgAry($tab,$where,$val[$kf],$ex.'&nbsp;&nbsp;',$nf,$pf,$kf));
	}
	!$reAry && $reAry=array();
	return $reAry;
}
/*
 * 从html中获得Images,还没有整理
 */
function find_html_images($images_dir) {
	while (list($key, ) = each($this->image_types)) {
		$extensions[] = $key;
	}
	preg_match_all('/"([^"]+\.(' . implode('|', $extensions).'))"/Ui', $this->html, $images);
	for ($i=0; $i<count($images[1]); $i++) {
		if (file_exists($images_dir . $images[1][$i])) {
			$html_images[] = $images[1][$i];
			$this->html = str_replace($images[1][$i], basename($images[1][$i]), $this->html);
		}
	}
	if (tep_not_null($html_images)) {
		$html_images = array_unique($html_images);
		sort($html_images);
		for ($i=0; $i<count($html_images); $i++) {
			if ($image = $this->get_file($images_dir . $html_images[$i])) {
				$content_type = $this->image_types[substr($html_images[$i], strrpos($html_images[$i], '.') + 1)];
				$this->add_html_image($image, basename($html_images[$i]), $content_type);
			}
		}
	}
}
/*
 * 时间计算
 */
function dateAdd($format='Y-m-d',$d=0,$m=0,$y=0){
	return date($format, mktime(0,0,0,date("m")+$m,date("d")+$d,date("Y")+$y));
}
/*
 * 数组排序
 */
function aryDesc($array,$key){
	if(is_array($array)){
		$order=$ary=array();
		foreach($array as $k=>$v){
			$ary[]=$v;
			$order[]=$v[$key];
		}
		array_multisort($order,$ary);
		return $ary;
	}else return false;
}
/*
 * 安全跳转到指定的路径
 */
function go($url){
	@header('Location :'.$url);
	jsCtrl::Location($url);
	exit;
}
/**
 * @param $name 隐藏字段名
 * @param $value 隐藏字段值
 * @param $toolbar 工具栏样式,可选值 Basic Default
 * @param $skin 面板,可选值 default office2003 silver
 * @param $Lang 语言,可选值 zh-cn zh e
 *
 */
function htmlEdit1($name,$value,$height=220,$width=640){
	$url = 'http://'.$_SERVER['HTTP_HOST'].'/';
	if(strpos($_SERVER['REQUEST_URI'],'kq/' )){
		$url .= 'kq/';
	}
	$url .= 'admin/saveImg.php';
	echo <<<EDO
	<div id="box" style="width:{$width}px;height:{$height}px;border:1px solid;"contenteditable>
			$value
</div>
<input type=hidden id='mycontent' name='$name' value='$value'/>
<script>
//查找box元素,检测当粘贴时候,
document.getElementById('box').onpaste= function(e) {
 
//判断是否是粘贴图片
 if (e.clipboardData && e.clipboardData.items[0].type.indexOf('image') > -1) 
{
var that = this,
reader = new FileReader();
file = e.clipboardData.items[0].getAsFile();
//ajax上传图片
 reader.onload = function(e) 
{
 var xhr = new XMLHttpRequest(),
 fd = new FormData();

 xhr.open('POST', '$url', true);
 xhr.onload = function () 
{
 var img = new Image();
 img.src = xhr.responseText;
 
  that.innerHTML += '<img src="'+img.src+'" style="max-width:600px;"/>';
 	document.getElementById("mycontent").value = that.innerHTML;
}
 
 // this.result得到图片的base64 (可以用作即时显示)
 fd.append('file', this.result); 
 //that.innerHTML += '<img src="'+this.result+'"/>';
xhr.send(fd);
}
reader.readAsDataURL(file);
}
}
var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串  
var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1; //判断是否IE<11浏览器  
    var wrap =document.getElementById('box');
    if(isIE){
        /*
        对于低版本的IE浏览器因为他们不支持事件捕获，而他们支持focusin、focusout事件
        使用该事件加事件委托能解决低版本IE的focus、blur事件委托的问题
         */
 		wrap.onfocusout = handler;
       // wrap.addEventListener('focusout',handler);
    }else{
        /*
            高版本的IE浏览器以及主流标准浏览器则可以利用事件捕获机制来解决
         */
        wrap.addEventListener('blur',handler,true);
    }
    function handler(event){
 		var html = wrap.innerHTML;
 		html.replace('<', '&lt;');
 		html.replace('>', '&gt;');
 		
       document.getElementById("mycontent").value = html;
    }

// document.querySelector('#box').addEventListener('blur', function(e) {
//  		document.getElementById("mycontent").value = this.innerHTML;
//  		})
</script>
EDO;
} 
function htmlEdit($name,$value,$height=220,$width=640,$toolbar='Default',$skin='office2003',$Lang='zh'){
	global $rootpath,$rooturl;
	include($rootpath."fckeditor/fckeditor.php") ;
	$sBasePath = $rooturl.'fckeditor/';
	$oFCKeditor = new FCKeditor($name) ;
	$oFCKeditor->BasePath = $sBasePath ;
	if($Lang) 		$oFCKeditor->Config['AutoDetectLanguage'] = false ;
	if($Lang) 		$oFCKeditor->Config['DefaultLanguage'] = $Lang ;
	if($skin) 		$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/'.$skin.'/' ;
	if($toolbar)	$oFCKeditor->ToolbarSet = $toolbar ;
	if($width) 		$oFCKeditor->Width = $width ;
	if($height) 	$oFCKeditor->Height = $height ;
	$oFCKeditor->Value = $value ;
	return $oFCKeditor->CreateHtml() ;
}
/*
 * 显示有或无
 */
function haveYN($val){
	return ($val==1)?'<span style="color: blue;">有</span>':'<span style="color: red;">无</span>';
}
/*
 * 广告显示
 */
function adshow($name,$page=null){
	global $webdb,$rooturl;
	!$page && $page=$_SERVER['PHP_SELF'];
	$sql="select * from _web_ad where page_tag='".$page."' and name_tag='".$name."'";
	$ad_data=$webdb->getValue($sql);
	/*
	 * 用于自动添加
	 */
	if(!$ad_data) $webdb->query("insert into _web_ad (page_tag,name_tag,adtype,img,title) values ('".$page."','".$name."','img','/images/index/ad1.jpg','');");
	//自动添加over
	if(substr($ad_data['img'],0,7)!='http://') $ad_data['img']=$rooturl.$ad_data['img'];
	$htmlstr='';
	if($ad_data['adtype']=='swf'){
		$htmlstr='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="968" height="118">
					<param name="movie" value="'.$ad_data['img'].'" />
					<param name="quality" value="high" />
					<embed src="'.$ad_data['img'].'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$ad_data['img_w'].'" height="'.$ad_data['img_h'].'"></embed>
					</object>';
	}else if($ad_data['adtype']=='img'){
		if($ad_data['img_w']) $wStr='width="'.$ad_data['img_w'].'"';
		if($ad_data['img_w']) $hStr='height="'.$ad_data['img_h'].'"';
		if($ad_data['link']){
			$htmlstr='<a href="'.$ad_data['link'].'"><img src="'.$ad_data['img'].'" '.$wStr.' '.$hStr.' border="0" /></a>';
		}else{
			$htmlstr='<img src="'.$ad_data['img'].'" '.$wStr.' '.$hStr.' border="0" />';
		}
	}else{
		$htmlstr=$ad_data['html'];
	}
	return $htmlstr;
}
function topAry($local,$limit=5,$page=null){
	!$page && $page=$_SERVER['PHP_SELF'];
	$class=new swf_news();
	$class->setPage($page,$local);
	$class->setLimit(0,$limit);
	return $class->getList();
}
/*
 * 添加一个点击
 */
function addHit($type,$id){
	if(class_exists($type)){
		$class=new $type();
		$tab=$class->tableName;
	}else $tab=$type;
	$sql="update ".$tab." set hit=hit+1 where id='".$id."';";
	global $webdb;
	$webdb->query($sql);
}

/*添加时间下拉控件
 *
 */
function addTime($name,$tag='',$val1='00',$val2='00')
{
    echo "<select name='hour_$name' $tag id='hour_$name' onchange='totaltime();'>";
    for($i=0;$i<=23;$i++)
    {
        $str = '';
        if($i<10)$str = '0';
        echo "<option value='".$str.$i."'".($i==$val1?' selected':'').">$str$i</option>";
    }
    echo "</select>时";
    echo "<select name='minute_$name' $tag id='minute_$name' onchange='totaltime();'>";
        echo "<option value='00'".($val2=='00'?' selected':'').">00</option>";
        echo "<option value='30'".($val2=='30'?' selected':'').">30</option>";
    echo "</select>分";
}

/*添加时间下拉控件0~59
 *
 */
function addTimeM($name,$tag='',$val1='00',$val2='00')
{
    echo "<select name='hour_$name' $tag id='hour_$name' onchange='totaltime();'>";
    for($i=0;$i<=23;$i++)
    {
        $str = '';
        if($i<10)$str = '0';
        echo "<option value='".$str.$i."'".($i==$val1?' selected':'').">$str$i</option>";
    }
    echo "</select>时";
    echo "<select name='minute_$name' $tag id='minute_$name' onchange='totaltime();'>";
    for($i=0;$i<=59;$i++)
    {
        $str = '';
        if($i<10)$str = '0';
        echo "<option value='".$str.$i."'".($i==$val2?' selected':'').">$str$i</option>";
    }
    echo "</select>分";
}

/*添加时间下拉控件0~59
 *
 */
function addTimeMForOdd($name,$tag='',$val1='00',$val2='00')
{
    echo "<select name='hour_$name' $tag id='hour_$name'>";
    for($i=0;$i<=23;$i++)
    {
        $str = '';
        if($i<10)$str = '0';
        echo "<option value='".$str.$i."'".($i==$val1?' selected':'').">$str$i</option>";
    }
    echo "</select>时";
    echo "<select name='minute_$name' $tag id='minute_$name'>";
    for($i=0;$i<=59;$i++)
    {
        $str = '';
        if($i<10)$str = '0';
        echo "<option value='".$str.$i."'".($i==$val2?' selected':'').">$str$i</option>";
    }
    echo "</select>分";
}

/*查找门禁打卡时间
 * *
 */
function seartime($from='',$end='',$uid='')
{
    $ad = new admin();
    $card_id = $ad->getInfo($uid,'card_id','pass');
    $record = new record();
    $record->wheres = "(recorddate>='$from' and recorddate<='$end') and card_id='$card_id' ";
    $record->setOrder(' recorddate asc');
    $relist = $record->getList();
    if(!$relist)echo "&nbsp;";
    foreach($relist as $v)
    {
        preg_match_all("/(\d{2}:\d{2}:\d{2}\s\[进门\]|\d{2}:\d{2}:\d{2}\s\[出门\])/",$v['addtime'],$out);
        foreach($out[0] as $val)
        {
            $val = str_replace(']','',$val);
            $val = str_replace('[','',$val);
            echo $v['recorddate']." ".$val."<br/>"."&nbsp;&nbsp;";
        }
    }
}

/*查找指纹打卡时间
 * *
 */
function searzhiwentime($dates='',$datee='',$uid='')
{
    $ad = new admin();
    $card_id = $ad->getInfo($uid,'card_id','pass');
    $record = new record();
    $record->wheres = " recorddate>='$dates' and recorddate<='$datee' and card_id='$card_id' ";
    $record->setOrder(' recorddate asc');
    $relist = $record->getList();
    if(!$relist)echo "&nbsp;";
    foreach($relist as $v)
    {
        $ary = explode(',',$v['addtime_ex']);
        foreach($ary as $val)
        {
            echo $v['recorddate']." ".$val."<br/>&nbsp;&nbsp;";
        }
    }
}
/*查找昨天指纹打卡时间最后一个时间点*/
function getLasttodayzhiwentime($dates='',$datee='',$uid=''){
	/*
	$week=date('Y-m-d w',strtotime($dates));
	if($week=='6'||$week=='0'){
		return false;
	}*/
 	$ad = new admin();
    $card_id = $ad->getInfo($uid,'card_id','pass');
    $record = new record();
    $record->wheres = " recorddate>='$dates' and recorddate<='$datee' and card_id='$card_id' ";
    $record->setOrder(' recorddate asc');
    $relist = $record->getList();
    
    if(!$relist){
    	return true;
    }
    foreach($relist as $v){
        $ary = explode(',',$v['addtime_ex']);
    }
    $time=array_pop($ary);
    //echo $time;
    if(strtr($time,':','')>='2130'){
    	return false;
    }else{
    	return true;
    }
}
/*添加分钟下拉控件
 *
 */
function addMinute($name)
{
    
}
//计算时间戳
function acTime()
{
    $today = date('j');
    $ym = date('Y-m');
    if($today>5)
    {
        $strs = strtotime($ym."-01".' 00:00:00');   //当月第一天的时间戳
    }
    else
    {
        $pf_time = strtotime("-1 months");
        $pf_date = date("Y-m", $pf_time);
        $strs = strtotime($pf_date."-01".' 00:00:00');   //上个月第一天的时间戳
    }
    return $strs;
}

/*根据类名返回待处理列表
 *
 */
function getListByCN($cn='',$rose='',$uid='',$limit='')
{
    global $personnelId;

        $class = new $cn();
        $admin = new admin();
        $depid = $admin->getInfo($uid,'depId','pass');
        if($rose=='1')   //总经理
        {
            $class->setWhere("(perTag='2' and manTag='0' and available='1') or (depId='$depid' and depTag='0' and manTag='0' and available='1')");
        }
        elseif($rose=='2')   //部门主管
        {
            $class->setWhere("depTag='0' and depId='$depid' and manTag='0' and available='1'");
        }
        elseif($uid==$personnelId)//人事部门
        {
            $class->setWhere("depTag='2' and perTag='0' and manTag='0' and available='1'");
        }
        if($limit)$class->setLimit('0','1');
        else $class->setLimit('0','1000');
        $class->setOrder("uid asc,addDate desc");
        if($rose=='1' || $rose=='2' || $uid==$personnelId)
        $list = $class->getList();
        return $list;
}

function urlkillforRecord($key,$fullurl=true){        //多个key可以通过|分隔
                 $url=preg_replace('/&('.$key.')\=[^&]*/','', '&' . $_SERVER['QUERY_STRING']);
                 if($fullurl) $url = $_SERVER['SCRIPT_NAME'] . '?' .substr($url,1);
                         else $url=substr($url . $ext,1);
                 return $url;
        }
//根据数组分页
function getPageInfoHTMLForRecord($ary=array(),$page = 0,$url='',$pageReNum='15'){
                $recordCount = count($ary);
                $pageReNum = $pageReNum;
                $pageCount = ceil($recordCount / $pageReNum);
                
                $getPageNo = $page;
               	if(!$url){
               		$html = true;
               		$url='?'.urlkillforRecord('p',false).'&p=';
               	}
                
                if ($page){
                    
                	
                	$htmlstr='%pagestr%';
                	$fstr='<a href="%url%" class="BtnFirst">首页</a>';
                	$pstr='<a href="%url%" class="BtnPrev">上一页</a>';
                	$nstr='<a href="%url%" class="BtnNext">下一页</a>';
                	$estr='<a href="%url%" class="BtnEnd">尾页</a>';
                	$goto='<a href="%url%" class="BtnNum">%num%</a>';
                	$now='<em class="BtnNumSelect">%num%</em>';

                	if ($getPageNo>1){
                		$fstr=str_replace('%url%',$url.'1',$fstr);
                		$pstr=str_replace('%url%',$url.($getPageNo-1),$pstr);
                	}else{
                		$fstr=str_replace('%url%','javascript:;',$fstr);
                		$pstr=str_replace('%url%','javascript:;',$pstr);
                	}

                	if ($getPageNo != $pageCount && $pageCount > 0){
                		$nstr=str_replace('%url%',$url.($getPageNo+1),$nstr);
                		$estr=str_replace('%url%',$url.($pageCount),$estr);
                	}else{
                		$nstr=str_replace('%url%','javascript:;',$nstr);
                		$estr=str_replace('%url%','javascript:;',$estr);
                	}

                	$begin=(($getPageNo-4)>0)?$getPageNo-4:0;
                	$end=(($getPageNo+3)<$pageCount)?$getPageNo+3:$pageCount;
                	$numstr='';
                	for($i=$begin;$i<$end;$i++){
                		if($getPageNo==$i+1){
                			$tstr=str_replace('%num%',($i+1),$now);
                		}else{
                			$tstr=str_replace('%url%',$url.($i+1),$goto);
                			$tstr=str_replace('%num%',($i+1),$tstr);
                		}
                		$numstr.=$tstr;
                	}

                	$pagestr=$fstr.$pstr.$numstr.$nstr.$estr;

                	$pagehtml=str_replace('%pagestr%',$pagestr,$htmlstr);
                }
                return $pagehtml;
        }
        
//totaltime计算某时间段有效时间
//$fromtime 开始时间
//$totime  结束时间
//$id 为record表ID
function totaltime($fromtime='',$totime='',$id='')
{
	//$id = 291128;
    $workClass = new workday();
    if(!$fromtime)
    $fromtime = date("Y-m-d",strtotime("-2 days"));
    if(!$totime)
    {
        $totime = date("Y-m-d",strtotime("-1 days"));
    }
    $workClass->setWhere(" workday>='$fromtime' and workday<='$totime' and tag='1' ");
    $workClass->pageReNum = "1000";
    $timelist = $workClass->getArray('pass');
    $recordClass = new record();
    if($id)
    $recordClass->wheres = " id=$id ";
    $recordClass->pageReNum = "10000";
    foreach($timelist as $val)
    {
        $recordClass->wheres = " recorddate='".$val['workday']."' and gong_id!='0' and card_id!=0 ";
        $recordClass->orders = " recorddate desc";
        $recordlist = $recordClass->getList();
        foreach($recordlist as $recordval)
        {
            $timeary = array();
            if($recordval["addtime_ex"])
            $timeary[$recordval["id"]] = explode(",",str_replace('s','',$recordval['addtime_ex']));
            //调用有效上班时间
            //getTotalTime($timeary[77129]=array('1357','1823'), 77129, '2015-06-13', '[13:55:58 [进门]] [18:21:44 [出门]]');
            getTotalTime($timeary[$recordval["id"]],$recordval["id"],$recordval["recorddate"],$recordval['addtime']);
        }
    }
}

//计算有效上班时间
//$ary为指纹时间数组，$id为考勤记录id,$date当天日期,$menka为门禁卡数据
//注：使用该函数须初始化$astart,$bstart...变量
function getTotalTime($ary,$id,$date,$menka)
{
    global $astart;
    global $bstart;
    global $cstart;
    global $dstart;
    global $estart;
    global $aend;
    global $bend;
    global $cend;
    global $dend;
    global $eend;
    //检查指纹打卡是否有时间相近的记录，有取前条记录为准，去掉后条记录
    //相近记录取5分钟以内
    //处理上班期间的指纹
    $new_ary=array();//找出上班时间内的指纹时间数组
    $unset_ary=array();//删除的指纹时间数组
    //$ary= array_flip(array_flip($ary));
    /*if($ary){ //连续打卡无效判断
    	foreach ($ary as $key=>$val){
    		$time=str_replace(':','',$val);
    		$arys[] = $time;
    	}
    	foreach ($arys as $key=>$val){
    		if($arys[$key+1]!=''&&$arys[$key+1]-$val<=5){
    			if($time>='1200'&&$time<='1330'){
    				continue;
    			}
    			$unset_ary[]=substr($arys[$key+1],0,2).':'.substr($arys[$key+1],-2);
    		}
    	}
    	$ary=array_diff($arys,$unset_ary);
    	unset($arys,$unset_ary);
    }*/
    if($ary){
    	foreach ($ary as $key=>$val){
	    	$time=str_replace(':','',$val);
	    	if(($time>='0900'&&$time<='1200')||($time>='1330'&&$time<='1830')){
	    		$new_ary[]=$time;
	    	}
	    }
	    unset($val);
    }

    if($new_ary){
    	foreach ($new_ary as $key=>$val){
    		if($new_ary[$key+1]!=''&&$new_ary[$key+1]-$val<=5){
				if(($new_ary[$key+1] == '1200' && $val == '1200') ||  ($val == '1830' && $new_ary[$key+1] =='1830')){
					continue;
				}
    			$unset_ary[]=substr($new_ary[$key+1],0,2).':'.substr($new_ary[$key+1],-2);
    		}
    	}
    }
    $new_ary=array_diff($ary,$unset_ary);
    unset($ary);
    foreach ($new_ary as $val){
    	$ary[]=$val;
    }	
    //print_r($ary);
    //-----------------
    $total = "0";
    $length = count($ary);
    if($length>1)
    {
        $time09 = strtotime($date." 09:00:00");
        $time12 = strtotime($date." 12:00:00");
        $time13 = strtotime($date." 13:30:00");
        $time18 = strtotime($date." 18:30:00");

        for($i=0;$i<$length;)
        {
            //拿指纹卡第一点跟门禁卡比较，判断第一点对应点门禁卡是进门还是出门，出门则表示是加班到第2天，过滤第一点
            if($i=='0')
            {
            	
                //第一点是出门的情况
                if(ckInorOut($ary[0],$menka,$date)=='0')
                {
                    $i++;
                    continue;
                }
               
            }
            if($length>$i+1)
            {
                //$i为进门标识，$+1为出门标识
                $thistime = strtotime($date." ".$ary[$i].":00");
                //确定进门时间在哪个范围
                if($thistime<$time09)
                $astart = $thistime;
                elseif($thistime>=$time09 && $thistime<$time12)
                $bstart = $thistime;
                elseif($thistime>=$time12 && $thistime<=$time13)
                $cstart = $thistime;
                elseif($thistime>$time13 && $thistime<$time18)
                $dstart = $thistime;
                elseif($thistime>=$time18)
                $estart = $thistime;
                $thattime = strtotime($date." ".$ary[$i+1].":00");
                //确定出门时间在哪个范围
                if($thattime<$time09)
                $aend = $thattime;
                elseif($thattime>=$time09 && $thattime<$time12)
                $bend = $thattime;
                elseif($thattime>=$time12 && $thattime<=$time13)
                $cend = $thattime;
                elseif($thattime>$time13 && $thattime<$time18)
                $dend = $thattime;
                elseif($thattime>=$time18)
                $eend = $thattime;
                $dis = 0;
                if($astart && $aend)
                {
                    //不执行
                    $i++;
                    $astart = '';
                    $aend = '';
                }
                elseif($astart && $bend)
                {
                    $dis = aczaotuitime($time09,$bend,$menka,$date);
                    $total += $bend - $time09 - $dis;
                    $i += 2;
                    $astart = '';
                    $bend = '';
                }
                elseif($astart && $cend)
                {
                    $dis = aczaotuitime($time09,$time12,$menka,$date);
                    $total += 3*60*60 - $dis;
                    $i += 2;
                    $astart = '';
                    $cend = '';
                }
                elseif($astart && $dend)
                {
                    $astart = '';
                    $dend = '';
                    $i++;
                    continue;   //跳出循环
                }
                elseif($astart && $eend)
                {
                    $astart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                elseif($bstart && $bend)
                {
                    $dis = aczaotuitime($bstart,$bend,$menka,$date);
                    $c = $bend - $bstart - $dis;
                    $total += $c;
                    if($c==0)
                    $i++;
                    else
                    $i += 2;
                    $bstart = '';
                    $bend = '';
                }
                elseif($bstart && $cend)
                {
                    $dis = aczaotuitime($bstart,$time12,$menka,$date);
                    $total += $time12 - $bstart - $dis;
                    $i += 2;
                    $bstart = '';
                    $cend = '';
                }
                elseif($bstart && $dend)
                {
                    $bstart = '';
                    $dend = '';
                    break;   //跳出循环
                }
                elseif($bstart && $eend)
                {
                    $bstart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                elseif($cstart && $cend)
                {
                    $i++;           //调到下一个循环
                    $cstart = '';
                    $cend = '';
                }
                elseif($cstart && $dend)
                {
                    $dis = aczaotuitime($time13,$dend,$menka,$date);
                    $total += $dend - $time13 - $dis;
                    $i += 2;
                    $cstart = '';
                    $dend = '';
                }
                elseif($cstart && $eend)
                {
                    $dis = aczaotuitime($time13,$time18,$menka,$date);
                    $total += 5*60*60 - $dis;
                    $i = $length;
                    $cstart = '';
                    $eend = '';
                }
                elseif($dstart && $dend)
                {
                    $dis = aczaotuitime($dstart,$dend,$menka,$date);
                    $d = $dend - $dstart - $dis;
                    $total += $d;
                    if($d==0)
                    $i++;
                    else
                    $i += 2;
                    $dstart = '';
                    $dend = '';
                }
                elseif($dstart && $eend)
                {
                    $dis = aczaotuitime($dstart,$time18,$menka,$date);
                    $total += $time18 - $dstart - $dis;
                    $dstart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                elseif($estart && $eend)
                {
                    $estart = '';
                    $eend = '';
                    break;   //跳出循环
                }
                else
                {
                    break;
                }
            }
            else{                	
            	//$starttime = strtotime($date." ".$ary[0]."00");    	
            	//$endtime = strtotime($date." ".$ary[1]."00");
            	//$dis=aczaotuitime($starttime, $endtime, $menka, $date);
            	//$total += $endtime - $starttime - $dis;      	
                break;
            }
        }
    }

    $totaltime = $total/60;
    $latetime = 480 - $totaltime;
    $recordC = new record();
	
    $recordC->edit(array('latetime'=>$latetime,'totaltime'=>$totaltime), $id);
}
//判断第一点进出情况
//$zhiwen为第一条指纹记录
//$menjin为当天门禁卡数据
//$date当天日期
//返回$ret 返回0 跳过第一点，1 保留第一点
function ckInorOut($zhiwen,$menjin,$date)
{
    $zhi = strtotime($date." ".$zhiwen.":00");
    preg_match_all("/(\d{2}:\d{2}:\d{2}\s\[进门\]|\d{2}:\d{2}:\d{2}\s\[出门\])/",$menjin,$out);
    foreach($out[0] as $val)
    {
        $men = strtotime($date." ".substr($val,0,8));
        $cha = abs($men-$zhi);
        //小于5分钟则与指纹卡打卡匹配
        if($cha<=300)
        {
            if(substr($val,9)=='[出门]')
            return 0;
            elseif(substr($val,9)=='[进门]')
            return 1;
        }
    }
    $time730 = strtotime($date." "."07:30:00");
    if($zhi>$time730)   //如果没有对应门禁卡时间，则按7点半后为上班时间计算
    return 1;
    else
    return 0;
    //找不到记录则返回1 即保留第一点
    return 1;
}
//计算早退时间
//from开始时间
//to结束时间
//mendata门禁数据
//当天日期
function aczaotuitime($from,$to,$mendata,$date)
{
    preg_match_all("/(\d{2}:\d{2}:\d{2}\s\[进门\]|\d{2}:\d{2}:\d{2}\s\[出门\])/",$mendata,$out3);
        $i = 0;
        $kou = 0;
        
            foreach($out3[0] as $v)
            {
                $menout = strtotime($date." ".substr($v,0,8));
                if($menout>=$from && $menout<=$to && substr($v,9)=='[出门]')  //上班时间出门,且出去时间超过15分钟 则须扣考勤
                {
                    if($out3[0][$i+1] && substr($out3[0][$i+1],9)=='[进门]')
                    {
                        $menin = strtotime($date." ".substr($out3[0][$i+1],0,8));
                        if($menin-$menout>=900){
	                        if($menin>=$to)
	                        {
	                            $kou += $to - $menout;
	                        }
	                        else
	                        {
	                            $kou += $menin - $menout;
	                        }
                        }
                    }
                }
                $i++;
            }
    $kou = ceil($kou/60);
    if($kou<=16)
    $kou = 0;
   // echo $kou."<br>";

        
        return $kou*60;
}
//acLateTime函数
//计算迟到扣考勤时间
//$id 为调休单ID
//$uid员工ID
//$st~$et为调休单日期范围
function acLateTime($id='',$st='',$et='',$uid='')    //调休对应时间
{
    global $webdb;
    $hugh = new hugh();
    if($st&&$et)
    $hugh->wheres = " fromTime >='$st' and  fromTime <='$et' and available='1' and manTag='2'";
    elseif($id)
    $hugh->wheres = " id='$id' and available='1' and manTag='2' ";
    if($uid)
    $hugh->wheres .= " and uid='$uid'";
    $hugh->pageReNum = '10000';
    $hughlist = $hugh->getList();
    $latetime = '';
    foreach($hughlist as $info)
    {
        $admin = new admin();
        $card_id = $admin->getInfo($info['uid'],'card_id','pass');
        if($info['fromTime']==$info['toTime'])
        {
            $sttime = strtotime($info['fromTime']." ".$info['hour_s'].":".$info['minute_s'].":00");
            $entime = strtotime($info['toTime']." ".$info['hour_e'].":".$info['minute_e'].":00");
            $ststr = $info['hour_s'].":".$info['minute_s'];
            $endstr = $info['hour_e'].":".$info['minute_e'];
            $record  = new record();
            $record->wheres = "recorddate = '".$info['fromTime']."' and card_id='".$card_id."'";
            $record->pageReNum = '1';
            $res = $record->getList();
            $timelist = $res[0]['addtime_ex'];

            //迟到与早退的情况
                $timeary = explode(',',str_replace('s','',$timelist));
                $tag = 0;
                foreach($timeary as $val)
                {
                    $lt = strtotime($info['fromTime']." ".$val.":00");
                    if($lt>$sttime && $lt<$entime)
                    {
                        if($ststr=='09:00' || $ststr=='13:30')
                        {
                            $latetime += ($lt - $sttime)/60;
                        }
                        elseif($endstr=='12:00' || $endstr=='18:30')
                        {
                            $latetime += ($entime - $lt)/60;
                        }
                        else
                        {
                            $latetime += ($entime - $sttime)/60;
                        }
                        $tag = 1;
                        break;
                    }
                }
                if($tag == '0') //无打卡记录请假的情况
                {
                    $latetime += ($entime - $sttime)/60;
                }
            //$hugh->editData(array('latetime'=>$latetime),$info['id']);
        }
    }
    return $latetime;
}

//计算总上班时间
//参数 卡号，日期
function acAllTotalTime($card_id,$to)
{
    global $webdb;
    $tot = strtotime($to." 00:00:00");

    $nextday = date('Y-m-d',$tot+86400);   //加一天，如果是加班到第2天的，时间算到24:00:00
    $yestoday = date('Y-m-d',$tot-86400);   //减一天,如果最后一点是加班时间(进门)，则上班时间从0凌晨开始算起
    $record = new record();
    $record->pageReNum = 100;
    $record->wheres = "(recorddate>='".$yestoday."' and recorddate<='".$nextday."') and card_id='$card_id' ";
    $record->setOrder(' recorddate asc');
    $relist = $record->getList();
    
    foreach($relist as $list)
    {
        preg_match_all("/(\d{2}:\d{2}:\d{2}\s\[进门\]|\d{2}:\d{2}:\d{2}\s\[出门\])/",$list['addtime'],$out);
        if($list['recorddate']==$yestoday)
        $yesstr = $out[0];
        if($list['recorddate']==$to)
        {
            $today = $list['recorddate'];
            $todaystr = $out[0];
        }
        if($list['recorddate']==$nextday)
        $nextstr = $out[0];
    }

    //判断今天第一点，如果是出门，则判断昨天最后一点是否进门，如果进门，则时间总凌晨0点开始算起
    $ytotal = count($yesstr);
    $ttotal = count($todaystr);
    $ntotal = count($nextstr);
    $total = 0;
    for($i=0;$i<$ttotal;$i++)
    {
        if($i == 0)   //判断第一点
        {
            if(substr($todaystr[$i],9)=='[出门]' && $yesstr[$ytotal-1] && substr($yesstr[$ytotal-1],9)=='[进门]')
            {
                $total += strtotime($today." ".substr($todaystr[$i],0,8)) - strtotime($today." 00:00:00");
                continue;
            }
        }
        if($i == $ttotal-1)   //判断最后一点
        {
            if(substr($todaystr[$i],9)=='[进门]' && $nextstr[0] && substr($nextstr[0],9)=='[出门]')
            {
                $total += strtotime($today." 23:59:59") + 1 - strtotime($today." ".substr($todaystr[$i],0,8));  //加一是补一秒钟
            }
            break;
        }
        if($i<$ttotal-1)   //小于等于倒数第2点
        {
            if(substr($todaystr[$i],9)=='[进门]' && substr($todaystr[$i+1],9)=='[出门]')   //一对进出门记录
            {
                $total += strtotime($today." ".substr($todaystr[$i+1],0,8)) - strtotime($today." ".substr($todaystr[$i],0,8));
                $i++;
            }
            else
            continue;  //下一轮
        }
    }
    //echo $total;exit;
    $total = floor($total/60);//转换为分钟
    $webdb->query("update _web_record set totalall='$total' where recorddate='$today' and card_id='$card_id' ");
}
/*
 * 字典处理函数
 */
include($rootpath.'include/function/dic.fun.php');
/*
 * 图像函数，包括 从html截取图像，显示默认图像
 */
include($rootpath.'include/function/img.fun.php');
/*
 * 截取本页URL
 */
include($rootpath.'include/function/url.fun.php');
/*
 * 用户函数，包括 判断是否是商家，是否是管理员等，返回userID、shopID等
 */
include($rootpath.'include/function/user.fun.php');
/*
 * 文件写入函数
 */
include($rootpath.'include/function/file.fun.php');
/*
 * EXT服务器端用到的函数
 */
include($rootpath.'include/function/json.fun.php');
/*
 * 后台菜单用到的函数
 */
include($rootpath.'include/function/menu.fun.php');
/*
 * 字符串处理函数
 */
include($rootpath.'include/function/string.fun.php');
/*
 * 拼音处理函数
 */
include($rootpath.'include/function/pinyin.fun.php');
/*
 * 邮件处理函数
 */
include($rootpath.'include/function/email.fun.php');
/*
 * 树形菜单函数
 */
include($rootpath.'include/function/tree.function.php');
?>