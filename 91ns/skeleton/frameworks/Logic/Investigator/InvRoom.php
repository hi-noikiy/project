<?php
//后台客服 == 直播间 == 消息内容
namespace Micro\Frameworks\Logic\Investigator;

use Micro\Models\InvRuleMessage;

class InvRoom extends InvBase{
	
	//
	public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }
	public function getRuleMessageList($page,$pageSize){
		$list = array();
		try{

			$limit = ($page - 1) * $pageSize;
			$InvRuleMessage = InvRuleMessage::find(array(
				'limit' => $limit.','.$pageSize,
				'order' => 'id desc'
			));

			if($InvRuleMessage){
				foreach($InvRuleMessage as $val){
					$data['id'] = $val->id;
					$data['content'] = $val->content;
					array_push($list,$data);
				}

			}
			//统计总条数
			$count = 0;
			if($list){
				$count = InvRuleMessage::count();				
			}
			
			$result['count'] = $count;
			$result['list'] = $list;
			return $result;
		} catch (\Exception $e) {
            $this->errLog('getIdMessage error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	}
	
	//根据ID获取信息
	public function getIdMessage($id){		
		try{
			$ruleMessage = InvRuleMessage::findFirst('id = '.$id);
			$data = array();
			if($ruleMessage){
				$data['id'] = $ruleMessage->id;
				$data['content'] = $ruleMessage->content;
			}	
			return $data;

		} catch (\Exception $e) {
            $this->errLog('getIdMessage error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }		
	
	}

	//保存数据
	public function getSaveData($id,$data){
		try{
			if($id != 0){
				$InvRuleMessage = InvRuleMessage::findFirst('id = '.$id);
				if($InvRuleMessage == false){
					return false;
				}
				$this->addOperate($this->username,'修改','机器人随机消息内容 ','修改消息内容',$InvRuleMessage->content,$data['content']);
			}else{
				$InvRuleMessage = new InvRuleMessage();
				$this->addOperate($this->username,'新增','机器人随机消息内容 ','添加消息内容');
			}

			$InvRuleMessage->content = $data['content'];
			$reusult = $InvRuleMessage->save(); 
			if($reusult){
				return true;
			}
			return false;

		}catch (\Exception $e) {
            $this->errLog('getSaveData error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }		

	}

	//删除直播间消息
	public function getDelMessage($id){
		try{
			$InvRuleMessage = InvRuleMessage::findFirst('id = '.$id);
			if($InvRuleMessage != false){				
				if($InvRuleMessage->delete() != false){
					$this->addOperate($this->username,'删除','机器人随机消息内容 ','删除消息内容',$InvRuleMessage->content);
					return true;
				}
			}
		
		} catch (\Exception $e) {
            $this->errLog('getDelMessage error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return false;
	}
	
}
