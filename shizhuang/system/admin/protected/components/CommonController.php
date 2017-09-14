<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/14
 * Time: 下午5:37
 */
require_once(ROOT_PATH.'inc/config.php');
require_once(ROOT_PATH.'inc/config_account.php');
require_once(ROOT_PATH.'inc/function.php');
class CommonController extends Controller{

    protected function checkAddGood($itemtypeId, $amount){
        if(!$itemtypeId) $this->display('物品ID错误！', 0);
        if($amount == 0)
            $this->display('物品数量不能为空！', 0);
        $addGood = AddGood::model();
        $sql = "select * from {{add_good}} where itemtype_id='$itemtypeId' limit 1";
        $rs = $addGood->findBySql($sql);
        if(!$rs)
            $this->display('物品ID'.$itemtypeId.'不存在！', 0);
        if($rs->amount_limit < $amount && $rs->amount_limit != 0)
            $this->display('物品限制数量为'.$rs->amount_limit, 0);
        return true;
    }


    protected function checkPlayer($player, $type, $serverId){
        $rs = false;
        switch ($type) {
        	case 0 :
        		$where = " name='$player'";break;
        	case 1 :
        		$where = " id='$player'";break;
        	case 2 :
        		$where = " account_id='$player'";break;
        }
        //$where = ($type ==0) ? " name='$player'" : " id = '$player'";
        /**
         * 由于u_player合服并未合表
         * 所以表还是原来的表
         */
        $sid = togetherServer($serverId);
        $conn = SetConn($sid);
        $table = subTable($serverId, 'u_player', 1000);
        $sql = "select id,name,account_id from $table where $where limit 1";
        $query = @mysqli_query($conn,$sql);
        $rows = @mysqli_fetch_assoc($query);
        if($rows)
            $rs = $rows;
        @mysqli_close($conn);
        return $rs;
    }

    protected function getGoodsType($gameId = 8){
        if($gameId == 8){
            return array(
                '' =>'请选择', 0=>'物品', 1=>'体力', 2=>'金钱',
                3=>'钻石', 7=>'VIP等级',
            );
        } else {
            return array(
                '' =>'请选择', 0=>'物品', 1=>'金钱', 2=>'vip经验',
                6=>'精灵', 7=>'钻石', 8=>'活跃度', 9=>'体力', 10=>'学习机碎片',
                11=>'抽奖积分', 12=>'暂未使用', 14=>'精力', 15=>'联盟币',
                16=>'冠军币', 17=>'全球币', 18=>'努力点'
            );
        }
    }

    protected function getStatusArr(){
        return array('-1'=>'退回','0'=>'有效','1'=>'作废','2'=>'结束');
    }

    protected function checkAccount($username, $field = false, $gameId = 8){
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false)
            return false;
        $where = ($field == false) ? "NAME='$username'" : "id='$username'";
        $sql = "select id,NAME,dwFenBaoID from account where $where limit 1";
        $msg = false;
        if(false != $query = mysqli_query($conn,$sql)){
            $rs = @mysqli_fetch_array($query);
            $msg =  !empty($rs) ? $rs : false;
        }
        @mysqli_close($conn);
        return $msg;
    }
}