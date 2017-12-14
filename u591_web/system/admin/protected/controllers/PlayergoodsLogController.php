<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/13
 * Time: 上午10:08
 */
class PlayergoodsLogController extends CommonController{
    private $gmtoolTable = 'u_gmtool';
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
            'dwInfo'=>$this->getFenbao(),'goodsType'=>$this->getGoodsType(),'status'=>$status,
            'statusArr'=>$statusArr,'typeArr'=>$typeArr,'gameInfo'=>$this->getGame(),'gameServer'=>$this->getServer(),'emp'=>$this->getEmp()
        );

    }
    private function getEmp(){
    	$result = EmpAccount::model()->findAll();
    	$arr = array();
    	foreach ($result as $v){
    		$arr[$v->accountid]=$v->name;
    	}
    	return $arr;
    }
    //退回status -1
    public function actionRepass($id){
        $model = PlayergoodsLog::model();
        $rs = $model->findByPk($id);
        if(!$rs)
            $this->display('数据不存在.', 0);
        if($rs->status != 0)
            $this->display('已作废或已结束.', 0);
        /**if($this->mangerInfo['level'] == 0)
            $this->display('流程等级为0或不能操作此级流程.', 0);*/
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
            $serverId = $rs->server_id;
            $playerIdArr = explode(';', $rs->player_id);
            $playerNameArr = explode(';', $rs->name);
            //由于合服
            $sid = togetherServer($serverId);
            $table = subTable($sid, $this->gmtoolTable, 1000);
            $conn = SetConn($sid);

            if($conn == false)
                $this->display('链接游服数据库失败.', 0);
            $message = $rs->message;
            $type1 = intval($rs->type1);
            $param1 = intval($rs->param1);
            $amount1 = intval($rs->amount1);
            $type2 = intval($rs->type2);
            $param2 = intval($rs->param2);
            $amount2 = intval($rs->amount2);
            $type3 = intval($rs->type3);
            $param3 = intval($rs->param3);
            $amount3 = intval($rs->amount3);
            $type4 = intval($rs->type4);
            $param4 = intval($rs->param4);
            $amount4 = intval($rs->amount4);

            foreach ($playerIdArr as $key => $userId){
                $playerName = $playerNameArr[$key];
                $sql = "insert into $table(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2,  award_type3, award_param3, award_amount3,  award_type4, award_param4, award_amount4)";
                $sql .= " values(8, '$sid', '$userId' ,'$message', '$type1', '$param1', '$amount1', '$type2', '$param2', '$amount2', '$type3', '$param3', '$amount3', '$type4', '$param4', '$amount4') ";
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
            $table = subTable($sid, $this->gmtoolTable, 1000);
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
        $gameId = isset($_POST['PlayergoodsLog']['game_id']) ? intval($_POST['PlayergoodsLog']['game_id']) : $this->mangerInfo['game_id'];
        $goodsType = $this->getGoodsType($gameId);
        $model = new PlayergoodsLog;

        if(isset($_POST["PlayergoodsLog"])){
            $type = intval($_POST['PlayergoodsLog']['type']);
            $name = trim($_POST['PlayergoodsLog']['name']);
            $serverId = intval($_POST['PlayergoodsLog']['server_id']);
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
            'title'=>'添加物品','model'=>$model,'gameId'=>$gameId,'goodsType'=>$goodsType,
            'game' => $this->getGame(),'gameServer' =>$this->getServer(),
        ));
    }

    public function actionUpdate($id){
        $gameId = isset($_POST['PlayergoodsLog']['game_id']) ? intval($_POST['PlayergoodsLog']['game_id']) : $this->mangerInfo['game_id'];
        $rs = PlayergoodsLog::model()->findByPk($id);

        if(isset($_POST["PlayergoodsLog"])){
            $type = intval($_POST['PlayergoodsLog']['type']);
            $name = trim($_POST['PlayergoodsLog']['name']);
            $serverId = intval($_POST['PlayergoodsLog']['server_id']);
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
    /**
     * 上传文件
     */
    public function actionUpfile(){
    	$handle = fopen($_FILES['myfile']['tmp_name'], "r");
    	$i = 0;
    	$c = 0;
    	$message = '';
    	if ($handle) {
    		while (!feof($handle)) {
    			$c++;
    			$buffer = fgets($handle, 4096);
    			$data = explode(',', $buffer);
    			$mydata['game_id'] = 8;
    			$mydata['type'] = 1;
    			$mydata['server_id'] = $data[0];
    			$mydata['player_id'] = $data[1];
    			$mydata['message'] = $data[2];
    			$mydata['remark'] = $data[3];
    			$mydata['type1'] = $data[4]?$data[4]:0;
    			$mydata['param1'] = $data[5]?$data[5]:0;
    			$mydata['amount1'] = $data[6]?$data[6]:0;
    			$mydata['type2'] = $data[7]?$data[7]:0;
    			$mydata['param2'] = $data[8]?$data[8]:0;
    			$mydata['amount2'] = $data[9]?$data[9]:0;
    			$mydata['type3'] = $data[10]?$data[10]:0;
    			$mydata['param3'] = $data[11]?$data[11]:0;
    			$mydata['amount3'] = $data[12]?$data[12]:0;
    			$mydata['type4'] = $data[13]?$data[13]:0;
    			$mydata['param4'] = $data[14]?$data[14]:0;
    			$mydata['amount4'] = $data[15]?$data[15]:0;
    			/*$playerInfo = $this->checkPlayer($mydata['player_id'], 1, $mydata['server_id']);
    			if(!$playerInfo){
    				$message = '角色不存在';
    				break;
    			}
    				
    			$mydata['name'] = $playerInfo['name'];*/
    			$mydata['name'] = 12321;
    			
    			if(isset($mydata['param1']) && !empty($mydata['param1'])){
    				if(!$this->checkAddGodd($mydata['param1'])){
    					$message = '道具1不存在';
    					break;
    				}	
    			}
    			if(isset($mydata['param2']) && !empty($mydata['param2'])){
    				if(!$this->checkAddGodd($mydata['param2'])){
    					$message = '道具2不存在';
    					break;
    				}
    					
    			}
    			if(isset($mydata['param3']) && !empty($mydata['param3'])){
    				if(!$this->checkAddGodd($mydata['param3'])){
    					$message = '道具3不存在';
    					break;
    				}
    					
    			}
    			if(isset($mydata['param4']) && !empty($mydata['param4'])){
    				if(!$this->checkAddGodd($mydata['param4'])){
    					$message = '道具4不存在';
    					break;
    				}
    					
    			}
    			$model = new PlayergoodsLog;
    			$model->attributes=$mydata;
    			if($model->save())
    				$i++;
    		}
    	}
    	fclose($handle);
    	if($message){
    		$this->display("第{$c}条数据{$message}", 0);
    	}
    	$this->display("成功添加{$i}条发放内容！", 1);
    }
    
    private function checkAddGodd($itemtypeId){
    	$model = AddGood::model();
    	$sql = "select id,name from {{add_good}} where itemtype_id='{$itemtypeId}'";
    	$rs = $model->findBySql($sql);
    	if($rs)
    		return true;
    	return false;
    }

}