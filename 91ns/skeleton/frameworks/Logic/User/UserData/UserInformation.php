<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

//用户通知类
class UserInformation extends UserDataBase {

    protected $di;
    protected $logger;
    protected $modelsManager;
    protected $status;

    public function __construct($uid) {
        parent::__construct($uid);
        $this->di = FactoryDefault::getDefault();
        $this->logger = $this->di->get('logger');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->status = $this->di->get('status');
    }

    //查询用户的通知列表
    public function getUserInformationList($type = 0, $status = 0, $currentPage = 1, $pageSize = 20) {
        try {
            $return = array();
            $count = 0;
            $list = array();
            $limit = $pageSize * ( $currentPage - 1);
            $exp = "uid=" . $this->uid;
            if ($type) {
                $exp.=" and type=" . $type;
            }
            if ($status !== '') {
                $exp.=" and status=" . $status;
            }
            $result = \Micro\Models\UserInformation::find($exp . " order by id desc limit " . $limit . ", " . $pageSize);
            if ($result->valid()) {
                $operArray = array_column($this->config->informationOperType->toArray(), 'name', 'id');
                foreach ($result as $key => $val) {
                    $data['id'] = $val->id;
                    $data['createTime'] = date("Y-m-d H:i:s", $val->createTime);
                    $data['status'] = $val->status;
                    $data['title'] = $val->title;
                    $data['content'] = $val->content;
                    $data['link'] = $val->link; //链接
                    $operation = isset($operArray[$val->operType]) ? $operArray[$val->operType] : ''; //操作
                    $data['operation'] = $operation;
                    array_push($list, $data);
                }
                //总条数
                $count = \Micro\Models\UserInformation::count($exp);
            }
            $return['list'] = $list;
            $return['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询用户未读通知数
    public function getUnReadInformationNum($type = 0) {
        try {
            $exp = "uid=" . $this->uid . " and status=0";
            if ($type) {
                $exp .=" and type=" . $type;
            }
            //未读通知数
            $count = \Micro\Models\UserInformation::count($exp);
            return $this->status->retFromFramework($this->status->getCode('OK'), $count ? $count : 0);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //是否有未读通知
    public function isHasUnReadInformation() {
        try {
            $exp = "uid=" . $this->uid . " and status=0";
            //未读通知数
            $count = \Micro\Models\UserInformation::count($exp);
//            if (!$count) {
//                //查询是否有申请未读信息
//                $user = UserFactory::getInstance($this->uid);
//                $count = $user->getUserApplyObject()->getApplyCount();
//                if (!$count) {
//                    //查询是否有申请加入家族的未处理信息
//                    $count = $user->getUserApplyObject()->getAuditingCount();
//                }
//            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $count ? $count : 0);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查看用户某条通知
    public function getOneInformation($id) {
        try {
            $data = array();
            $info = \Micro\Models\UserInformation::findfirst("id=" . $id . " and uid=" . $this->uid);
            if ($info->valid()) {
                $data['id'] = $info->id;
                $data['createTime'] = date("Y-m-d H:i:s", $info->createTime);
                $data['content'] = $info->content;
                $data['title'] = $info->title;
                //修改为已读
                if ($info->status != 1) {
                    $info->status = 1;
                    $info->save();
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //删除通知
    public function delUserInformation($ids) {
        try {
            if ($ids) {
                $idArray = explode(',', $ids);
                foreach ($idArray as $key => $val) {
                    if ($val) {
                        $info = \Micro\Models\UserInformation::findfirst("id=" . $val . " and uid=" . $this->uid);
                        if ($info != false) {
                            $info->delete();
                        }
                    }
                }
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //阅读通知
    public function readUserInformation($ids) {
        try {
            if ($ids) {
                $idArray = explode(',', $ids);
                foreach ($idArray as $key => $val) {
                    $info = \Micro\Models\UserInformation::findfirst("id=" . $val . " and uid=" . $this->uid);
                    if ($info != false) {
                        $info->status = 1;
                        $info->save();
                    }
                }
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //给用户发送通知
    public function addUserInformation($type = 1, $params = array()) {
        try {
            $content = $params['content']; //通知内容
            $operType = isset($params['operType']) ? $params['operType'] : 0;  //操作类型：查看、审核、续费
            $link = isset($params['link']) ? $params['link'] : ''; //链接
            $title = isset($params['title']) ? $params['title'] : ''; //通知标题
            if ($content) {
                $info = new \Micro\Models\UserInformation();
                $info->uid = $this->uid;
                $info->type = $type;
                $info->title = $title;
                $info->content = $content;
                $info->status = 0; //未读
                $info->createTime = time();
                $info->operType = $operType;
                $info->link = $link;
                return $info->save();
            }
        } catch (\Exception $e) {
            $this->logger->error('addUserInformation error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
        }
        return false;
    }

    //消息内容整合
    public function getInfoContent($type, $param = array()) {
        $content = ''; //消息内容
        $operType = 0; //消息操作类型
        $link = ''; //链接
        switch ($type) {
            case $this->config->informationCode->applySignAnchor:
                $content = "您申请入驻{$this->config->webType[$this->config->channelType]['name']}平台，成为签约主播";
                $operType = $this->config->informationOperType->check->id;
                $link = '/transition/applysign';
                break;
            case $this->config->informationCode->passAnchorSign:
                $content = "您已成功成为签约主播";
                break;
            case $this->config->informationCode->failAnchorSign:
                $content = "您的主播签约申请未通过";
                break;
            case $this->config->informationCode->unbindAnchorSign:
                $content = "您已被官方解除签约";
                break;
            case $this->config->informationCode->applyCreateFamily:
                $content = "您申请创建家族<span class='nickname'>" . $param[0] . "</span>";
                $operType = $this->config->informationOperType->check->id;
                $link = '/family/create';
                break;
            case $this->config->informationCode->passCreateFamily:
                $content = "您已成功创建家族<span class='nickname'>" . $param[0] . "</span>";
                break;
            case $this->config->informationCode->failCreateFamily:
                $content = "您的创建家族<span class='nickname'>" . $param[0] . "</span>申请未通过";
                break;
            case $this->config->informationCode->failJoinFamily:
                $content = "您申请加入家族<span class='nickname'>" . $param[0] . "</span>未通过";
                break;
            case $this->config->informationCode->applyFamily:
                $content = "<span class='nickname'>" . $param[0] . "</span>申请加入家族，请到【个人中心-我是家族长-成员审核】里审核";
                $operType = $this->config->informationOperType->audit->id;
                $link = '/personal/familyheader?secondpage=memberaudit';
                break;
            case $this->config->informationCode->applyJoinFamily:
                $content = "您申请加入家族<span class='nickname'>" . $param[0] . "</span>";
                break;
            case $this->config->informationCode->passJoinFamily:
                $content = "您已成功加入家族<span class='nickname'>" . $param[0] . "</span>";
                break;
            case $this->config->informationCode->outFamily:
                $content = "<span class='nickname'>" . $param[0] . "</span>已退出家族";
                break;
            case $this->config->informationCode->familyHeaderDelAnchor:
                $content = "您已被家族长从家族<span class='nickname'>" . $param[0] . "</span>中除名";
                break;
            case $this->config->informationCode->histManagement:
                $content = "您被取消<span class='nickname'>" . $param[0] . "</span>的房间管理";
                break;
            case $this->config->informationCode->management:
                $content = "<span class='nickname'>" . $param[0] . "</span>用户辞掉了你的房管";
                break;

            case $this->config->informationCode->addManagement:
                $content = "您被设为<span class='nickname'>" . $param[0] . "</span>的房间管理";
                break;
            case $this->config->informationCode->guard:
                $content = "<span class='nickname'>" . $param[0] . "</span>成为了您的守护";
                $operType = $this->config->informationOperType->check->id;
                $link = '/personal/imanchor?secondpage=myGuard';
                break;
            case $this->config->informationCode->rich:
                $content = "尊贵的用户，您的富豪等级达到<span class='level'>" . $param[0] . "</span> ，专属座驾为 <span class='nickname'>" . $param[1] . "</span>";
                $operType = $this->config->informationOperType->check->id;
                $link = '/personal/props?type=car';
                break;
            case $this->config->informationCode->carAboutToExpire:
                $content = "您的座驾<span class='nickname'>" . $param[0] . "</span>还有3天到期，快去续费吧";
                $operType = $this->config->informationOperType->charge->id;
                $link = '/personal/props?type=car';
                break;
            case $this->config->informationCode->carHasExpired:
                $content = "您的座驾<span class='nickname'>" . $param[0] . "</span>已到期报废，被拖走扔到【我的道具-已过期】";
                $operType = $this->config->informationOperType->check->id;
                $link = '/personal/props?type=overdue';
                break;
            case $this->config->informationCode->vipHasExpired:
                $content = "您的 购买VIP 已到期，不再享有特权";
                break;
            case $this->config->informationCode->vipAboutToExpire:
                $content = "您的VIP 还有3天到期，快去续费吧";
                $operType = $this->config->informationOperType->charge->id;
                $link = '/personal/props';
                break;
            case $this->config->informationCode->badgeHasExpired:
                $content = "您的<span class='nickname'>" . $param[0] . "</span>已到期";
                $operType = $this->config->informationOperType->check->id;
                $link = '/personal/props?type=overdue';
                break;
            case $this->config->informationCode->guardHasExpired:
                $content = "您对主播<span class='nickname'>" . $param[0] . "</span>的守护已到期";
                break;
            case $this->config->informationCode->guardAboutToExpire:
                $content = "您对主播<span class='nickname'>" . $param[0] . "</span>的守护还有3天到期，爱TA就继续守护TA，快去续费吧";
                $operType = $this->config->informationOperType->charge->id;
                $link = '/personal/props';
                break;
            case $this->config->informationCode->giveVip:
                $content = "<span class='nickname'>" . $param[0] . "</span>赠送了" . $param[2] . $param[3] . "给你，已放入【我的道具】里";
                $operType = $this->config->informationOperType->check->id;
                $link = '/personal/props';
            case $this->config->informationCode->giveCar:
                $content = "<span class='nickname'>" . $param[0] . "</span>赠送了" . $param[2] . $param[3] . "给你，已放入【我的道具】里";
                $operType = $this->config->informationOperType->check->id;
                $link = '/personal/props?type=car';
                break;
            case $this->config->informationCode->givePay:
                $content = "<span class='nickname'>" . $param[0] . "</span>(" . $param[1] . ")" . "为你充值了" . $param[2] . "聊币！";
                break;
            case $this->config->informationCode->totalPay:
                $content = "【充值送豪礼】您可以领取礼包啦，快去领奖吧";
                $operType = $this->config->informationOperType->check->id;
                $link = '/activities/charge';
                break;
            case $this->config->informationCode->anchorPosterSuccess:
                $content = "您上传的<a class='poster' imgurl='{$param[0]}'>房间封面</a>已通过审核";
                 break;
             case $this->config->informationCode->anchorPosterFail:
                $content = "您上传的<a class='poster' imgurl='{$param[0]}'>房间封面</a>未通过审核";
                break;
            case $this->config->informationCode->redPacketReturn:
                $content = "您撒出的红包中，还剩余{$param[0]}聊币的红包未被用户打开，现已返还，请查收！";
                break;
            
             case $this->config->informationCode->richerHorn:
                $content = "尊贵的用户，您的富豪等级达到<span class='level'>" . $param[0] . "</span> ，获得金喇叭" . $param[1] . "个";
                $operType = $this->config->informationOperType->check->id;
                $link = '/personal/props';
                break;
        }
        $result['content'] = $content;
        $result['link'] = $link;
        $result['operType'] = $operType;
        return $result;
    }

}
