<?php
class sign extends getList {

        public function __construct(){
                $this->tableName = '_web_sign';
                $this->key = 'id';
                $this->wheres = ' 1=1 ';
                $this->orders = 'id desc';
                $this->pageReNum = 15;
        }

        public function add($array)
        {
            global $webdb;
            $signId = $this->addData($array);
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
            $this->editData($array,$id);
        }

        //撤销函数
         function doCancle($tag,$id)
         {
            global $webdb;
            $overinfo = $this->getInfo($id);
            //判断此单是否有效
            if($overinfo['available']=='1')
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