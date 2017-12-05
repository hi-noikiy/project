<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/13
 * Time: 上午10:08
 */
class PlayergoodsLogController extends CommonController{
    private $gmtoolTable = 'u_system_mail';
    public function _condition(&$condition){
        //审核权限
        $typeArr = array('角色名','角色ID');
        $statusArr = $this->getStatusArr();
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $condition=array();
        if($this->mangerInfo['level'] == 0){
            $condition[] = "operator='{$this->mangerInfo['login_name']}'";
        } else {
            $condition[] = 'verify_level='.$this->mangerInfo['level']-1;
        }

        $condition[] = "status='$status'";

        $condition['param'] = array(
            'dwInfo'=>$this->getFenbao(),'status'=>$status,
            'statusArr'=>$statusArr,'typeArr'=>$typeArr
        );

    }
    //退回status -1
    public function actionRepass($id){
        $model = PlayergoodsLog::model();
        $rs = $model->findByPk($id);
        if(!$rs)
            $this->display('数据不存在.', 0);
        if($rs->status != 0)
            $this->display('已作废或已结束.', 0);
        if($this->mangerInfo['level'] == 0)
            $this->display('流程等级为0或不能操作此级流程.', 0);
        $count = $model->updateByPk($id, array('status'=>'-1', 'verify_level'=>0));
        if($count > 0 ) {
            $erpLogModel = new ErpLog;
            $erpLogModel->saveData($this->getId(), '退回', $rs->id);
            $this->display('退回成功.', 1);
        }
        $this->display('退回失败.', 0);
    }
    public function actionPass($id){
        $erpLogModel = new ErpLog;
        $erpLevel = new ErpLevel;
        $model = PlayergoodsLog::model();
        $rs = $model->findByPk($id);
        $maxLevel = $erpLevel->getMaxLevel();
        if(!$erpLevel)
            $this->display('获取流程等级出错.', 0);
        $msg = '';
        if($maxLevel == $this->mangerInfo['level']){
            $serverId = 0;
            $playerIdArr = explode(';', $rs->player_id);
            $playerNameArr = explode(';', $rs->name);
            //由于合服
            $sid = togetherServer($serverId);
            $table = $this->gmtoolTable;
            $conn = SetConn($sid);
            if($conn == false)
                $this->display('链接游服数据库失败.', 0);
			$time = time();
            foreach ($playerIdArr as $key => $userId){
                $playerName = $playerNameArr[$key];
                $sql = "insert into $table(mail_time,player_id, awardmoney, awardemoney, awardtired, awardrose, awardlily, awardnarcissus, 
                 awarditemtype1, awarditemtype2, awarditemtype3,  awarditemtype4, mailtype, mail_theme, mail_describe)";
                $sql .= " values('$time','$userId', '{$rs->awardmoney}', '{$rs->awardemoney}' ,'{$rs->awardtired}', '{$rs->awardrose}', '{$rs->awardlily}', '{$rs->awardnarcissus}',
                 '{$rs->awarditemtype1}', '{$rs->awarditemtype2}', '{$rs->awarditemtype3}', '{$rs->awarditemtype4}', '{$rs->mailtype}', '{$rs->mail_theme}', '{$rs->mail_describe}') ";
                if(false == mysqli_query($conn, $sql)){
                    echo "<script>alert('角色：".$playerName."发放物品失败！');</script>";
                    write_log(ROOT_PATH.'log', 'add_good_fail_', "player=$userId($playerName),sql=$sql".date('Y-m-d H:i:s')."\r\n");
                }
            }
            $msg .= "流程结束,审核通过.";
            $model->updateByPk($id, array('status'=>2));
            $erpLogModel->saveData($this->getId(), '流程结束', $rs->id);
        } else {
            $msg .= "审核通过.";
            $model->updateByPk($id, array('verify_level'=>$rs->verify_level+1));
            $erpLogModel->saveData($this->getId(), '通过', $rs->id);
        }
        $this->display($msg, 1);
    }
    //一健审核
    public function actionOneKeyPass(){
        $erpLogModel = new ErpLog;
        $model = new PlayergoodsLog;
        $erpLevel = new ErpLevel;
        $info = $model->getInfo();
        if(!$info)
            $this->display('没有要审核的记录.', 0);
        $maxLevel = $erpLevel->getMaxLevel();
        if($maxLevel != $this->mangerInfo['level'])
            $this->display('你没有该权限.', 0);
        foreach ($info as $v){
            $serverId = $v->server_id;
            $playerIdArr = explode(';', $v->player_id);
            $playerNameArr = explode(';', $v->name);
            //合服
            $sid = togetherServer($serverId);
            $table = $this->gmtoolTable;
            $conn = SetConn($sid);

            if($conn == false)
                $this->display('链接游服数据库失败.', 0);
            $message = $v->message;
            $type1 = intval($v->type1);
            $param1 = intval($v->param1);
            $amount1 = intval($v->amount1);
            $type2 = intval($v->type2);
            $param2 = intval($v->param2);
            $amount2 = intval($v->amount2);
            $type3 = intval($v->type3);
            $param3 = intval($v->param3);
            $amount3 = intval($v->amount3);
            $type4 = intval($v->type4);
            $param4 = intval($v->param4);
            $amount4 = intval($v->amount4);
            foreach ($playerIdArr as $key => $userId){
                $playerName = $playerNameArr[$key];
                $sql = "insert into $table(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2,  award_type3, award_param3, award_amount3,  award_type4, award_param4, award_amount4)";
                $sql .= " values(8, '$sid', '$userId' ,'$message', '$type1', '$param1', '$amount1', '$type2', '$param2', '$amount2', '$type3', '$param3', '$amount3', '$type4', '$param4', '$amount4') ";
                if(false == mysqli_query($conn, $sql)){
                    echo "<script>alert('角色：".$playerName."发放物品失败！');</script>";
                    write_log(ROOT_PATH.'log', 'add_good_fail_', "player=$userId($playerName),sql=$sql".date('Y-m-d H:i:s')."\r\n");
                }
                write_log(ROOT_PATH.'log', 'add_good_success_', "player=$userId($playerName),sql=$sql".date('Y-m-d H:i:s')."\r\n");
            }
            $model->updateByPk($v->id, array('status'=>2));
            $erpLogModel->saveData($this->getId(), '流程结束', $v->id);
        }
        $this->display('一键审核通过.',1);
    }

    public function actionAdd(){
        $model = new PlayergoodsLog;

        if(isset($_POST["PlayergoodsLog"])){
            $type = intval($_POST['PlayergoodsLog']['type']);
            $name = trim($_POST['PlayergoodsLog']['name']);
            $nameArr = explode(';', $name);
            //先循环判断角色是否都正常
            $playerIdArr = $playerNameArr = array();
            foreach ($nameArr as $k => $name){
                $playerInfo = $this->checkPlayer($name, $type , 0);
                if(!$playerInfo)
                    $this->display($name.'角色不存在！', 0);
                $playerIdArr[] = $playerInfo['id'];
                $playerNameArr[] = $playerInfo['name'];
            }
            $model->verify_level = $this->mangerInfo['level'];
            foreach ($_POST['PlayergoodsLog'] as $k => $v){
                if(empty($v))
                    $_POST['PlayergoodsLog'][$k] = intval($v);
            }
            $model->attributes=$_POST["PlayergoodsLog"];
            $model->name = implode(';', $playerNameArr);
            $model->player_id = implode(';', $playerIdArr);

            if($model->save())
                $this->display('发放物品添加成功！', 1);
            $this->display('发放物品添加失败！'.CHtml::errorSummary($model), 0);
        }
        $this->renderPartial('add', array(
            'title'=>'添加物品','model'=>$model,
        ));
    }

    public function actionUpdate($id){
        $gameId = isset($_POST['PlayergoodsLog']['game_id']) ? intval($_POST['PlayergoodsLog']['game_id']) : $this->mangerInfo['game_id'];
        $rs = PlayergoodsLog::model()->findByPk($id);

        if(isset($_POST["PlayergoodsLog"])){
            $type = intval($_POST['PlayergoodsLog']['type']);
            $name = trim($_POST['PlayergoodsLog']['name']);
            //$serverId = intval($_POST['PlayergoodsLog']['server_id']);
            $serverId = 0;
            $nameArr = explode(';', $name);
            //先循环判断角色是否都正常
            $playerIdArr = $playerNameArr = array();
            foreach ($nameArr as $k => $name){
                $playerInfo = $this->checkPlayer($name, $type, $serverId);
                if(!$playerInfo)
                    $this->display($name.'角色不存在！', 0);
                $playerIdArr[] = $playerInfo['id'];
                $playerNameArr[] = $playerInfo['name'];
            }
            $rs->verify_level = $this->mangerInfo['level'];
            $rs->attributes = $_POST["PlayergoodsLog"];
            $rs->name = implode(';', $playerNameArr);
            $rs->player_id = implode(';', $playerIdArr);
            $rs->status = 0;
            if($rs->save())
                $this->display('发放物品编辑成功！', 1);
            $this->display('发放物品编辑失败！'.CHtml::errorSummary($rs), 0);
        }
        $this->renderPartial('update', array(
            'title'=>'添加物品','model'=>$rs,'gameId'=>$gameId,'goodsType'=>$this->getGoodsType($gameId),
            'game' =>$this->getGame(),'gameServer' =>$this->getServer(),

        ));
    }

}