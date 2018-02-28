<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/13
 * Time: 上午10:08
 */
class CompensateLogController extends CommonController{
    private  $compensateTable = 'u_compensate';
    private $gmtoolTable = 'u_gmtool';
    
    public function _condition(&$condition){
        //审核权限
        $typeArr = array('账号','角色名');
        $statusArr = $this->getStatusArr();
        $gameInfo = Game::model()->getGame($this->mangerInfo['game_id']);
        $gameServer = GameServer::model()->getInfo();
        $dwInfo = Dwfenbao::model()->getInfo();
        $goodsType = $this->getGoodsType();

        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $condition=array();
        if($this->mangerInfo['level'] == 0){
            $condition[] = "operator='{$this->mangerInfo['login_name']}'";
        } else {
            $condition[] = 'verify_level='.$this->mangerInfo['level']-1;
        }

        $condition[] = "status='$status'";

        $condition['param'] = array('dwInfo'=>$dwInfo, 'goodsType'=>$goodsType, 'status'=>$status, 'statusArr'=>$statusArr, 'typeArr'=>$typeArr, 'gameInfo'=>$gameInfo, 'gameServer'=>$gameServer);

    }

    //退回status -1
    public function actionRepass($id){
        $model = CompensateLog::model();
        $rs = $model->findByPk($id);
        if(!$rs)
            $this->display('数据不存在.', 0);
        if($rs->status != 0)
            $this->display('已作废或已结束.', 0);
        if($this->mangerInfo['level'] == 0)
            $this->display('流程等级为0.', 0);
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
        $model = CompensateLog::model();

        $rs = $model->findByPk($id);
        $maxLevel = $erpLevel->getMaxLevel();
        if(!$erpLevel)
            $this->display('获取流程等级出错.', 0);
        $msg = '';
        if($maxLevel == $this->mangerInfo['level']){
            $serverIdArr = explode(',', $rs->server_id);
            foreach ($serverIdArr as $serverId){
                $conn = SetConn($serverId);
                if($conn){
                    $table = $this->compensateTable;
                    $mailtable = $this->gmtoolTable;
                    $indexId = $rs->index_id;
                    $beginTime = $rs->begin_time;
                    $endTime = $rs->end_time;
                    $roleBeginTime = $rs->role_begin_time;
                    $roleEndTime = $rs->role_end_time;
                    $levelMin = $rs->level_min;
                    $levelMax = $rs->level_max;
                    $message = $rs->message;
                    $type1 = intval($rs->type1);
                    $param1 = intval($rs->param1);

                    $type2 = intval($rs->type2);
                    $param2 = intval($rs->param2);

                    $type3 = intval($rs->type3);
                    $param3 = intval($rs->param3);

                    $type4 = intval($rs->type4);
                    $param4 = intval($rs->param4);
                    
                    $type5 = intval($rs->type5);
                    $param5 = intval($rs->param5);
                    
                    $type6 = intval($rs->type6);
                    $param6 = intval($rs->param6);
                    
                    $type7 = intval($rs->type7);
                    $param7 = intval($rs->param7);
					
                    $sql = "insert into $table( time_begin, time_end, create_mintime, create_maxtime, level_min, level_max, description, 
                    award_type1, award_param1, award_type2, award_param2,  award_type3, award_param3 ,  award_type4, award_param4, server_id,
                    award_type5, award_param5, award_type6, award_param6,  award_type7, award_param7)";
                    $sql .= " values( '$beginTime', '$endTime', '$roleBeginTime', '$roleEndTime','$levelMin', '$levelMax', '$message', '$type1', '$param1',
                     '$type2', '$param2', '$type3', '$param3', '$type4', '$param4', '$serverId',
                     '$type5', '$param5', '$type6', '$param6', '$type7', '$param7') ";
                    if(false == mysqli_query($conn, $sql)){
                        write_log(ROOT_PATH.'log', 'add_good_buchang_error_', "sql=$sql,".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
                        echo "<script>alert('区服：".$serverId."发放补偿失败！');</script>";
                    } else{
                    	write_log(ROOT_PATH.'log', 'add_good_buchang_view_', "result=success,sql=$sql".date('Y-m-d H:i:s')."\r\n");
                    	$time = time();
                    	$sql = "insert into $mailtable(type)value('14')";
                    	if(false == mysqli_query($conn, $sql)){
                    		echo "<script>alert('全服补偿通知失败！');</script>";
                    		write_log(ROOT_PATH.'log', 'add_good_buchang_view_fail_', "sql=$sql,".mysqli_error($conn).date('Y-m-d H:i:s')."\r\n");
                    	}
                    }
                        

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

    public function actionAdd(){
        $gameId = isset($_POST['PlayergoodsLog']['game_id']) ? intval($_POST['PlayergoodsLog']['game_id']) : $this->mangerInfo['game_id'];
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        //$gameServer = GameServer::model()->getInfo();
        $indexId = IndexId::model()->getIndexId();
        $goodsType = $this->getGoodsType($gameId);

        $model = new CompensateLog;
        if(isset($_POST["CompensateLog"])){
            $serverId = $_POST['serverId'];
            $beginTime = strtotime($_POST['begin_time']);
            $endTime = strtotime($_POST['end_time']);
            $roleBeginDate =  $_POST['role_begin_time'];
            $roleEndDate =  $_POST['role_end_time'];
            $roleBeginTime = (isset($roleBeginDate) && !empty($roleBeginDate)) ? strtotime($roleBeginDate) : 0;
            $roleEndTime =  (isset($roleEndDate) && !empty($roleEndDate)) ? strtotime($roleEndDate) : 0;

            $model->server_id = implode(',', $serverId);
            $model->verify_level = $this->mangerInfo['level'];
            $model->begin_time = $beginTime;
            $model->end_time = $endTime;
            $model->role_begin_time = $roleBeginTime;
            $model->role_end_time = $roleEndTime;
            foreach ($_POST['CompensateLog'] as $k => $v){
                if(empty($v))
                    $_POST['CompensateLog'][$k] = intval($v);
            }
            $model->attributes=$_POST["CompensateLog"];
            if($model->save()){
                IndexId::model()->insertIndexId($indexId);
                $this->display('多服补偿物品添加成功', 1);
            }
            $this->display('添加失败'.CHtml::errorSummary($model), 0);
        }

        $this->render('add', array('model'=>$model, 'title'=>'补偿物品',  'game' => $game, 'goodsType'=>$goodsType, 'indexId'=>$indexId));
    }

    public function actionUpdate($id){
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        //$gameServer = GameServer::model()->getInfo();
        $gameId = isset($_POST['CompensateLog']['game_id']) ? $_POST['CompensateLog']['game_id'] : $this->mangerInfo['game_id'];

        $goodsType = $this->getGoodsType($gameId);
        $model = CompensateLog::model();
        $rs = $model->findByPk($id);

        $beginDate = $rs->begin_time ? date('Y-m-d H:i:s', $rs->begin_time) : '';
        $endDate = $rs->end_time ? date('Y-m-d H:i:s', $rs->end_time) : '';
        $roleBeginDate = $rs->role_begin_time ? date('Y-m-d H:i:s', $rs->role_begin_time) : '';
        $roleEndDate = $rs->role_end_time ? date('Y-m-d H:i:s', $rs->role_end_time) : '';

        if(isset($_POST["CompensateLog"])){
            $serverId = $_POST['serverId'];
            $beginTime = strtotime($_POST['begin_time']);
            $endTime = strtotime($_POST['end_time']);
            $roleBeginDate =  $_POST['role_begin_time'];
            $roleEndDate =  $_POST['role_end_time'];
            $roleBeginTime = (isset($roleBeginDate) && !empty($roleBeginDate)) ? strtotime($roleBeginDate) : 0;
            $roleEndTime =  (isset($roleEndDate) && !empty($roleEndDate)) ? strtotime($roleEndDate) : 0;

            $rs->server_id = implode(',', $serverId);
            $rs->verify_level = $this->mangerInfo['level'];
            $rs->begin_time = $beginTime;
            $rs->end_time = $endTime;
            $rs->role_begin_time = $roleBeginTime;
            $rs->role_end_time = $roleEndTime;
            $rs->status = 0;
            $rs->attributes=$_POST["CompensateLog"];

            if($rs->save()){
                $this->display('多服补偿物品编辑成功', 1);
            }
            $this->display('编辑失败'.CHtml::errorSummary($rs), 0);
        }

        $this->render('update', array(
            'model'=>$rs, 'title'=>'补偿物品',  'game' => $game, 'goodsType'=>$goodsType,
            'beginDate'=>$beginDate, 'endDate'=>$endDate, 'roleBeginDate'=>$roleBeginDate, 'roleEndDate'=>$roleEndDate,
            )
        );

    }

}