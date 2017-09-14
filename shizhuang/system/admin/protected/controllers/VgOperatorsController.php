<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/4/7 下午2:33
 * @desc:时尚运营管理
 * @param:
 * @return:
 */
require_once(ROOT_PATH.'inc/config.php');
require_once(ROOT_PATH.'inc/config_account.php');
require_once(ROOT_PATH.'inc/function.php');
class VgOperatorsController extends Controller {
    private $gmtoolTable = 'u_gmtool';
    //账号封解
    private function checkAccount($account, $type, $gameId){
        $rs = false;
        $where = ($type == 1) ? "NAME='$account'" :  "id='$account'";
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        $sql = "SELECT * FROM account where $where limit 1";

        $query = @mysqli_query($conn,$sql);
        $rows = @mysqli_fetch_assoc($query);
        if($rows)
            $rs = $rows;
        mysqli_close($conn);
        return $rs;
    }

    private function updateAccount($accountId, $operate, $gameId){
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false)
            return false;
        $rs = false;
        $sql = "update account set limitType='$operate' where id='$accountId'";
        if(mysqli_query($conn,$sql))
            $rs = true;
        write_log(ROOT_PATH.'log','limit_account_err', "sql=$sql,".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
        @mysqli_close($conn);
        return $rs;
    }

    private function gameAccountLimit($accountId, $serverId){
        $conn = SetConn($serverId);//根据SvrID连接服务器
        $table = subTable($serverId, 'u_accountlimit', 1000);
        $sql = "select count(*) as count from $table where account_id='$accountId'";
        $query = @mysqli_query($conn,$sql);
        $RowCount = @mysqli_fetch_assoc($query);
        if (!$RowCount['count']){
            $sql = "insert into $table(account_id, server_id, endtime) values('$accountId', '0', '0')";
            if(mysqli_query($conn,$sql) == False)
                write_log(ROOT_PATH."log","accountlimit_log_error_", "serverId=$serverId, accountId=$accountId ".date("Y-m-d H:i:s")."\r\n"."  , sql=$sql,   ".mysqli_error($conn));
            else
                write_log(ROOT_PATH."log","accountlimit_log_", "serverId=$serverId, accountId=$accountId ".date("Y-m-d H:i:s")."\r\n");
        }
        @mysqli_close($conn);
    }


