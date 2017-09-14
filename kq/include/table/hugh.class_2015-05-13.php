<?php
//调休类 hugh
class hugh extends getList {

    public function __construct(){
        $this->tableName = '_web_hugh';
        $this->key = 'id';
        $this->wheres = ' 1=1 ';
        $this->orders = 'id desc';
        $this->pageReNum = 15;
    }

    public function add($array)
    {
        global $webdb;
        $sttime = strtotime($array['fromTime']." ".$array['hour_s'].":".$array['minute_s'].":00");
        $entime = strtotime($array['toTime']." ".$array['hour_e'].":".$array['minute_e'].":00");
        $hughclass = new hugh();
        $hughclass->wheres = " uid='".$array['uid']."' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and (fromTime='".$array['fromTime']."' or fromTime='".$array['toTime']."'"." or toTime='".$array['fromTime']."' or toTime='".$array['toTime']."')";
        //$hughary = $hughclass->getList();

        $sql = " select * from ".$this->tableName." where "." uid='".$array['uid']."' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and (fromTime='".$array['fromTime']."' or fromTime='".$array['toTime']."'"." or toTime='".$array['fromTime']."' or toTime='".$array['toTime']."')";
        $hughary = $webdb->getList($sql);

        foreach($hughary as $v)
        {
            $dben = strtotime($v['toTime']." ".$v['hour_e'].":".$v['minute_e'].":00");
            $dbst = strtotime($v['fromTime']." ".$v['hour_s'].":".$v['minute_s'].":00");
            //判断时间是否有交叉
            if(($dben>=$entime&&$entime>$dbst) || ($dben>$sttime&&$sttime>=$dbst) || ($sttime<=$dbst&&$entime>=$dben))
            {
                echo "<script>alert('时间跟其他调休单有交叉,请检查!')</script>";
                go('index.php?type=web&do=info&cn=hugh');
                exit;
            }
        }

        //判断是否有足够的时间调休
        $sql_check = " select * from  _sys_admin where id ='".$array['uid']."' ";
        $query_check=mysql_query($sql_check);
        $result_check=mysql_fetch_assoc($query_check);
        $result_check['totalOverTime'];

        if((($entime-$sttime)/60)>($result_check['totalOverTime']-$result_check['reserve'])){
            echo "<script>alert('你的调休时间不足,请检查!')</script>";
            go('index.php?type=web&do=info&cn=hugh');
            exit;
        }
        $jobId = $this->addData($array);
        acLateTime($jobId);    //计算调休对应的迟到早退时间
        $this->dure($array['uid']);
    }

    public function edit($array,$id)
    {

        global $webdb;
        $datet = date("Y-m-d H:i:s");
        if($array['depTag'] )
        $array['depTime'] = $datet;
        if($array['perTag'] )
        $array['perTime'] = $datet;
        if($array['manTag'] )
        $array['manTime'] = $datet;

        $infos = $this->getInfo($id,null,'pass');
        //判断是否该调休时间已经抵掉了加班时间,若抵过则要将加班时间加回去
        if(isset($array['available']) && $array['available']!='1')
        {
            if($infos['addtag']==1)//已经扣过加班时间
            {
                $totaltime = strtotime($infos['toTime']." ".$infos['hour_e'].":".$infos['minute_e'].":00")-strtotime($infos['fromTime']." ".$infos['hour_s'].":".$infos['minute_s'].":00");
                $totaltime = $totaltime/60;//转换为分钟
                $webdb->query("update _sys_admin set totalOverTime = totalOverTime+$totaltime where id='".$infos['uid']."'");//部门主管需存在
            }
        }
        //将已经通过的调休单时间抵消加班时间
        if($array['manTag']=='2')
        {
            $toTime = time() - strtotime($infos['toTime']." ".$infos['hour_e'].":".$infos['minute_e'].":00");
            if($toTime > 0)
            {   //计算调休时间
                $totaltime = strtotime($infos['toTime']." ".$infos['hour_e'].":".$infos['minute_e'].":00")-strtotime($infos['fromTime']." ".$infos['hour_s'].":".$infos['minute_s'].":00");
                $array['addtag'] = '1';
                $totaltime = $totaltime/60;//累加调休秒数转换为分钟
                $webdb->query("update _sys_admin set totalOverTime=totalOverTime-$totaltime where id ='".$infos['uid']."'");
            }
        }
        $this->editData($array,$id);
        $this->dure($infos['uid']);
    }

