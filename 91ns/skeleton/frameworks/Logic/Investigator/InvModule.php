<?php

namespace Micro\Frameworks\Logic\Investigator;

//客服后台--模块类
class InvModule extends InvBase {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    //获得模块列表
    public function getModuleList() {
        $newList = array();
        try {
            $info = \Micro\Models\InvModule::find("status=1 and moduleType<2 order by moduleSort asc");
            if ($info->valid()) {
                $carr = array();
                //一级分类
                foreach ($info as $val) {
                    if (!$val->parentId) {
                        $list[$val->id]['id'] = $val->id;
                        $list[$val->id]['moduleName'] = $val->moduleName;
                        $list[$val->id]['moduleAction'] = $val->moduleAction;
                        $list[$val->id]['moduleCss'] = $val->moduleCss;
                        $list[$val->id]['children'] = array();
                    }
                }
                //二级分类
                foreach ($list as $k => $vl) {
                    foreach ($info as $val) {
                        if ($val->parentId == $vl['id']) {
                            $carr['id'] = $val->id;
                            $carr['moduleName'] = $val->moduleName;
                            $carr['moduleAction'] = $val->moduleAction;
                            $carr['moduleCss'] = $val->moduleCss;
                            $list[$val->parentId]['children'][] = $carr;
                        }
                    }
                }
                foreach ($list as $k => $v) {
                    $newList[$v['moduleAction']] = $v;
                }
            }
        } catch (\Exception $e) {
            $this->errLog('getModuleList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $newList;
    }

    //编辑模块
    public function setModule($info) {
        try {
            if ($info['id']) {//修改
                $inv = \Micro\Models\InvModule::findfirst("id=" . $info['id']);
                if ($inv == FALSE) {
                    return FALSE;
                }
                $info['parentId'] && $inv->parentId = $info['parentId'];
                $info['moduleName'] && $inv->moduleName = $info['moduleName'];
                $info['moduleAction'] && $inv->moduleAction = $info['moduleAction'];
                $info['moduleCss'] && $inv->moduleCss = $info['moduleCss'];
                $info['moduleSort'] && $inv->moduleSort = $info['moduleSort'];
                $info['status'] && $inv->moduleSort = $info['status'];
                return $inv->save();
            } else {//新增
                $new = new \Micro\Models\InvModule();
                $new->parentId = $info['parentId'];
                $new->moduleName = $info['moduleName'];
                $new->moduleAction = $info['moduleAction'];
                $new->moduleCss = $info['moduleCss'];
                $new->moduleSort = $info['moduleSort'];
                $new->status = $info['status'];
                $new->createTime = time();
                return $new->save();
            }
        } catch (\Exception $e) {
            $this->errLog('setModule error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

}