    public function actionSealingSolutionLog(){
        $model = new AccountLimit;
        $criteria = new CDbCriteria;

        if($this->mangerInfo['server_id']) {
            $username = $this->mangerInfo['login_name'];
            $criteria->condition = "server_id like '{$this->mangerInfo['server_id']}%' and operator='$username'";
        }
        if(isset($_POST['accountId']) && !empty($_POST['accountId'])){
            if(isset($criteria->condition) && !empty($criteria->condition))
                $criteria->condition .= ' and account='.intval($_POST['accountId']);
            else
                $criteria->condition = 'account='.intval($_POST['accountId']);
        }

        $count = $model->count($criteria); //统计
        $pages=new CPagination($count);
        $pages->pageSize=20;
        $pages->applyLimit($criteria);

        $criteria->limit=$pages->pageSize;

        if(isset($_POST['pagination'])){
            $pagination=$_POST['pagination'];
            $criteria->offset=($_POST['pagination']-1)*$criteria->limit;
        }else {
            $pagination=1;
            $criteria->offset=0;
        }
        //排序
        $criteria->order="id desc";

        $info=$model->findAll($criteria);
        //print_r(CJSON::encode($info));
        $this->renderPartial('sealingSolutionLog', array('info'=>$info,'pages'=>$pages,'count'=>$count,'pagination'=>$pagination));
    }
    //获取角色信息
    private function getGamePlayerField($accountId, $serverId, $field='*' ){
        $conn = SetConn($serverId);
        if($conn == false)
            return false;
        $playerTable = subTable($serverId, 'u_player', 1000);
        $sql = "select $field from $playerTable where account_id='$accountId' and serverid='$serverId'  limit 1";
        $query = @mysqli_query($conn,$sql);
        if($query == false)
            return false;
        $rs = @mysqli_fetch_assoc($query);
        return $rs ? $rs : false;
    }
    //获取帐号信息
    private function getAccountField($where, $gameId, $field='*'){
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        if($accountConn == false)
            return false;
        $conn = SetConn($accountConn);
        $sql = "select $field from account where $where limit 1";
        $query = @mysqli_query($conn,$sql);
        if($query == false)
            return false;
        $rs = @mysqli_fetch_assoc($query);
        @mysqli_close($conn);
        return $rs;
    }
    //获取角色信息
    private function getPlayerField($where, $serverId, $field){
        $conn = SetConn($serverId);
        if($conn == false)
            return false;
        @mysqli_query($conn,'set names utf8');
        $playerTable = subTable($serverId, 'u_player', 1000);
        $sql = "select $field from $playerTable where $where  limit 1";
        $query = @mysqli_query($conn,$sql);
        if($query == false)
            return false;
        $rs = @mysqli_fetch_assoc($query);
        return $rs;
    }
    public function actionSealingSolution(){
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        $gameServer = GameServer::model()->getInfo();
        $info = '';
        if(!empty($_POST)){
            $type = intval($_POST['type']);
            $name = $_POST['name'];
            $operate = intval($_POST['operate']);
            $reason = trim($_POST['reason']);
            $gameId = $_POST['gameid'];
            if(!$gameId)
                $this->display('请选择游戏ID', 0);
            if(empty($_POST['serverId']) || !is_array($_POST['serverId']))
                $this->display('请选择区服', 0);
            $serverId = $_POST['serverId']; //array
            if(!$name)
                $this->display('请填写账号或者账号ID', 0);
            if($operate !=2 && $reason == '')
                $this->display('请填写封号/解号原因', 0);
            $info = $this->checkAccount($name, $type, $gameId);
            if($info == false)
                $this->display('账号或者账号ID有误！', 0);

            $accountId = $info['id'];
            $gamePlayerInfo = $this->getGamePlayerField($accountId, $serverId[0], 'id');
            if($gamePlayerInfo == false)
                $this->display('角色信息有误！', 0);
            $gamePlayerId = $gamePlayerInfo['id'];
            if($operate != 2){
                if($info['limitType'] == $operate)
                    $this->display('该账号状态与操作相同！', 0);

                $adminName = Yii::app()->user->name;
                $date = date('Y-m-d H:i:s');
                $sql = "insert into {{account_limit}} (operator, addtime, reason, account,server_id, type, status)";
                $sql .=" values('$adminName', '$date', '$reason','$accountId', '{$serverId[0]}' ,'$operate','$operate')";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $command->execute();
                //写入下架标识
                if($operate == 1){
                    foreach ($serverId as $v){
                        //游服变更accountId改为角色ID
                        $this->gameAccountLimit($gamePlayerId, $v);
                    }
                }
                if($this->updateAccount($accountId, $operate, $gameId) == false)
                    $this->display('执行失败，请重试！', 0);

                $this->display('操作成功！', 1);
            }

        }

        $this->renderPartial('sealingSolution', array('title' => '账号封解', 'game' => $game, 'gameServer' => $gameServer, 'info' => $info));
    }
    //客服命令
    public function actionServiceCommand(){
        $typeList = array(''=>'请选择...', 1=>'强制下线', 2=>'禁言', 3=>'禁言解封', 6=>'游戏公告',8=>'给玩家发送邮件', /*66=>'取消游戏公告'*/);
        if($this->mangerInfo['login_name'] == 'admin')
            $typeList['7'] = '模拟充值';
        $gameId = isset($_POST['gameId']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
        if(!empty($_POST)){

            $serverid = intval($_POST['serverid']);
            $type = intval($_POST['type']);
            $playerName = trim($_POST['name']);
            $banTime = intval($_POST['banTime']);
            $message= trim($_POST['message']);
            $awardType1 = intval($_POST['awardType1']);
//            if(!$serverid )
//                $this->error('区服不能为空！');
            if(!$type)
                $this->error('命令类型不能为空！');
            $conn = SetConn($serverid);

            $table = subTable($serverid, $this->gmtoolTable, 1000);
            if(!in_array($type, array(6, 66))){
                $playerTable = subTable($serverid, 'u_player', '1000');
                $sql = "select * from $playerTable where name='$playerName' and serverid='$serverid'";
                if(false == $query = mysqli_query($conn,$sql))
                    $this->error('sql error!');
                $playerInfo = @mysqli_fetch_assoc($query);
                if(!$playerInfo)
                    $this->error('角色不存在！');
                $playerId = $playerInfo['id'];
                $accountId = $playerInfo['account_id'];
            }
            if($type == 1 || $type == 3){
                $sql = "insert into $table(type, serverid, param) values('$type', '$serverid', '$playerId')";
                $tag = ($type == 1) ? "强制下线  玩家:$playerName($playerId) 区服:$serverid" : "禁言解封  玩家:$playerName($playerId) 区服:$serverid";
            }else if($type == 2){
                $talkEndtime = strtotime($_POST['talkEndtime']);
                if(!$talkEndtime)
                    $this->error('禁言时间不能为空！');
                $sql = "insert into $table(type, serverid, param, award_type1) values('$type', '$serverid', '$playerId', '$talkEndtime')";
                $tag = "禁言  玩家:$playerName($playerId) 区服:$serverid 禁言时间:$talkEndtime";
            }else if($type == 6){
                if($banTime>60)
                    $this->error('循环分钟数不能超过60！');
                if(!$message)
                    $this->error('游戏公告不能为空！');
                $bulletinEndtime = strtotime($_POST['bulletinEndtime']);

                if(!$bulletinEndtime)
                    $this->error('游戏公告到期时间不能为空！');

                $sql = "insert into $table(type, serverid, param, message, award_type1) values('$type','$serverid', '$banTime', '$message', '$bulletinEndtime')";
                $tag = "游戏公告  区服:$serverid 滚动间隔时间(分):$banTime 截至时间:$bulletinEndtime 消息:$message";
            } elseif($type == 8) {
                if(!$message)
                    $this->error('信息不能为空！');
                $sql = "insert into $table(type, serverid, param, message) values('$type', '$serverid', '$playerId', '$message')";

                $tag = "玩家发邮件  玩家:$playerName($playerId) 区服:$serverid 消息:$message";
            } else if($type == 7){
                if($awardType1 <=0)
                    $this->error('充值金额不能小等于0');
                $sql = "insert into $table(type, serverid, param, award_type1) values('$type', '$serverid', '$accountId', '$awardType1')";

                $tag = "模拟充值  玩家:$playerName($playerId) 账号ID:$accountId 区服:$serverid 金额:$awardType1";
            }
            $conn = SetConn($serverid);
            $queryR = @mysqli_query($conn,$sql);
            $ip = Yii::app()->request->getUserHostAddress();
            $adminName = Yii::app()->user->name;
            write_log(ROOT_PATH.'log', 'customer_command_view_', "$tag, ip=$ip, operator=$adminName ".date('Y-m-d H:i:s')."\r\n");
            @mysqli_close($conn);
            if($queryR)
                $this->success('客服命令执行成功！');
            write_log(ROOT_PATH.'log', 'customer_command_error_', "sql=$sql, ".mysqli_error($conn)." operator=$adminName ".date('Y-m-d H:i:s')."\r\n");
            $this->error('失败');
        }

        $this->renderPartial('serviceCommand', array(
            'title'=>'客服命令','gameId'=>$gameId,'typeList'=>$typeList,
            'game' =>$this->getGame(),'gameServer'=>$this->getServer(),
        ));
    }
    //滚动消息取消
    public function actionCancelServiceCommand($id, $serverId, $gameId){
        $url = $this->createUrl('operators/bulletinLog/gameid/'.$gameId.'/serverid/'.$serverId.'/type/6');
        $conn = SetConn($serverId);
        $table = subTable($serverId, $this->gmtoolTable, 1000);
        $sql = "select type from $table where id='$id' limit 1";
        $query = @mysqli_query($conn,$sql);
        $info = @mysqli_fetch_assoc($query);
        if($info['type'] != 6)
            $this->display('非公告，不能禁用！', 0, $url);
        $sql = "update $table set status=1 where id='$id'";
        if(false != mysqli_query($conn,$sql))
            $this->display('禁用公告成功', 1, $url);
        else
            $this->display('禁用公告失败', 0, $url);
    }
    //账号信息查询
    public function actionAccountInfo(){
        $type = 0;
        $info = array();
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
        if(!empty($_POST)){
            $type = intval($_POST['type']);
            $serverId = intval($_POST['serverid']);
            $name = trim($_POST['name']);
            $gameId = $_POST['gameid'];

            if(is_null($serverId))
                $this->display('请选择区服...', 0);
            if(!$name)
                $this->display('请输入查询的角色！', 0);
            if($type == 0 || $type == 1){
                $where = ($type ==0) ? " name='$name' and serverid='$serverId'" : " id='$name'";
            } else if($type == 2){
                $field = 'id,NAME,dwFenBaoID,channel_account,limitType,dwFenBaoUserID';
                $where = "NAME='$name'";
                $result = $this->getAccountField($where, $gameId, $field);
                $accountId                  = intval($result['id']);
                $info['id']                 = $accountId;
                $info['NAME']               = $result['NAME'];
                $info['dwFenBaoID']         = $result['dwFenBaoID'];
                $info['channel_account']    = $result['channel_account'];
                $info['limitType']	        = $result['limitType'];
                $info['dwFenBaoUserID']     = $result['dwFenBaoUserID'];
                $where = " account_id='$accountId' and serverid='$serverId'";
            }else {
                $where = " account_id='$name' and serverid='$serverId'";
            }
            $plyerField = 'id,name,account_id,serverid,closet_value,vip_lev,money,emoney';
            //获取角色信息
            $rs_player = $this->getPlayerField($where, $serverId, $plyerField);
            $info['server_id']      = $rs_player['serverid'];
            $info['user_id']        = $rs_player['id'];
            $info['name']           = $rs_player['name'];
            $info['closet_value']   = $rs_player['closet_value']; //衣柜值
            $info['vip_lev']        = $rs_player['vip_lev']; //VIP等级
            $info['money']          = $rs_player['money']; //金币
            $info['emoney']         = $rs_player['emoney']; //钻石
//            $info['rose']           = $rs_player['rose']; //玫瑰币
//            $info['lily']           = $rs_player['lily']; //百合币
//            $info['narcissus']      = $rs_player['narcissus']; //水仙花

            if(!isset($info['id'])){
                $accountId = intval($rs_player['account_id']);
                $field = 'id,NAME,dwFenBaoID,channel_account,limitType,dwFenBaoUserID';
                $where = "id='$accountId'";
                $result = $this->getAccountField($where, $gameId, $field);
                $accountId                  = intval($result['id']);
                $info['id']                 = $accountId;
                $info['NAME']               = $result['NAME'];
                $info['dwFenBaoID']         = $result['dwFenBaoID'];
                $info['channel_account']    = $result['channel_account'];
                $info['limitType']	        = $result['limitType'];
                $info['dwFenBaoUserID']     = $result['dwFenBaoUserID'];
            }
        }
        $this->renderPartial('accountInfo', array(
            'title'=>'账号信息查询','gameId'=>$gameId,'info'=>$info,'type'=>$type,
            'game'=>$this->getGame(),'gameServer' =>$this->getServer(),
        ));
    }
    //日志查看
    public function actionLog(){
        $logName = $info = '';
        $logList = array(
            ''					        =>'请选择日志...',
            'add_good_buchang_view_'	=>'多服补偿',
            'customer_command_view_'	=>'客服命令',
            'logRecord_log_'			=>'异常日志',
            'card_err_'                 =>'游服充值未到账日志',
        );
        if(!empty($_POST)){
            $time =  $_POST['date'];
            $logName = trim($_POST['logName']);
            if(file_exists(ROOT_PATH.'log/'.date('ym', strtotime($time))."/$logName".date('ymd', strtotime($time)).'.txt')) {
                $fullFileName = ROOT_PATH.'log/'.date('ym', strtotime($time))."/$logName".date('ymd',strtotime($time)).'.txt';
                if($logName == 'logRecord_log_'){
                    //异常日志下载
                    header("Content-Type:application/force-download");
                    header("Content-Disposition:attachment;filename=".basename($fullFileName));
                    readfile($fullFileName);
                    return ;
                } else {
                    $handle = @fopen($fullFileName, 'r');
                    while (!@feof($handle)) {
                        $buffer = @fgets($handle, 4096);
                        if($buffer)
                            $info .= $buffer.'<br>';
                    }
                    fclose($handle);
                }
            }
        }
        $this->renderPartial('log',  array('title'=>'日志查看', 'logName'=>$logName, 'logList' => $logList, 'info'=>$info));
    }
    //游戏公告
    protected function bulletin($serverId, $type, $banTime, $message, $bulletinEndtime, $index_id){
        $conn = SetConn($serverId);
        if($conn == false )
            return false;
        $table = subTable($serverId, $this->gmtoolTable, 1000);
        $sql = "insert into $table(index_id,type, serverid, param, message, award_type1) values('$index_id','$type', '$serverId', '$banTime', '$message', '$bulletinEndtime')";
        if(false == mysqli_query($conn,$sql))
            return false;
        return true;
    }
    public function actionBulletin(){
        $typeList = array(6=>'游戏公告');
        $indexId = IndexId::model()->getIndexId();
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        if(!empty($_POST)){
            $gameId = $_POST['gameid'];
            $serverId = $_POST['serverId'];
            $banTime = $_POST['banTime'];
            $index_id = $_POST['index_id'];
            $bulletinEndtime = $_POST['bulletinEndtime'];
            if(!$gameId)
                $this->display('请选择游戏ID', 0);
            if(empty($serverId) || !is_array($serverId))
                $this->display('请选择区服', 0);
            if($banTime>60)
                $this->error('循环分钟数不能超过60！');

            $type = intval($_POST['type']);
            $message = trim($_POST['message']);
            $bulletinEndtime = strtotime($bulletinEndtime);
            foreach ($serverId as $v){
                $rs = $this->bulletin($v, $type, $banTime, $message, $bulletinEndtime, $index_id);
                if($rs === false)
                    echo "<script>alert('$v 滚动消息发送失败.')</script>";
            }
            $this->display('公告发送成功！', 1);
        }
        $this->renderPartial('bulletin', array('title' =>'游戏公告', 'game' =>$game, 'typeList'=>$typeList, 'indexId'=>$indexId));
    }
    public function actionBulletinCancel(){
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        if(!empty($_POST)){
            $serverId = $_POST['serverId'];
            $indexId = $_POST['indexId'];
            //if(!$indexId)
            //    $this->display('请填写标识', 0);
            if(empty($serverId) || !is_array($serverId))
                $this->display('请选择区服', 0);

            foreach ($serverId as $v){
                $conn = SetConn($v);
                if($conn == false)
                    continue;
                $table = subTable($v, $this->gmtoolTable, 1000);
                $sql = "update $table set status=1 where type=6 and index_id='$indexId'";
                if(false == mysqli_query($conn,$sql)){
                    echo "<script>alert('$v 公告禁用失败.')</script>";
                }
            }
            $this->display('公告发送成功！', 1);
        }
        $this->renderPartial('bulletinCancel', array('title' => '游戏公告', 'game' => $game));
    }


    public function actionBulletinLog(){
        $gameId = Yii::app()->request->getParam('gameid');
        $gameId = isset($gameId) ? $gameId : $this->mangerInfo['game_id'];
        $serverId =Yii::app()->request->getParam('serverid');
        $type = Yii::app()->request->getParam('type');
        $typeList = array(6=>'游戏公告');
        $info = array();
        $count = 0;
        if(isset($_POST['pagination'])){
            $pagination=$_POST['pagination'];
            $offset=($_POST['pagination']-1)*20;
        }else {
            $pagination=1;
            $offset=0;
        }
        if($serverId >= 0 && !empty($type)){
            $conn = SetConn($serverId);
            $where = 'status=0 and serverid='.$serverId;
            $table = subTable($serverId, $this->gmtoolTable, 1000);
            $where .= $type ? " and type='$type'" : '';
            $sqlCount = "select * from $table where $where";
            $queryCount = @mysqli_query($conn,$sqlCount);
            $count = @mysqli_num_rows($queryCount);
            $sql = "select * from $table where $where order by id desc limit 20 offset $offset";
            $query = @mysqli_query($conn,$sql);
            $i = 0;
            while (@$rows = mysqli_fetch_assoc($query)){
                $info[$i]['id'] = $rows['id'];
                $info[$i]['index_id'] = $rows['index_id'];
                $info[$i]['type'] = $rows['type'];
                $info[$i]['serverid'] = $rows['serverid'];
                $info[$i]['award_type1'] = $rows['award_type1'];
                if($type == 2){
                    $playerTable = subTable($serverId, 'u_player', '1000');
                    $sql = "select name from $playerTable where id='{$rows['param']}' limit 1";
                    $query = @mysqli_query($conn,$sql);
                    $result = @mysqli_fetch_array($query);
                    $info[$i]['param'] = $rows['param'].'('.$result['name'].')';
                } else {
                    $info[$i]['param'] = $rows['param'];
                }
                $info[$i]['message'] = $rows['message'];
                $info[$i]['status'] = $rows['status'];
                $i++;
            }
        }



        $this->renderPartial('bulletinLog', array(
            'info'=>$info,'pages'=>20,'count'=>$count,'pagination'=>$pagination, 'type'=>$type,
            'title'=>'客服命令日志', 'game' => $this->getGame(), 'gameServer' => $this->getServer(), 'typeList'=>$typeList,
            'gameId'=>$gameId, 'serverId'=>$serverId,
        ));
    }
}