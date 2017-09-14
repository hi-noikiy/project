<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/14
 * Time: 下午3:12
 * 后台基本配置信息
 */
require_once(ROOT_PATH.'inc/config.php');
require_once(ROOT_PATH.'inc/config_account.php');
require_once(ROOT_PATH.'inc/function.php');
class BtController extends Controller{
    public function actionBt(){
    	$fenbao = $this->getFenbao();
        if(isset($_POST['channel'])){
        	//$a = [6,25,30,98,128,328,648];
			$channel = $_POST['channel'];
			$fenbao = $_POST['fenbao'];
			$ydate = $_POST['ydate'];
			$rate = $_POST['rate'];
			if($channel == 0 ){
				$this->error('请选择充值渠道');
			}
			if($fenbao == 0 ){
				$this->error('请选择客户端渠道');
			}
			if(strlen($ydate) != 7){
				$this->error('日期格式不正确');
			}
			if(!$rate){
				$this->error('未选择倍数');
			}
			//当月充值玩家
            $sql = "SELECT PayID,PayName,ServerID,dwFenBaoID,CPID,clienttype FROM `web_pay_log` where 
            CPID=$channel and dwFenBaoID=$fenbao and Add_Time like '$ydate%' GROUP BY PayID,ServerID;";
            $conn = SetConn(88);
            $query = @mysqli_query($conn,$sql);
            $info = array();
            while($row = @mysqli_fetch_assoc($query)){
            	$info[] = $row;
            }
            $t = date("t",strtotime("$ydate")); //月天数
            $count = count($info);
            $insertsql = "insert into web_pay_log_copy(isbt,rpCode,game_id,PayID,PayName,ServerID,dwFenBaoID,CPID,clienttype,PayMoney,OrderID,Add_Time)values";
            for ($i=1;$i<=$t;$i++){ //循环日期
            	$mydatas = array();
            	$msql = $insertsql;
            	for ($j=0;$j<=23;$j++){ //循环小时
            		//小时内充值数据
            		$sql = "SELECT PayMoney,Add_Time FROM `web_pay_log` where
            		CPID=$channel and dwFenBaoID=$fenbao and Add_Time like '$ydate-".str_pad($i,2,'0',STR_PAD_LEFT)." ".str_pad($j,2,'0',STR_PAD_LEFT)."%';";
            		$query = @mysqli_query($conn,$sql);
            		while($row = @mysqli_fetch_assoc($query)){ 
            			$mydata = array();
            			for($z=0;$z<$rate;$z++){ // 插入倍数相同数据
            				$n = rand(0,$count-1);
            				$mtime = substr($row['Add_Time'], 0,14).str_pad(rand(0,59),2,'0',STR_PAD_LEFT).':'.str_pad(rand(0,59),2,'0',STR_PAD_LEFT);
            				$mydata['PayName'] = $info[$n]['PayName'];
            				$mydata['CPID'] = $info[$n]['CPID'];
            				$mydata['clienttype'] = $info[$n]['clienttype'];
            				$mydata['Add_Time'] = $mtime;
            				$mydata['PayID'] = $info[$n]['PayID'];
            				$mydata['ServerID'] = $info[$n]['ServerID'];
            				$mydata['dwFenBaoID'] = $info[$n]['dwFenBaoID'];
            				$mydata['payMoney'] = $row['PayMoney'];
            				$mydata['orderId'] = '8_'.$mydata['ServerID'].'_'.$mydata['PayID'].'_'.$this->random_my($mtime);;
            				$mydatas[] = $mydata;
            				$msql .= "(1,1,8,{$mydata['PayID']},'{$mydata['PayName']}','{$mydata['ServerID']}','{$mydata['dwFenBaoID']}','{$mydata['CPID']}','{$mydata['clienttype']}'
            				,'{$mydata['payMoney']}','{$mydata['orderId']}','{$mydata['Add_Time']}'),";
            			}
            		}
            	}
            	$query = @mysqli_query($conn,rtrim($msql,','));
            	if($query){ //插入成功
            		write_log(ROOT_PATH."log","btsql_{$ydate}-{$i}_",$msql .date("Y-m-d H:i:s")."\r\n");
            		write_log(ROOT_PATH."log","btdata_{$ydate}-{$i}_",json_encode($mydatas) .date("Y-m-d H:i:s")."\r\n");
            		foreach ($mydatas as $v){
            			$this->myTongjiData(8,$v['PayID'],$v['ServerID'],$v['dwFenBaoID'],0,$v['payMoney'],$v['orderId'],1,10002,0,1,strtotime($v['Add_Time']));
            		}
            	}
            }
            /*foreach ($a as $v){
            	for( $i=0;$i <${'m'.$v}; $i++){
            		$mydata = array();
            		$mtime = $ydate.'-'.str_pad(rand(10,$t),2,'0',STR_PAD_LEFT).' '.str_pad(rand(0,23),2,'0',STR_PAD_LEFT).':'.str_pad(rand(0,59),2,'0',STR_PAD_LEFT).':'.str_pad(rand(0,59),2,'0',STR_PAD_LEFT);
            		$orderId = $gameId.'_'.$servId.'_'.$rs['account_id'].'_'.$this->random_my($mtime);
            		$n = rand(0,$count-1);
            		//$mtime = $ydate.'-'.str_pad(rand(10,$t),2,'0',STR_PAD_LEFT).' '.str_pad(rand(0,23),2,'0',STR_PAD_LEFT).':'.str_pad(rand(0,59),2,'0',STR_PAD_LEFT).':'.str_pad(rand(0,59),2,'0',STR_PAD_LEFT);
            		$insertsql .= "(1,1,8,{$info[$n]['PayID']},'{$info[$n]['PayName']}','{$info[$n]['ServerID']}','{$info[$n]['dwFenBaoID']}','{$info[$n]['CPID']}','{$info[$n]['clienttype']}'
            		,'{$v}','{$orderId}','$mtime'),";
            		$mydata['PayName'] = $info[$n]['PayName'];
            		$mydata['CPID'] = $info[$n]['CPID'];
            		$mydata['clienttype'] = $info[$n]['clienttype'];
            		$mydata['Add_Time'] = $mtime;
            		$mydata['PayID'] = $info[$n]['PayID'];
            		$mydata['ServerID'] = $info[$n]['ServerID'];
            		$mydata['dwFenBaoID'] = $info[$n]['dwFenBaoID'];
            		$mydata['payMoney'] = $v;
            		$mydata['orderId'] = $orderId;
            		$mydatas[] = $mydata;
            	}
            }
            $insertsql = rtrim($insertsql,',');
            $query = @mysqli_query($conn,$insertsql);
            if($query){
            	write_log(ROOT_PATH."log","btsql_",$insertsql .date("Y-m-d H:i:s")."\r\n");
            	write_log(ROOT_PATH."log","btdata_",json_encode($mydatas) .date("Y-m-d H:i:s")."\r\n");
            	foreach ($mydatas as $v){
            		sendTongjiData(8,$v['PayID'],$v['ServerID'],$v['dwFenBaoID'],0,$v['payMoney'],$v['orderId'],1,10002,0,1,strtotime($v['Add_Time']));
            	}
            	$this->success('操作成功');
            } else{
            write_log(ROOT_PATH."log","btsql_error_",$insertsql.',messge:'.mysqli_error($conn) .date("Y-m-d H:i:s")."\r\n");
                $this->error(mysqli_error($conn));
            }*/
            	
        }
        