    //统计预约时间
    function dure($uid)
    {
        global $webdb;

        $this->wheres = "addtag='0' and depTag <>'1' and perTag<>'1' and manTag<>'1' and available='1' and uid='".$uid."'";
        // $hughList_dure = $this->getList();

        $sql = " select * from ".$this->tableName." where "."addtag='0' and depTag <>'1' and perTag<>'1' and manTag<>'1' and available='1' and uid='".$uid."'";
        $hughList_dure = $webdb->getList($sql);

        $total_hugh_dure = 0;
        foreach($hughList_dure as $key => $val)
        {
            $totaltime = strtotime($val['toTime']." ".$val['hour_e'].":".$val['minute_e'].":00")-strtotime($val['fromTime']." ".$val['hour_s'].":".$val['minute_s'].":00");
            $total_hugh_dure += $totaltime;
        }

        $total_hugh_dure = $total_hugh_dure/60;//累加预约调休秒数转换为分钟

        $webdb->query("update _sys_admin set reserve=$total_hugh_dure where id ='".$uid."'");
    }

    //撤销函数
    function doCancle($tag,$id)
    {
        global $webdb;
        $hughinfo = $this->getInfo($id);
        //判断此单是否有效
        if($hughinfo['available']=='1')
        {
            $ary = array();
            //判断此单是否已经累加过时间,若已加过，则扣除
            if($hughinfo['addtag']=='1')
            {
                $totaltime = strtotime($hughinfo['toTime']." ".$hughinfo['hour_e'].":".$hughinfo['minute_e'].":00")-strtotime($hughinfo['fromTime']." ".$hughinfo['hour_s'].":".$hughinfo['minute_s'].":00");
                $totaltime = $totaltime/60;//转换为分钟
                $webdb->query("update _sys_admin set totalOverTime = totalOverTime+$totaltime where id='".$hughinfo['uid']."'");
                $ary['addtag']='0';
            }
            //判断申请撤销的部门
            if($tag=='dep')
            {
                $ary['depTag'] = '0';
                $ary['perTag'] = '0';
                $ary['manTag'] = '0';
                $ary['depTime'] = '';
                $ary['perTime'] = '';
                $ary['manTime'] = '';
            }
            elseif($tag=='per')
            {
                $ary['perTag'] = '0';
                $ary['manTag'] = '0';
                $ary['perTime'] = '';
                $ary['manTime'] = '';
            }
            elseif($tag=='man')
            {
                $ary['manTag'] = '0';
                $ary['manTime'] = '';
            }
            $this->editData($ary, $id);//回滚数据
            $this->dure($hughinfo['uid']);
        }
        else
        {
            echo "<script>alert('此单已作废')</script>";
        }
    }

        /*function acLateTime($id)    //调休对应时间
        {
            global $webdb;
            $info = $this->getInfo($id);
            $admin = new admin();
            $card_id = $admin->getInfo($info['uid'],'card_id','pass');
            if($info['fromTime']==$info['toTime'])
            {
                $sttime = strtotime($info['fromTime']." ".$info['hour_s'].":".$info['minute_s'].":00");
                $entime = strtotime($info['toTime']." ".$info['hour_e'].":".$info['minute_e'].":00");
                $record  = new record();
                $record->wheres = "recorddate = '".$info['fromTime']."' and card_id='".$card_id."'";
                $record->pageReNum = '1';
                $res = $record->getList();
                $timelist = $res[0]['addtime_ex'];

                //迟到与早退的情况
                    $timeary = explode(',',$timelist);
                    $tag = 0;
                    foreach($timeary as $val)
                    {
                        $lt = strtotime($info['fromTime']." ".$val.":00");

                        if($lt>$sttime && $lt<$entime)
                        {
                            if($info['hour_s']=='09' || $info['hour_s']=='13')
                            {
                                $latetime = ($lt - $sttime)/60;
                            }
                            elseif($info['hour_e']=='13' || $info['hour_e']=='18')
                            {
                                $latetime = ($entime - $lt)/60;
                            }
                            $tag = 1;
                            break;
                        }
                    }
                    if($tag == '0') //无打卡记录请假的情况
                    {
                        $latetime = ($entime - $sttime)/60;
                    }
                $this->editData(array('latetime'=>$latetime),$id);
            }
        }*/
}
?>