<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
class SystemAnalysis extends MY_Controller {

	
	/**
	 * 赛事统计
	 *
	 * @author 王涛 20170505
	 */
	public function Game()
	{
		if (parent::isAjax()) {
			$where['beginid'] = $this->input->get('beginid');
			$where['endid'] = $this->input->get('endid');
			$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
			$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
			$where['begindate'] = date('Ymd',strtotime($date1));
			$where['enddate'] = date('Ymd',strtotime($date2));
			$where['tasktype'] = 2;
			$this->load->model('FashionData');
			$field = 'a.accountid,a.taskid,a.tasktime,ifnull(b.starttime,0) starttime,a.wardrobeLevel,a.consumetime,ifnull(count(c.id),0) c';
			$group = 'a.accountid,a.taskid,a.tasktime,a.serverid,a.tasktype';
			$data = $this->FashionData->game($where,$field,$group,$order);
			foreach ($data as &$v){
				$v['startime'] = date('Ymd H:i:s',$v['starttime']);
				$v['tasktime'] = date('Ymd H:i:s',$v['tasktime']);
			}
			if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
			else echo json_encode(['info'=>'无数据']);
		}
		else {
			$this->data['hide_channel_list'] = true;
			$this->data['hide_server_list'] = true;
			$this->data['taskid_filter'] = true;
			$this->body = 'SystemAnalysis/game';
			$this->layout();
		}
	}
	/**
	 * 分享统计
	 *
	 * @author 王涛 20170505
	 */
	public function Share()
	{
		if (parent::isAjax()) {
			$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
			$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
			$where['begindate'] = date('Ymd',strtotime($date1));
			$where['enddate'] = date('Ymd',strtotime($date2));
			$where['btype'] = $this->input->get('sharetype');
			$this->load->model('FashionData');
			$data = $this->FashionData->share($where,$field,$group);
			$types=array(1=>[1=>'QQ好友',2=>'微信好友',3=>'QQ空间分享',4=>'微信朋友圈'],
					2=>[1=>'时尚圈内分享',2=>'世界聊天分享',3=>'QQ好友分享',4=>'微信好友']);
			foreach ($data as &$v){
				$v['type'] = $types[$where['btype']][$v['type']];
			}
			if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
			else echo json_encode(['info'=>'无数据']);
		}
		else {
			$this->data['hide_channel_list'] = true;
			$this->data['hide_server_list'] = true;
			$this->data['taskid_filter'] = true;
			$this->data['tasktype_filter'] = true;
			$this->body = 'SystemAnalysis/share';
			$this->layout();
		}
	}
	/**
	 * 分享统计
	 *
	 * @author 王涛 20170505
	 */
	public function Sharedata()
	{
		if (parent::isAjax()) {
			$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
			$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
			$where['begindate'] = date('Ymd',strtotime($date1));
			$where['enddate'] = date('Ymd',strtotime($date2));
			$where['btype'] = $this->input->get('sharetype');
			$this->load->model('FashionData');
			$field = "count(*) c,type";
			$group = 'type';
			$data = $this->FashionData->share($where,$field,$group);
			$types=array(1=>[1=>'QQ好友',2=>'微信好友',3=>'QQ空间分享',4=>'微信朋友圈'],
					2=>[1=>'时尚圈内分享',2=>'世界聊天分享',3=>'QQ好友分享',4=>'微信好友']);
			foreach ($data as &$v){
				$v['type'] = $types[$where['btype']][$v['type']];
			}
			if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
			else echo json_encode(['info'=>'无数据']);
		}
		else {
			$this->data['hide_channel_list'] = true;
			$this->data['hide_server_list'] = true;
			$this->data['share_filter'] = true;
			$this->body = 'SystemAnalysis/sharedata';
			$this->layout();
		}
	}
	/**
	 * 借衣饰统计
	 *
	 * @author 王涛 20170505
	 */
	public function Borrowdata()
	{
		if (parent::isAjax()) {
			$where['beginid'] = $this->input->get('beginid');
			$where['endid'] = $this->input->get('endid');
			$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
			$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
			$where['begindate'] = date('Ymd',strtotime($date1));
			$where['enddate'] = date('Ymd',strtotime($date2));
			$where['tasktype'] = $this->input->get('tasktype');
			$group = 'taskid';
    		$order = 'taskid';
    		$this->load->model('FashionData');
    		$field = 'taskid,count(distinct accountid) caccountid';
    		$data = $this->FashionData->borrow($where,$field,$group);
    		$borrowdata = array();
    		foreach ($data as $v){
    			$borrowdata[$v['taskid']] = $v['caccountid'];
    		}
    		$field = 'taskid,count(distinct accountid) caccountid,sum(if(isok=1,1,0)) sok,min(consumetime) mintime,max(consumetime) maxtime,ceil(avg(consumetime)) avgtime';
    		$data = $this->FashionData->taskCount($where,$field,$group,$order);
    		foreach ($data as &$v){
    			$v['borrownum'] = $borrowdata[$v['taskid']]?$borrowdata[$v['taskid']]:0;
    			$v['borrowrate'] = $v['borrownum']?ceil($v['borrownum']/$v['sok']*100).'%':'0%';
    		}
			if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
			else echo json_encode(['info'=>'无数据']);
		}
		else {
			$this->data['hide_channel_list'] = true;
			$this->data['hide_server_list'] = true;
			$this->data['taskid_filter'] = true;
			$this->data['tasktype_filter'] = true;
			$this->body = 'SystemAnalysis/borrowdata';
			$this->layout();
		}
	}
	/**
	 * 借衣饰统计
	 *
	 * @author 王涛 20170505
	 */
	public function Borrow()
	{
		if (parent::isAjax()) {
			$where['beginid'] = $this->input->get('beginid');
			$where['endid'] = $this->input->get('endid');
			$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
			$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
			$where['begindate'] = date('Ymd',strtotime($date1));
			$where['enddate'] = date('Ymd',strtotime($date2));
			$where['tasktype'] = $this->input->get('tasktype');
			$this->load->model('FashionData');
			$data = $this->FashionData->borrow($where);
			$types=array(1=>'金币',2=>'钻石');
			foreach ($data as $v){
				$v['borrowTime'] = date('Ymd H:i:s',$v['borrowTime']);
				$v['type'] = $types[$v['type']];
			}
			if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
			else echo json_encode(['info'=>'无数据']);
		}
		else {
			$this->data['hide_channel_list'] = true;
			$this->data['hide_server_list'] = true;
			$this->data['taskid_filter'] = true;
			$this->data['tasktype_filter'] = true;
			$this->body = 'SystemAnalysis/borrow';
			$this->layout();
		}
	}
	/**
	 * 钻石消耗统计
	 * 
	 * @author 王涛 20170314
	 */
    public function Emoney()
    {
    	if (parent::isAjax()) {
    		$types = include APPPATH .'/config/comsume_types.php'; //统计类型字典
    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		$field = 'act_id,count(u.id) cid,sum(item_num) snum';
    		$group = 'act_id'; 
    		$where['begintime'] = strtotime($date);
    		$this->load->model('SystemFunction_model');
    		$where['itemid'] = "3";
    		$where['typeids'] = array(3,10,11);
    		$where['type'] = 1;//消耗
    		$data = $this->SystemFunction_model->BehaviorProduceSaleNew($where,$field,$group);
    		foreach ($data as &$v){
    			$v['act_name'] = $v['act_id'].$types[$v['act_id']];
    		}
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->data['hide_server_list'] = true;
    		$this->data['hide_end_time'] = true;
    		
    		$this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
    		$this->body = 'SystemAnalysis/emoney';
    		$this->layout();
    	}
    }


