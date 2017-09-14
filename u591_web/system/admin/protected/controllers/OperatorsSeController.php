<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/20
 * Time: 下午5:03
 */
require_once(ROOT_PATH.'inc/config.php');
require_once(ROOT_PATH.'inc/config_account.php');
require_once(ROOT_PATH.'inc/function.php');
class OperatorsSeController extends Controller{
    public function actionSendFenBaoMessage(){
        $dwfenbaoList = Dwfenbao::model()->getInfo('626001');
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        if(!empty($_POST)){
            $gameId = intval($_POST['game_id']);
            if(!isset($_POST['serverId']) || empty($_POST['serverId']))
                $this->error('请选择区服...');
            if(empty($_POST['dwFenBaoID']))
                $this->error('请选择渠道');
            if(count($_POST['dwFenBaoID']) > 2)
                $this->error('渠道不能多选');
            $message = $_POST['message'];
            $serverIdArr = $_POST['serverId']; //array
            $channel = $_POST['dwFenBaoID'][1];
            global $accountServer;
            $accountConn = $accountServer[$gameId];
            $conn = SetConn($accountConn);
            if($conn == false)
                $this->error('账号库连接错误.');
            $sql = "select id from account where dwFenBaoID = '$channel'";
            $query = @mysqli_query($conn,$sql);
            while ($row = @mysqli_fetch_assoc($query)){
                $accountId = $row['id'];
                $this->sendMessage($accountId, $serverIdArr, $message);
            }
            $this->success('发送消息成功');
        }
        $this->render('sendFenbaoMessage', array('dwfenbaoList'=>$dwfenbaoList,'game'=>$game));
    }
    private function sendMessage($accountId, $serverIdArr, $message){
        foreach ($serverIdArr as $serverId){
            $playerTable = subTable($serverId, 'u_player', '1000');
            $sql = "select id from $playerTable where account_id='$accountId' and serverid='$serverId' limit 1";
            $conn = SetConn($serverId);
            if($conn == false)
               continue;
            $query = @mysqli_query($conn,$sql);
            $rs = @mysqli_fetch_assoc($query);
            if(isset($rs['id'])){
                $playerId = $rs['id'];
                $table = subTable($serverId, 'u_gmtool', 1000);
                $sql = "insert into $table(type, serverid, param, message) values('8', '$serverId', '$playerId', '$message');";
                if(mysqli_query($conn,$sql) == false){
                   write_log(ROOT_PATH."log","fenbaoID_error_", "sql=$sql ".date("Y-m-d H:i:s") . "\r\n" );
                } else {
                    write_log(ROOT_PATH."log","fenbaoID_success_", "sql=$sql ".date("Y-m-d H:i:s") . "\r\n" );
                }
            }
        }
    }
}