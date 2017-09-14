<?php
//异常考勤类
class oddtime extends getList {
		
        public function __construct(){
                $this->tableName = '_web_oddtime';
                $this->key = 'id';
                $this->wheres = ' 1=1 ';
                $this->orders = 'id desc';
                $this->pageReNum = 15;
        }
        
        public function add($array)
        {
            global $webdb;
            $addtime = '';
            if($array['amstart'])
            $addtime .= $array['amstart'].",";
            if($array['amend'])
            $addtime .= $array['amend'].",";
            if($array['pmstart'])
            $addtime .= $array['pmstart'].",";
            if($array['pmend'])
            $addtime .= $array['pmend'].",";
            
            $array['addtime'] = substr($addtime,0,-1);
            
            $oddclass = new oddtime();
            //判断交差时间
            $oddclass->wheres = " uid='".$array['uid']."' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and supdate='".$array['supdate']."'";
            $oddary = $oddclass->getList();
            if($oddary)
            {
                echo "<script>alert('该天已经申请过异常单,一天只能填写一单,请检查!')</script>";
                go('index.php?type=web&do=info&cn=oddtime');
                exit;
            }
            $this->addData($array);
        }

        public function edit($array,$id)
        {
            global $webdb;
            $datet = date("Y-m-d H:i:s");
            if($array['depTag'])
            $array['depTime'] = $datet;
            if($array['perTag'])
            $array['perTime'] = $datet;
            if($array['manTag'])
            $array['manTime'] = $datet;
            $this->editData($array,$id);
            //将数据加入考勤表中
            if($array['manTag']=='2')
            {
                $adt = $this->getInfo($array['id'], 'addtime');
                $ad = new admin();
                $card_id = $ad->getInfo($array['uid'], 'card_id', 'pass');
                $record = new record();
                $record->wheres = " card_id='$card_id' and recorddate='".$array['supdate']."'";
                $record->pageReNum = '1';
                $hastime = $record->getArray('pass');

                $time = '';

                $tmp = explode(',',$adt);
                foreach($tmp as $key=>$val)
                {
                    $tmp[$key] = $val."s";
                }
                $adt = implode(',',$tmp);
                if($hastime)    //存在指纹打卡记录
                {
                    $time = $hastime[0]['addtime_ex'];
                    if($time)
                    {
                        $newtime = getArysort($adt.",".$time);     //加入的异常时间重新排序
                        $record->editData(array('addtime_ex'=>$newtime), $hastime[0]['id'],'pass');
                        totaltime($array['supdate'],$array['supdate'],$hastime[0]['id']);            //修改迟到和有效时间
                    }
                    else   //指纹打卡字段为空时，直接将异常时间点插入
                    {
                        $record->editData(array('addtime_ex'=>$adt), $hastime[0]['id'],'pass');
                        totaltime($array['supdate'],$array['supdate'],$hastime[0]['id']);            //修改迟到和有效时间
                    }
                }
                else   //不存在打卡记录，新增一条新的打卡记录
                {
                    $res = $webdb->query("select employee_no,employee_name from employee_account where account_id='$card_id' limit 0,1");
                    if ($rs = mysql_fetch_array($res))
                    {
                        $gong_id = $rs['employee_no'];
                        $name = $rs['employee_name'];
                        $recordid = $record->addData(array('card_id'=>$card_id,'gong_id'=>$gong_id,'name'=>$name,'addtime_ex'=>$adt,'recorddate'=>$array['supdate']));
                        totaltime($array['supdate'],$array['supdate'],$recordid);            //修改迟到和有效时间
                    }
                    else
                    {
                        echo "<script>alert('该员工指纹号与门禁卡不匹配,请先校正')</script>";
                    }
                }
            }
            //作废处理：判断是否该异常时间已经累加过,若累加过则要减掉
            if(isset($array['available']) && $array['available']!='1')
            {
                $infos = $this->getInfo($id);
                if($infos['manTag']=='2')
                {
                    $ad = new admin();
                    $card_id = $ad->getInfo($infos['uid'], 'card_id', 'pass');
                    $record = new record();
                    $record->wheres = " card_id='$card_id' and recorddate='".$infos['supdate']."' ";
                    $record->pageReNum = 1;
                    $info = $record->getList();
                    if($info)
                    {
                        $oldadtime = $info[0]['addtime_ex'];
                        $newadtime = preg_replace('/\d{2}:\d{2}s,/i','',$oldadtime);
                        $newadtime = preg_replace('/,\d{2}:\d{2}s/i','',$newadtime);
                        $newadtime = preg_replace('/\d{2}:\d{2}s/i','',$newadtime);
                        $record->editData(array('addtime_ex'=>$newadtime), $info[0]['id'], 'pass');
                        //echo $newadtime;exit;
                        totaltime($info[0]['recorddate'],$info[0]['recorddate'],$info[0]['id']);            //修改迟到和有效时间
                    }
                }
            }
            //修改调休单调休扣考勤时间
            
         }
        //撤销函数
         function doCancle($tag,$id)
         {
            global $webdb;
            $oddinfo = $this->getInfo($id);
            //判断此单是否有效
            if($oddinfo['available']=='1')
            {
                $ary = array();
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
                //撤销前判断，此单是否已经加入打卡记录，如果已经加入，则扣除判断条件:$oddinfo['manTag']=='2'
                if($oddinfo['manTag']=='2')
                {
                    $ad = new admin();
                    $card_id = $ad->getInfo($oddinfo['uid'], 'card_id', 'pass');                    
                    $record = new record();
                    $record->wheres = " card_id='$card_id' and recorddate='".$oddinfo['supdate']."' ";
                    $record->pageReNum = 1;
                    $info = $record->getList();
                    if($info)
                    {
                        $oldadtime = $info[0]['addtime_ex'];
                        $newadtime = preg_replace('/\d{2}:\d{2}s,/i','',$oldadtime);
                        $newadtime = preg_replace('/,\d{2}:\d{2}s/i','',$newadtime);
                        $newadtime = preg_replace('/\d{2}:\d{2}s/i','',$newadtime);
                        $record->editData(array('addtime_ex'=>$newadtime), $info[0]['id'], 'pass');
                        //echo $newadtime;exit;
                        totaltime($info[0]['recorddate'],$info[0]['recorddate'],$info[0]['id']);            //修改迟到和有效时间
                    }
                }
                $this->editData($ary, $id);//回滚数据
            }
            else
            {
                echo "<script>alert('此单已作废')</script>";
            }
         }
}
function getArysort($str)
{
    $ary = explode(',',$str);
    $tmpary = array();
    $i = 10;
    foreach($ary as $v)
    {
        //echo "2000-12-15 ".str_replace('s','',$v).":00";
        $tmpary[$v.$i] = strtotime("2000-12-15 ".str_replace('s','',$v).":00");
        $i++;
    }
    asort($tmpary,SORT_NUMERIC);
    foreach($tmpary as $k=>$v)
    {
        $tmpary[$k] = substr($k,0,-2);
    }
    $str = implode(',',$tmpary);
    return $str;
}
?>