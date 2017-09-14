<?php
/**
 * 检测管理员是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author  wangtao
 */
function admin_is_login(){
    $user = session('adminGoodsInfo');
    return $user;
}



/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 * @author 
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? admin_is_login() : $uid;
    return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}


function formatCinema($value)
{
	if($value == -1){
		echo '全部影城';
	}else{
		$cinemaList = D('Cinema')->getCinemaList('cinemaName', array('cinemaCode' => array('in', $value)));
		$count = count($cinemaList);
		$i = 1;
		foreach ($cinemaList as $key => $value) {
			echo $value['cinemaName'];
			if($i < $count){echo '、';}
			$i++;
		}
	}
	
}

function getRulesName($value)
{
	// echo $value;
	// die();
	$menuList = D('Admin')->getMenuList('menuName', array('mid' => array('in', $value)));

	$count = count($menuList);
	$i = 1;
	foreach ($menuList as $key => $value) {
		echo $value['menuName'];
		if($i < $count){echo '、';}
		$i++;
	}
}


function memberConfig($json, $cinemaGroupList, $cinemaGroupId){
	$json = json_decode($json, true);

	foreach ($cinemaGroupList as $key => $value) {
		if($cinemaGroupId == $value['id']){
			$tempMemberGroup = $value['memberGroupInfo'];
			foreach ($tempMemberGroup as $key => $value) {
				$memberGroup[$value['groupId']] = $value;
			}
			break;
		}
	}
	if($json[1]){
		foreach ($json[1] as $key => $value) {
			echo $memberGroup[$key]['groupName'] . ':' . $value . '元<br />';
		}
	}
	if($json[2]){
		foreach ($json[2] as $key => $value) {
			echo $memberGroup[$key]['groupName'] . ':' . $value . '折<br />';
		}
	}
}

function memberWeeks($weeks){
	$array[0] = '星期日';
	$array[1] = '星期一';
	$array[2] = '星期二';
	$array[3] = '星期三';
	$array[4] = '星期四';
	$array[5] = '星期五';
	$array[6] = '星期六';

	$arrayWeeks = explode(',', $weeks);
	$count = count($arrayWeeks) - 1;
	foreach ($arrayWeeks as $key => $value) {
		echo $array[$value];
		if(($key + 1) % 3 == 0){
			echo '<br />';
		}elseif($key != $count){
			echo '、';
		}
	}

}

function jsalert($msg, $url = ''){
	echo '<script>';
	echo 'parent.jsalert("' . $msg . '", "' . $url . '");';
	echo '</script>';
	die();
}