    /**
     * 任务相关统计
     * 
     * @author 王涛 20170314
     */
	public function Task()
    {
    	if (parent::isAjax()) {
    		$where['beginid'] = $this->input->get('beginid');
    		$where['endid'] = $this->input->get('endid');
    		$date1 = $this->input->get('date1');
    		$date2 = $this->input->get('date2');
    		$where['tasktype'] = $this->input->get('tasktype');
    		if($date1)$where['begindate'] = date('Ymd', strtotime($date1));
    		if($date2)$where['enddate'] = date('Ymd', strtotime($date2));
    		$group = 'logdate,taskid';
    		$order = 'logdate,taskid';
    		
    		$votedata = $stardata =  array();
    		$this->load->model('FashionData');
    		$field = 'taskid,logdate,count(distinct accountid) vaccountid,count(id) vid';
    		$data = $this->FashionData->voteCount($where,$field,$group,$order);
    		foreach ($data as $v){
    			$votedata[$v['logdate'].$v['taskid']]['vaccountid'] = $v['vaccountid'];
    			$votedata[$v['logdate'].$v['taskid']]['vid'] = $v['vid'];
    		}
    		$field = 'taskid,logdate,sum(if(star>=30&&star<35,1,0)) star3,sum(if(star>=40&&star<45,1,0)) star4,sum(if(star>=50,1,0)) star5,sum(if(star>=45&&star<50,1,0)) star4_5';
    		$data = $this->FashionData->starCount($where,$field,$group,$order);
    		foreach ($data as $v){
    			$stardata[$v['logdate'].$v['taskid']]['star3'] = $v['star3'];
    			$stardata[$v['logdate'].$v['taskid']]['star4'] = $v['star4'];
    			$stardata[$v['logdate'].$v['taskid']]['star5'] = $v['star5'];
    			$stardata[$v['logdate'].$v['taskid']]['star4_5'] = $v['star4_5'];
    		}
    		$field = 'taskid,logdate,count(distinct accountid) caccountid,sum(if(isok=1,1,0)) sok,min(consumetime) mintime,max(consumetime) maxtime,ceil(avg(consumetime)) avgtime';
    		$data = $this->FashionData->taskCount($where,$field,$group,$order);
    		foreach ($data as &$v){
    			$v['rate'] = ceil($v['sok']/$v['caccountid']*100).'%';
    			$v['vaccountid'] = $votedata[$v['logdate'].$v['taskid']]['vaccountid']?$votedata[$v['logdate'].$v['taskid']]['vaccountid']:0;
    			$v['vid'] = $votedata[$v['logdate'].$v['taskid']]['vid']?$votedata[$v['logdate'].$v['taskid']]['vid']:0;
    			$v['star3'] = $stardata[$v['logdate'].$v['taskid']]['star3']?$stardata[$v['logdate'].$v['taskid']]['star3']:0;
    			$v['star4'] = $stardata[$v['logdate'].$v['taskid']]['star4']?$stardata[$v['logdate'].$v['taskid']]['star4']:0;
    			$v['star5'] = $stardata[$v['logdate'].$v['taskid']]['star5']?$stardata[$v['logdate'].$v['taskid']]['star5']:0;
    			$v['star4_5'] = $stardata[$v['logdate'].$v['taskid']]['star4_5']?$stardata[$v['logdate'].$v['taskid']]['star4_5']:0;
    		}
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->data['hide_server_list'] = true;
    		$this->data['taskid_filter'] = true;
    		$this->data['tasktype_filter'] = true;
    		$this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
    		$this->body = 'SystemAnalysis/task';
    		$this->layout();
    	}
    }
    
