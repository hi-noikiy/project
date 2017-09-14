<?php

namespace Micro\Frameworks\Logic\Investigator;

//客服后台--登录类
class InvLogin extends InvBase {

    public function __construct() {
        parent::__construct();
    }

    //用户登录
    public function login($username, $password) {
        try {
            $info = \Micro\Models\InvUser::findfirst("userName='" . $username . "' AND password='" . md5($password) . "' AND status=" . $this->config->userStatus->normal);
            if ($info == FALSE) {
                return array("code" => "DATA_IS_NOT_EXISTED", "data" => '');
            }
			
            $this->session->set($this->config->userSession->invUid, $info->uid);
            $this->session->set($this->config->userSession->invUsername, $info->userName);
            $rinfo = \Micro\Models\InvRole::findfirst($info->roleId);

            if ($rinfo != FALSE && !$rinfo->roleModule) {
                $roleModule = 'all'; //全部的权限
            } else {
                $roleModule = $rinfo->roleModule;
            }

            $where = "status=1 ";
            if ($roleModule != 'all') {
                $where.=" AND id in(" . $roleModule . ")";
            }
            $where.=" order by moduleSort asc";
            $moduleList = \Micro\Models\InvModule::find($where);
            if ($moduleList->valid()) {
                $list = array();
                $alist = array();
                foreach ($moduleList as $val) {
                    if ($val->moduleType < 2) {//用户显示的模块
                        if (!$val->parentId) {//一级分类
                            $list[$val->id]['id'] = $val->id;
                            $list[$val->id]['moduleName'] = $val->moduleName;
                            $list[$val->id]['moduleAction'] = $val->moduleAction;
                            $list[$val->id]['moduleCss'] = $val->moduleCss;
                        } else {//二级分类
                            $carr[$val->id]['id'] = $val->id;
                            $carr[$val->id]['moduleName'] = $val->moduleName;
                            $carr[$val->id]['moduleAction'] = $val->moduleAction;
                            $carr[$val->id]['moduleCss'] = $val->moduleCss;
                            $list[$val->parentId]['children'] = $carr;
                        }
                    } else {//用户可操作的动作
                        $alist[] = $val->moduleAction;
                    }
                }
                $this->session->set($this->config->userSession->invRoleModule, $alist);
                $this->session->set($this->config->userSession->invShowModuleList, $list);
            }
			
			//登录日志
			$this->addLoginLog($info->userName,1);
            return array("code" => "OK", "data" => '');
        } catch (\Exception $e) {
            $this->errLog('login error username=' . $username . ' errorMessage = ' . $e->getMessage());
            return array("code" => "DB_OPER_ERROR", "data" => '');
        }
    }

    //用户退出登录
    public function loginOut() {
		$userName = $this->session->get($this->config->userSession->invUsername);
        foreach ($this->config->userSession as $key => $val) {
            $this->session->remove($this->config->userSession->$key);
        }
        $this->session->remove($this->config->investigator->authkey);
        $this->session->remove($this->config->investigator->cashkey);
		$this->addLoginLog($userName,2);
        return array("code" => "OK", "data" => '');
    }

     
}
