<?php
/**
 * 检测管理员是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author  wangtao
 */
function admin_is_login(){
    $user = session('adminUserInfo');
     return (int)$user['uid'];
}


function select($nowValue, $config)
{
	// print_r($config);
	echo $config[$nowValue];
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

	// print_r($memberGroup);
	foreach ($json as $key => $value) {
		if ($value[1]) {
			$str = $value[1] . '元';
		}else{
			$str = $value[2] . '折';
		}
		echo $memberGroup[$key]['groupName'] . ':' . $str . '<br />';
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

/**
 * 导出数据为excel表格
 *@param $data    一个二维数组,结构如同从数据库查出来的数组
 *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
 *@param $filename 下载的文件名
 *@examlpe
 *$stu = M ('User');
 *$arr = $stu -> select();
 *exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
 */
function exportexcel($data=array(),$title=array(),$filename='report'){
	header("Content-Type: application/vnd.ms-excel; charset=GBK");
	header("Content-Disposition: inline; filename=\"" . $filename . ".xls");
	echo '<?xml version="1.0"?>' . "\n" . '
    <?mso-application progid="Excel.Sheet"?>' . "\n" . '
    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n" . '
    xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n" . '
    xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n" . '
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n" . '
    xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n" .'
    <Worksheet ss:Name="Sheet1">' . "\n" . '
    <Table>' . "\n";

	//导出xls 开始
	if (!empty($title)){
		$title_str = "<Row>\n";
		foreach ($title as $k => $v) {
			if(is_array($v)){
				echo '<Column ss:Width="' . $v[1] . '"/>' . "\n";
				$title_str .=  '<Cell><Data ss:Type="String">' .  $v[0] . '</Data></Cell>' . "\n";
			}else{
				$title_str .=  '<Cell><Data ss:Type="String">' .  $v . '</Data></Cell>' . "\n";

			}

		}
		$title_str .=  "</Row>\n";
	}
	echo $title_str;
	if (!empty($data)){
		foreach($data as $key=>$val){
			$cells = '';
			echo "<Row>\n";
			foreach ($val as $ck => $cv) {
				echo  '<Cell><Data ss:Type="String">' .  $cv . '</Data></Cell>'. "\n";
			}
			echo  "</Row>\n";
		}
	}
	echo '  </Table>' . "\n" . '
    </Worksheet>' . "\n" . '
    </Workbook>';
}