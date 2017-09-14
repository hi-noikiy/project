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
        $startTime_09 = strtotime('09:00:00');
        $endTime_12 = strtotime('12:00:00');
        $startTime_13 = strtotime('13:30:00');
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
		$fromTime = $array['fromTime'];
		$uid = $array['uid'];
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
        $leaveId = $this->addData($array);    
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
        $this->editData($array,$id);    
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