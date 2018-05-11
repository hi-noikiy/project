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

    function  giQSAccountHash( $string )
    {
    	$length = strlen($string);
    	$result = 0;
    	for($i=0;$i<$length;$i++){
    		$result = bcadd(bcmul($result,397), ord($string[$i]));
    	}
    	return $result;
    }

    protected function selectByName($name,$serverid = 0){
    	$nameacc = $this->giQSAccountHash( $name );
    	$table = subTable($nameacc, 'u_playername', 200);
    	$sql = "select user_id as id,name,account_id from $table where name='$name' limit 1";
    	$conn = SetConn($serverid);
    	$query = @mysqli_query($conn,$sql);
    	$rows = @mysqli_fetch_assoc($query);
    	$rs = array();
    	if($rows)
    		$rs = $rows;
    	@mysqli_close($conn);
    	return $rs;
    }
    protected function checkPlayer($player, $type, $serverId){
        return $this->selectByName($player,$serverId);
    }

    protected function getGoodsType($gameId = 8){
         return array(
                '' =>'请选择', 0=>'物品', 1=>'体力', 2=>'金币',
                3=>'钻石', 4=>'玫瑰币', 5=>'百合币', 6=>'水仙币',
                7=>'VIP等级'
            );
    }

    protected function getStatusArr(){
        return array('-1'=>'退回','0'=>'有效','1'=>'作废','2'=>'结束');
    }

    protected function checkAccount($accountid, $field = false, $gameId = 9){
        $snum = giQSModHash($accountid);
		$conn = SetConn($gameId,$snum,1);//account分表
		$acctable = betaSubTableNew($accountid,'account',999);
		$sql = "select * from $acctable where id = '$accountid' limit 1";
		$rs = false;
		$query = @mysqli_query($conn,$sql);
		$rows = @mysqli_fetch_assoc($query);
		if($rows)
			$rs = $rows;
		mysqli_close($conn);
		return $rs;
    }
}