    /**
     * 投票统计
     * 
     * @author 王涛 20170314
     */
    public function Vote()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1');
    		$date2 = $this->input->get('date2');
    		if($date1)$where['begindate'] = date('Ymd', strtotime($date1));
    		if($date2)$where['enddate'] = date('Ymd', strtotime($date2));
    		$where['tasktype'] = $this->input->get('tasktype');
    		$this->load->model('FashionData');
    		$newdata = array();
    		$data = $this->FashionData->voteCountOne($where);
    		foreach ($data as $v){
    			$newdata[$v['logdate']] = $v;
    		}
    		$group = 'logdate';
    		$order = 'logdate';
    		$field = 'logdate,count(distinct accountid) vaccountid,count(id) vid,sum(if(way=0,1,0)) s0,sum(if(way=1,1,0)) s1,sum(if(way=2,1,0)) s2';
    		$data = $this->FashionData->voteCount($where,$field,$group,$order);
    		foreach ($data as &$v){
    			$v['rate'] = ceil($v['vid']/$v['vaccountid']);
    			$v['mincid'] = $newdata[$v['logdate']]['mincid'];
    			$v['maxcid'] = $newdata[$v['logdate']]['maxcid'];
    		}
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->data['hide_server_list'] = true;
    		$this->data['tasktype_filter'] = true;
    		$this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
    		$this->body = 'SystemAnalysis/vote';
    		$this->layout();
    	}
    }
    
    /**
     * 商店统计
     * 
     * @author 王涛 20170329
     */
    public function Shop()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    		$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
    		$where['begindate'] = date('Ymd',strtotime($date1));
    		$where['enddate'] = date('Ymd',strtotime($date2));
    		$this->load->model('GameServerData');
    		$data = $this->GameServerData->shop($where);
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['hide_channel_list'] = true;
    		$this->data['hide_server_list'] = true;
    		$this->body = 'SystemAnalysis/shop';
    		$this->layout();
    	}
    }
    
    /**
     * 商店统计排行
     *
     * @author 王涛 20170314
     */
    public function Shoprank()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    		//$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d', strtotime('-1 days'));
    		$where['begindate'] = date('Ymd',strtotime($date1));
    		$where['enddate'] = date('Ymd',strtotime($date1));
    		$where['itemid'] = $this->input->get('itemid');
    		$where['tabletype'] = $this->input->get('gametype');
    		$this->load->model('GameServerData');
    		$field = "itemid,get_account,get_num";
    		$group = "itemid";
    		$order = "get_num desc";
    		$data = $this->GameServerData->shoprank($where,$field,$group,$order);
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['info'=>'无数据']);
    	}
    	else {
    		$this->data['items'] = include APPPATH .'/config/item_types.php'; //道具字典
    		$this->data['hide_channel_list'] = true;
    		$this->data['hide_server_list'] = true;
    		$this->data['hide_end_time'] = true;
    		$this->data['item_id_filter'] = true;
    		$this->data['game_filter'] = true;
    		$this->body = 'SystemAnalysis/shoprank';
    		$this->layout();
    	}
    }
    
    

    

}
