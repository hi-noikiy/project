<?php

namespace Micro\Frameworks\Logic\Investigator;

use Micro\Frameworks\Activation\Activator;

//客服后台--结算
class InvSettle extends InvBase {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    // 申请结算的主播/家族列表
    public function getAccountApplyList($isFamily = 0, $namelike = '', $currentPage = 1, $pageSize = 10, $ids = '') {
        $newresult = array();
        try {
            $list = array();
            $exp = "1";
            if ($isFamily) {//家族
                $table = "\Micro\Models\Family f left join \Micro\Models\SignAnchor s on f.id=s.familyId ";
                $field = "f.id,f.logo,f.name,sum(money) as money ";
                $exp.=" and money>0 ";
                //排除申请中的id、已选择的id
                $idsString = '';
//                $accountResult = \Micro\Models\InvAccounts::find("type=2 and status=0");
//                if ($accountResult->valid()) {
//                    foreach ($accountResult as $v) {
//                        $exIds[] = $v->id;
//                    }
//                    $idsString = implode(',', $exIds);
//                    $ids && $idsString = $idsString . ',' . $ids;
//                } else {
                $idsString = $ids;
//                }
                $idsString && $exp.=" and f.id not in (" . $ids . ")";
				
				if(!empty($namelike)){
					$exp.=" and f.id like '%".$namelike."%'";
				}
				 
                $exp.=" group by f.id";
                $order = "f.id ASC";
            } else {//主播
                //查询兑换下限的值
                $anchor = new \Micro\Frameworks\Logic\Investigator\InvAnchor();
                $exLimit = $anchor->setExchangeLimit(0);
                $field = "up.uid as id, ui.nickName as name, ui.avatar as logo ,up.money";
                $table = "\Micro\Models\SignAnchor s inner join \Micro\Models\UserProfiles up on s.uid=up.uid "
                        . " inner join  \Micro\Models\UserInfo ui on up.uid=ui.uid "
                        . " left join \Micro\Models\InvUserException ue on up.uid=ue.uid and ue.type=" . $this->config->exceptionType->exchange;
                $exp.=" and s.familyId=0 and (up.money>" . $exLimit." or up.money>=ue.value)  AND s.status <> {$this->config->signAnchorStatus->apply} AND s.status <> {$this->config->signAnchorStatus->refuse}";

                //排除申请中的id、已选择的id
                $idsString = '';
//                $accountResult = \Micro\Models\InvAccounts::find("status=0");
//                if ($accountResult->valid()) {
//                    foreach ($accountResult as $v) {
//                        $exIds[] = $v->id;
//                    }
//                    $idsString = implode(',', $exIds);
//                    $ids && $idsString = $idsString . ',' . $ids;
//                } else {
                $idsString = $ids;
//                }
                $idsString && $exp.=" and up.uid not in (" . $idsString . ")";
                //按昵称、uid查找
                $namelike && $exp.=" and ((up.uid like '%" . $namelike . "%') OR (ui.nickName like '%" . $namelike . "%')) ";
                $order = "up.uid ASC";
            }
            $limit = $pageSize * ( $currentPage - 1);
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $exp . " ORDER BY " . $order . " limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['id'] = $val->id;
                    $data['isFamily'] = $isFamily;
                    $data['name'] = $val->name;
                    $data['logo'] = $val->logo;
                    $data['money'] = $val->money;
                    array_push($list, $data);
                }
            }
            //统计总数
            $count = 0;
            if ($list) {
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $exp . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                if ($countresult->valid()) {
                    $count = $countresult[0]['count'];
                }
            }
            $newresult['list'] = $list;
            $newresult['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newresult);
        } catch (\Exception $e) {
            $this->errLog('getAccountApplyList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //提交结算申请
    public function setAccountApply($ids, $isFamilys,$settleTypes) {
        try {
            $result = false;
            if ($ids) {
                $idsArray = explode(',', $ids);
                $isFamilysArray = explode(',', $isFamilys);
				$settleTypesArray = explode(',', $settleTypes);
                $account = new \Micro\Models\InvAccounts();
                $account->createTime = time();
                $account->status = 0; //申请中
                $account->peopleNum = count($idsArray);
                $account->applyUser = $this->username;
                $result = $account->save();
                if ($result) {
                    $basicSalary = 0;
                    foreach ($idsArray as $key => $val) {
                        if ($isFamilysArray[$key] == 1) {//家族
                            //家族可结算聊币
                            $money = \Micro\Models\SignAnchor::sum(
                                            array("column" => "money", "conditions" => "familyId = " . $val . " and money>0"));
                            $familyBonus = $this->checkRuleByOne($this->config->ruleType->familyBonus, $val); //查询家族对应的分成
                            $cashScale = $familyBonus / 100; //分成比例百分比
                            $rmb = sprintf("%.3f", $money / $this->config->cashScale * $cashScale); //兑换成人民币
                            $type = 2;
                            $signAnchor = \Micro\Models\SignAnchor::find("familyId=" . $val . " and money>0");
                            $cashArray = array();
                            foreach ($signAnchor as $ks => $vs) {
                                //扣除家族下主播的收益
                                $sign = \Micro\Models\SignAnchor::findfirst("uid=" . $vs->uid);
                                $cashArray[$vs->uid] = $vs->money;
                                $sign->money = 0.000;
                                $isUpdate = $sign->save();
                            }
                        } else {//主播
                            //扣除主播的收益
                            $userInfo = \Micro\Models\UserProfiles::findfirst($val);
                            $money = $userInfo->money; //可结算聊币
                            $rmb = sprintf("%.3f", $money / $this->config->cashScale); //兑换成人民币
                            //查询主播底薪设置
                            $basicSalary = $this->getAnchorBasicSalary($val, $rmb);
                            $type = 1;
                            $userInfo->money = 0.000;
                            $isUpdate = $userInfo->save();
                        }
                        if ($isUpdate) {//扣除成功
                            //写入结算日志表
                            $accountLog = new \Micro\Models\InvAccountsLog();
                            $accountLog->accountId = $account->id;
                            $accountLog->uid = $val;
                            $accountLog->type = $type;
                            $accountLog->cash = $money;
                            $accountLog->rmb = $rmb;
                            $accountLog->basicSalary = $basicSalary;
							$accountLog->settleType = isset($settleTypesArray[$key]) ? $settleTypesArray[$key] : 0;
                            $accountLog->status = 0; //申请中
                            $accountResult = $accountLog->save();


                            //添加日志记录
                            if ($isFamilysArray[$key] == 1) {//家族
                                $familyInfo = \Micro\Models\Family::findfirst($val);
                                $this->addOperate($this->username, '新增', "家族," . $familyInfo->name, "申请结算收益，申请金额{$rmb}人民币", '', '');
                            } else {//主播
                                $this->addOperate($this->username, '新增', "主播,uid" . $val, "申请结算收益，申请金额{$rmb}人民币", '', '');
                            }
                        }

                        if ($accountResult && $isFamilysArray[$key] == 1) {//家族
                            //家族结算还要写入家族结算日志表 记录结算了该家族的哪些主播
                            foreach ($signAnchor as $ks => $vs) {
                                $familyLog = new \Micro\Models\InvAccountsFamilyLog();
                                $familyLog->uid = $vs->uid;
                                $familyLog->logId = $accountLog->id;
                                $familyLog->cash = $cashArray[$vs->uid];
                                $familyLog->save();
                            }
                        }
                    }
                    //发送短信提醒
                    $noticeList = \Micro\Models\InvAccountsNotice::find();
                    if ($noticeList->valid()) {
                        $activator = new Activator();
                        foreach ($noticeList as $key => $val) {
                            //$activator->sendSMS($val->mobile, $this->username, $this->config->webType['1']['smsType']->accountNotice, date("Y-m-d"));
                            //改用bmob的短信通道 edit by 2015/07/21
                            $param = array('0' => $this->username, '1' => date("Y-m-d"));
                            $content = $activator->smsTemplate($this->config->sms_template->accountNotice, $param);
                            $result = $activator->bmobSendSms($val->mobile, $content);
                        }
                    }
                }
            }
            return $result;
        } catch (\Exception $e) {
            $this->errLog('setAccountApply error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return false;
    }
	
	//家族计算
	public function setFamilyAccountApply($family){
		try{
			//加入申请表
			$result = false;
			if($family){
				$account = new \Micro\Models\InvAccounts();
				$account->createTime = time();
				$account->status = 0; //申请中
				$account->peopleNum = count($family);
				$account->applyUser = $this->username;
				$result = $account->save(); 
				if($result){
					foreach($family as $k => $v){
							$rmb = 0;
							$cash = 0;
							$salary = 0;
							$stype = array();
							$frmb = array();
							$basic = array();
							$anchorCash = array();   //获取每个主播收益
						//用户为这个家族消费了多少计算分成百分比	提成			
						$familyBonus = $this->checkRuleByOne($this->config->ruleType->familyBonus, $k); //查询家族对应的分
						
						//家族下主播的收益 （计算每个主播的工资 （收益*百分比）/100）				
						if(is_array($v)){
							foreach($v as $key => $val){
								$sign = \Micro\Models\SignAnchor::findfirst("uid=" . $key);
								$value = $sign->money - floor($sign->money);
								
								$anchorMoney = \Micro\Models\SignAnchor::sum(
												array("column" => "money", "conditions" => "uid = " . $key . " and money>0"));
								$anchorCash[$key] = $anchorMoney;
								//收益
								if($val == 1){
									$money = \Micro\Models\SignAnchor::sum(
												array("column" => "money", "conditions" => "uid = " . $key . " and money>0"));
									$pushMoney =  sprintf("%.3f", ($money * $familyBonus)/ $this->config->cashScale ); //兑换成人民币 
									$rmb += $pushMoney;
									$cash += $money;	
									$stype[$key] = $val;
									$frmb[$key] = $pushMoney;
									$basic[$key] = 0;
								//时长
								}else if($val == 2){ 
									$basicSalary = \Micro\Models\BasicSalary::findfirst('uid = '.$key);
									//print_r($basicSalary->toarray());exit;
									$rmb += isset($basicSalary->money) ? $basicSalary->money : 0;
									$value = 0.000;	
									$stype[$key] = $val;
									$frmb[$key] = 0;
									$basic[$key] = isset($basicSalary->money) ? $basicSalary->money : 0;
								//收益 + 时长
								}else if($val == 3){
									$basicSalary = \Micro\Models\BasicSalary::findfirst('uid = ' . $key);
									$money = \Micro\Models\SignAnchor::sum(
												array("column" => "money", "conditions" => "familyId = " . $key . " and money>0"));
									$pushMoney = sprintf("%.3f", ($money * $familyBonus)/ $this->config->cashScale ); //兑换成人民币 
									$rmb += $pushMoney + $basicSalary->money;
									$cash += $money;
									$stype[$key] = $val;
									$frmb[$key] = $pushMoney;
									$basic[$key] = $basicSalary->money;
								}
								//echo $value;exit;
								//扣除家族下主播的收益 (仅按时长结算主播的聊币清0，仅按收益结算,收益+时长的收益部分结算到个位数，小数位保留。)
								$sign->money = $value;
								$isUpdate = $sign->save(); 
								
							}
						}else{
							$signAnchor = \Micro\Models\SignAnchor::find("familyId=" . $k . " and money>0");
							$cashArray = array();
							foreach ($signAnchor as $ks => $vs) {
								$anchorMoney = \Micro\Models\SignAnchor::sum(
											array("column" => "money", "conditions" => "uid = " . $vs->uid . " and money>0"));
								$anchorCash[$vs->uid ] = $anchorMoney;
								
								$value = $vs->money - floor($vs->money);
								//判断主播的结算类型
								$basicSalary = \Micro\Models\BasicSalary::findfirst('uid = ' . $vs->uid);
								if($basicSalary->type == 0 || $basicSalary->type == 1){ 
									//按收益
									$money = \Micro\Models\SignAnchor::sum(
											array("column" => "money", "conditions" => "uid = " . $vs->uid . " and money>0"));
									$pushMoney = sprintf("%.3f", ($money * $familyBonus)/ $this->config->cashScale ); //兑换成人民币 
									$rmb += $pushMoney;
									$cash += $money;
									$stype[$vs->uid] =1;
									
									$frmb[$vs->uid] = $pushMoney;
									$basic[$vs->uid] = 0;
								}else if($basicSalary->type == 2){  //固定  ## 收益+时长
									$basicSalary = \Micro\Models\BasicSalary::findfirst('uid = ' . $vs->uid);
									$money = \Micro\Models\SignAnchor::sum(
											array("column" => "money", "conditions" => "uid = " . $vs->uid . " and money>0"));
									$pushMoney = sprintf("%.3f", ($money * $familyBonus)/ $this->config->cashScale ); //兑换成人民币 
									$rmb += $pushMoney + $basicSalary->money;
									$cash += $money;
									$stype[$vs->uid] = 3;
									
									$frmb[$vs->uid] = $pushMoney;
									$basic[$vs->uid] = $basicSalary->money;
								}else {
									$basicSalary = \Micro\Models\BasicSalary::findfirst('uid = ' . $vs->uid);
									$rmb += $basicSalary->money;
									$value = 0.000;
									$stype[$vs->uid] = 2;
									
									$frmb[$vs->uid] = 0;
									$basic[$vs->uid] = $basicSalary->money;
								}
							   //扣除家族下主播的收益 (仅按时长结算主播的聊币清0，仅按收益结算,收益+时长的收益部分结算到个位数，小数位保留。)
								$sign = \Micro\Models\SignAnchor::findfirst("uid=" . $vs->uid);
								$sign->money = $value;
								$isUpdate = $sign->save();								
							}
							
						}
						if($isUpdate){
							$this->_setLog($k,$account->id,$cash,$rmb,$salary,$stype,$frmb,$basic,$anchorCash);
						}
					}
					//发送短信提醒
                    $noticeList = \Micro\Models\InvAccountsNotice::find();
                    if ($noticeList->valid()) {
                        $activator = new Activator();
                        foreach ($noticeList as $key => $val) {
                            $activator->sendSMS($val->mobile, $this->username, $this->config->webType['1']['smsType']->accountNotice, date("Y-m-d"));
                        }
                    }
				}	
			}
		
			return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
             return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
        return false;
	}
	
	//写入各种日志
	private function _setLog($familyId,$accountId,$cash,$rmb,$salary,$stype,$frmb,$basicSalary,$anchorCash){
		//扣除成功
			//写入结算日志表
			$accountLog = new \Micro\Models\InvAccountsLog();
			$accountLog->uid = $familyId;
			$accountLog->accountId = $accountId;
			$accountLog->type = 2;
			$accountLog->cash = $cash;
			$accountLog->rmb = $rmb;
			$accountLog->basicSalary = $salary;
			$accountLog->status = 0; //申请中
			$accountResult = $accountLog->save();

			//添加日志记录
			$familyInfo = \Micro\Models\Family::findfirst($familyId);
			$this->addOperate($this->username, '新增', "家族," . $familyInfo->name, "申请结算收益，申请金额{$rmb}人民币", '', '');				
		if ($accountResult) {//家族
			//家族结算还要写入家族结算日志表 记录结算了该家族的哪些主播
			$signAnchor = \Micro\Models\SignAnchor::find("familyId=" . $familyId);
			foreach ($signAnchor as $ks => $vs) {
				$familyLog = new \Micro\Models\InvAccountsFamilyLog();
				$familyLog->uid = $vs->uid;
				$familyLog->logId = $accountLog->id;
				$familyLog->cash = $anchorCash[$vs->uid];
				$familyLog->stype = $stype[$vs->uid];
				$familyLog->basicSalary = $basicSalary[$vs->uid];
				$familyLog->rmb = $frmb[$vs->uid];
				$familyLog->save();
            }
		}
	}

    //待结算列表
    public function getAccountWaitingList($currentPage = 1, $pageSize = 10) {
		$list = array();
        try {           
            $count = 0;
            $limit = $pageSize * ( $currentPage - 1);
           // $exp = "a.status=0 or a.status=2 ";
			$exp = "l.status=0 ";
            $table = "\Micro\Models\InvAccounts a left join \Micro\Models\InvAccountsLog l on a.id=l.accountId ";
           /*  $sql = "SELECT l.id,sum(rmb) as rmb,a.applyUser,a.createTime,a.peopleNum,a.status,l.type,l.uid "
                    . "  FROM " . $table . " WHERE " . $exp . " group by a.id order by a.status desc,a.createTime asc limit " . $limit . ", " . $pageSize; */
			 $sql = "SELECT l.id,l.rmb,a.applyUser,a.createTime,a.peopleNum,a.status,l.type,l.uid "
				. "  FROM " . $table . " WHERE " . $exp . " order by a.status desc,a.createTime asc limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['id'] = $val->id;
                    $data['applyUser'] = $val->applyUser;
                    $data['createTime'] = date("Y-m-d H:i:s", $val->createTime);
                    $data['peopleNum'] = $val->peopleNum;
                    $data['rmb'] = $val->rmb;
					$data['type'] = $val->type;
					$data['uid'] = $val->uid;
                    if ($val->status == 2) {//锁定状态
                        $status = $val->lockUser . "锁定";
                        $isAccount = 0; //不可结算
                    } else {
                        $status = '正常';
                        $isAccount = 1;
                    }					
                    $data['status'] = $status;
                    $data['isAccount'] = $isAccount;
					if ($val->type == 1) {//主播
                        $hsql = "select ui.nickName,ui.avatar"
                                . " from \Micro\Models\UserInfo ui "
                                . " where ui.uid=" . $val->uid;
                        $hquery = $this->modelsManager->createQuery($hsql);
                        $hresult = $hquery->execute();
                        $data['name'] = $hresult[0]['nickName'];
                        $data['logo'] = $hresult[0]['avatar'] ? $hresult[0]['avatar'] : $this->pathGenerator->getFullDefaultAvatarPath();
                    } elseif ($val->type == 2) {//家族
                        $hsql = "select f.name,f.logo"
                                . " from \Micro\Models\Family f "
                                . " where f.id=" . $val->uid;
                        $hquery = $this->modelsManager->createQuery($hsql);
                        $hresult = $hquery->execute();
                        $data['name'] = $hresult[0]['name'];
                        $data['logo'] = $hresult[0]['logo'];
                    }
                    $list[] = $data;
                }
				
                //$count = \Micro\Models\InvAccounts::count("status=0 or status=2");
				$count = \Micro\Models\InvAccountsLog::count("status=0");
            }

            $return['list'] = $list;
            $return['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getAccountWaitingList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }
	
	//待结算 ## 导出excel 
	public function getWaitSettleByExcel(){
		$list = array();
		try{
			$exp = "a.status=0 or a.status=2";
            $table = "\Micro\Models\InvAccountsLog l left join \Micro\Models\SignAnchor s on l.uid=s.uid left join \Micro\Models\InvAccounts a on a.id=l.accountId";
            $sql = "SELECT l.id,sum(l.rmb) as rmb,l.type,l.uid,l.basicSalary,s.realName,s.telephone,s.idCard,s.bank,s.cardNumber,l.settleType "
                    . "  FROM " . $table . " WHERE " . $exp . " group by a.id order by a.status desc,a.createTime asc";
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if(!empty($tempData)){
				$wage = 0;
				foreach($tempData as $val){
					$data['basicSalary'] = $val->basicSalary;
					$data['rmb'] = $val->rmb;
					$data['uid'] = $val->uid;
					$data['name'] = $val->realName;
					$data['telephone'] = $val->telephone;
					$data['idCard'] = $val->idCard;
					$wage += $val->rmb;
					
					$data['bank'] = $val->bank;
					$data['cardNumber'] = $val->cardNumber;
					if ($val->type == 2) {//家族
                        $hsql = "select f.name,s.realName,s.telephone,s.idCard,s.bank,s.cardNumber"
                                . " from \Micro\Models\Family f inner join \Micro\Models\SignAnchor s on f.creatorUid = s.uid"
                                . " where f.id=" . $val->uid;
                        $hquery = $this->modelsManager->createQuery($hsql);
                        $hresult = $hquery->execute();
						$data['uid'] = $hresult[0]['name'];
                        $data['name'] = $hresult[0]['realName'];
                        $data['telephone'] = $hresult[0]['telephone'];
                        $data['idCard'] = $hresult[0]['idCard'];
                        $data['bank'] = $hresult[0]['bank'];
                        $data['cardNumber'] = $hresult[0]['cardNumber'];
                    }		
					array_push($list,$data);
				}
				$result['wage'] = $wage;
				$result['list'] = $list;
				$this->getWaitExcel('待结算工资表',$result);
			}
		} catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	} 

    //待结算 ## 导出excel 
    public function getWaitSettleExcel(){
        $list = array();
        try{
            $sql = 'select sl.id,sl.uid,ui.telephone,sl.rmb,sa.realName,sa.cardNumber,sa.bank,sa.idCard,cl.orderNum from \Micro\Models\InvSettleLog as sl ' 
                   . ' left join \Micro\Models\SignAnchor as sa on sa.uid = sl.uid left join \Micro\Models\ChangeLog as cl on cl.id = sl.changeId '
                   . ' left join \Micro\Models\UserInfo as ui on ui.uid = sl.uid '
                   . ' where cl.status = 0 and (cl.type = ' . $this->config->changeType[2]['type'] . ' or cl.type = ' . $this->config->changeType[3]['type'] . ') ';
            
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
            if(!empty($tempData)){
                $wage = 0;
                foreach($tempData as $val){
                    $data['orderNum'] = $val->orderNum;
                    $data['rmb'] = - $val->rmb;
                    $data['id'] = $val->id;
                    $data['uid'] = $val->uid;
                    $data['name'] = $val->realName;
                    $data['telephone'] = $val->telephone;
                    $data['idCard'] = $val->idCard;
                    $wage += $val->rmb;
                    
                    $data['bank'] = $val->bank;
                    $data['cardNumber'] = $val->cardNumber;      
                    array_push($list,$data);
                }
                $result['wage'] = -$wage;
                $result['list'] = $list;
                $this->getSettleExcel('待结算工资表',$result);
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }
	 
	
	//家族 ## 主播工资表
	public function getAnchorSalaryList($familyId,$page,$pageSize){
		$list = array();
		try{
			/* $table = '\Micro\Models\SignAnchor s LEFT JOIN \Micro\Models\UserInfo ui on s.uid = ui.uid '.
					// 'LEFT JOIN \Micro\Models\UserProfiles p on s.uid = p.uid '.
					 'LEFT JOIN \Micro\Models\InvAccountsLog al on al.uid = s.uid'.
					' left join \Micro\Models\InvAccountsFamilyLog af on af.logId = al.id'; */
			$table = '\Micro\Models\InvAccountsLog al left join \Micro\Models\InvAccountsFamilyLog af on af.logId = al.id'.
					' left join \Micro\Models\SignAnchor s on s.uid = af.uid'.
					' left join \Micro\Models\UserInfo ui on ui.uid = af.uid';
			$field = "s.id,ui.uid,ui.avatar,ui.nickName,s.realName,s.idCard,s.telephone,af.stype,af.basicSalary,af.rmb";
			$condition = " s.familyId = " . $familyId . " GROUP BY s.uid";
			$limit = ($page-1)*$pageSize; 
			$sql="select ".$field." from ".$table." where ".$condition." limit ".$limit.",".$pageSize;
			$query = $this->modelsManager->createQuery($sql);
			$tempData = $query->execute();
			if(!empty($tempData)){
				foreach($tempData as $val){
					$data['id'] = $val->id;
					$data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
					$data['uid'] = $val->uid;
					$data['nickName'] = $val->nickName;
					$data['realName'] = $val->realName;
					$data['idCard'] = $val->idCard;
					$data['telephone'] = $val->telephone;
					$data['settleType'] = $val->stype;
					$data['basicSalary'] = $val->basicSalary;
					$data['rmb'] = $val->rmb;
					$data['total'] = $val->rmb + $val->basicSalary;
					$data['duration'] = $this->_getAnchorDuration($val->uid);
					array_push($list,$data);
				}
			}
			 //统计总数
            $count = 0;    
             if ($list) {
                $countSql = "SELECT count(*) counts FROM " . $table . " WHERE " . $condition;
                $countquery = $this->modelsManager->createQuery($countSql);				
                $countresult = $countquery->execute();
                $count =count($countresult);
            }
			$result['count'] = $count;
			$result['list'] = $list;
			return $this->status->retFromFramework($this->status->getCode('OK'), $result);
		} catch (\Exception $e) {          
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	} 
	
	//工资表 ## 导出excel
	public function getSalaryByExcel($familyId){
		$list = array();
		try{
			/* $table = '\Micro\Models\SignAnchor s LEFT JOIN \Micro\Models\UserInfo ui on s.uid = ui.uid '.
					'LEFT JOIN \Micro\Models\UserProfiles p on s.uid = p.uid '.
					'LEFT JOIN \Micro\Models\InvAccountsLog al on al.uid = s.uid'; */
			$table = '\Micro\Models\InvAccountsLog al left join \Micro\Models\InvAccountsFamilyLog af on af.logId = al.id'.
					' left join \Micro\Models\SignAnchor s on s.uid = af.uid'.
					' left join \Micro\Models\UserInfo ui on ui.uid = af.uid';
			$field = "ui.uid,s.realName,s.idCard,s.telephone,af.basicSalary,af.rmb";
			$condition = " s.familyId = " . $familyId . " GROUP BY s.uid";
			$sql = "select ".$field." from ".$table." where ".$condition;
			$query = $this->modelsManager->createQuery($sql);
			$tempData = $query->execute();		
			if(!empty($tempData)){
				$wage = 0; //总工资
				$_basicSalary = 0;
				$_rmb = 0;
				$length = 0; //总时长
				foreach($tempData as $val){
					$data['uid'] = $val->uid;
					$data['realName'] = $val->realName;
					$data['telephone'] = $val->telephone;
					$data['idCard'] = $val->idCard;
					$data['basicSalary'] = $val->basicSalary;
					$data['rmb'] = $val->rmb;
					$data['duration'] = $this->_getAnchorDuration($val->uid);
					$_rmb += $val->rmb;
					$_basicSalary += $val->basicSalary;
					$length += $data['duration'];
					array_push($list,$data);
				}
				$wage = $_basicSalary + $_rmb;
				$result['wage'] = $wage;
				$result['length'] = $length;
				$result['list'] = $list;
				$this->getSalaryExcel('主播工资表',$result);
			}
		} catch (\Exception $e) {          
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	}
	
	//计算某个主播的播出时长
	private function _getAnchorDuration($uid){
		$sql = 'select sum(rl.endTime-rl.publicTime) as times from \Micro\Models\Rooms r left join \Micro\Models\RoomLog rl on rl.roomId = r.roomId where r.uid = '.$uid;
		$query = $this->modelsManager->createQuery($sql);
        $result = $query->execute();
		return $result[0]['times'] ? $result[0]['times'] : 0;
	}
	
    //已结算列表
    public function getAccoutList($beginDate = 0, $endDate = 0, $currentPage = 1, $pageSize = 10, $isExcel = 0) {
        try {
            $list = array();
            $count = 0;
            $limit = $pageSize * ( $currentPage - 1);
            $exp = "a.status=1";
            $countexp = "status=1";
            if ($beginDate) {
                $exp.=" and a.auditTime>" . strtotime($beginDate);
                $countexp.=" and auditTime>" . strtotime($beginDate);
            }
            if ($endDate) {
                $exp.=" and a.auditTime<" . strtotime($endDate);
                $countexp.=" and auditTime<" . strtotime($endDate);
            }
            $table = "\Micro\Models\InvAccounts a inner join \Micro\Models\InvAccountsLog l on a.id=l.accountId ";
            if ($isExcel) {
                $sql = "SELECT l.id,l.uid,l.type,l.rmb,l.basicSalary,a.auditUser,a.auditTime,a.applyUser"
                        . "  FROM \Micro\Models\InvAccountsLog l inner join \Micro\Models\InvAccounts a on a.id=l.accountId "
                        . " WHERE " . $exp . "  ORDER BY a.auditTime desc limit 200";
                $query = $this->modelsManager->createQuery($sql);
                $result = $query->execute();
                $excelData = array();
                if ($result->valid()) {
                    foreach ($result as $val) {
                        $name = '';
                        if ($val->type == 1) {//主播
                            $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $val->uid);
                            $userInfo != false && $name = $userInfo->nickName . "(" . $val->uid . ")";
                            $type = "主播";
                        } elseif ($val->type == 2) {//家族
                            $familyInfo = \Micro\Models\Family::findfirst($val->uid);
                            $familyInfo != false && $name = $familyInfo->name;
                            $type = "家族";
                        }
                        //上一次结算
                        $lastrmb = '';
                        $lasttime = '';

                        $lastInfo = \Micro\Models\InvAccountsLog::findfirst("uid=" . $val->uid . " and type=" . $val->type . " and status=1 and id<" . $val->id . " order by id desc");

                        $lastInfo != false && $lastrmb = $lastInfo->rmb;
                        $lastInfo != false && $lasttime = date("Y-m-d", $lastInfo->auditTime);
                        unset($data);
                        $data[] = $name;
                        $data[] = $type;
                        $data[] = $val->rmb;
                        $data[] = $val->basicSalary;
                        $data[] = date("Y-m-d", $val->auditTime);
                        $data[] = $val->auditUser;
                        $data[] = $val->applyUser;
                        $data[] = $lastrmb;
                        $data[] = $lasttime;
                        array_push($excelData, $data);
                    }
                }
                //添加日志记录
                $this->addOperate($this->username, '新增', "结算表导出", "导出{$beginDate}-{$endDate}的结算表", '', '');
                
                $headarr = array("结算对象", "对象类型", "本次结算（人民币）", "底薪", "结算日期", "申请人", "结算人", "上次结算（人民币）", "结算日期");
                $this->getExcel('结算列表', $headarr, $excelData);
                exit;
            } else {
                $sql = "SELECT l.id,sum(rmb) as rmb,applyUser,a.createTime,peopleNum,a.auditUser,a.auditTime,l.type,l.uid"
                        . "  FROM " . $table . " WHERE " . $exp . " GROUP BY a.id ORDER BY a.auditTime desc limit " . $limit . ", " . $pageSize;
            }
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['id'] = $val->id;
                    $data['applyUser'] = $val->applyUser;
                    $data['createTime'] = date("Y-m-d", $val->createTime);
                    $data['peopleNum'] = $val->peopleNum;
                    $data['rmb'] = $val->rmb;
                    $data['type'] = $val->type;
                    $data['uid'] = $val->uid;
                    $data['auditUser'] = $val->auditUser;
                    $data['auditTime'] = date("Y-m-d", $val->auditTime);
                    if ($val->type == 1) {//主播
                        $hsql = "select ui.nickName,ui.avatar"
                                . " from \Micro\Models\UserInfo ui "
                                . " where ui.uid=" . $val->uid;
                        $hquery = $this->modelsManager->createQuery($hsql);
                        $hresult = $hquery->execute();
                        $data['name'] = $hresult[0]['nickName'];
                        $data['logo'] = $hresult[0]['avatar'] ? $hresult[0]['avatar'] : $this->pathGenerator->getFullDefaultAvatarPath();
                    } elseif ($val->type == 2) {//家族
                        $hsql = "select f.name,f.logo"
                                . " from \Micro\Models\Family f "
                                . " where f.id=" . $val->uid;
                        $hquery = $this->modelsManager->createQuery($hsql);
                        $hresult = $hquery->execute();
                        $data['name'] = $hresult[0]['name'];
                        $data['logo'] = $hresult[0]['logo'];
                    }   
                    array_push($list, $data);
                }
                $count = \Micro\Models\InvAccounts::count($countexp);
            }
            $return['list'] = $list;
            $return['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getAccounList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }


    //待结算申请详情
    public function getOneAccountApply($accountId) {
        try {
            $table = "\Micro\Models\InvAccounts a left join \Micro\Models\InvAccountsLog l on a.id=l.accountId";
            $sql = "SELECT a.id,sum(rmb) as rmb,a.applyUser,a.createTime,a.peopleNum,a.auditUser,a.auditTime "
                    . "  FROM " . $table . " WHERE a.id=" . $accountId;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result != false) {
                $data['id'] = $result[0]['id'];
                $data['applyUser'] = $result[0]['applyUser'];
                $data['createTime'] = date("Y-m-d", $result[0]['createTime']);
                $data['peopleNum'] = $result[0]['peopleNum'];
                $data['rmb'] = $result[0]['rmb'];
                $data['auditTime'] = date("Y-m-d", $result[0]['auditTime']);
                $data['auditUser'] = $result[0]['auditUser'];
                return $data;
            }
        } catch (\Exception $e) {
            $this->errLog('getOneAccountApply error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return;
    }

    //待结算申请详情--申请列表
    public function getOneAccountList($accountId, $currentPage = 1, $pageSize = 10) {
        try {
            $count = 0;
            $list = array();
            $limit = $pageSize * ( $currentPage - 1);
            $table = " \Micro\Models\InvAccountsLog l ";
            $sql = "SELECT l.id,l.uid,l.type,l.rmb,l.status,l.auditImg,l.basicSalary "
                    . "  FROM " . $table . " WHERE l.accountId=" . $accountId . " limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $key => $val) {
                    $data['id'] = $val->id;
                    $data['uid'] = $val->uid;
                    $data['rmb'] = $val->rmb;
                    $data['status'] = $val->status;
                    $data['auditImg'] = $val->auditImg;
                    $data['basicSalary'] = $val->basicSalary;
                    if ($val->type == 1) {//主播
                        $hsql = "select ui.nickName,ui.avatar,s.cardNumber,s.accountName"
                                . " from \Micro\Models\SignAnchor s inner join \Micro\Models\UserInfo ui on s.uid=ui.uid "
                                . " where s.uid=" . $val->uid;
                        $hquery = $this->modelsManager->createQuery($hsql);
                        $hresult = $hquery->execute();
                        $data['name'] = $hresult[0]['nickName'];
                        $data['logo'] = $hresult[0]['avatar'];
                        $data['cardNumber'] = $hresult[0]['cardNumber'];
                        $data['accountName'] = $hresult[0]['accountName'];
                    } elseif ($val->type == 2) {//家族
                        $hsql = "select f.name,f.logo,s.cardNumber,s.accountName"
                                . " from \Micro\Models\Family f inner join \Micro\Models\SignAnchor s on f.creatorUid=s.uid "
                                . " where f.id=" . $val->uid;
                        $hquery = $this->modelsManager->createQuery($hsql);
                        $hresult = $hquery->execute();
                        $data['name'] = $hresult[0]['name'];
                        $data['logo'] = $hresult[0]['logo'];
                        $data['cardNumber'] = $hresult[0]['cardNumber'];
                        $data['accountName'] = $hresult[0]['accountName'];
                    }
                    $list[] = $data;
                }
                $count = \Micro\Models\InvAccountsLog::count("accountId=" . $accountId);
            }

            $return['list'] = $list;
            $return['count'] = $count;	
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getOneAccountList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //上传结算图片
    public function uploadAccountPic($files,$id,$remark) {
        try {
            $logInfo = \Micro\Models\InvAccountsLog::findfirst($id);
            if ($logInfo != false && $logInfo->status == 0) {//未结算
                $file = $files[0];
                $fileNameArray = explode('.', strtolower($file->getName()));
                $fileExt = $fileNameArray[count($fileNameArray) - 1];
                $filePath = $this->pathGenerator->getInvAccountPath(date("Ymd"));
                $fileName = time() . '.' . $fileExt;
                $result = $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                if ($result != false) {
                    $fileUrl = $this->pathGenerator->getFullInvAccountPath(date("Ymd"), $fileName);
                    //修改结算记录表
                    $logInfo->status = 1;
                    $logInfo->auditUser = $this->username;
                    $logInfo->auditTime = time();
					$logInfo->auditImg = $fileUrl;
                    $logInfo->remark = $remark;
                    $logResult = $logInfo->save();

                    //添加日志记录
                    if ($logInfo->type == 1) {//主播
                        $this->addOperate($this->username, '修改', "主播,uid" . $logInfo->uid, "完成收益结算，结算金额{$logInfo->rmb}人民币", '', '');
                    } else {//家族
                        $familyInfo = \Micro\Models\Family::findfirst($logInfo->uid);
                        $this->addOperate($this->username, '修改', "家族," . $familyInfo->name, "完成收益结算，结算金额{$logInfo->rmb}人民币", '', '');
                    }

                    if ($logResult) {//操作成功
                        $logOther = \Micro\Models\InvAccountsLog::findfirst("accountId=" . $logInfo->accountId . " and status=0");
                        if ($logOther == false) {//同批次申请的都已结算
                            //更改结算表
                            $accountInfo = \Micro\Models\InvAccounts::findfirst($logInfo->accountId);
                            $accountInfo->status = 1;
                            $accountInfo->auditUser = $this->username;
                            $accountInfo->auditTime = time();
                            $accountInfo->save();
                        }
                        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
                    }
                }
                return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), '');
            }
        } catch (\Exception $e) {
            $this->errLog('getOneAccountList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }
	

	//主播结算详情
	public function getAnchorSettleInfo($id){
		$info = array();
		try{
			$table ='\Micro\Models\InvAccounts a inner join \Micro\Models\InvAccountsLog al on a.id = al.accountId'.
					' inner join \Micro\Models\UserInfo ui on ui.uid = al.uid'.
					' left join \Micro\Models\SignAnchor s on s.uid = al.uid';
					
			$field = 'a.id,a.applyUser,a.createTime,al.uid,ui.nickName,s.realName,s.idCard,s.telephone,al.settleType,al.basicSalary,sum(al.rmb) as rmb,al.auditImg,s.bank,s.cardNumber,al.auditUser,al.auditTime,al.remark';
			$sql="select ".$field." from ".$table." where al.id =".$id." and al.type=1 group by al.uid";
			$query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if(!empty($tempData)){
				$info['id'] = $tempData[0]['id'];
				$info['applyUser'] = $tempData[0]['applyUser'];
				$info['createTime'] = date('Y-m-d H:i:s',$tempData[0]['createTime']);
				$info['uid'] = $tempData[0]['uid'];
				$info['nickName'] = $tempData[0]['nickName'];
				$info['realName'] = $tempData[0]['realName'];
				$info['idCard'] = $tempData[0]['idCard'];
				$info['telephone'] = $tempData[0]['telephone'];
				$info['settleType'] = $tempData[0]['settleType'];
				$info['basicSalary'] = $tempData[0]['basicSalary'];
				$info['rmb'] = $tempData[0]['rmb'];
				$info['auditImg'] = $tempData[0]['auditImg'];
				$info['bank'] = $tempData[0]['bank'];
				$info['cardNumber'] = $tempData[0]['cardNumber'];
				$info['auditUser'] = $tempData[0]['auditUser'];
				$info['auditTime'] = date('Y-m-d H:i:s',$tempData[0]['auditTime']);
				$info['remark'] = $tempData[0]['remark']; //备注
				if($tempData[0]['settleType'] == 1){
					$info['total'] = $tempData[0]['rmb']; //总结算 
					$info['basicSalary'] = 0;
				}else if($tempData[0]['settleType'] == 2){
					$info['total'] = $tempData[0]['basicSalary'];
					$info['rmb'] = 0;
				}else if($tempData[0]['settleType'] == 3){
					$info['total'] = $tempData[0]['basicSalary'] + $tempData[0]['rmb'];
				}
			}
			return $this->status->retFromFramework($this->status->getCode('OK'), $info);
		} catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
        
	}
	//主播分成
	private function anchorBonus($uid){
		$userInfo = \Micro\Models\UserProfiles::findfirst($uid);
		$cashScale = 0;
		if($userInfo){
			$money = $userInfo->money;
			$cashScale = floor($money / 100);
		}
		return $cashScale;
	}
	
	//家族结算详情
	public function getFamilySettleInfo($id){
		$info = array();
		try{
			$table = '\Micro\Models\InvAccounts a inner join \Micro\Models\InvAccountsLog al on a.id = al.accountId'.
					' inner join \Micro\Models\Family f on f.id = al.uid '.
					' left join \Micro\Models\UserInfo ui on ui.uid = f.creatorUid'.
					' left join \Micro\Models\SignAnchor s on s.uid = f.creatorUid';
			$field = 'a.id,a.applyUser,a.createTime,f.name,ui.nickName,s.idCard,s.telephone,al.basicSalary,s.bank,s.cardNumber,sum(al.rmb) as rmb,al.auditImg,f.id as familyId,al.settleType,al.auditUser,al.auditTime,al.remark';
			$sql="select ".$field." from ".$table." where al.id =".$id." and al.type = 2 group by al.uid";
			$query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if(!empty($tempData)){
				$info['id'] = $tempData[0]['id'];
				$info['applyUser'] = $tempData[0]['applyUser'];
				$info['createTime'] = date('Y-m-d H:i:s',$tempData[0]['createTime']);
				$info['name'] = $tempData[0]['name'];
				$info['nickName'] = $tempData[0]['nickName'];
				$info['idCard'] = $tempData[0]['idCard'];
				$info['telephone'] = $tempData[0]['telephone'];
				$info['settleType'] = $tempData[0]['settleType'];
				$info['basicSalary'] = $tempData[0]['basicSalary'];
				$info['rmb'] = $tempData[0]['rmb'];
				$info['auditImg'] = $tempData[0]['auditImg'];
				$info['bank'] = $tempData[0]['bank'];
				$info['cardNumber'] = $tempData[0]['cardNumber'];
				$info['auditUser'] = $tempData[0]['auditUser'];
				$info['auditTime'] = date('Y-m-d H:i:s',$tempData[0]['auditTime']);
				$info['remark'] = $tempData[0]['remark']; //备注
					$info['total'] = $tempData[0]['basicSalary'] + $tempData[0]['rmb']; //分成
			}
			return $this->status->retFromFramework($this->status->getCode('OK'), $info);
		} catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	} 
	
	
	//计算家族分成
	private function familyBonus($familyId){
		$money = \Micro\Models\SignAnchor::sum(array("column" => "money", "conditions" => "familyId = " . $familyId . " and money>0"));			
			$cashScale = floor($money / 100); //分成比例百分比
			return $cashScale;
	}
	
	

    //结算申请提醒列表
    public function getAccountNoticeList() {
        try {
            $list = array();
            $result = \Micro\Models\InvAccountsNotice::find();
            if ($result->valid()) {
                foreach ($result as $val) {
                    $data['id'] = $val->id;
                    $data['mobile'] = $val->mobile;
                    array_push($list, $data);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $list);
        } catch (\Exception $e) {
            $this->errLog('getAccountNoticeList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //结算申请提醒添加/修改
    public function setAccountNotice($mobile, $id = 0) {
        try {
            if ($id) {//修改
                $info = \Micro\Models\InvAccountsNotice::findfirst($id);
                if ($info != false) {
                    $info->mobile = $mobile;
                    $result = $info->save();
                }
            } else {//新增
                $info = new \Micro\Models\InvAccountsNotice();
                $info->mobile = $mobile;
                $result = $info->save();
            }
            if ($result) {
                //添加日志记录
                if (!$id) {//新增
                    $this->addOperate($this->username, '新增', "结算提醒", "添加号码", '', $mobile);
                }
                return $this->status->retFromFramework($this->status->getCode('OK'), '');
            }
        } catch (\Exception $e) {
            $this->errLog('setAccountNotice error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //结算申请提醒删除
    public function delAccountNotice($id) {
        try {
            $result = false;
            if ($id) {
                $info = \Micro\Models\InvAccountsNotice::findfirst($id);
                $mobile=$info->mobile;
                if ($info != false) {
                    $result = $info->delete();
                }
            }
            if ($result) {
                 $this->addOperate($this->username, '删除', "结算提醒", "删除号码",$mobile, '');
                return $this->status->retFromFramework($this->status->getCode('OK'), '');
            }
        } catch (\Exception $e) {
            $this->errLog('delAccountNotice error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }
	
	//主播 ## 结算方式
	public function getAnchorClearingForm($uid,$page,$pageSize){
		$list = array();
		try {
			$table = '\Micro\Models\UserInfo ui left join \Micro\Models\ConsumeLog cl on ui.uid = cl.anchorId '.
					' left join \Micro\Models\BasicSalary b on ui.uid = b.uid ';
			$field = 'ui.avatar,ui.nickName,ui.uid,sum(cl.income) as income,b.type,b.money';
			$where = " ui.uid in (".$uid.") AND cl.type < ".$this->config->consumeType->coinType." group by ui.uid ";
			$limit = ($page-1)*$pageSize;
			$sql = " SELECT " . $field . " FROM " . $table . " WHERE ".$where." order by b.type=1 desc,ui.uid desc limit ".$limit.",".$pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if(!empty($tempData)){
				foreach($tempData as $val){
					$data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
					$data['uid'] = $val->uid;
					$data['nickName'] = $val->nickName;
					$data['income'] = $val->income;
					$data['type'] = $val->type;
					$data['money'] = $val->money;
					array_push($list,$data);
				}
			}
			
			//统计总数
			 $count = 0;    
            if ($list) {
                $countSql = "SELECT count(*) counts FROM " . $table . " WHERE " . $where;
                $countquery = $this->modelsManager->createQuery($countSql);				
                $countresult = $countquery->execute();
                $count =count($countresult);
            }
			$result['count'] = $count;
			$result['list'] = $list;
			return $this->status->retFromFramework($this->status->getCode('OK'), $result);
		}catch (\Exception $e) {
           return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	}
	

	
	//家族 ## 选择结算方式
	public function getFamilyClearingForm($familyId,$page,$pageSize){
		$list = array();
		try{
			$table = "\Micro\Models\SignAnchor s LEFT JOIN \Micro\Models\UserInfo ui on s.uid = ui.uid LEFT JOIN \Micro\Models\UserProfiles p on s.uid = p.uid LEFT JOIN \Micro\Models\ConsumeLog cl on s.uid = cl.anchorId and  cl.type <".$this->config->consumeType->coinType." and s.familyId = cl.familyId".
			" left join \Micro\Models\BasicSalary b on s.uid = b.uid";
			$field = "ui.uid,ui.avatar,ui.nickName,sum(cl.income) as income,b.type,b.money";
			$condition = " s.familyId = " . $familyId . " GROUP BY s.uid";
			
			$limit = ($page - 1) * $pageSize;	
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $condition . " order by b.type=1 desc,s.uid desc limit " . $limit . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);  
            $tempData = $query->execute();	
			if(!empty($tempData)){
				foreach($tempData as $val){
					$data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
					$data['uid'] = $val->uid;
					$data['nickName'] = $val->nickName;
					$data['income'] = $val->income;
					$data['type'] = $val->type;
					$data['money'] = $val->money;
					array_push($list,$data);
				}
			}
			
			//统计总数
            $count = 0;    
             if ($list) {
                $countSql = "SELECT count(*) counts FROM " . $table . " WHERE " . $condition;
                $countquery = $this->modelsManager->createQuery($countSql);				
                $countresult = $countquery->execute();
                $count =count($countresult);
            }
			
			$result['count'] = $count;
			$result['list'] = $list;				
			return $this->status->retFromFramework($this->status->getCode('OK'), $result);
		}catch (\Exception $e) {
           return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	}
	
	// 获取要结算的家族
	public function getFamily($familyId){
		$info = array();
		try{
			$sql = 'select f.id,f.name,f.logo from \Micro\Models\Family f where f.id in('.$familyId.')';
			$query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if(!empty($tempData)){
				foreach($tempData as $val){
					$data['id'] = $val->id;
					$data['name'] = $val->name;
					$data['logo'] = $val->logo;
					array_push($info,$data);
				}
			}
			return $this->status->retFromFramework($this->status->getCode('OK'), $info);
		}catch (\Exception $e) {
           return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	}

}
