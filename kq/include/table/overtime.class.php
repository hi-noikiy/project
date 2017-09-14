<?php
//加班类 overtime
class overtime extends getList {
	
    public function __construct(){
        $this->tableName = '_web_overtime';
        $this->key = 'id';
        $this->wheres = ' 1=1 ';
        $this->orders = 'id desc';
        $this->pageReNum = 15;
    }
        
    public function add($array){
        global $webdb;
        $sttime = strtotime($array['fromTime']." ".$array['hour_s'].":".$array['minute_s'].":00");
        $entime = strtotime($array['toTime']." ".$array['hour_e'].":".$array['minute_e'].":00");
        $ovclass = new overtime();
        //判断交差时间
        $ovclass->wheres = " uid='".$array['uid']."' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and (fromTime='".$array['fromTime']."' or fromTime='".$array['toTime']."'"." or toTime='".$array['fromTime']."' or toTime='".$array['toTime']."')";
        $ovary = $ovclass->getList();
        foreach($ovary as $v){
            $dben = strtotime($v['toTime']." ".$v['hour_e'].":".$v['minute_e'].":00");
            $dbst = strtotime($v['fromTime']." ".$v['hour_s'].":".$v['minute_s'].":00");
            //判断时间是否有交叉
            if(($dben>=$entime&&$entime>$dbst) || ($dben>$sttime&&$sttime>=$dbst) || ($sttime<=$dbst&&$entime>=$dben)){   	
                echo "<script>alert('时间跟其他加班单有交叉,请检查!')</script>";
                go('index.php?type=web&do=info&cn=overtime');
                exit;
            }
         }
         $sql="select count(*) as count from _web_hugh_pass where uid='{$array['uid']}' and hughdate='{$array ['fromTime']}' limit 1;";
         $hughPass=$webdb->query($sql);
         $hughPassRows=mysql_fetch_array($hughPass);
         $hughPassFlag=false;
         if($hughPassRows['count'])
         	$hughPassFlag=true;
         
         // 判断是否超过7个工作日
      $formTime=$array['fromTime'];
         $workDayList=$this->workDay();  
         if(is_array($workDayList) && !in_array($formTime, $workDayList) && $hughPassFlag==false){
         	echo "<script>alert('加班时间单时间段申请日期不能超过7天！')</script>";
         	go ( 'index.php?type=web&do=info&cn=overtime' );
         	exit ();
         }   
                  
         $jobId = $this->addData($array);
         //部门主管需存在
         //$webdb->query("update _sys_admin set unread = CONCAT_WS('?',unread,'4#$jobId') where depId='".$array['depId']."' and depMax='1'");
	}
	
	private  function workDay(){
		//检查是否超过七个工作日
		$date=date('Y-m-d', time());
		$workdaySql="select id from _web_workday where tag='1' and workday<='$date' order by id desc limit 7";
		$query=mysql_query($workdaySql);
		$array=array();
		while (@$row=mysql_fetch_array($query)){
			$array[]=$row['id'];
		}
		$bigId=intval($array[0]);
		$smallId=intval($array[6]);	
		$sql="select workday from _web_workday where id<=$bigId and id>=$smallId";
		$query=mysql_query($sql);
		$array=array();
		while (@$row=mysql_fetch_array($query)){
			$array[]=$row['workday'];
		}
		
		return $array;
	}
	
    public function edit($array,$id){    	
        global $webdb;
        $datet = date("Y-m-d H:i:s");
        if($array['depTag'])
            $array['depTime'] = $datet;
        if($array['perTag'] )
            $array['perTime'] = $datet;
        if($array['manTag'] )
            $array['manTime'] = $datet;           
        if($array['manTag']=='2'){
            $overinfo = $this->getInfo($array['id']);
            $totaltime = 0;
            $toTime = time() - strtotime($overinfo['toTime']." ".$overinfo['hour_e'].":".$overinfo['minute_e'].":00");
            //计算加班时间
            if($toTime > 0){
                $totaltime = strtotime($overinfo['toTime']." ".$overinfo['hour_e'].":".$overinfo['minute_e'].":00")-strtotime($overinfo['fromTime']." ".$overinfo['hour_s'].":".$overinfo['minute_s'].":00");
                $totaltime = $totaltime/60;//累加加班秒数转换为分钟
                $webdb->query("update _sys_admin set totalOverTime=totalOverTime+$totaltime where id ='".$overinfo['uid']."'");
                //审批后加班时间累计  ..end
                $array['addtag']='1';//修改累计状态
             }
        }
        $this->editData($array,$id);           
        //作废处理：判断是否该加班时间已经累加过,若累加过则要减掉
        if(isset($array['available']) && $array['available']!='1'){
            $infos = $this->getInfo($id,null,'pass');
            if($infos['addtag']==1){
                $totaltime = strtotime($infos['toTime']." ".$infos['hour_e'].":".$infos['minute_e'].":00")-strtotime($infos['fromTime']." ".$infos['hour_s'].":".$infos['minute_s'].":00");
                $totaltime = $totaltime/60;//转换为分钟
                $webdb->query("update _sys_admin set totalOverTime = totalOverTime-$totaltime where id='".$infos['uid']."'");
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
            //判断此单是否已经累加过时间,若已加过，则扣除
            if($overinfo['addtag']=='1'){	
                $totaltime = strtotime($overinfo['toTime']." ".$overinfo['hour_e'].":".$overinfo['minute_e'].":00")-strtotime($overinfo['fromTime']." ".$overinfo['hour_s'].":".$overinfo['minute_s'].":00");
                $totaltime = $totaltime/60;//转换为分钟
                $webdb->query("update _sys_admin set totalOverTime = totalOverTime-$totaltime where id='".$overinfo['uid']."'");
                $ary['addtag']='0';
            }
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