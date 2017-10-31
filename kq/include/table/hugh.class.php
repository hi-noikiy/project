<?php
// 调休类 hugh
class hugh extends getList {
	public function __construct() {
		$this->tableName = '_web_hugh';
		$this->key = 'id';
		$this->wheres = ' 1=1 ';
		$this->orders = 'id desc';
		$this->pageReNum = 15;
	}
	public function add($array) {
		global $webdb;
		$sttime = strtotime ( $array ['fromTime'] . " " . $array ['hour_s'] . ":" . $array ['minute_s'] . ":00" );
		$entime = strtotime ( $array ['toTime'] . " " . $array ['hour_e'] . ":" . $array ['minute_e'] . ":00" );
		$hughclass = new hugh ();
		$hughclass->wheres = " uid='" . $array ['uid'] . "' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and (fromTime='" . $array ['fromTime'] . "' or fromTime='" . $array ['toTime'] . "'" . " or toTime='" . $array ['fromTime'] . "' or toTime='" . $array ['toTime'] . "')";
		// $hughary = $hughclass->getList();
		
		$sql = " select * from " . $this->tableName . " where " . " uid='" . $array ['uid'] . "' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and (fromTime='" . $array ['fromTime'] . "' or fromTime='" . $array ['toTime'] . "'" . " or toTime='" . $array ['fromTime'] . "' or toTime='" . $array ['toTime'] . "')";
		$hughary = $webdb->getList ($sql);
		
		foreach ( $hughary as $v ) {
			$dben = strtotime ( $v ['toTime'] . " " . $v ['hour_e'] . ":" . $v ['minute_e'] . ":00" );
			$dbst = strtotime ( $v ['fromTime'] . " " . $v ['hour_s'] . ":" . $v ['minute_s'] . ":00" );
			// 判断时间是否有交叉
			if (($dben >= $entime && $entime > $dbst) || ($dben > $sttime && $sttime >= $dbst) || ($sttime <= $dbst && $entime >= $dben)) {
				echo "<script>alert('时间跟其他调休单有交叉,请检查!')</script>";
				go ('index.php?type=web&do=info&cn=hugh');
				exit ();
			}
		}
		// 判断是否有足够的时间调休
		$sql_check = " select * from  _sys_admin where id ='" . $array ['uid'] . "' ";
		$query_check = mysql_query ( $sql_check );
		$result_check = mysql_fetch_assoc ( $query_check );
		$result_check ['totalOverTime'];
		
		if ((($entime - $sttime) / 60) > ($result_check ['totalOverTime'] - $result_check ['reserve'])) {
			echo "<script>alert('你的调休时间不足,请检查!')</script>";
			go ( 'index.php?type=web&do=info&cn=hugh' );
			exit ();
		}
		//判断调休时间是否在此日期之前有充值充值的时间
		if($this->getOverTime($array ['uid'], $array ['totalTime'], $result_check ['reserve'], $array ['fromTime'])){
			echo "<script>alert('调休日期之前加班时间不足！')</script>";
			go ('index.php?type=web&do=info&cn=hugh');
			exit ();
		}
		$sql="select count(*) as count from _web_hugh_pass where uid='{$array['uid']}' and hughdate='{$array ['fromTime']}' limit 1;";
		$hughPass=$webdb->query($sql);
		$hughPassRows=mysql_fetch_array($hughPass);
		$hughPassFlag=false;
		if($hughPassRows['count'])
			$hughPassFlag=true;
		// 判断是否超过7个工作日
		$addDay=$array['fromTime'];
		$workDayList=$this->workDay();
		if(is_array($workDayList) && !in_array($addDay, $workDayList) && $hughPassFlag==false){	
			echo "<script>alert('调休时间申请日期不能超过7个工作日或非工作日不必调休')</script>";
			go ( 'index.php?type=web&do=info&cn=hugh' );
			exit ();
		}
		/*
		* 1.有调休时间：9：00-9：30可以调
		* 2.前天加班到21：00，第二天9：30之后的才可以调10-10：30
		* 3.加班到23：30，第二天早上可以不来，但下午一定要准时上班
		* 4.加班超过凌晨2：00，早上可以不来，下午可以迟到，但还是必须要求
		* 5.通宵，第二天可以不来
		*
		*/
		$workDayClass=new workday();
		$fromTime=$array['fromTime'];
		$preWorkDay=$workDayClass->getOneRow($fromTime);

		$isPreDay=false; //是否要查询昨天的记录
		//从2015年6月1号执行
		if(strtotime($array['fromTime']) == strtotime('2016-02-05') || strtotime($array['fromTime']) == strtotime('2016-02-06'))
			$hughPassFlag = true;
		
		if($preWorkDay && strtotime($array ['fromTime'])>=strtotime('2015-06-01')&& $hughPassFlag==false){
			$filing=new leave_filing();
			//搜索是否报备
			$filingArr=$filing->isFiling($array['uid'], $fromTime);
			
			if(!empty($filingArr)){
				$filingFlag=false;
				foreach ($filingArr as $v){
					$fStartTime=strtotime($v['fromTime'] . " " . $v ['hour_s'] . ":" . $v ['minute_s'] . ":00");
					$fEndTime=strtotime($v ['toTime'] . " " . $v ['hour_e'] . ":" . $v ['minute_e'] . ":00");
					if($sttime==$fStartTime  && $entime<=$fEndTime){
						$filingFlag=true;
						break;
					}
				}	
				//找不到记录只能9：00-9：30可以调
				$startTime=strtotime($array ['fromTime'].' 09:00:00');
				$endTime  =strtotime($array ['toTime'].' 09:30:00');
				if($sttime==$startTime && $entime==$endTime )
					$filingFlag=true;
				//还有一种情况，昨天加班21点以后，早上9.30之后才来，下午又请假报备，有时间完善
				if($filingFlag==false){
					echo "<script>alert('调休单子填写错误！')</script>";
					go('index.php?type=web&do=info&cn=hugh');
					exit();
				}
			} else {
				$record= new record();
				//没有报备，查询是否有凌晨加班
				$recordRow=$record->getOneRow($array['name'], $fromTime);
				if(!empty($recordRow)){
					$nowMenjin=$recordRow[0]['addtime'];
					$nowZhiwen=$recordRow[0]['addtime_ex'];	
					$nowZhiwenArr=explode(',', $nowZhiwen);
					//如果一天就打一次指纹就判断通宵加班
					if($nowZhiwenArr && count($nowZhiwenArr)>0){
								
						$fourGear=strtotime($fromTime.' 02:00:00');
						$overnightTime=strtotime($fromTime.' 05:00:00');
						$isOvernightTime=strtotime($fromTime.' 07:00:00');//以七点为界定是否通宵加班
						foreach ($nowZhiwenArr as $k=>$v){
							if(strtotime($fromTime.' '.$v.':00')>$isOvernightTime)
								unset($nowZhiwenArr[$k]);
						}
					
						preg_match_all('/\d{2}:\d{2}:\d{2}\s\[出门\]/', $nowMenjin, $match);
						if(!empty($match[0])){
							$nowMenjinArr=$match[0];
							foreach ($nowMenjinArr as $k=>$v){
								$v=substr($v, 0, 8);
								$nowMenjinArr[$k]=$v;
								if(strtotime($fromTime.' '.$v)>$isOvernightTime)
									unset($nowMenjinArr[$k]);
							}					
						}
						if(!empty($nowZhiwenArr) || !empty($nowMenjinArr)){	
							$isPreDay=true;
							$nowZhiwenTime=strtotime($fromTime.' '.$nowZhiwenArr[count($nowZhiwenArr)-1]);
							$nowMenjinTime=strtotime($fromTime.' '.$nowMenjinArr[count($nowMenjinArr)-1]);
							//超过5点界定为通宵,调休一天
							if($nowZhiwenTime>$overnightTime || $nowMenjinTime>$overnightTime ){
								
							} else{	
								//可以调休早上
								$morningTime=strtotime($array ['toTime'].' 12:00:00');
								if($entime>$morningTime || $sttime>$morningTime){
									echo "<script>alert('只能早上调休！如有问题，请咨询管理员！')</script>";
									go('index.php?type=web&do=info&cn=hugh');
									exit();
								}
							}
								
						}
				}
					
				}
				if($isPreDay==false) {
					//单天没有凌晨加班，查询前一天的情况
					$preRecordRow=$record->getOneRow($array['name'], $preWorkDay);
					if(!empty($preRecordRow)){
						//找到记录
						$menjin=$preRecordRow[0]['addtime'];
						$zhiwen=$preRecordRow[0]['addtime_ex'];
						preg_match_all('/\d{2}:\d{2}:\d{2}\s\[出门\]/', $menjin, $match);				
						if(!empty($match)) {
							$lastMenjin=$match[0][count($match[0])-1]; //获取最后一个出门门禁记录18:31:48 [出门]
							$lastMenjin=substr($lastMenjin, 0, 8);
						}
						$lastZhiwen=substr($zhiwen,strlen($zhiwen)-5);//最后一个指纹

						$lastMenjinTime=strtotime($preWorkDay.' '.$lastMenjin);
						$lastZhiwenTime=strtotime($preWorkDay.' '.$lastZhiwen);
						$secondGear=strtotime($preWorkDay.' 21:00:00');
						$threeGear=strtotime($preWorkDay.' 23:30:00');
						$menjinTime=strtotime($preWorkDay.' '.$lastMenjin);
						$zhiwenTime=strtotime($preWorkDay.' '.$lastZhiwen);		
						if($menjinTime>= $threeGear || $zhiwenTime>=$threeGear){
							//可以调休早上
							$morningTime=strtotime($array ['toTime'].' 12:00:00');
							if($entime>=$morningTime || $sttime>=$morningTime){
								echo "<script>alert('只能早上调休！如有问题，请咨询管理员！')</script>";
								go('index.php?type=web&do=info&cn=hugh');
								exit();
							}
					
						} elseif($menjinTime>=$secondGear || $zhiwenTime>=$secondGear){
							//可以调休到10:30
							$morningTime=strtotime($array ['toTime'].' 10:30:00');
							if($entime>$morningTime || $sttime>$morningTime){
								echo "<script>alert('只能调休到10：30！如有问题，请咨询管理员！')</script>";
								go('index.php?type=web&do=info&cn=hugh');
								exit();
							}
						} else {
							//只能9：00-9：30可以调
							$startTime=strtotime($array ['fromTime'].' 09:00:00');
							$endTime  =strtotime($array ['toTime'].' 09:30:00');
							if($sttime!=$startTime || $entime!=$endTime ){
								echo "<script>alert('~~只能调休9：00-9：30！如有问题，请咨询管理员！')</script>";
								go('index.php?type=web&do=info&cn=hugh');
								exit();
							}
						}
					} else{
						//找不到记录只能9：00-9：30可以调
						$startTime=strtotime($array ['fromTime'].' 09:00:00');
						$endTime  =strtotime($array ['toTime'].' 09:30:00');
						if($sttime!=$startTime || $entime!=$endTime ){
							echo "<script>alert('只能调休9：00-9：30！如有问题，请咨询管理员！')</script>";
							go('index.php?type=web&do=info&cn=hugh');
							exit();
						}
					}	
				}	
			}
		}
		$jobId = $this->addData($array);
		acLateTime($jobId); // 计算调休对应的迟到早退时间
		$this->dure($array['uid']);
	}
	/**
	 * $totalTime申请的调休时间
	 * $uid会员ID
	 * $reserve 申请中的时间
	 */
	protected function getOverTime($uid, $totalTime, $reserve, $fromTime){
		$overtime=$hughtime=0;
		//统计今天之前有多少加班时间
		$sql="select sum(totalTime) as overtime from _web_overtime where depTag='2' and perTag='2' and manTag='2' and uid=$uid and fromTime<='$fromTime'";
	    $query1=mysql_query($sql);
	    $overResult=mysql_fetch_row($query1);
	    if(!empty($overResult[0]))
	    	$overtime=$overResult[0];
	    if($uid==364)
	    	$overtime+=390.2;
	    if($uid==130)
	    	$overtime+=24;
	    if($uid=='356')
	    	$overtime+= 458;  //剩余199.5 未审核:20.5    总加班269.5  已调休211
	    if($uid=='133')
	    	$overtime += 74;
	    if($uid=='330')
	    	$overtime+=0.5;
	    //统计已经调休了多少时间
		$sql="select sum(totalTime) as hughtime from _web_hugh where depTag='2' and perTag='2' and manTag='2' and uid=$uid and addDate<='$fromTime'";
		$query2=mysql_query($sql);
		$hughResult=mysql_fetch_row($query2);
		if(!empty($hughResult[0]))
			$hughtime=$hughResult[0];
		//小时转化为分钟
		if($totalTime*60+$reserve+$hughtime*60>$overtime*60)
			return true;
		return false;
	}
	
	
	private  function workDay(){
		//检查是否超过七个工作日
		$date=date('Y-m-d', time());
		$workdaySql="select workday from _web_workday where tag='1' and workday<='$date' order by id desc limit 7";
		$query=mysql_query($workdaySql);
		$array=array();
		while (@$row=mysql_fetch_array($query)){
			$array[]=$row['workday'];
		}
		return $array;
	}
	
	
	public function edit($array, $id) {
		global $webdb;
		$datet = date ( "Y-m-d H:i:s" );
		if ($array ['depTag'])
			$array ['depTime'] = $datet;
		if ($array ['perTag'])
			$array ['perTime'] = $datet;
		if ($array ['manTag'])
			$array ['manTime'] = $datet;
		
		$infos = $this->getInfo ( $id, null, 'pass' );
		// 判断是否该调休时间已经抵掉了加班时间,若抵过则要将加班时间加回去
		if (isset ( $array ['available'] ) && $array ['available'] != '1') {
			if ($infos ['addtag'] == 1) 			// 已经扣过加班时间
			{
				$totaltime = strtotime ( $infos ['toTime'] . " " . $infos ['hour_e'] . ":" . $infos ['minute_e'] . ":00" ) - strtotime ( $infos ['fromTime'] . " " . $infos ['hour_s'] . ":" . $infos ['minute_s'] . ":00" );
				$totaltime = $totaltime / 60; // 转换为分钟
				$webdb->query ( "update _sys_admin set totalOverTime = totalOverTime+$totaltime where id='" . $infos ['uid'] . "'" ); // 部门主管需存在
			}
		}
		// 将已经通过的调休单时间抵消加班时间
		if ($array ['manTag'] == '2') {
			$toTime = time () - strtotime ( $infos ['toTime'] . " " . $infos ['hour_e'] . ":" . $infos ['minute_e'] . ":00" );
			if ($toTime > 0) { // 计算调休时间
				$totaltime = strtotime ( $infos ['toTime'] . " " . $infos ['hour_e'] . ":" . $infos ['minute_e'] . ":00" ) - strtotime ( $infos ['fromTime'] . " " . $infos ['hour_s'] . ":" . $infos ['minute_s'] . ":00" );
				$array ['addtag'] = '1';
				$totaltime = $totaltime / 60; // 累加调休秒数转换为分钟
				$webdb->query ( "update _sys_admin set totalOverTime=totalOverTime-$totaltime where id ='" . $infos ['uid'] . "'" );
			}
		}
		$this->editData ( $array, $id );
		$this->dure ( $infos ['uid'] );
	}
	
