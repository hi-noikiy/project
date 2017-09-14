<?php
//外出类
class outrecord extends getList {
		
        public function __construct(){
                $this->tableName = '_web_outrecord';
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
            $outclass = new outrecord();
            //判断交差时间
            $outclass->wheres = " uid='".$array['uid']."' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and (fromTime='".$array['fromTime']."' or fromTime='".$array['toTime']."'"." or toTime='".$array['fromTime']."' or toTime='".$array['toTime']."')";
            $outary = $outclass->getList();
            foreach($outary as $v)
            {
                $dben = strtotime($v['toTime']." ".$v['hour_e'].":".$v['minute_e'].":00");
                $dbst = strtotime($v['fromTime']." ".$v['hour_s'].":".$v['minute_s'].":00");
                //判断时间是否有交叉
                if(($dben>$entime&&$entime>$dbst) || ($dben>$sttime&&$sttime>$dbst) || ($sttime<$dbst&&$entime>$dben))
                {
                    echo "<script>alert('时间跟其他公出单有交叉,请检查!')</script>";
                    go('index.php?type=web&do=info&cn=outrecord');
                    exit;
                }
            }
            $this->addData($array);            
        }

        public function edit($array,$id)
        {
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
         function doCancle($tag,$id)
         {
            global $webdb;
            $outinfo = $this->getInfo($id);
            //判断此单是否有效
            if($outinfo['available']=='1')
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
                $this->editData($ary, $id);//回滚数据
            }
            else
            {
                echo "<script>alert('此单已作废')</script>";
            }
         }
}
?>