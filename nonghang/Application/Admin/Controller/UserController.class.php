<?php
/**
 *后台用户权限控制类
 * 
 * @author 王涛
 * @package  admin
 */
namespace Admin\Controller;
use Think\Controller;
class UserController extends AdminController {

	/**
	 * 用户列表
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function userlist(){

		
		$cinemaGroupId = I('request.cinemaGroupId');
        if(!empty($cinemaGroupId)){
            $map['cinemaGroup'] = $cinemaGroupId;
            $roleMap['cinemaGroupId'] = $cinemaGroupId;
            $this->assign('cinemaGroupId',$cinemaGroupId);

        }

        $map['uid'] = array('neq', C('USER_ADMINISTRATOR'));

        $userGroupList = D('Admin')->getUserGroupList('id, roleName', $roleMap);
		$groupList = D('Cinema')->getGroup();
		$adminList = D('Admin')->getAdminList('*', $map);

    	$this->assign('groupList',$groupList);
    	$this->assign('userGroupList',$userGroupList);
		$this->assign('adminList',$adminList);
		$this->display();
	}
	/**
	 * 添加用户
	 */
    public function addUser(){
		if(IS_AJAX){
			$action = I('request.action');
			$cinemaGroupId = I('request.cinemaGroupId');
			if ($action == 'getCinemaList') {

				$groupInfo = D('Cinema')->getGroupInfo('cinemaList', array('id'=>$cinemaGroupId));
				$cinemaListMap['cinemaCode'] = array('IN', $groupInfo['cinemaList']);
				$cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName', $cinemaListMap);
				if ($cinemaList) {
					$this->success('影院列表获取成功！', $cinemaList);
				}else{
					$this->error('暂无下属影院！');
				}
				
			}elseif ($action == 'getUserGroupList') {
				$userGroupList = D('Admin')->getUserGroupList('id, roleName', array('cinemaGroupId' => intval($cinemaGroupId)));
				if ($userGroupList) {
					$this->success('获取权限组列表获取成功！', $userGroupList);
				}else{
					$this->error('暂无权限组！');
				}
			}
		}if(IS_POST){
			$data = I('post.data');
			$uid = I('post.uid');
			if(!empty($data['password'])){
				$data['password'] = chrent_md5($data['password']);
			}else{
				unset($data['password']);
			}

			if(!empty($data['cinemaCodeList'])){
				$data['cinemaCodeList'] = implode(',', $data['cinemaCodeList']);
			}else{
				unset($data['password']);
			}


			if(D('Admin')->addAdmin($data)){
				jsalert('职员信息添加成功!', U('userList'));
			}else{
				jsalert('职员信息添加失败，请重试!');

			}
		}else{

			$groupList = D('Cinema')->getGroup();
			$this->assign('groupList',$groupList);
		    $this->display('userfrom');
		}
    }
    /**
     * 编辑用户
     * 
     * @param string $id
     */
    public function editUser()
	{
		if(IS_AJAX){
			$action = I('request.action');
			$cinemaGroupId = I('request.cinemaGroupId');
			if ($action == 'getCinemaList') {
				$groupInfo = D('Cinema')->getGroupInfo('cinemaList', array('id'=>$cinemaGroupId));
				$cinemaListMap['cinemaCode'] = array('IN', $groupInfo['cinemaList']);
				$cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName', $cinemaListMap);
				if ($cinemaList) {
					$this->success('影院列表获取成功！', $cinemaList);
				}else{
					$this->error('暂无下属影院！');
				}
			}elseif ($action == 'getUserGroupList') {
				$userGroupList = D('Admin')->getUserGroupList('id, roleName', array('cinemaGroupId' => intval($cinemaGroupId)));
				if ($userGroupList) {
					$this->success('获取权限组列表获取成功！', $userGroupList);
				}else{
					$this->error('暂无权限组！');
				}
			}
		}if(IS_POST){
			$data = I('post.data');
			$uid = I('post.uid');
			if(!empty($data['password'])){
				$data['password'] = chrent_md5($data['password']);
			}else{
				unset($data['password']);
			}

			if(!empty($data['cinemaCodeList'])){
				$data['cinemaCodeList'] = implode(',', $data['cinemaCodeList']);
			}else{
				unset($data['password']);
			}


			if(D('Admin')->setAdminInfoByUid($data, $uid)){
				jsalert('职员信息更新成功!', U('userList'));
			}else{
				jsalert('职员信息更新失败，请重试!');

			}
		}else{
			$uid = I('request.uid');
			$groupList = D('Cinema')->getGroup();
			$adminInfo = D('Admin')->getAdminInfoByUid($uid);
			$this->assign('adminInfo',$adminInfo);
			// print_r($adminInfo);
			$this->assign('groupList',$groupList);
			$this->display('userfrom');
		}

		
	}