	// 统计预约时间
	function dure($uid) {
		global $webdb;
		
		$this->wheres = "addtag='0' and depTag <>'1' and perTag<>'1' and manTag<>'1' and available='1' and uid='" . $uid . "'";
		// $hughList_dure = $this->getList();
		
		$sql = " select * from " . $this->tableName . " where " . "addtag='0' and depTag <>'1' and perTag<>'1' and manTag<>'1' and available='1' and uid='" . $uid . "'";
		$hughList_dure = $webdb->getList ( $sql );
		
		$total_hugh_dure = 0;
		foreach ( $hughList_dure as $key => $val ) {
			$totaltime = strtotime ( $val ['toTime'] . " " . $val ['hour_e'] . ":" . $val ['minute_e'] . ":00" ) - strtotime ( $val ['fromTime'] . " " . $val ['hour_s'] . ":" . $val ['minute_s'] . ":00" );
			$total_hugh_dure += $totaltime;
		}
		
		$total_hugh_dure = $total_hugh_dure / 60; // 累加预约调休秒数转换为分钟
		
		$webdb->query ( "update _sys_admin set reserve=$total_hugh_dure where id ='" . $uid . "'" );
	}
	
	// 撤销函数
	function doCancle($tag, $id) {
		global $webdb;
		$hughinfo = $this->getInfo ( $id );
		// 判断此单是否有效
		if ($hughinfo ['available'] == '1') {
			$ary = array ();
			// 判断此单是否已经累加过时间,若已加过，则扣除
			if ($hughinfo ['addtag'] == '1') {
				$totaltime = strtotime ( $hughinfo ['toTime'] . " " . $hughinfo ['hour_e'] . ":" . $hughinfo ['minute_e'] . ":00" ) - strtotime ( $hughinfo ['fromTime'] . " " . $hughinfo ['hour_s'] . ":" . $hughinfo ['minute_s'] . ":00" );
				$totaltime = $totaltime / 60; // 转换为分钟
				$webdb->query ( "update _sys_admin set totalOverTime = totalOverTime+$totaltime where id='" . $hughinfo ['uid'] . "'" );
				$ary ['addtag'] = '0';
			}
			// 判断申请撤销的部门
			if ($tag == 'dep') {
				$ary ['depTag'] = '0';
				$ary ['perTag'] = '0';
				$ary ['manTag'] = '0';
				$ary ['depTime'] = '';
				$ary ['perTime'] = '';
				$ary ['manTime'] = '';
			} elseif ($tag == 'per') {
				$ary ['perTag'] = '0';
				$ary ['manTag'] = '0';
				$ary ['perTime'] = '';
				$ary ['manTime'] = '';
			} elseif ($tag == 'man') {
				$ary ['manTag'] = '0';
				$ary ['manTime'] = '';
			}
			$this->editData ( $ary, $id ); // 回滚数据
			$this->dure ( $hughinfo ['uid'] );
		} else {
			echo "<script>alert('此单已作废')</script>";
		}
	}
	
