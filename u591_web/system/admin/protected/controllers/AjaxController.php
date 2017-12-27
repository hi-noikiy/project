<?php
/**
 * @author luoxue
 * @date 2013-10-21 08:55
 */
require_once(ROOT_PATH.'inc/config.php');
require_once(ROOT_PATH.'inc/config_account.php');
require_once(ROOT_PATH.'inc/function.php');
class AjaxController extends Controller{
	function init(){

        $managerId = $this->getSession('authId');
        $this->mangerInfo = Manager::model()->findByPk($managerId);
    }

	public function actionMenu($id=NULL){	
		echo Menu::model()->json($id);
	}
	
	public function actionManager(){
		if(!isset($_POST['depId']))
			return ;
		
		$depId = intval($_POST['depId']);
		$manager = new Manager;
		$criteria = new CDbCriteria;
		$criteria->select = 'id, real_name';
		$criteria->order = 'id desc';
		$criteria->condition = "depId='$depId' and status=0";
		$list = $manager->findAll($criteria);
		$str = '<option value="">请选择...</option>';
		
		if($list){
			foreach ($list as $v){
					$str .= "<option value='$v->id'>$v->real_name</option>";
			}
		}
			exit($str);
	}
	
	public function actionRoleModel(){
		if(!isset($_POST['appId']))
			return '';
		$id = intval($_POST['id']);
		$appId = intval($_POST['appId']);
		$str = '<option value="">请选择模块...</option>';
		$access=new Access();
		$modelList = $access->getModelList($id, $appId);
	
		if($modelList){
			foreach ($modelList as $v){
				$str .= "<option value='{$v['id']}'>|--{$v['title']}</option>";
			}
		}
		exit($str);	
	}
	
	public function actionActedit(){
		$id = $_GET['id'];
		$isShowActivity = $_GET['status'];
		$rs = Act::model()->findByPk($id);
		$rs->isShowActivity=$isShowActivity;
		$rs->save();
	}
	public function actionGetServerList(){
		if(!isset($_POST['gameId']))
			return '';
		$gameId = intval($_POST['gameId']);
		$gameServer = GameServer::model()->getInfoList($gameId);
		$str = '<option value="0">区服</option>';
		//$str .='<option value="">全服</option>';
		if(!empty($gameServer)){
			foreach ($gameServer as $v){
				$str .= "<option value='$v->server_id'>$v->server_name</option>";
			}
		}
		exit($str);
	}

    public function actionGetServerPre(){
        if(!isset($_POST['gameId']))
            return '';
        $gameId = intval($_POST['gameId']);
        $gameServer = GameServer::model()->getServer($gameId);
        $str = '';
        if(!empty($gameServer)){
            foreach ($gameServer as $k => $v){
                $str .= "<option value='$k'>$v</option>";
            }
        }
        exit($str);
    }

    /**
     * 已合服展示区服列表
     * @return string
     */
	public function actionGetCheckServerList(){
	    $togetherServerArr = array();
        $serverArr = array();
		if(!isset($_POST['gameId']))
			return '';
        $selectedArr = array();
        if(isset($_POST['selected']))
            $selectedArr = explode(',', $_POST['selected']);
		$gameId = intval($_POST['gameId']);

		$gameServer = GameServer::model()->getInfoList($gameId, $this->mangerInfo['server_id']);
		$str ="<input type='checkbox' id='checkAll'>全选&nbsp;&nbsp;";
		if(!empty($gameServer)){

			foreach ($gameServer as $k => $v){
                $sid = togetherServer($v->server_id);
                if($sid != $v->server_id){
                    //说明是合服的
                    $togetherServerArr[$sid][]=$v->server_name;
                    continue;
                } else {
                    $serverArr[$k]['server_id'] = $v->server_id;
                    $serverArr[$k]['server_name'] = $v->server_name;
                }
            }
            $i = 0;
            sort($serverArr);
            foreach ($serverArr as $k => $v){
                $serverId = $v['server_id'];
                $serverName = $v['server_name'];
                $together = isset($togetherServerArr[$serverId]) ? '<font color="red">('.implode(' ', $togetherServerArr[$serverId]).')</font>' : '';
                $selected = in_array($serverId, $selectedArr) ? 'checked' : '';
                $br = $pre = '';
                if($together) {
                    $i = 0;
                    $br = '<br>';
                    if(isset($serverArr[$k+1]['server_id'])
                        && isset($togetherServerArr[$serverArr[$k+1]['server_id']]))
                    {
                        $pre = '<br>';
                    }
                    if(isset($serverArr[$k-1]['server_id'])
                        && isset($togetherServerArr[$serverArr[$k-1]['server_id']])){
                        $pre = '';
                    }

                }else if($i%8 == 0 && $i != 0)
                    $br = '<br>';
                $str .="$pre<input name='serverId[]' value='$serverId' type='checkbox' $selected>$serverName $together".'&nbsp;&nbsp;'.$br;
                $i++;
            }
		}
		$str .="<script>$('#checkAll').click(function(){";
		$str .="if($(this).is(':checked')) {";
		$str .="$('input[name=\"serverId[]\"]').prop('checked', 'checked');";
		$str .="} else {";
		$str .="$('input[name=\"serverId[]\"]').removeAttr('checked');";
		$str .="}";
		$str .="});</script>";
		exit($str);
	}

