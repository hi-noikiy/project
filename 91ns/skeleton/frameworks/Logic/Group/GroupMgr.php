<?php

/*
 * 用户军团管理类
 */

namespace Micro\Frameworks\Logic\Group;

use Phalcon\DI\FactoryDefault;
use Micro\Models\Group;
use Micro\Models\GroupMember;

class GroupMgr {

    protected $di;
    protected $status;
    protected $config;
    protected $validator;
    protected $logger;
    protected $db;
    protected $userAuth;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->logger = $this->di->get('logger');
        $this->db = $this->di->get('db');
        $this->userAuth = $this->di->get('userAuth');
    }

    //编辑/添加军团
    public function editGroup($id = 0, $name = '', $shortName = '') {
        $name = trim($name);
        $shortName = trim($shortName);
        $postData['groupname'] = $name;
        $postData['groupshortname'] = $shortName;
        if ($id) {
            $postData['id'] = $id;
        }
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //检查名称是否存在
        $checkName = $this->checkGroupName($id, 1, $name);
        if ($checkName) {
            return $this->status->retFromFramework($this->status->getCode('GROUP_NAME_IS_EXIST'));
        }
        $checkshortName = $this->checkGroupName($id, 2, $shortName);
        if ($checkshortName) {
            return $this->status->retFromFramework($this->status->getCode('GROUP_SHORTNAME_IS_EXIST'));
        }
        $this->editGroupInfo($id, $name, $shortName);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //查询军团列表
    public function groupList() {
        $return = array();
        try {
            $sql = "select g.id,g.name,g.shortName,g.leaderUid,m.uid,ui.nickName "
                    . "from pre_group g left join pre_group_member m on g.id=m.groupId "
                    . "left join pre_user_info ui on m.uid=ui.uid "
                    . "order by g.id desc";
            $res = $this->db->fetchAll($sql);
            if ($res) {
                foreach ($res as $val) {
                    $list[$val['id']]['name'] = $val['name'];
                    $list[$val['id']]['shortName'] = $val['shortName'];
                    $list[$val['id']]['memberList'][$val['uid']]['uid'] = $val['uid'];
                    $list[$val['id']]['memberList'][$val['uid']]['nickName'] = $val['nickName'];
                    $list[$val['id']]['leaderUid'] = $val['leaderUid'];
                }
                foreach ($list as $key => $val) {
                    $data['id'] = $key;
                    $data['shortName'] = $val['shortName'];
                    $data['name'] = $val['name'];
                    $data['leaderUid'] = $val['leaderUid'];
                    $arr = $val['memberList'];
                    $memberlist = array();
                    foreach ($arr as $k => $mem) {
                        if ($mem['uid']) {
                            $mdata['uid'] = $mem['uid'];
                            $mdata['nickName'] = $mem['nickName'];
                            if ($val['leaderUid'] == $mem['uid']) {
                                $mdata['isLeader'] = 1;
                            }
                            $memberlist[] = $mdata;
                            unset($mdata);
                        } else {
                            break;
                        }
                    }
                    $data['memberList'] = $memberlist;
                    $return[] = $data;
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //删除军团
    public function delGroup($ids = '') {
        $idArray = explode(',', $ids);
        foreach ($idArray as $val) {
            if ($val) {
                $this->delGroupInfo($val);
            }
        }
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //设置/取消军团长
    public function setGroupLeader($id, $uid, $type = 1) {
        $postData['id'] = $id;
        $postData['uid'] = $uid;
        $postData['type'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //查询军团是否存在
        $info = Group::findfirst($id);
        if (!$info) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }

        if ($type == 1) {//设置军团长
            //查询军团长是否已设置
            if ($info->leaderUid) {
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
            }
        } else {//取消军团长
            if ($info->leaderUid != $uid) {
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
            }
            $uid = 0;
        }
        $this->setGroupLeaderUid($id, $uid);

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //添加军团成员
    public function addGroupMember($id, $uid) {
        $postData['id'] = $id;
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //查询军团是否存在
        $info = Group::findfirst($id);
        if (!$info) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }
        //查询是否已加入军团
        $check = GroupMember::findfirst("uid=" . $uid);
        if ($check) {
            return $this->status->retFromFramework($this->status->getCode('HAS_JOIN_GROUP'));
        }
        $this->addGroupMembers($id, $uid);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //删除军团成员
    public function delGroupMember($id, $uid) {
        $postData['id'] = $id;
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //查询军团是否存在
        $info = Group::findfirst($id);
        if (!$info) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }
        //判断是否是军团长
        if ($info->leaderUid == $uid) {//如果是军团长
            //撤销军团长
            $info->leaderUid = 0;
            $info->save();
        }
        $this->delGroupMemberInfo($id, $uid);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //检查名称是否存在
    private function checkGroupName($id = 0, $type = 1, $name = '') {
        $isExit = 0;
        try {
            $where = '1';
            if ($id > 0) {
                $where.=" and id<>" . $id;
            }
            if ($type == 2) {
                $where.= " and shortName='" . $name . "'";
            } else {
                $where.= " and name='" . $name . "'";
            }
            $checkName = Group::count($where);
            if ($checkName) {
                $isExit = 1;
            }
            return $isExit;
        } catch (\Exception $e) {
            $this->logger->error('checkGroupName error：errorMessage = ' . $e->getMessage());
            return $isExit;
        }
    }

    //编辑/添加军团
    private function editGroupInfo($id = 0, $name = '', $shortName = '') {
        try {
            if ($id) {//编辑
                $info = Group::findfirst($id);
                if ($info) {
                    $info->name = $name;
                    $info->shortName = $shortName;
                    $info->save();
                }
            } else {//新增
                $new = new Group();
                $new->name = $name;
                $new->shortName = $shortName;
                $new->leaderUid = 0;
                $new->createTime = time();
                $new->save();
            }
            return true;
        } catch (\Exception $e) {
            $this->logger->error('editGroupInfo error：errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //删除军团及其成员
    private function delGroupInfo($id) {
        try {
            $info = Group::findfirst($id);
            $info->delete();
            $parameters = array(
                "id" => $id,
            );
            $list = GroupMember::find(array(
                        "conditions" => "groupId=:id:",
                        "bind" => $parameters,
            ));
            $list->delete();
            return true;
        } catch (\Exception $e) {
            $this->logger->error('delGroupInfo error：errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //设置/取消团长
    private function setGroupLeaderUid($id, $uid) {
        try {
            $info = Group::findfirst($id);
            $info->leaderUid = $uid;
            $info->save();
            return true;
        } catch (\Exception $e) {
            $this->logger->error('setGroupLeader error：errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //添加团员
    private function addGroupMembers($id, $uid) {
        try {
            $info = new GroupMember();
            $info->groupId = $id;
            $info->uid = $uid;
            $info->createTime = time();
            $info->save();
            return true;
        } catch (\Exception $e) {
            $this->logger->error('addGroupMember error：errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //删除成员
    private function delGroupMemberInfo($id, $uid) {
        try {
            $parameters = array(
                "groupId" => $id,
                "uid" => $uid,
            );
            $info = GroupMember::findFirst(array(
                        "conditions" => "groupId=:groupId: and uid=:uid:",
                        "bind" => $parameters,
            ));

            if ($info) {
                $info->delete();
            }
            return true;
        } catch (\Exception $e) {

            $this->logger->error('delGroupInfo error：errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //查询用户的
    public function checkUserGroup($uid=0) {
        $result = array();
        if (!$uid) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        $groupMemInfo = GroupMember::findfirst("uid=" . $uid);
        if (!$groupMemInfo) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        $groupInfo = Group::findfirst($groupMemInfo->groupId);
        if ($groupInfo->leaderUid == $uid) {
            $result['isLeader'] = 1;
        }
        $result['shortName'] = $groupInfo->shortName;
        $result['name'] = $groupInfo->name;
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

}
