<?php

namespace Micro\Controllers;

use Micro\Frameworks\Logic\User\UserFactory;

class PushController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    //七夕活动
    public function qixiAction() {
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        $limit = 30000;
        if ($act) {
            try {
                $uids = $this->config->qixi->uids->toArray();
                $uidString = implode(',', $uids);
                if ($act == 2) {//查询
                    $sql = "select sum(count) sum,receiveUid "
                            . " from \Micro\Models\ConsumeDetailLog "
                            . " where receiveUid in(" . $uidString . ") and type=" . $this->config->consumeType->sendGift
                            . " and itemId=" . $this->config->qixi->giftId . " and createTime<" . $this->config->qixi->endTime
                            . " group by receiveUid having sum>=" . $limit . " order by NULL ";
                    $list = $this->modelsManager->createQuery($sql);

                    $list = $list->execute();
                    $list = $list->toArray();
                    $result = array();
                    if ($list) {
                        foreach ($list as $val) {
                            $reuserInfo = \Micro\Models\UserInfo::findfirst("uid=" . $val['receiveUid']);
                            //$result[$key]['nickName'] = $userInfo->nickName;
                            //$result[$key]['sum'] = $val['sum'];
                            echo "主播" . $reuserInfo->nickName . "&nbsp;&nbsp;共收到" . $val['sum'] . "个";


                            //查询给该主播送莲花灯最多的用户
                            $usql = "select uid, sum(count) sum "
                                    . "from \Micro\Models\ConsumeDetailLog "
                                    . "where receiveUid=" . $val['receiveUid'] . " and type=" . $this->config->consumeType->sendGift
                                    . " and itemId=" . $this->config->qixi->giftId . " and createTime<" . $this->config->qixi->endTime
                                    . " group by uid order by sum desc limit 1";
                            $uquery = $this->modelsManager->createQuery($usql);
                            $ures = $uquery->execute();
                            $ures = $ures->toArray();
                            $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $ures[0]['uid']);
                            //$result[$key]['sendNickName'] = $userInfo->nickName;
                            //$result[$key]['sendSum'] = $val['sum'];
                            echo "&nbsp;&nbsp;贡献最多的是" . $userInfo->nickName . "&nbsp;&nbsp;贡献了" . $ures[0]['sum'] . "个<br/>";
                        }
                    }
                    exit;
                }




                //给用户赠送徽章
                foreach ($uids as $val) {
                    //判断该主播收到的莲花灯总数
                    $sumsql = "select sum(count) sum "
                            . "from \Micro\Models\ConsumeDetailLog "
                            . "where receiveUid=" . $val . " and type=" . $this->config->consumeType->sendGift
                            . " and itemId=" . $this->config->qixi->giftId . " and createTime<" . $this->config->qixi->endTime
                            . " group by receiveUid  having sum>=" . $limit;
                    $sumquery = $this->modelsManager->createQuery($sumsql);
                    $sumres = $sumquery->execute();
                    $sumres = $sumres->toArray();
                    if (!$sumres) {
                        continue;
                    }


                    //判断是否已送过徽章
                    $zhinv = \Micro\Models\UserItem::findfirst("uid=" . $val . " and itemType=" . $this->config->itemType->item . " and itemId=" . $this->config->qixi->zhinv);
                    if ($zhinv == false) {
                        //给参与活动的主播送七夕织女徽章
                        $user = $user = UserFactory::getInstance($val);
                        $user->getUserItemsObject()->giveItem($this->config->qixi->zhinv, 1, 2592000);
                        //给用户发送通知
                        $content = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->zhinv, array());
                        $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    }
                    //查询给该主播送莲花灯最多的用户
                    $listsql = "select uid, sum(count) sum "
                            . "from \Micro\Models\ConsumeDetailLog "
                            . "where receiveUid=" . $val . " and type=" . $this->config->consumeType->sendGift
                            . " and itemId=" . $this->config->qixi->giftId . " and createTime<" . $this->config->qixi->endTime
                            . " group by uid order by sum desc limit 1";
                    $listquery = $this->modelsManager->createQuery($listsql);
                    $listres = $listquery->execute();
                    $listres = $listres->toArray();
                    if ($listres) {
                        //判断是否已送过
                        $niulang = \Micro\Models\UserItem::findfirst("uid=" . $val . " and itemType=" . $this->config->itemType->item . " and itemId=" . $this->config->qixi->niulang);
                        if ($niulang == false) {
                            //给用户送七夕牛郎徽章
                            $user = $user = UserFactory::getInstance($listres[0]['uid']);
                            $user->getUserItemsObject()->giveItem($this->config->qixi->niulang, 1, 2592000);
                            //给用户发送通知
                            $content = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->niulang, array());
                            $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                        }
                    }
                }
                echo "ok";
                exit;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br>七夕活动给用户赠送徽章.</br></br><form action='' method='post'>";
        $str.="<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='点击执行'></form>";
        echo $str;

        $str1 = "</br></br></br>七夕活动莲花灯累计达到" . $limit . "的直播间.</br></br><form action='' method='post'>";
        $str1.="<input type='hidden' name='act' value='2'>"
                . "&nbsp;&nbsp;<input type='submit' value='点击查看'></form>";
        echo $str1;
        exit;
    }

    //清除缓存
    public function delcacheAction() {
        header("Content-Type: text/html; charset=utf-8");
        if ($this->request->getPost('act')) {
            try {
                $key = $this->request->getPost('key');
                if (!$key) {
                    echo "key不能为空";
                    exit;
                }
                $normalLib = $this->di->get('normalLib');
                $normalLib->delCache($key);
                echo "ok";
                exit;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br>缓存的key名称如下</br>"
                . "</br>礼物座驾配置：consume_configs</br></br><hr></br><form action='' method='post'>";
        $str.="请输入要清除的缓存的key：</br></br><input type='text' name='key' value=''>"
                . "<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='点击清除'></form>";
        echo $str;
        exit;
    }

    //兑换系统
    public function exchangeAction() {
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        if ($act) {
            try {
                $giftPackageId = $this->request->getPost('giftPackageId');
                if (!$giftPackageId) {
                    echo "礼包不能为空";
                    exit;
                }
                $num = $this->request->getPost('num');
                !$num && $num = 1;
                $day = $this->request->getPost('day');
                !$day && $day = 30;
                $normalLib = $this->di->get('normalLib');
                $now = time();
                $excelList = array();
                $excelList[] = array('礼包id：' . $giftPackageId);
                $excelList[] = array('');
                $excelList[] = array('');
                $excelList[] = array('兑换码');

                for ($i = 0; $i < $num; $i++) {
                    $randStr = $normalLib->getRandStr(16);
                    //echo $randStr;
                    //echo "<br/>";
                    $new = new \Micro\Models\ExchangeGiftLog();
                    $new->code = $randStr;
                    $new->createTime = $now;
                    $new->expireTime = $now + $day * 86400;
                    $new->giftPackageId = $giftPackageId;
                    $new->uid = 0;
                    $new->getTime = 0;
                    $new->save();
                    $data[] = $randStr;
                    $excelList[] = $data;
                    unset($data);
                }



                //导出excel
                $fileName = '兑换码_' . date("Ymd"); //excel文件名
                $excelResult[0]['sheetName'] = '兑换码';
                $excelResult[0]['list'] = $excelList;
                $normalLib->toExcel($fileName, $excelResult);
                exit;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br>生成礼包兑换码</br></br>"
                . "<form action='' method='post'>";
        $str.="礼包id：<input size=5 type='text' name='giftPackageId' value='39'>"
                . "&nbsp;&nbsp;兑换码数量：<input size=2 type='text' name='num' value='1'>"
                // . "&nbsp;&nbsp;有效期：<input size=2 type='text' name='day' value='30'>天"
                . "<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='导出excel'></form>";
        echo $str;
        exit;
    }

    //赠送座驾、徽章 临时添加 add by 2015/12/18
    public function sendcarAction() {
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        if ($act) {
            try {
                $uid = $this->request->getPost('uid');
                if (!$uid) {
                    exit("uid不正确");
                }
                if ($act == 1) {//送座驾
                    $carId = $this->request->getPost('carId');
                    if (!$carId) {
                        exit("参数不正确");
                    }
                } elseif ($act == 2) {//送徽章
                    $itemId = $this->request->getPost("itemId");
                    if (!$itemId) {
                        exit("参数不正确");
                    }
                }
                $day = $this->request->getPost('day');
                !$day && $day = 10;
                $userinfo = \Micro\Models\UserInfo::findfirst($uid);
                if (!$userinfo) {
                    die("用户信息不正确");
                }
                $user = UserFactory::getInstance($uid);
                if ($act == 1) {//送座驾
                    $user->getUserItemsObject()->giveCar($carId, $day * 86400);
                } elseif ($act == 2) {//送徽章
                    $user->getUserItemsObject()->giveItem($itemId, 1, $day * 86400);
                }
                echo"操作成功。<br>接收人：" . $userinfo->nickName;
                exit;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br>给用户赠送座驾:</br></br>"
                . "<form action='' method='post'>";
        $str.="用户uid：<input size=5 type='text' name='uid'>&nbsp;&nbsp;"
                //. "&nbsp;&nbsp;座驾id：
                . "<input  size=2 type='hidden' name='carId' value='48'>"//先写死圣诞座驾
                //. "&nbsp;&nbsp;有效期：
                . "<input size=2 type='hidden' name='day' value='10'>"//先写死10天
                . "<br><br>座驾：圣诞雪橇，天数：10天"
                . "<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='确定'></form>";

        $str.="<hr/><br/>给用户发放徽章：<br/><br/><form action='' method='post'>"
                . "用户uid：<input size=5 type='text' name='uid'>&nbsp;&nbsp;"
                . "<input  size=2 type='hidden' name='itemId' value='16'>"
                . "<br><br>徽章：91荣耀徽章"//先写91荣耀徽章
                . "&nbsp;&nbsp;天数："
                . "<input size=2 type='text' name='day' value='10'>天"
                . "<input type='hidden' name='act' value='2'>"
                . "&nbsp;&nbsp;<input type='submit' value='确定'></form>";
        echo $str;
        exit;
    }

    //富豪等级改版，刷数据库，送座驾 add by 2016/01/05
    public function richerAction() {
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        if ($act) {
            try {
                //九富补送黑熊座驾
                $connect = $this->di->get("db");
                $selsql = "select uid,level3 from pre_user_profiles where level3=9";
                $selres = $connect->fetchAll($selsql);
                if ($selres) {
                    if ($selres) {
                        $richLevelArr = array();
                        $richNameArr = array();
                        $carNameArr = array();
                        $configslist = \Micro\Models\RicherConfigs::find();
                        foreach ($configslist as $c) {
                            $richLevelArr[$c->level] = $c->carId;
                            $richNameArr[$c->level] = $c->name;
                        }
                        $carlist = \Micro\Models\CarConfigs::find();
                        foreach ($carlist as $c) {
                            $carNameArr[$c->id] = $c->name;
                        }

                        foreach ($selres as $v) {
                            //查询是否已有该座驾
                            $carId = $richLevelArr[$v['level3']];
                            $carinfo = \Micro\Models\UserItem::findfirst("uid=" . $v['uid'] . " and itemType=" . $this->config->itemType->car . " and itemId=" . $carId);
                            if ($carinfo) {
                                if ($carinfo->itemExpireTime == $this->config->richerConfigs->carExpireTime) {//座驾有效期正确
                                    continue;
                                }
                                //改座驾有效期
                                $user = UserFactory::getInstance($v['uid']);
                                $carinfo->itemExpireTime = $this->config->richerConfigs->carExpireTime;
                                $carinfo->save();

                                //发送通知
                                $message = '全新富豪专属座驾上线，您当前的富豪等级为' . $richNameArr[$v['level3']] . '，获得专属座驾：' . $carNameArr[$carId] . '。';
                                $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $message, 'link' => '/personal/props?type=car', 'operType' => $this->config->informationOperType->check->id));
                            }
                        }
                    }
                }
                exit("OK");
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br>九富补送黑熊座驾 </br></br>"
                . "<form action='' method='post'>"
                . "<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='点击执行'></form>";
        echo $str;
        exit;
    }

    //猴年红包活动发放徽章
    public function monkeyAction() {
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        if ($act) {
            try {
                $sql = "select l.uid,ui.nickName,count(*)count from pre_red_packet l "
                        . "inner join pre_user_info ui on l.uid=ui.uid "
                        . "where l.redPacketType=2 and l.status>0 group by l.uid order by count desc limit 1";
                $info = $this->db->fetchOne($sql);
                //派红包最多的人
                if ($info) {
                    $user = UserFactory::getInstance($info['uid']);
                    $expireTime = $this->config->redPacketConfigs->monkeyRedPacket->rewards->send->expireDay * 86400;
                    $user->getUserItemsObject()->giveItem($this->config->redPacketConfigs->monkeyRedPacket->rewards->send->itemId, 1, $expireTime);
                    // 给用户发信息
                    $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $this->config->redPacketConfigs->monkeyRedPacket->rewards->send->message));

                    echo "派红包最多的用户：" . $info['nickName'];
                    echo "<br/>";
                }

                $sql = "select l.uid,ui.nickName,count(*)count from pre_red_packet_log l "
                        . "inner join pre_red_packet r on l.redPacketId=r.id "
                        . "inner join pre_user_info ui on l.uid=ui.uid "
                        . "where r.redPacketType=2 group by l.uid order by count desc limit 1";
                $info = $this->db->fetchOne($sql);
                //收红包最多的人
                if ($info) {
                    $user = UserFactory::getInstance($info['uid']);
                    $expireTime = $this->config->redPacketConfigs->monkeyRedPacket->rewards->get->expireDay * 86400;
                    $user->getUserItemsObject()->giveItem($this->config->redPacketConfigs->monkeyRedPacket->rewards->get->itemId, 1, $expireTime);
                    // 给用户发信息
                    $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $this->config->redPacketConfigs->monkeyRedPacket->rewards->get->message));
                    echo "收红包最多的用户：" . $info['nickName'];
                    echo "<br/>";
                }

                echo "操作成功。";
                exit;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br>猴年春节红包活动给用户赠送徽章.</br></br><form action='' method='post'>";
        $str.="<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='点击执行'></form>";
        echo $str;
        exit;
    }

}
