<?php

namespace Micro\Frameworks\Logic\Investigator;

//客服后台--用户类
class InvUser extends InvBase {

    public function __construct() {
        parent::__construct();
    }

    //查询用户列表
    public function getUserList() {
        try {
            $sql = "select u.uid,u.userName,r.roleName,u.status from \Micro\Models\InvUser u inner join \Micro\Models\InvRole r on u.roleId=r.id";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                $list = array();
                foreach ($result as $val) {
                    $data['uid'] = $val->uid;
                    $data['userName'] = $val->userName;
                    $data['roleName'] = $val->roleName;
                    $data['status'] = $val->status;
                    array_push($list, $data);
                }
            }
            //return $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $list);
        } catch (\Exception $e) {
            $this->errLog('getUserList error : errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    //添加用户
    public function addUser($username, $password, $roleId) {
        if (!$username || !$password || !$roleId) {
            return false;
        }
        try {
            $info = new \Micro\Models\InvUser();
            $info->userName = $username;
            $info->password = $password;
            $info->status = $this->config->userStatus->normal;
            $info->createTime = time();
            $info->roleId = $roleId;
            $info->createTime = $roleId;
            return $info->save();
        } catch (\Exception $e) {
            $this->errLog('addUser error username=' . $username . ' errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //查询旧密码是否正确
    public function checkPassword($password) {
        $result = $this->checkLogin();
        if (!$result) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        try {
            $info = \Micro\Models\InvUser::findfirst("uid=" . $this->uid . " AND password='" . $password . "'");
            if ($info != false) {
                return $this->status->retFromFramework($this->status->getCode('OK'), true);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), false);
            }
        } catch (\Exception $e) {
            $this->errLog('editUserInfo error : errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //修改密码
    public function editPassword($password) {
        $result = $this->checkLogin();
        if (!$result) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $this->uid = 1;
        try {
            //查询是否本人操作
            $info = \Micro\Models\InvUser::findfirst("uid=" . $this->uid);
            if ($info != false) {
                $newinfo['password'] = $password;
                $flag = $this->editInfo($this->uid, $newinfo);
                if ($flag) {
                    return $this->status->retFromFramework($this->status->getCode('OK'), true);
                }
            }
        } catch (\Exception $e) {
            $this->errLog('editPassword error : errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //修改信息
    public function editInfo($uid = 0, $info) {
        !$uid && $uid = $this->uid;
        $info['uid'] = $uid;
        return $this->editUserInfo($info);
    }

    //编辑用户信息
    private function editUserInfo($newinfo) {
        try {
            $info = \Micro\Models\InvUser::findfirst("uid=" . $newinfo['uid']);
            if ($info == false) {
                return false;
            }
            !empty($newinfo['password']) && $info->password = $newinfo['password'];
            !empty($newinfo['picture']) && $info->picture = $newinfo['picture'];
            !empty($newinfo['roleId']) && $info->roleId = $newinfo['roleId'];
            !empty($newinfo['status']) && $info->status = $newinfo['status'];
            return $info->save();
        } catch (\Exception $e) {
            $this->errLog('editUserInfo error : errorMessage = ' . $e->getMessage());
            return false;
        }
    }

}
