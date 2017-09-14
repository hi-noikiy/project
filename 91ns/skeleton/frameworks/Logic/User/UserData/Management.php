<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Micro\Frameworks\Logic\User\UserFactory;
use Phalcon\DI\FactoryDefault;
use Micro\Models\CashLog;
use Micro\Models\Rooms;
use Micro\Models\RoomUserStatus;

  /**
   *	房管
  **/
class Management extends UserDataBase {

    public function __construct($uid) {
        parent::__construct($uid);
    }
	
	//我担任的房管
   public function getCondoList($roomId,$times,$p = 1){
      $list = array();
      try {          
        $table ='\Micro\Models\RoomUserStatus rs inner join  \Micro\Models\Rooms r on rs.roomId = r.roomId '.
                ' left join \Micro\Models\UserInfo ui on r.uid = ui.uid'.
                ' left join \Micro\Models\UserProfiles up on r.uid = up.uid'.
                ' left join \Micro\Models\Users u on r.uid = u.uid'.
                ' left join \Micro\Models\FamilyLog fl  on fl.uid = r.uid'.
                ' left join \Micro\Models\Family f on f.id = fl.familyId'.
                ' left join \Micro\Models\AnchorConfigs a on up.level2 = a.level';
        $field = 'rs.id,rs.roomId,r.uid,ui.nickName,ui.avatar,a.name,up.level2,f.shortName,rs.remarks';
        //条件
        $condition = 'rs.uid = '.$this->uid.' AND rs.level = 2';
        if($roomId != ''){
           $condition .= ' AND r.uid ='.$roomId;
        }
       $sevenDayStart = strtotime(date('Y-m-d'))- 60*60*24*7;   // 7天
        switch ($times) {
            case 1:             
              $condition .= ' AND u.updateTime  BETWEEN '.$sevenDayStart.' and '.time();
              break;
            
            case 2:
              $condition .= ' AND u.updateTime <'.$sevenDayStart;
              break;
            default :
            //
        }
        $sql = 'SELECT '.$field.' FROM '.$table.' where '.$condition.' order by up.level2 desc';
        $query = $this->modelsManager->createQuery($sql);
        $tempData = $query->execute();

        if(!empty($tempData)){
          foreach($tempData as $val){
            if($val->uid != $this->uid){
              $data['id'] = $val->id;
              $data['roomId'] = $val->roomId;
              $data['uid'] = $val->uid;
              $data['nickName'] = $val->nickName;
              $data['avatar'] = $val->avatar ?  $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
              $data['name'] = $val->name;
              $data['level'] = $val->level2;
              $data['shortName'] = $val->shortName;
              $data['remarks'] = $val->remarks;
              array_push($list,$data);
            }
          }
        }

          $result['count'] = count($list);
          if($p >= 1){
              $offset = ($p - 1) * 9;
          }else{
              $offset = 0;
          }

          $list = array_slice($list, $offset, 9);
          $result['list'] = $list;        
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
      } catch (\Exception $e) {
          return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
      }
    }


