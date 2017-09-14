<?php
// +----------------------------------------------------------------------
// | 系统基础控制器
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Check\Controller;
use Think\Controller;
class HomeController extends Controller {
	private $limit=5;
	private $user='';
    /**
     * 系统基础控制器初始化
     */
    protected function _initialize(){
        $this->tab_pre = C('DB_PREFIX');
        // 获取当前用户ID

        $user=admin_is_login();

        define('GUID',(int)$user['uid']);
        if( !GUID ){// 还没登录 跳转到登录页面
            $this->redirect('Public/login');
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
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     * @author 
     */
    final protected function accessControl(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
		$allow = C('ALLOW_VISIT');
		$deny  = C('DENY_VISIT');
		$check = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
        if ( !empty($deny)  && in_array_case($check,$deny) ) {
            return false;//非超管禁止访问deny中的方法
        }
        if ( !empty($allow) && in_array_case($check,$allow) ) {
            return true;
        }
        return null;//需要检测节点权限
    }
    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     * @author 
     */
    final protected function checkRule($rule, $type=1, $mode='url'){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        static $Auth    =   null;
        if (!$Auth) {
            $Auth       =   new \Common\Controller\Auth();
        }
        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
    }
    /**
     * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
     * @author 
     */
    final public function chrentMenus(){
		$menus  =   session('ADMIN_MENU_LIST'.MODULE_NAME);
		if(empty($menus))
		{
			$where['position']   =   1;
			$menu = M('Menu')->field('mid,pid,menu_name,url,ico,isshow')->where($where)->order('sort_order asc')->select();
			$tree = new \Common\Controller\Tree();
			$tree->setData($menu);
			$tree->chrent_menu();
			$menus = $tree->menuStr;
            session('ADMIN_MENU_LIST'.MODULE_NAME,$menus);
        }
        return $menus;
	}

    public function success($content, $dataList = array())
    {
        $data['status']  = 0;
        $data['content'] = $content;
        $data['data'] = $dataList;
        $this->ajaxReturn($data);
    }
    function curPage($page,$count){
    	if($page < 0 || empty($page)){
    		$page=1;
    	}elseif($page > $count){
    		$page = $count;
    	}
    	return $page;
    }
    
    public function getPageList($limitCount , $pageLength, $map = ''){
    	$Page       = new \Think\Page($limitCount,$pageLength); // 实例化分页类 传入总记录数和每页显示的记录数
    	$config  = array(
    			'prev'   => '上一页',
    			'next'   => '下一页',
    			'first'  => '首页',
    			'last'   => '最后一页',
    			'theme'  => '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
    	);
    	foreach($config as $key=>$val){
    		$Page->setConfig($key , $val);
    	}
    
    	foreach($map as $key=>$val) {
    		$Page->parameter[$key]   =   urlencode($val);
    	}
    
    
    	$show  = $Page->show();
    
    	if($show){
    		$show = '<div class="pagination"><div class="page">' . $show . '</div></div>';
    	}
    	return $show ;// 分页显示输出
    }
    public function error($content, $status = 1)
    {
        $data['status']  = $status;
        $data['content'] = $content;
        $this->ajaxReturn($data);
    }
}