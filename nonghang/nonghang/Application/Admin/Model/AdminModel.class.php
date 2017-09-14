<?php

namespace Admin\Model;
use Think\Model;

class AdminModel extends Model {


    /**
    * 根据UID删除职员信息
    * @param array();
    * @return array();
    * @author 宇
    */

    public function delUserByUid($uid)
    {
        if(M('Admin')->where(array('uid'=>$uid))->delete()){
            return true;
        }else{
            return false;
        }
    }


    /**
    * 根据UID获取职员信息
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getAdminInfoByUid($uid)
    {
        $groupInfo = M('Admin')->field($field)->where(array('uid' => $uid))->order($order)->find();
        return $groupInfo;
    }


    /**
    * 添加职员
    * @param array();
    * @return array();
    * @author 宇
    */
    public function addAdmin($data)
    {
        $mod = D('Admin');
        
        if (!$mod->create($data)){
            $this->error($mod->getError());
        }else{
            if($mod->add($data)){
               return true;
            }else{
                return false;
            }
        }
    }

    /**
    * 更新职员信息
    * @param array();
    * @return array();
    * @author 宇
    */
    public function setAdminInfoByUid($data, $uid)
    {
        $mod = M('Admin');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where(array('uid'=>$uid))->data($data)->save();
            if($id){
                return true;
            }else{
                return false;
            }
        }

    }

    /**
    * 获取影院列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getAdminList($field = '*', $map = '', $limit = '', $order = '')
    {

        $userInfo = session('adminUserInfo');

        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaGroup'])){
                $map['cinemaGroup'] = array('IN', $userInfo['cinemaGroup']);
            }else{
                $map['cinemaGroup'] = array('IN', $userInfo['cinemaGroup'] . ',' . $map['cinemaGroupId']);
            }
            
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {

            if(empty($map['cinemaCodeList'])){
            $arrCinemaCodeList = explode(',', $userInfo['cinemaCodeList']);

            foreach ($arrCinemaCodeList as $key => $value) {
                if(empty($sqlStr)){
                    $sqlStr = ' cinemaCodeList like "%' . $value . '%" ';
                }else{
                    $sqlStr .= ' or cinemaCodeList like "%' . $value . '%" ';
                }
            }

            $map['_string'] = $sqlStr;
            }
            
        }

        $adminList = M('Admin')->field($field)->limit($limit)->where($map)->order($order)->select();
     
        return $adminList;
    }


    /**
    * 添加权限分组
    * @param array();
    * @return array();
    * @author 宇
    */
    public function AddUserGroup($data)
    {
         $mod = D('AdminRole');
        if (!$mod->create($data)){
                $this->error($mod->getError());
            }else{
                if($mod->add($data)){
                    return true;
                }
                else{
                    return false;
                }
            }
    }
    

    /**
    * 根据ID删除权限分组
    * @param array();
    * @return array();
    * @author 宇
    */
    public function delUserGroupById($id)
    {
        if(M('AdminRole')->where(array('id'=>$id))->delete()){
            return true;
        }else{
            return false;
        }
    }

    /**
    * 获取权限分组列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getUserGroupList($field = '*', $map = '', $limit = '', $order = '')
    {
        $userInfo = session('adminUserInfo');

        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaGroupId'])){
                $map['cinemaGroupId'] = array('IN', $userInfo['cinemaGroup']);
            }else{
                $map['cinemaGroupId'] = array('IN', $userInfo['cinemaGroup'] . ',' . $map['cinemaGroupId']);
            }
            
        }

        $userGroupList = M('AdminRole')->field($field)->limit($limit)->where($map)->order($order)->select();
        $newArray = '';
        foreach ($userGroupList as $key => $value) {
            $newArray[$value['id']] = $value;
        }

        return $newArray;
    }

    /**
    * 获取权限分组信息
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getGroupInfoById($id)
    {
        $groupInfo = M('AdminRole')->field($field)->where(array('id' => $id))->order($order)->find();
        return $groupInfo;
    }

    /**
    * 更新权限分组信息
    * @param array();
    * @return array();
    * @author 宇
    */
    public function setUserGroup($data, $map)
    {
        $mod = M('AdminRole');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if($id){
                return true;
            }else{
                return false;
            }
        }

    }
    
    /**
    * 获取系统菜单列表
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getMenuList($field = '*', $map = '', $limit = '', $order = 'pid asc, sortOrder asc')
    {
        
        $adminMenuList = M('adminMenu')->field($field)->limit($limit)->where($map)->order($order)->select();
     
        return $adminMenuList;
    }


    /**
    * 获取系统菜单信息
    * @param array();
    * @return array();
    * @author 宇
    */
    public function getMenuInfo($field = '*', $map = '')
    {
        
        $adminMenuInfo = M('adminMenu')->field($field)->where($map)->find();
     
        return $adminMenuInfo;
    }



    /**
    * 根据MID删除系统菜单
    * @param array();
    * @return array();
    * @author 宇
    */
    public function delMenuByMid($mid)
    {
        if(M('adminMenu')->where(array('mid'=>$mid))->delete()){
            return true;
        }else{
            return false;
        }
    }


    /**
    * 添加系统菜单
    * @param array();
    * @return array();
    * @author 宇
    */
    public function addMenu($data)
    {
        $mod = D('adminMenu');
        
        if (!$mod->create($data)){
            $this->error($mod->getError());
        }else{
            if($mod->add($data)){
               return true;
            }else{
                return false;
            }
        }
    }


    /**
    * 更新系统菜单
    * @param array();
    * @return array();
    * @author 宇
    */
    public function setMenu($data, $map)
    {
        $mod = D('adminMenu');
        
        if (!$mod->create($data)){
            $this->error($mod->getError());
        }else{
            if($mod->where($map)->data($data)->save()){
               return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 登录指定用户
     * @param  varchar $username 用户名
     * @param  varchar $password 用户密码
     * @return boolean      ture-登录成功，false-登录失败
     * @Author: 嗄沬 280708784@qq.com
     */
    public function login($username, $password){
        /* 检测是否在当前应用注册 */
        $map['username'] = $username;
        $user = M('Admin')->where($map)->find();
        if ($user['cinemaCodeList'] == -1 && $user['cinemaGroup'] != -1) {

            $cinemaCodeMap['id'] = $user['cinemaGroup'];
            $cinameCodeList = M('CinemaGroup')->field('cinemaList')->where($cinemaCodeMap)->find();
            $user['cinemaList'] = $cinameCodeList['cinemaList'];

        }elseif ($user['cinemaGroup'] == -1) {
            $cinemaList = M('Cinema')->field('cinemaCode')->select();
            // unset($user['cinemaCodeList']);
            foreach ($cinemaList as $key => $value) {
                if (empty($user['cinemaList'])) {
                    $user['cinemaList'] = $value['cinemaCode'];
                }else{
                    $user['cinemaList'] .= ',' . $value['cinemaCode'];
                }
            }
        }

        if(is_array($user) && $user['status']){
            /* 验证用户密码 */
            if(chrent_md5($password) != $user['password']){
                $this->error = '密码错误！'; 
                return false;
            }
        } else {
            $this->error = '用户不存在或已被禁用！'; 
            return false;
        }

        //记录行为
        //action_log('user_login', 'user', $uid, $uid);

        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

// e21c6c756ffb918a6e67faeec05eff6﻿===ae21c6c756ffb918a6e67faeec05eff6
     /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'uid'             => $user['uid'],
            'visitCount'     => array('exp', '`visitCount`+1'),
            'lastLoginTime' => NOW_TIME,
            'lastLoginIp'   => get_client_ip(1),
        );
        M('Admin')->save($data);


        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['uid'],
            'username'       => $user['username'],
            'realName'       => $user['realName'],
            'lastLoginTime' => $user['lastLoginTime'],
            'cinemaGroup' => $user['cinemaGroup'],
            'roleId' => $user['roleId'],
            'cinemaCodeList' => $user['cinemaCodeList'],
            'cinemaList' => $user['cinemaList'],
        );
        session('adminUserInfo', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }


}