<?php
/*
 * 递归某个表,获得下拉菜单
 */
function dgArray($tab,$where='',$pid=0,$ex='',$nf='name',$pf='parent_id',$kf='id'){
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

function arrayOption($ary,$def=null,$py=true,$desc=true){
	foreach($ary as $val){
		if($val['id']==$def) $selected='selected';
		else $selected='';
		$str.='<option value="'.$val['id'].'" '.$selected.'>'.$val['py'].' '.$val['name'].'</option>';
	}
	return $str;
}

function resetArray($parent_id,$array){
	$retVal=array();
	foreach($array as $val){
	   if($parent_id==$val["parent_id"]){
	    $retVal[]=$val;
	    //$retVal[count($retVal)-1]['indent'].='&nbsp;&nbsp;&nbsp;&nbsp;';
	    $tmp=array();
	    $tmp=resetArray($val["id"],$array);
	    if($tmp){
	     foreach($tmp as $val2){
	      $retVal[]=$val2;
	      $retVal[count($retVal)-1]['indent'].='&nbsp;&nbsp;&nbsp;&nbsp;';
		  $retVal[count($retVal)-1]['flag']++;
	     }
	    }
	    unset($tmp);
	   }
	}
	return $retVal;
}
function hasSub($id,$array){
	$retVal=false;
	foreach($array as $ary){
		if($ary["parent_id"]==$id){
			$retVal=true;
			break;
		}
	}
	return $retVal;
}
function createmenu($imgRootPath,$parent_id=0){
	$result="";
	$array=getMenuData();
	$rs=resetArray($parent_id,$array);
	$result= '<div class="tree_menu">';
	for($i=0;$i<count($rs);$i++){
		for($j=0;$j<$rs[$i]["flag"];$j++){
			if($rs[$i]["name"]&&(($j+1)==$rs[$i]["flag"])){
				if($rs[$i+1]["parent_id"]==$rs[$i]["parent_id"]){
					$result.= '<img src="'.$imgRootPath.'images/tree/H.gif">';
				}else{
					if($rs[$i]["flag"]<=($rs[$i+1]["flag"]+1)){
						$result.= '<img src="'.$imgRootPath.'images/tree/H.gif">';
					}else{
						$result.= '<img src="'.$imgRootPath.'images/tree/L.gif">';
					}
				}
				//echo "<hr />";
			}else{
				$result.= '<img src="'.$imgRootPath.'images/tree/I.gif">';
			}
		}
		
		$rsid=$rs[$i]["id"];
		$flag_img="nfolder.gif";
		$onclick="";
		if(hasSub($rs[$i]["id"],$array)){
			$flag_img="ofolder.gif";
			//隐藏
			if($rs[$i]["hide_sub"]==1) $flag_img="folder.gif";
			
			$onclick=' onclick="OnClickOutline(\''.$imgRootPath.'\','.$rsid.')" ';
		}
		
		$result.= '<img class="Outline" id="ID'.$rsid.'" style="CURSOR: pointer" '.$onclick.' alt="" src="'.$imgRootPath.'images/tree/'.$flag_img.'" />&nbsp;';
		if($rs[$i]["link"]){
			$result.='<a href="'.$rs[$i]["link"].'">';
		}else{
			$result.='<a href="javascript:void(0);"';
			if($onclick) $result.=$onclick;
			$result.='>';
		}
		$result.= $rs[$i]["name"];
		$result.='</a>';
		if($rs[$i]["pid"]==0) $result.= "<br />";
		
		//</div>
		if($rs[$i]["flag"]>$rs[$i+1]["flag"]){
			$div_n=$rs[$i]["flag"]-$rs[$i+1]["flag"];
			for($div_i=0;$div_i<$div_n;$div_i++){
				$result.=  "</div>";
			}
		}
		//end </div>
		//<div>
		if(hasSub($rs[$i]["id"],$array)){
			$result.= '<div id="ID'.$rsid.'d"';
			if($rs[$i]["hide_sub"]==1) $result.= ' style="display:none" ';
			$result.= '>';
		}
		//end <div>
	}
	$result.= '  <div id="infodisplay"><font color="#999999">点击＋展开节点</font></div>';
	$result.= '</div>';
	return $result;
}
function get_hide_sub($hide_sub){
	$result="";
	if($hide_sub==1){
		$result="是";
	}else $result="否";
	return $result;
}

function getMenuData(){
	global $webdb;
	return $webdb->getList("select * from _sys_section order by sort,id asc");
}
?>