	public function editPassWord()
    {
    	if(IS_POST){
			$data = I('post.data');
			$uid = CPUID;

			if (empty($data['oldPasswd'])) {
				jsalert('请输入原始密码!');
			}

			if (empty($data['password'])) {
				jsalert('请输入新密码!');
			}

			if (empty($data['password2'])) {
				jsalert('请输入确认密码!');
			}

			if ($data['password2'] != $data['password']) {
				jsalert('两次密码输入不一致，请重新输入!');
			}

			$adminInfo = D('Admin')->getAdminInfoByUid($uid);
			if ($adminInfo['password'] != chrent_md5($data['oldPasswd'])) {
				jsalert('原密码不正确，请重新输入!');
			}

			$updateData['password'] = chrent_md5($data['password']);

			if(D('Admin')->setAdminInfoByUid($updateData, $uid)){
				jsalert('密码更新成功!', U('userList'));
			}else{
				jsalert('密码更新失败，请重试!');

			}
		}else{
			$this->display('editpassword');
		}
    	
    }
	/**
     * 删除用户
     * 
     * @param string $id
     */
	public function delUser()
	{
		if(IS_AJAX){
			$uid = I('request.uid');
			if(D('Admin')->delUserByUid($uid)){
	            $this->success('恭喜您，职员删除成功！');
	        }else{
	            $this->error('很遗憾，职员删除失败！');
	        }
		}
	}