        $this->render('bt', array('fenbao'=>$fenbao,'channel'=>$this->getChannel(),));
    }
    
    function random_my($date) {
    	$hash = '';
    	$array = array ('~','!','@','#','$','%','^','&','*','(');
    	shuffle ( $array );
    	for($i = 0; $i < 3; $i ++) {
    		$hash .= uniqid ( rand () ). $date . $array [$i];
    	}
    	return md5 ( md5 ( $hash ) );
    }
    
    function myTongjiData($gameId, $accountId,$serverId,$channel,$lev=0,$money,$orderId,$isNew=1,$appId,$isPay=0 , $isbt = '2' ,$created = 0){
    	$tongjiArr = array();
    	$tongjiArr['accountid'] = $accountId;
    	$tongjiArr['serverid'] = $serverId;
    	$tongjiArr['channel'] = $channel;
    	$tongjiArr['lev'] = $lev;
    	$tongjiArr['money'] = $money;
    	$tongjiArr['orderid'] = $orderId;
    	$tongjiArr['is_new'] = $isNew;
    	$tongjiArr['is_pay'] = $isPay;
    	if(in_array($isbt, array(0,1))){
    		$tongjiArr['isbt'] = $isbt;
    	}
    	$tongjiArr['created_at'] = time();
    	if($created){
    		$tongjiArr['created_at'] = $created;
    	}
    	$tongjiArr['appid'] = $appId; //$tongjiServer[$gameId];
    	//发送数据
    	SAddData($tongjiArr);
    	return true;
    }
}