    /**
     * 不合服展示列表
     * @return string
     */
    public function actionGetCheckServerList2(){
        if(!isset($_POST['gameId']))
            return '';
        $selectedArr = array();
        if(isset($_POST['selected']))
            $selectedArr = explode(',', $_POST['selected']);
        $gameId = intval($_POST['gameId']);

        $gameServer = GameServer::model()->getInfoList($gameId, $this->mangerInfo['server_id']);
        $str ="<input type='checkbox' id='checkAll'>全选&nbsp;&nbsp;";
        if(!empty($gameServer)){
            foreach ($gameServer as $k => $v){
                $selected = in_array($v->server_id, $selectedArr) ? 'checked' : '';
                $br = '';
                if( ($k+1)%8 == 0 && $k != 0)
                    $br = '<br>';
                $str .="<input name='serverId[]' value='$v->server_id' type='checkbox' $selected>$v->server_name".'&nbsp;&nbsp;'.$br;
            }
        }
        $str .="<script>$('#checkAll').click(function(){";

        $str .="if($(this).is(':checked')) {";
        $str .="$('input[name=\"serverId[]\"]').prop('checked', 'checked');";
        $str .="} else {";
        $str .="$('input[name=\"serverId[]\"]').removeAttr('checked');";
        $str .="}";
        $str .="});</script>";
        exit($str);
    }

    function actionGetCheckServerListPre($pre = 8){
        if(!isset($_POST['gameId']))
            return '';
        $selectedArr = array();
        if(isset($_POST['selected']))
            $selectedArr = explode(',', $_POST['selected']);
        $gameId = intval($_POST['gameId']);

        $gameServer = GameServer::model()->getInfoList($gameId, $this->mangerInfo['server_id']);
        $str ="<input type='checkbox' id='checkAll'>全选&nbsp;&nbsp;";
        if(!empty($gameServer)){
            foreach ($gameServer as $k => $v){
                if(mb_substr($v->server_id,0,1) != $pre)
                    continue;
                $selected = in_array($v->server_id, $selectedArr) ? 'checked' : '';
                $br = '';
                if( ($k+1)%8 == 0 && $k != 0)
                    $br = '<br>';
                $str .="<input name='serverId[]' value='$v->server_id' type='checkbox' $selected>$v->server_name".'&nbsp;&nbsp;'.$br;
            }
        }
        $str .="<script>$('#checkAll').click(function(){";

        $str .="if($(this).is(':checked')) {";
        $str .="$('input[name=\"serverId[]\"]').prop('checked', 'checked');";
        $str .="} else {";
        $str .="$('input[name=\"serverId[]\"]').removeAttr('checked');";
        $str .="}";
        $str .="});</script>";
        exit($str);
    }

	
	public function actionGetCateList(){
		if(!isset($_POST['gameId']))
			return '';
		$gameId = intval($_POST['gameId']);
		$list = Category::model()->getInfoList($gameId);
		$str = '<option value="">请选择...</option>';
		if(!empty($list)){
			foreach ($list as $v){
				$str .= "<option value='$v->name'>$v->name</option>";
			}
		}
		exit($str);
	}

	public function actionCheckAddGodd(){
        if(!isset($_POST['itemtypeId']))
            exit(json_encode(array('status'=>1, 'msg'=>'物品id不存在.')));
        $model = AddGood::model();
        $sql = "select id,name from {{add_good}} where itemtype_id='{$_POST['itemtypeId']}'";
        $rs = $model->findBySql($sql);
        if($rs)
            exit(json_encode(array('status'=>0, 'msg'=>"你发放的物品是：$rs->name")));
        exit(json_encode(array('status'=>1, 'msg'=>"物品ID不存在")));
    }
}