	/**
	 * 修改权限组
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function editUserGroup()
	{
		$id = I('request.id');
		if(IS_POST){
			$data = I('post.data');

			if(empty($data['cinemaGroupId'])){
				jsalert('影院分组不能为空！');
			}

			if(empty($data['roleName'])){
				jsalert('权限组名称不能为空！');
			}

			if(empty($data['rules'])){
				jsalert('请选择权限！');
			}

			$data['rules'] = implode(',', $data['rules']);
			$map['id'] = $id;
			
			if(D('Admin')->setUserGroup($data, $map)){
				jsalert('权限分组更新成功!', U('editUserGroup', array('id' => $id)));
			}else{
				jsalert('权限分组更新失败，请重试!');

			}
		}else{

			$userGroupInfo = D('Admin')->getGroupInfoById($id);
			$groupList = D('Cinema')->getGroup();
			$this->assign('groupList',$groupList);
			$menu = D('Admin')->getMenuList('mid,pid,menuName,url,ico,isshow,position');
			$tree = new \Common\Controller\Tree();
			$tree->setData($menu);
			$tree->checkMenu(0, $userGroupInfo['rules']);
			$menuStrCheck = $tree->menuStrCheck;
			$this->assign('menuStrCheck',$menuStrCheck);

			$this->assign('userGroupInfo',$userGroupInfo);
			$this->display('usergroupfrom');
		}
	}

	/**
	 * 删除权限组
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function delUserGroup()
	{
		if(IS_AJAX){
			$id = I('request.id');
			if(D('Admin')->delUserGroupById($id)){
	            $this->success('恭喜您，影院删除成功！', $cinemaList);
	        }else{
	            $this->error('很遗憾，影院删除失败！');
	        }
		}
	}

	/**
	 * 权限组列表
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function userGroupList()
	{
		$groupList = D('Cinema')->getGroup();
		$this->assign('groupList',$groupList);
		$userGroupList = D('Admin')->getUserGroupList();
		$this->assign('userGroupList',$userGroupList);

		$this->display();
	}

	/**
	 * 添加权限组
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function AddUserGroup()
	{

		if(IS_POST){
			$data = I('post.data');

			if(empty($data['cinemaGroupId'])){
				jsalert('影院分组不能为空！');
			}

			if(empty($data['roleName'])){
				jsalert('权限组名称不能为空！');
			}

			if(empty($data['rules'])){
				jsalert('请选择权限！');
			}

			$data['rules'] = implode(',', $data['rules']);
			
			if(D('Admin')->AddUserGroup($data)){
				jsalert('权限分组添加成功!', U('userGroupList'));
			}else{
				jsalert('权限分组添加失败，请重试!');

			}
		}else{
			$pid = I('request.pid');
			$groupList = D('Cinema')->getGroup();
			$this->assign('groupList',$groupList);
			$menu = D('Admin')->getMenuList('mid,pid,menuName,url,ico,isshow, position', array('isshow'=>1));
			$tree = new \Common\Controller\Tree();
			$tree->setData($menu);
			$tree->checkMenu(0);
			$menuStrCheck = $tree->menuStrCheck;
			$this->assign('menuStrCheck',$menuStrCheck);
			$this->display('usergroupfrom');
		}
		
	}

	/**
	 * 系统菜单列表
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function menuList()
	{
		$menuList = D('Admin')->getMenuList('mid,pid,menuName,url,ico,isshow', array('pid' => 0));
		$this->assign('menuList',$menuList);

		$pid = I('request.pid');
		$menu = D('Admin')->getMenuList('mid,pid,menuName,url,ico,isshow, position');
		$tree = new \Common\Controller\Tree();
		$tree->setData($menu);
		$tree->chrent_menu(0);
		$menuStr = $tree->menuStr;

		$this->assign('menuStr',$menuStr);
		$this->display();
	}

	/**
	 * 添加系统菜单
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function addMenu()
	{

		if(IS_AJAX){

			$data = I('post.data');

			$menuInfo = D('Admin')->getMenuInfo('position', array('mid' => $data['pid']));

			$data['position'] = intval($menuInfo['position'] + 1);

			if(D('Admin')->addMenu($data)){
				$this->success('操作成功！', U('index'));
			}else{
				$this->error('数据写入失败!');
			}

		}else{
			$pid = I('request.pid');
			$menu = D('Admin')->getMenuList('mid,pid,menuName,url,ico,isshow');
			$tree = new \Common\Controller\Tree();
			$tree->setData($menu);
			$tree->option(0,$pid);
			$menuList = $tree->menuStr;
			$this->assign('menuList',$menuList);
			$this->display('menufrom');
		}
	}


	/**
	 * 更新系统菜单
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function setMenu()
	{

		if(IS_AJAX){

			$data = I('post.data');
			$map['mid'] = $data['mid'];
			if(D('Admin')->setMenu($data, $map)){
				$this->success('操作成功！', U('index'));
			}else{
				$this->error('数据写入失败!');
			}

		}else{
			$mid = I('request.mid');
			$menu = D('Admin')->getMenuList('mid,pid,menuName,url,ico,isshow');
			$menuInfo = D('Admin')->getMenuInfo('mid,pid,menuName,url,ico,isshow,sortOrder', array('mid' => $mid));

			$tree = new \Common\Controller\Tree();
			$tree->setData($menu);
			$tree->option(0,$menuInfo['pid']);
			$menuList = $tree->menuStr;
			$this->assign('data',$menuInfo);
			$this->assign('menuList',$menuList);
			$this->display('menufrom');
		}
	}

	/**
	 * 删除系统菜单
	 * 
	 * @param string $id
	 * @param array $data
	 * @param int $p
	 * @see AdminController::curPage()
	 * @see AdminController::getPageList()
	 */
	public function delMenu()
	{
		if(IS_AJAX){
			$mid = I('request.mid');

			$menuInfo = D('Admin')->getMenuInfo('position', array('pid' => $mid));
			if (!empty($menuInfo)) {
				$this->error('很遗憾，当前有子级不可删除！');
			}

			if(D('Admin')->delMenuByMid($mid)){
	            $this->success('恭喜您，系统菜单删除成功！', $cinemaList);
	        }else{
	            $this->error('很遗憾，系统菜单删除失败！');
	        }
		}
	}
	

}