    // == 删除
    public function delCondo($id,$type,$uid){
      try{    

          if($id != ''){
            $ids = explode(',', $id);
            foreach($ids as $v){
              //判断是否为超级管理员
              $userData = \Micro\Models\Users::findfirst('uid = ' . $v . ' and manageType = 1');
              if(!empty($userData)){
                return $this->status->retFromFramework($this->status->getCode('IS_SUPER_ADMIN'));
              }
              $re = \Micro\Models\RoomUserStatus::findfirst('id = '.$v);
      				$re->level = 1;
                                $re->levelTimeLine = 0;
      				if($re->save() == false){        
                return false;
      				}                     
            }               
          } 
         
         //发送通知
          if(isset($uid)){
            $uids = explode(',', $uid); 
              foreach($uids as $k){
                $content = '';
                $sendUser = UserFactory::getInstance($k); 
                if($type == 2){ 
                  $rooms = \Micro\Models\Rooms::findfirst('uid = '.$this->uid);
                  $userInfo = \Micro\Models\UserInfo::findfirst('uid = '.$rooms->uid);  
                  $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->histManagement, array(0 => $userInfo->nickName));                 
                }else{
                  $userInfo = \Micro\Models\UserInfo::findfirst('uid = '.$this->uid); 
                  $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->management, array(0 => $userInfo->nickName));
                } 
               
                $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content); 
              }
          }
        return $this->status->retFromFramework($this->status->getCode('OK'));
      } catch (\Exception $e) {
          return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
      }    
    }

    // 获取直播间的房管
    public function getHisCondoListNew($type = 1, $page = 1, $pageSize = 20, $search = ''){
      try {
        $room = \Micro\Models\Rooms::findfirst('uid = ' . $this->uid);
        if(empty($room)){
          return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }
        $roomId = $room->roomId;
        //获取主播等级
        $anchorInfo = \Micro\Models\UserProfiles::findfirst('uid = ' . $this->uid);
        if(empty($anchorInfo)){
          return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }
        // $maxNum = $anchorInfo->level3 >= 11 ? $this->config->manageCfg->imperialUp : $this->config->manageCfg->normalUp;
        $maxNum = 50;
        $sql = 'select rs.id,rs.uid,ui.nickName,ui.avatar,rc.name,up.level3,rs.hisRemarks as remarks,rs.levelTimeLine '
          . ' from \Micro\Models\RoomUserStatus as rs '
          . ' left join \Micro\Models\UserInfo as ui on rs.uid = ui.uid '
          . ' left join \Micro\Models\UserProfiles as up on rs.uid = up.uid '
          . ' left join \Micro\Models\Users as u on u.uid = rs.uid '
          . ' left join \Micro\Models\RicherConfigs as rc on up.level3 = rc.level';
        $condition = ' where rs.roomId = ' . $roomId . ' AND rs.level = 2 ';
        $condition .= ' and ( (rs.levelTimeLine > ' . time() . ') or (rs.levelTimeLine = 0 or isnull(rs.levelTimeLine)) )';
        if($search != ''){
          $condition .= ' and (rs.uid != ' . $this->uid . ' and rs.uid like "%' . $search . '%" or ui.nickName like "%' . $search . '%" )';
        }else{
          $condition .= ' and rs.uid != ' . $this->uid;
        }
        switch ($type) {
            case 2: //30
              $dayStart = strtotime(date('Y-m-d')) - 60*60*24*30;     
              $condition .= ' AND u.updateTime < ' . $dayStart;
              break;
            
            case 3: //7
              $dayStart = strtotime(date('Y-m-d')) - 60*60*24*7;   
              $condition .= ' AND u.updateTime < ' . $dayStart;
              break;
            case 1: // all
              break;

            default: //all
              break;
        }
        $limit = ($page - 1) * $pageSize;
        $query = $this->modelsManager->createQuery($sql . $condition . ' limit ' . $limit . ',' . $pageSize);
        $tempData = $query->execute();
        $list = array();
        $count = 0;
        if($tempData->valid()){
          foreach($tempData as $val){
            if($val->uid != $this->uid){
              $data['id'] = $val->id;
              $data['roomId'] = $roomId;
              $data['uid'] = $val->uid;
              $data['nickName'] = $val->nickName;
              $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
              $data['name'] = $val->name;
              $data['richerLevel'] = $val->level3;
              $data['remarks'] = $val->remarks ? $val->remarks : '';
              if ($val->levelTimeLine) {
                  $data['isTemp'] = 1; //临时管理员
              } else {
                 $data['isTemp'] = 0;
              }
             array_push($list,$data);
            }
          }
          $queryNum = $this->modelsManager->createQuery($sql . $condition);
          $numRes = $queryNum->execute();
          $count = $numRes->valid() ? count($numRes) : 0;
        }

        $sqlLeft = $sql . ' where rs.roomId = ' . $roomId 
          . ' AND rs.level = 2 and ( (rs.levelTimeLine > ' . time() . ') or (rs.levelTimeLine = 0 or isnull(rs.levelTimeLine)) )' 
          . ' and rs.uid != ' . $this->uid;
        $queryLeft = $this->modelsManager->createQuery($sqlLeft);
        $leftRes = $queryLeft->execute();
        $count1 = $leftRes->valid() ? count($leftRes) : 0;
        $leftNum = $maxNum - $count1;

        return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$list,'count'=>$count,'leftNum'=>$leftNum,'roomId'=>$roomId));

      } catch (\Exception $e) {
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
      }
    }

    //我自己的房管
    public function  getHisCondoList($roomId,$times, $p = 1){
      $list = array();
      try {          
        $table ='\Micro\Models\RoomUserStatus rs inner join  \Micro\Models\Rooms r on rs.roomId = r.roomId '.
                ' left join \Micro\Models\UserInfo ui on rs.uid = ui.uid'.
                ' left join \Micro\Models\UserProfiles up on rs.uid = up.uid'.
                ' left join \Micro\Models\Users u on rs.uid = u.uid'.
                ' left join \Micro\Models\FamilyLog fl  on fl.uid = rs.uid'.
                ' left join \Micro\Models\Family f on f.id = fl.familyId'.
                ' left join \Micro\Models\RicherConfigs rc on up.level3 = rc.level';
        $field = 'rs.id,rs.roomId,rs.uid,ui.nickName,ui.avatar,rc.name,up.level3,f.shortName,rs.hisRemarks as remarks';
        //条件
        $condition = 'r.uid = '.$this->uid.' AND rs.level = 2 ';
        if($roomId != ''){
           $condition .= ' AND rs.uid ='.$roomId;
        }
       $sevenDayStart = strtotime(date('Y-m-d'))- 60*60*24*30;   // 30天
        switch ($times) {
            case 1:             
              $condition .= ' AND u.updateTime  BETWEEN '.$sevenDayStart.' and '.time();
              break;
            
            case 2:
              $condition .= ' AND u.updateTime < '.$sevenDayStart;
              break;
        }
        $sql = 'SELECT '.$field.' FROM '.$table.' where '.$condition.' GROUP BY rs.uid order by up.level3 desc';
        $query = $this->modelsManager->createQuery($sql);
        $tempData = $query->execute();
        if(!empty($tempData)){
          foreach($tempData as $val){
            if($val->uid != $this->uid){
              $data['id'] = $val->id;
              $data['roomId'] = $val->roomId;
              $data['uid'] = $val->uid;
              $data['nickName'] = $val->nickName;
              $data['avatar'] = $val->avatar ?  $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
              $data['name'] = $val->name;
              $data['level'] = $val->level3;
              $data['shortName'] = $val->shortName;
              $data['remarks'] = $val->remarks;
              array_push($list,$data);
            }
            
          }
        }

         //计算上限
        $count = 0;  

        $countSql = 'SELECT '.$field.' FROM '.$table.' where r.uid = '.$this->uid.' AND rs.level=2 order by up.level3 desc';
        $countQuery = $this->modelsManager->createQuery($countSql);
        $number = $countQuery->execute();
        $_tmpCount = 1000;
        $_count = 200;
        if(!empty($number)){
          foreach($number as $tmp){
            if($tmp->uid == $this->uid){
              $_tmpCount += 1;
              $_count += 1;
            }
          }
        }       
          $sql = 'select u.level2 from \Micro\Models\UserProfiles u where u.uid = '.$this->uid.'limit 1';
          $query = $this->modelsManager->createQuery($sql);
          $level2 = $query->execute();
          if(!empty($level2)){
            if($level2[0]->level2 >= 35){
              $count = $_tmpCount-count($number);
            }else{
              $count = $_count-count($number);
            }
          }     
          $rooms = Rooms::findfirst('uid = '.$this->uid); 
          if($p >= 1){
              $offset = ($p - 1) * 9;
          }else{
              $offset = 0;
          }

          $list = array_slice($list, $offset, 9);
          $result['roomId'] = $rooms->roomId; 
          $result['count'] = $count;
          $result['list'] = $list;               
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
      } catch (\Exception $e) {
          return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
      }
    }

    //
    public function checkAccountByUid($uid = 0, $roomId = 0){
      try {
        $res = \Micro\Models\Users::findfirst('uid = ' . $uid);
        if(empty($res)){
          return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        $res = \Micro\Models\RoomUserStatus::findfirst('level = 2 and roomId = ' . $roomId . ' and uid = ' . $uid);
        if(!empty($res)){
          return $this->status->retFromFramework($this->status->getCode('IS_YOUR_ROOM'));
        }

        $res = \Micro\Models\UserInfo::findfirst('uid = ' . $uid);
        if(empty($res)){
          return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$res->nickName));
      } catch (\Exception $e) {
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
      }
    }

    //
    //添加房管
    public function addHisCondo($roomId,$uid){ 
        try{
            $info = Rooms::findfirst('roomId  = '.$roomId);
            //判断该房间是否存在
            if(empty($info)){
              return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
	
            // 判断是否添加自己为房管
            if($info->uid == $uid){
               return $this->status->retFromFramework($this->status->getCode('CANNOT_OPER_OWNER'));
            }

            //增加判断是否为超级管理员操作
            $userInfo = \Micro\Models\Users::findfirst('uid = ' . $uid);// . ' and manageType = 1'
            if(empty($userInfo)){
              return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }elseif($userInfo->manageType == 1){
              return $this->status->retFromFramework($this->status->getCode('IS_SUPER_ADMIN'));
            }

            $maxNum = 50;
            $nums = RoomUserStatus::count(
                "uid <> " . $info->uid . " and roomId = " . $roomId . " and level = 2 and " . 
                "( (levelTimeLine > " . time() . ") or (levelTimeLine = 0 or isnull(levelTimeLine)) )"
            );
            if($nums >= $maxNum){
              return $this->status->retFromFramework($this->status->getCode('ROOM_HAS_REACHED_ITS_LIMIT'));
            }
           
            //验证该用户是不是你的房管
            $count = RoomUserStatus::findfirst(
              'uid = ' . $uid . ' and level = 2 and roomId = ' . $roomId . 
              ' and ( (levelTimeLine > ' . time() . ') or (levelTimeLine = 0 or isnull(levelTimeLine)) )'
            );

      			if(!empty($count)){
    			    // if($count->level == 2){
                return $this->status->retFromFramework($this->status->getCode('IS_YOUR_ROOM'));
    				  // }
		        }
			
			      //判断不是管理员是否存在  存在就修改状态不存在就添加 and level = 1
      			$roomStatus = RoomUserStatus::findfirst('uid =' . $uid . ' and roomId = ' . $roomId);
      			if(!empty($roomStatus)){
      				$roomStatus->level = 2;
              // $roomStatus->createTime = time();
              $roomStatus->levelTimeLine = 0;
      				$roomStatus->save();
      			}else{         				 
      				$roomUserStatus =  new RoomUserStatus();
      				$roomUserStatus->roomId = $roomId;
      				$roomUserStatus->uid = $uid;
      				$roomUserStatus->level = 2;
              $roomUserStatus->createTime = time();
              $roomUserStatus->forbid = 0;
              $roomUserStatus->kick = 0;
              $roomUserStatus->kickTimeLine = 0;
              $roomUserStatus->levelTimeLine = 0;
              $roomUserStatus->remarks = '';
      				$roomUserStatus->hisRemarks = '';
      				$roomUserStatus->save();
      			}
                        
            //给用户发送通知
            $senduser = UserFactory::getInstance($uid);
            $rooms = \Micro\Models\Rooms::findfirst('uid = '.$this->uid);
            $userInfo = \Micro\Models\UserInfo::findfirst('uid = '.$rooms->uid); 
            // $senduserInfo = \Micro\Models\UserInfo::findfirst('uid = ' . $uid);
            $content = $senduser->getUserInformationObject()->getInfoContent($this->config->informationCode->addManagement, array(0 => $userInfo->nickName));
            $senduser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
          return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
      }

    //添加备注
    public function getRemarks($id,$remarks, $self = 1){
		try{
			if($id == '')return false;
			$roomUserStatus = RoomUserStatus::findfirst('id = '.$id);
			if(!$roomUserStatus){
			  return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
			}

            if($self == 1){
                $roomUserStatus->remarks = $remarks;
            }else{
                $roomUserStatus->hisRemarks = $remarks;
            }

			$roomUserStatus->save();
			return $this->status->retFromFramework($this->status->getCode('OK'));
		} catch (\Exception $e) {
			return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
		}
    }
}
