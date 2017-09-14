<?php
// +----------------------------------------------------------------------
// | 首页控制器
// +----------------------------------------------------------------------
// | 南华包场验证系统
// +----------------------------------------------------------------------
// | Author: jcjtim
// +----------------------------------------------------------------------

namespace Check\Controller;
use Think\Controller;
class PrivateController extends Controller {

	/**
	 * 系统基础控制器初始化
	 */
	protected function _initialize(){
		$this->tab_pre = C('DB_PREFIX');
		// 获取当前用户ID

		$user=admin_is_login();

		define('GUID',(int)$user['uid']);
		if( !GUID ){// 还没登录 跳转到登录页面
			$this->redirect('Public/privatelogin');
		}
		$this->user=$user;
		$this->assign('user',$user);
		/*define('IS_ROOT',   is_administrator());
		 if(!IS_ROOT && C('ADMIN_ALLOW_IP')){
		 // 检查IP地址访问
		 if(!in_array(get_client_ip(),explode(',',C('ADMIN_ALLOW_IP')))){
		 $this->error('403:禁止访问');
		 }
		 }
		 // 检测访问权限
		 $access =   $this->accessControl();
		 if ( $access === false ) {
		 $this->error('403:禁止访问');
		 }elseif( $access === null ){
			//检测非动态权限
			$rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
			if ( !$this->checkRule($rule,array('in','1,2')) ){
			$this->error('未授权访问!');
			}
			}
			$this->assign('__MENU__', $this->chrentMenus());
			$this->assign('__TIME__', time());*/
	}

	/**
	 * 包场首页展示
	 */
	function index() {
		if((isset($_REQUEST['id'])&&$_REQUEST['id']!='')||(isset($_REQUEST['code'])&&$_REQUEST['code']!='')) {

			$mode=D('WholeReserve');
			$data=array();
			$data['eltstate']=2;


			if(isset($_REQUEST['id'])&&$_REQUEST['id']!='') {
					
				$data['id']=$_REQUEST['id'];
				 

			}
			if(isset($_REQUEST['code'])&&$_REQUEST['code']!='') {
					
				$data['code']=$_REQUEST['code'];
				 

			}
			$list=$mode->getlist($data);
		  
			foreach($list as $k => $v){

				$list[$k]['paymentTimeflag']=date('Y-m-d H:i',$v['paymentTime']);
				 
				$list[$k]['cr']=$v['total']-$v['prepay'];


				if($v['state']==2) {
					 
					$list[$k]['stateflag']='已验证';
					 
					 
					$data=array();
					$data['uid']=$v['adminUid'];
					$return=$mode->admin_getlist($data,4);
					 
					$list[$k]['realName']=$return['realName'];

				}else{
					$list[$k]['stateflag']='未验证';


				}


			}

			$this->assign('map',$_REQUEST);
			$this->assign('list',$list);
			 
			 
			 
			 
		}

		 
		$this->display();
	}
	/**
	 * 包场首页展示
	 */
	function index_search() {
		if((isset($_REQUEST['id'])&&$_REQUEST['id']!='')||(isset($_REQUEST['code'])&&$_REQUEST['code']!='')) {
			$mode=D('WholeReserve');
			$data=array();
			$data['eltstate']=2;
			if(isset($_REQUEST['id'])&&$_REQUEST['id']!='') {
				$data['id']=$_REQUEST['id'];
			}
			if(isset($_REQUEST['code'])&&$_REQUEST['code']!='') {
				$data['code']=$_REQUEST['code'];
			}
			$list=$mode->getlist($data);
			$data=array();
			if($list) {
				foreach($list as $k => $v){
					$list[$k]['paymentTimeflag']=date('Y-m-d H:i',$v['paymentTime']);
					$list[$k]['cr']=$v['total']-$v['prepay'];
					if($v['state']==2) {
						$list[$k]['stateflag']='已验证';
						$data=array();
						$data['uid']=$v['adminUid'];
						$return=$mode->admin_getlist($data,4);
						$list[$k]['realName']=$return['realName'];
					}else{
						$list[$k]['stateflag']='未验证';
					}
				}
				$data['status']=1;
				$data['message']="操作成功";
				$data['list']=$list;
			}else{
				$data['status']=0;
				$data['message']="未查询到内容！";
			}
			echo json_encode($data);
		}
	}
	/**
	 * 历史操作记录展示
	 */

