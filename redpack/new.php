<?php
date_default_timezone_set('PRC');
$_GET['act']();
set_time_limit(0);
error_reporting(0);
ini_set('memory_limit', '1024M');
//
function geteudemon()
{
	$con = mysqli_connect("localhost","root","root",'tw');
	if (!$con)
	{
		die('Could not connect: ' . mysqli_error());
	}
	for($i=1;$i<=70;$i++){
    		$preser = str_pad($i,3,0,STR_PAD_LEFT);
    		$serverid = '8'.$preser;
    		echo $serverid.PHP_EOL;
    		$querysql = "insert into my_eudemon(player_id,server_id,name) select a.player_id,$serverid as server_id,b.name from u_eudemon$preser a inner join u_player$preser b
    		 on a.player_id=b.id  where template_id=101294 group by player_id ON DUPLICATE KEY UPDATE `name`=VALUES(name);";
    		$result1 = mysqli_query($con,$querysql);
    	}
	 }
	 function getcard()
	 {
	 	$con = mysqli_connect("localhost","root","root",'tw');
	 	if (!$con)
	 	{
	 		die('Could not connect: ' . mysqli_error());
	 	}
	 	for($i=1;$i<=70;$i++){
	 		$preser = str_pad($i,3,0,STR_PAD_LEFT);
	 		$serverid = '8'.$preser;
	 		echo $serverid.PHP_EOL;
	 		$querysql = "insert into my_card(player_id,server_id,name,data) select b.id,$serverid as server_id,b.name,sum(a.data) from u_card$preser a 
	 		 inner join u_player$preser b  on a.account_id=b.account_id where a.time_stamp like '170504%' group by a.account_id
	 		ON DUPLICATE KEY UPDATE `name`=VALUES(name),`data`=VALUES(data);";
	 		$result1 = mysqli_query($con,$querysql);
	 		//$result1->close();
	 	}
	 }

	 function getitem(){
	 	$con = mysqli_connect("localhost","root","root",'tw');
	 	if (!$con)
	 	{
	 		die('Could not connect: ' . mysqli_error());
	 	}
	 	$querysql ="select * from my_card";
	 	$result = mysqli_query($con,$querysql);
		$data=$userdata= array();
		while ($row=mysqli_fetch_assoc($result))
		{
			$data[] = $row;
		}
		$result->close();
		foreach ($data as $value){
			if(!isset($userdata[$value['server_id'].'_'.$value['player_id']])){
				$userdata[$value['server_id'].'_'.$value['player_id']]=array();
			}
			$userdata[$value['server_id'].'_'.$value['player_id']]['server_id'] = $value['server_id'];
			$userdata[$value['server_id'].'_'.$value['player_id']]['player_id'] = $value['player_id'];
			$userdata[$value['server_id'].'_'.$value['player_id']]['name'] = $value['name'];
			$userdata[$value['server_id'].'_'.$value['player_id']]['data'] = $value['data'];
			$userdata[$value['server_id'].'_'.$value['player_id']]['turncount'] = $value['turncount'];
			for($i=1;$i<=12;$i++){
				$userdata[$value['server_id'].'_'.$value['player_id']]['type'.$i]=0;
				$userdata[$value['server_id'].'_'.$value['player_id']]['itemid'.$i]=0;
				$userdata[$value['server_id'].'_'.$value['player_id']]['num'.$i]=0;
			}
		}
		$querysql ="select * from my_sum_turn";
		$result = mysqli_query($con,$querysql);
		$data= array();
		while ($row=mysqli_fetch_assoc($result))
		{
			$data[] = $row;
		}
		$result->close();
		foreach ($data as $v){
			if(!isset($userdata[$v['server_id'].'_'.$v['player_id']])){
				continue;
			}
			if(!isset($count[$v['server_id'].'_'.$v['player_id']])){
				$count[$v['server_id'].'_'.$v['player_id']]=0;
			}
			$count[$v['server_id'].'_'.$v['player_id']]+=1;
			$userdata[$v['server_id'].'_'.$v['player_id']]['type'.$count[$v['server_id'].'_'.$v['player_id']]] = $v['type'];
			$userdata[$v['server_id'].'_'.$v['player_id']]['itemid'.$count[$v['server_id'].'_'.$v['player_id']]] = $v['getid'];
			$userdata[$v['server_id'].'_'.$v['player_id']]['num'.$count[$v['server_id'].'_'.$v['player_id']]] = $v['getnum'];
		}
		//print_r($userdata);die;
		
		$userdata=array_values($userdata);
		//print_r($userdata);die;
		/*$pagenum=4;
		$ai=ceil(count($userdata)/$pagenum);
		for($i=0;$i<$pagenum;$i++){
			$bbb = array_slice($userdata, $i * $ai ,$ai);
			
		}*/
		$myfile = fopen("C:/Users/Administrator/Desktop/1.txt", "w") or die("Unable to open file!");
			
		fwrite($myfile, json_encode($userdata,JSON_UNESCAPED_UNICODE));
		fclose($myfile);
		
		//print_r($userdata[0]);die;
		/*print_r($userdata);die;
		exportexcel($userdata);*/
	 }
	 /**
	  * 导出excel
	  * @param unknown $data
	  * @param unknown $title
	  * @param string $filename
	  */
	 function exportexcel($mdata=array(),$title=array(),$filename='report'){
	 	header("Content-Type: application/vnd.ms-excel;");
	 	header("Content-Disposition: attachment; filename=\"" . $filename . ".xls");
	 	echo '<?xml version="1.0"?>' . "\n" . '
    <?mso-application progid="Excel.Sheet"?>' . "\n" . '
    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n" . '
    xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n" . '
    xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n" . '
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n" . '
    xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
	 
	 	echo '<Worksheet ss:Name="Sheet1">' . "\n" . '
    <Table>' . "\n";
	 	$pagenum=10;
	 	 $ai=ceil(count($mdata)/$pagenum);
	 	 for($i=0;$i<$ai;$i++){
	 	 $bbb[] = array_slice($mdata, $i * $pagenum ,$pagenum);
	 	 }
	 	 foreach ($bbb as $ke=>$data){
	 	 echo '<Worksheet ss:Name="Sheet'.$ke.'">' . "\n" .
	 	 '<Table>' . "\n";
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
	 	 </Worksheet>' . "\n" ;
	 	 }
	 	 echo '</Workbook>';
	 	//导出xls 开始
	 	/*if (!empty($title)){
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
    </Workbook>';*/
	 	exit;
	 }
	 function getturn()
	 {
	 	$con = mysqli_connect("localhost","root","root",'tw');
	 	if (!$con)
	 	{
	 		die('Could not connect: ' . mysqli_error());
	 	}
	 	for($i=1;$i<=70;$i++){
	 		$preser = str_pad($i,3,0,STR_PAD_LEFT);
	 		$serverid = '8'.$preser;
	 		$count = 0;
	 		$handle = fopen("C:\Users\Administrator\Desktop\TurntableAward\TurntableAward $serverid 2017-5-4.log", "r");
	 		if ($handle) {
	 			while (!feof($handle)) {
	 				$count ++;
	 				$sql = "insert into my_turn(player_id,server_id,turncount,create_time,type,getid,getnum)values";
	 				$buffer = fgets($handle, 4096);
	 				$sarr = explode('--', $buffer);
	 				$date = strtotime($sarr[0]);
	 				$main = str_replace(',', '&', $sarr[1]);
	 				$leftdata = substr($main, 0,strpos($main, '&usAwardType'));
	 				parse_str($leftdata, $data);
	 				$mysql = "({$data['idActor']},$serverid,{$data['ucDropNum']},{$date},";
	 				$rightdata = substr($main, strpos($main, '&usAwardType'));
	 				$data = explode('&usAwardTyp', $rightdata);
	 				foreach ($data as $v){
	 					parse_str($v, $value);
	 					if($value){
	 						$sql .= $mysql.$value['e'].','.$value['dwItemType'].','.$value['dwAmount'].'),';
	 					}
	 				}
	 				$result1 = mysqli_query($con,rtrim($sql,','));
	 			}
	 		}
	 	}
	 
	 }

