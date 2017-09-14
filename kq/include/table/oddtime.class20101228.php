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
            if(count($array['timepoint'])=='2')
            {
                $array['fromtime'] = $array['timepoint'][0];
                $array['totime'] = $array['timepoint'][1];
            }
            else
            {
                echo "<script>alert('时间段数据有误,请检查!')</script>";
                go('index.php?type=web&do=info&cn=oddtime');
                exit;
            }
            
            $sttime = strtotime($array['supdate']." ".$array['fromtime']);
            $entime = strtotime($array['supdate']." ".$array['totime']);
            $oddclass = new oddtime();
            //判断交差时间
            $oddclass->wheres = " uid='".$array['uid']."' and available='1' and manTag!='1' and perTag!='1' and depTag!='1' and supdate='".$array['supdate']."'";
            $oddary = $oddclass->getList();
            foreach($oddary as $v)
            {
                $dben = strtotime($v['supdate']." ".$v['totime']);
                $dbst = strtotime($v['supdate']." ".$v['fromtime']);
                //判断时间是否有交叉
                if(($dben>=$entime&&$entime>$dbst) || ($dben>$sttime&&$sttime>=$dbst) || ($sttime<=$dbst&&$entime>=$dben))
                {
                    echo "<script>alert('时间跟其他异常单有交叉,请检查!')</script>";
                    go('index.php?type=web&do=info&cn=oddtime');
                    exit;
                }
            }
            $this->addData($array);
        }

        public function edit($array,$id)
        {
            $datet = date("Y-m-d H:i:s");
            if($array['depTag'])
            $array['depTime'] = $datet;
            if($array['perTag'])
            $array['perTime'] = $datet;
            if($array['manTag'])
            $array['manTime'] = $datet;
            $this->editData($array,$id);
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
                $this->editData($ary, $id);//回滚数据
            }
            else
            {
                echo "<script>alert('此单已作废')</script>";
            }
         }
}
?>