	function privatelist(){
		 
		$mode=D('WholeReserve');
		$data=array();
		$data['state']=2;
		if(isset($_REQUEST['start_checkTime'])&&$_REQUEST['start_checkTime']!='') {
			$data['start_checkTime']=strtotime($_REQUEST['start_checkTime']);
			 
		}

		if(isset($_REQUEST['end_checkTime'])&&$_REQUEST['end_checkTime']!='') {
			$data['end_checkTime']=strtotime($_REQUEST['end_checkTime']);
			 
		}
		if(isset($_REQUEST['id'])&&$_REQUEST['id']!='') {

			$data['id']=$_REQUEST['id'];

				
		}
		if(isset($_REQUEST['adminUid'])&&$_REQUEST['adminUid']!='') {

			$data['adminUid']=$_REQUEST['adminUid'];

				
		}
		$list=$mode->getlist($data);
		foreach($list as $k => $v){

			$list[$k]['paymentTimeflag']=date('Y-m-d H:i',$v['paymentTime']);
			$list[$k]['checkTimeflag']=date('Y-m-d H:i',$v['checkTime']);

			 
			$list[$k]['cr']=$v['total']-$v['prepay'];


			if($v['state']==2) {
				$list[$k]['stateflag']='已验证';
			}else{
				$list[$k]['stateflag']='未验证';
			}


			$data=array();
			$data['uid']=$v['adminUid'];
			$return=$mode->admin_getlist($data,4);

			$list[$k]['realName']=$return['realName'];


		}

		$ret=$mode->admin_getlist();
		//    	dump($list);
		$this->assign('list',$list);
		 
		$this->assign('admin',$ret);
		$this->assign('map',$_REQUEST);

		$this->display();
	}

	//订单详情
	public function order_from() {
$id=$_GET['id'];
		$mode=D('WholeReserve');
		$data=array();
		$data['id']=$id;	
		$list=$mode->getlist($data,4);

		$list['paymentTimeflag']=date('Y-m-d H:i',$list['paymentTime']);	
		$list['cc']=date('H:i',$list['viewingDate']);
		$list['ec']=date('H:i',$list['endTime']);
		$list['time']=date('Y-m-d',$list['viewingDate']);
		$data=array();
		$data['reserveId']=$id;
		$data['type']=1;	
		$packagelist=$mode->reserve_relation_package_getlist($data);//获取关联套餐内容
		if($packagelist) {
			foreach($packagelist as $k=>$v) {
				
				
				$packagelist[$k]['tolprice']=$v['num']*$v['price'];
			
			
			
			}			
			$list['packagelist']=$packagelist;		
		}
		$data=array();
		$data['reserveId']=$id;
		$data['type']=2;	
		$servicelist=$mode->reserve_relation_service_getlist($data);//获取关联服务内容	
		if($servicelist) {
			foreach($servicelist as $k=>$v) {
				
				
				$servicelist[$k]['tolprice']=$v['num']*$v['price'];
			
			
			
			}
			
			
			$list['servicelist']=$servicelist;		
		}
		$this->assign($list);		
		$this->display('');
	}
	/**
	 * 订单兑换
	 */
	function checkCode(){
		$id=I('id');
		$data=array();
		$data['id']=$id;
		$data['state']='2';
		$data['checkTime']=time();
		$data['adminUid']=$_SESSION['adminGoodsInfo']['uid'];



		$model=D('WholeReserve');


		$model->update_model($data);

		$data=array();
		$data['status']=1;
		$data['text']='兑换成功';
		echo json_encode($data);
	}

}