function run()
    {
    	set_time_limit(0);
    	//$sql = "insert into u_register(mac,accountid,serverid,channel,client_type,client_version,ip,created_at,appid,regway,reg_date)values";
    	$sql = '';
    	$count = 0;
        $handle = fopen("C:\Users\Administrator\Desktop\\1.log", "r");
        $i = 0;
        if ($handle) {
            while (!feof($handle)) {
            	$count ++;
            	$sql .= "insert into u_register(mac,accountid,serverid,channel,client_type,client_version,ip,created_at,appid,regway,reg_date)values";
                $buffer = fgets($handle, 4096);
                $sarr = explode('#', $buffer);
                $date = strtotime($sarr[0]);
                $time = date('Ymd',$date);
                parse_str($sarr[1], $arr);
                $json = base64_decode($arr['data_raw']);
                $data = json_decode($json,true);
                $sql .= "('{$data['mac']}',{$data['accountid']},0,{$arr['channel']},'{$arr['client_type']}',{$arr['client_version']},".ip2long($arr['ip']).",{$date},10002,0,{$time}) 
                ON DUPLICATE KEY UPDATE `reg_date`=VALUES(reg_date);" . PHP_EOL;
            	$i++;
               //if($i>10)break;
            }
            
            //echo "TOTAL:$i\n";
            fclose($handle);
            //echo rtrim($sql,',');
        }
        echo $count;die;
       /* $myfile = fopen("C:/Users/Administrator/Desktop/1.sql", "w") or die("Unable to open file!");

        fwrite($myfile, $sql);
        fclose($myfile);*/
    }