	/*
	 * function acLateTime($id) //调休对应时间 { global $webdb; $info = $this->getInfo($id); $admin = new admin(); $card_id = $admin->getInfo($info['uid'],'card_id','pass'); if($info['fromTime']==$info['toTime']) { $sttime = strtotime($info['fromTime']." ".$info['hour_s'].":".$info['minute_s'].":00"); $entime = strtotime($info['toTime']." ".$info['hour_e'].":".$info['minute_e'].":00"); $record = new record(); $record->wheres = "recorddate = '".$info['fromTime']."' and card_id='".$card_id."'"; $record->pageReNum = '1'; $res = $record->getList(); $timelist = $res[0]['addtime_ex']; //迟到与早退的情况 $timeary = explode(',',$timelist); $tag = 0; foreach($timeary as $val) { $lt = strtotime($info['fromTime']." ".$val.":00"); if($lt>$sttime && $lt<$entime) { if($info['hour_s']=='09' || $info['hour_s']=='13') { $latetime = ($lt - $sttime)/60; } elseif($info['hour_e']=='13' || $info['hour_e']=='18') { $latetime = ($entime - $lt)/60; } $tag = 1; break; } } if($tag == '0') //无打卡记录请假的情况 { $latetime = ($entime - $sttime)/60; } $this->editData(array('latetime'=>$latetime),$id); } }
	 */
}
?>