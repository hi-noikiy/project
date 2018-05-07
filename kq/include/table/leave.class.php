<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 请假逻辑增加
* ==============================================
* @date: 2015-8-11
* @author: luoxue
* @return:
*/
class leave extends getList {
	protected $worktableName;
	protected $filingtableName;

    public function __construct(){
        $this->tableName = '_web_leave';
        $this->worktableName = '_web_workday';
		$this->annualtableName = '_web_annual_leave';
		$this->btableName = '_web_other_leave';
        $this->filingtableName = '_web_leave_filing';
        $this->key = 'id';
        $this->wheres = ' 1=1 ';
        $this->orders = 'id desc';
        $this->pageReNum = 15;
    }

    public function add($array){
        global $webdb;
        $sttime = strtotime($array['fromTime']." ".$array['hour_s'].":".$array['minute_s'].":00");
        $entime = strtotime($array['toTime']." ".$array['hour_e'].":".$array['minute_e'].":00");
        $leaveclass = new leave();
        $leaveclass->wheres = " uid='".$array['uid']."' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and fromTime='".$array['fromTime']."'";
        $leaveary = $leaveclass->getList();
        foreach($leaveary as $v){
        	$dben = strtotime($v['toTime']." ".$v['hour_e'].":".$v['minute_e'].":00");
        	$dbst = strtotime($v['fromTime']." ".$v['hour_s'].":".$v['minute_s'].":00");
        	//判断时间是否有交叉
        	if(($dben>=$entime&&$entime>$dbst) || ($dben>$sttime&&$sttime>=$dbst) || ($sttime<=$dbst&&$entime>=$dben)){
        		echo "<script>alert('时间跟其他请假单有交叉,请检查!')</script>";
        		go('index.php?type=web&do=info&cn=leave');
        		exit;
        	}
        }
        /*上班时间
         *早上09:00~12:00
         *下午13:30~18:30
         */
        $startTime_09 = strtotime('08:00:00');
        $endTime_12 = strtotime('12:00:00');
        $startTime_13 = strtotime('13:00:00');
        $entime_18 = strtotime('18:30:00');
        
        $userStart = strtotime($array['hour_s'].":".$array['minute_s'].":00");
        $userEnd = strtotime($array['hour_e'].":".$array['minute_e'].":00");
        
        if ($array['fromTime']!=$array['toTime']){
        	echo "<script>alert('开始日期跟起始日期不相等！')</script>";
        	go('index.php?type=web&do=info&cn=leave');
        	exit;
        }
        
        if (!($userStart>=$startTime_09 && $userEnd<=$endTime_12) && !($userStart>=$startTime_13 && $userEnd<=$entime_18)) {
        	echo "<script>alert('请假时间段必须在09:00~12:00或者13:30~18:30之内！')</script>";
        	go('index.php?type=web&do=info&cn=leave');
        	exit;
        }
        //检查是否工作日
       	$sql = "select * from ".$this->worktableName." where workday='".$array['fromTime']."' limit 1";
        $workDay = $webdb->getList($sql);
        if ($workDay[0]['tag']!=1){
        	echo "<script>alert('非工作日不必请假！')</script>";
        	go('index.php?type=web&do=info&cn=leave');
        	exit;
        }
		//请假报备
		$uid = $array['uid'];
		$by = 0;
		if($array['leaveType'] == '哺乳假'){
			$bresult = $this->getBAnnual($uid);
			if($bresult){
				$by = $bresult['allTime']-$bresult['useTime'];//哺乳假剩余时长
			}
			if($by<$array['totalTime']){
				echo "<script>alert('哺乳假剩余时长不足')</script>";
				go('index.php?type=web&do=info&cn=leave');
				exit;
			}
		}else{
			$fromTime = $array['fromTime'];
			$sql = "select * from ".$this->filingtableName." where fromTime='$fromTime' and uid='$uid'";
			$filingList = $webdb->getList($sql);
			if (empty($filingList)){
				echo "<script>alert('还未报备，请咨询财务！')</script>";
				go('index.php?type=web&do=info&cn=leave');
				exit;
			}else{
				$filingFlag = false;
				//如果在报备区间之内都可以
				foreach ($filingList as $v){
					$fStartTime=strtotime($v['fromTime'] . " " . $v ['hour_s'] . ":" . $v ['minute_s'] . ":00");
					$fEndTime=strtotime($v ['toTime'] . " " . $v ['hour_e'] . ":" . $v ['minute_e'] . ":00");
			
					if($sttime>=$fStartTime  && $entime<=$fEndTime){
						$filingFlag = true;
						break;
					}
				}
			}
			if($filingFlag == false){
				echo "<script>alert('请假时间跟报备时间不符，请咨询管理员！')</script>";
				go('index.php?type=web&do=info&cn=leave');
				exit;
			}
			$ny = 0;
			$ny1 = 0;
			$ny2 = 0;//请去年时长
			$ny3 = 0;//请今年时长
			if($array['leaveType'] == '年假'){
				$fy = date('Y',strtotime($array['fromTime']));
				$md = date('md',strtotime($array['fromTime'])); //使用开始日期
					
				$nowy = date('Y');
				$lasty = date('Y',strtotime('-1 year'));
				$result = $this->getAnnual($uid,$nowy);
				if($result){
					$ny = $result['allTime']-$result['useTime'];//今年年假剩余时长
				}
				if($md<'0301' || $fy == $lasty){ //先取去年年假时间
					$result1 = $this->getAnnual($uid,$lasty);//去年年假
					if($result1){
						$ny1 = ($result1['allTime']-$result1['useTime']);//去年年假剩余时长
					}
					if($fy == $nowy){
						$lastd = (strtotime($nowy.'0301')-strtotime($nowy.$md))/3600/3;//可使用去年时长
						if($lastd<$ny1){
							$ny1 = $lastd;
						}
					}
				}
				if($ny+$ny1<$array['totalTime']){
					echo "<script>alert('年假剩余时长不足')</script>";
					go('index.php?type=web&do=info&cn=leave');
					exit;
				}
				if($ny1 >= $array['totalTime']){//只使用去年时长
					$ny2 = $array['totalTime'];
				}else{
					$ny2 = $ny1;
					$ny3 = $array['totalTime']-$ny1;
				}
				$array['timeDetail'] = $ny3.'_'.$ny2;
			}
		}
        
        $leaveId = $this->addData($array);    
		if($leaveId){ //插入数据成功
			if($array['leaveType'] == '年假'){
				if($ny2>0){//使用去年时长
					$this->updateAnnual($uid,$lasty,$ny2);
				}
				if($ny3>0){//使用今年时长
					$this->updateAnnual($uid,$nowy,$ny3);
				}
			}elseif($array['leaveType'] == '哺乳假'){
				$this->updateBAnnual($uid,$array['totalTime']);
			}
		}
	}
	/**
	* 获取年假时间
	**/
    public function getAnnual($uid,$year){
        global $webdb;
        $sql = "select * from ".$this->annualtableName." where uid='$uid' and useYear='$year' limit 1";
        $result = $webdb->getValue($sql);   
		return $result;
    }
	/**
	* 更新年假数据
	**/
    public function updateAnnual($uid,$year,$useTime){
        global $webdb;
        $sql = "update ".$this->annualtableName." set useTime=useTime+$useTime where uid='$uid' and useYear='$year' limit 1";
        $result = $webdb->query($sql);   
		return $result;
    }
    /**
     * 获取哺乳假时间
     **/
    public function getBAnnual($uid){
    	global $webdb;
    	$sql = "select * from ".$this->btableName." where uid='$uid' limit 1";
    	$result = $webdb->getValue($sql);
    	return $result;
    }
    /**
     * 更新哺乳假数据
     **/
    public function updateBAnnual($uid,$useTime){
    	global $webdb;
    	$sql = "update ".$this->btableName." set useTime=useTime+$useTime where uid='$uid' limit 1";
    	$result = $webdb->query($sql);
    	return $result;
    }
	public function edit($array,$id){
        global $webdb;
        $datet = date("Y-m-d H:i:s");
        if($array['depTag'] )
            $array['depTime'] = $datet;
        if($array['perTag'] )
            $array['perTime'] = $datet;
        if($array['manTag'] )
            $array['manTime'] = $datet;
        if($this->editData($array,$id)){ // 处理成功后更改年假数据
			$result2 = $webdb->getValue("select * from ".$this->tableName." where id='$id' limit 1");   
			if($array['depTag'] == '1' || $array['perTag'] == '1' || $array['manTag'] == '1' || $array['available'] == '0'){
				if($result2['leaveType'] == '年假'){ //不通过或者作废回退年假时长
					$uid = $result2['uid'];
					$totaltime = explode('_',$result2['timeDetail']);
					$ny = isset($totaltime[0])?$totaltime[0]:0;//回退今年时长
					$ny1 = isset($totaltime[1])?$totaltime[1]:0;//回退去年时长
					$nowy = date('Y');
					$lasty = date('Y',strtotime('-1 year'));
					if($ny>0){
						$this->updateAnnual($uid,$nowy,-$ny);
					}
					if($ny1>0){
						$this->updateAnnual($uid,$lasty,-$ny1);
					}
				}elseif($result2['leaveType'] == '哺乳假'){
					$this->updateBAnnual($uid,-$result2['totalTime']);
				}
			}
			
		}    
    }

    //撤销函数
    public function doCancle($tag,$id){
        global $webdb;
        $overinfo = $this->getInfo($id);
        //判断此单是否有效
        if($overinfo['available']=='1'){
            $ary = array();
            //判断申请撤销的部门
            if($tag=='dep'){
                $ary['depTag'] = '0';
                $ary['perTag'] = '0';
                $ary['manTag'] = '0';
                $ary['depTime'] = '';
                $ary['perTime'] = '';
                $ary['manTime'] = '';
            }elseif($tag=='per'){
                $ary['perTag'] = '0';
                $ary['manTag'] = '0';
                $ary['perTime'] = '';
                $ary['manTime'] = '';
            }elseif($tag=='man'){
                $ary['manTag'] = '0';
                $ary['manTime'] = '';
            }
            $this->editData($ary, $id);//回滚数据
		}else
			echo "<script>alert('此单已作废')</script>";
	}
}
?>