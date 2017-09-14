<?php
// +----------------------------------------------------------------------
// | 权限认证类
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------
namespace Common\Controller;

class Auth{

    //默认配置
    protected $_config = array(
        'AUTH_ON'           => true,               // 认证开关
        'AUTH_TYPE'         => 1,                  // 认证方式，1为实时认证；2为登录认证。
        'TAB_ROLE'         => 'admin_role',              // 用户角色表名
        'TAB_MENU'         => 'admin_menu',              // 权限规则表
        'TAB_USER'         => 'admin'              // 用户信息表
    );

    public function __construct() {

        $prefix = C('DB_PREFIX');  
        $this->_config['TAB_ROLE'] = $prefix.$this->_config['TAB_ROLE'];
        $this->_config['TAB_MENU'] = $prefix.$this->_config['TAB_MENU'];
        $this->_config['TAB_USER'] = $prefix.$this->_config['TAB_USER'];
        if (C('AUTH_CONFIG')) 
		{
            $this->_config = array_merge($this->_config, C('AUTH_CONFIG'));
        }
    }

    /**
      * 检查权限
      * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
      * @param uid  int           认证用户的id
      * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
      * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid,$relation='or') {
        if (!$this->_config['AUTH_ON']){
            return true;
        }
        $authList = $this->getAuthList($uid);
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }

        $list = array();

        foreach ( $authList as $auth ) {
            if (in_array($auth , $name)){
                $list[] = $auth ;
            }
        }
        if (!empty($list)) {
            return true;
        }
        

        // if ($relation == 'or' && !empty($list)) {
        //     return true;
        // }
        // $diff = array_diff($name, $list);
        // if ($relation == 'and' && empty($diff)) {
        //     return true;
        // }
        return false;
    }

    /**
     * 获得权限列表
     * @param integer $uid  用户id
     */
    protected function getAuthList($uid) {
        static $_authList = array(); //保存用户验证通过的权限列表
        if (isset($_authList[$uid])) {
            return $_authList[$uid];
        }
        if( $this->_config['AUTH_TYPE']==2 && isset($_SESSION['_AUTH_LIST_'.$uid])){
            return $_SESSION['_AUTH_LIST_'.$uid];
        }
        $user = $this->getUserInfo($uid);

        $roles = array_unique(explode(',',$user['roleId']));
        $map=array(
            'id'=>array('in',$roles),
        );
        $user_func = M()
            ->table($this->_config['TAB_ROLE'])
            ->where($map)
            ->field('rules')->select();
        $ids = array();//保存用户所属用户组设置的所有权限规则id
        foreach ($user_func as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid] = array();
            return array();
        }

        $map=array(
            'mid'=>array('in',$ids),
        );
        //读取用户组所有权限规则
        $rules = M()->table($this->_config['TAB_MENU'])->where($map)->field('url')->select();
        $authList = array(); 
        foreach ($rules as $rule) {
                $authList[] = strtolower($rule['url']);
        }
		$authList = array_unique($authList);
        $_authList[$uid] = $authList;
        if($this->_config['AUTH_TYPE']==2){
            //规则列表结果保存到session
            $_SESSION['_AUTH_LIST_'.$uid]=$authList;
        }
        return $authList;
    }

    /**
     * 获得用户资料,根据自己的情况读取数据库
     */
    protected function getUserInfo($uid) {
        static $userinfo=array();
        if(!isset($userinfo[$uid])){
             $userinfo[$uid]=M()->where(array('uid'=>$uid))->table($this->_config['TAB_USER'])->find();
        }
        return $userinfo[$uid];
    }
}
