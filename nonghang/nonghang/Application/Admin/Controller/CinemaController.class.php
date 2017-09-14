<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class CinemaController extends AdminController {

    public function cinemalist(){



        $groupList = D('Cinema')->getGroup();

        $cinemaList = D('Cinema')->getCinemaList('*', $map);
        $this->assign('groupList',$groupList);
        $this->assign('cinemaList',$cinemaList);
        $this->display();
    }
    /**
     * 影厅管理
     */
    public function halllist(){
    	if(IS_POST){
    		$map['id']=I('id');
    		$data=I('data');
    		foreach ($data as $k=>$v){
    			if(!is_numeric($v)){
    				$data[$k]=0;
    			}
    		}
    		$map['price']=json_encode($data);
    		$result=D('cinemaHall')->save($map);
    		if($result!==false){
    			jsalert('修改成功');
    		}else{
    			jsalert('修改失败');
    		}
    	}else{
    		$cinemaCode = I('request.cinemaCode');
    		$cinema=D('Cinema')->find($cinemaCode);
    		$hallList = D('Cinema')->getHallList($cinemaCode);
    		$this->assign('cinemaName',$cinema['cinemaName']);
    		$this->assign('halllist',$hallList);
    		$this->display();
    	}
    }
    /**
     * 座区价格编辑
     */
    public function sectionEdit(){
    	$id=I('id');
    	$hall=D('cinemaHall')->find($id);
    	$prices=json_decode($hall['price'],true);
    	$sections=D('cinema')->getSectionList($hall['cinemaCode'],$hall['hallNo']);
    	foreach ($sections as $k=>$v){
    		if(empty($prices[$v['sectionId']])){
    			$sections[$k]['price']=0;
    		}else{
    			$sections[$k]['price']=$prices[$v['sectionId']];
    		}
    	}
    	echo json_encode($sections);
    }
    public function delCinema($cinemaCode)
    {
        if(D('Cinema')->delCinemaByCode($cinemaCode)){
            $this->success('恭喜您，影院删除成功！', $cinemaList);
        }else{
            $this->error('很遗憾，影院删除失败！');
        }
    }


    public function cinemaEdit($cinemaCode){
        if(IS_AJAX){
            $data = I('request.data');
                // print_r($data);
            if(empty($data['cinemaCode'])){
                $this->error('请输入影院编号！');
            }
            if(empty($data['prov'])){
                $this->error('请选择影院所属省份！');
            }
            if(empty($data['city'])){
                $this->error('请输入影院所属城市！');
            }
            if(empty($data['address'])){
                $this->error('请输入影院详细地址！');
            }
            if(empty($data['phone'])){
                $this->error('请输入影院联系电话！');
            }
            if(empty($data['longitude'])){
                $this->error('请输入影院经度！');
            }
            if(empty($data['latitude'])){
                $this->error('请输入影院纬度！');
            }
            if(empty($data['serviceStart'])){
                $this->error('请选择服务开始时间！');
            }
            if(empty($data['serviceEnd'])){
                $this->error('请选择服务结束时间！');
            }
            if(empty($data['interfaceConfig']['interfaceType'])){
                $this->error('请选择影院接口类型！');
            }
            if(empty($data['interfaceConfig']['appCode'])){
                $this->error('请输入接口帐号！');
            }
            if(empty($data['interfaceConfig']['appPwd'])){
                $this->error('请输入接口密码！');
            }

            
            $data['interfaceConfig'] = json_encode($data['interfaceConfig']);
                       
            $data['payConfig'] = json_encode($data['payConfig']);
            $data['payWay'] = json_encode($data['payWay']);


            $data['serviceStart'] = strtotime($data['serviceStart']);            
            $data['serviceEnd'] = strtotime($data['serviceEnd']);


            if(I('request.cinemaCode') != 0){
                if(D('Cinema')->setCinema($data, array('cinemaCode' => I('request.cinemaCode')))){
                    $this->success('恭喜您，影院信息更新成功！');
                }else{
                    $this->error('很遗憾，影院信息更新失败！');
                }
            }
        }else{
            $cinemaInfo = D('Cinema')->getCinemaInfoBycode('*', $cinemaCode);
            $cinemaInfo['interfaceConfig'] = json_decode($cinemaInfo['interfaceConfig'], true);
            $cinemaInfo['payConfig'] = json_decode($cinemaInfo['payConfig'], true);
            $cinemaInfo['payWay'] = json_decode($cinemaInfo['payWay'], true);
            $groupList = D('Cinema')->getGroup();
            // print_r($cinemaInfo);
            $this->assign('groupList',$groupList);
            $this->assign('cinemaInfo',$cinemaInfo);
            $this->assign('cinemaGroupId',$cinemaGroupId);

            $this->display('cinemafrom');
        }
        
    }


    public function cinemaAdd(){


        if(IS_AJAX){
            $data = I('request.data');
                // print_r($data);
            if(empty($data['cinemaCode'])){
                $this->error('请输入影院编号！');
            }
            if(empty($data['prov'])){
                $this->error('请选择影院所属省份！');
            }
            if(empty($data['city'])){
                $this->error('请输入影院所属城市！');
            }
            if(empty($data['address'])){
                $this->error('请输入影院详细地址！');
            }
            if(empty($data['phone'])){
                $this->error('请输入影院联系电话！');
            }
            if(empty($data['longitude'])){
                $this->error('请输入影院经度！');
            }
            if(empty($data['latitude'])){
                $this->error('请输入影院纬度！');
            }
            if(empty($data['serviceStart'])){
                $this->error('请选择服务开始时间！');
            }
            if(empty($data['serviceEnd'])){
                $this->error('请选择服务结束时间！');
            }
            if(empty($data['interfaceConfig']['interfaceType'])){
                $this->error('请选择影院接口类型！');
            }
            if(empty($data['interfaceConfig']['appCode'])){
                $this->error('请输入接口帐号！');
            }
            if(empty($data['interfaceConfig']['appPwd'])){
                $this->error('请输入接口密码！');
            }

            
            $data['interfaceConfig'] = json_encode($data['interfaceConfig']);
                       
            $data['payConfig'] = json_encode($data['payConfig']);
            $data['payWay'] = json_encode($data['payWay']);


            $data['serviceStart'] = strtotime($data['serviceStart']);            
            $data['serviceEnd'] = strtotime($data['serviceEnd']);

            if(D('Cinema')->addCinema($data)){
                $this->success('恭喜您，影院信息添加成功！');
            }else{
                $this->error('很遗憾，影院信息添加失败！');
            }
        }else{
            $cinemaInfo['prov'] = '福建';
            $cinemaInfo['city'] = '福州';

            $groupList = D('Cinema')->getGroup();
            $this->assign('cinemaInfo',$cinemaInfo);
            $this->assign('groupList',$groupList);
            $this->display('cinemafrom');  
        }

    }

    /**
    * 获取影院分组列表
    * @param null;
    * @return null
    * @author 宇
    */
    public function cinemaGroup()
    {
        $groupList = S('groupList' . admin_is_login());
        if(empty($groupList)){
           $groupList = D('Cinema')->getGroup();
            unset($groupList[-1]);
            S('groupList' . admin_is_login(), $groupList, 0); 
        }

        foreach ($groupList as $key => $value) {
            $memberGroupList[$value['id']] = D('Cinema')->getMemberGroupInfoById($value['id']);
        }

        $this->assign('memberGroupList',$memberGroupList);
        $this->assign('groupList',$groupList);
        $this->display();
    }


    public function setCinemaGroup($cinemaGroupId)
    {
        if (IS_AJAX) {
            $data = I('request.data');
            $groupData['cinemaList'] = implode(',', $data['cinemaCode']);


            if(D('Cinema')->setMemberGroup($groupData, array('id' => $cinemaGroupId))){
                    clearCache('groupList');
                    clearCache('getGroup');
                    $this->success('恭喜您，设置影院分组成功！');
            }else{
                $this->error('很遗憾，设置影院分组失败！');
            }

        }else{
            $this->cinemaGroupId = $cinemaGroupId;
            $this->cinemaCodeList = D('Cinema')->getCinemaList('cinemaCode,cinemaName', $map);
            $groupIdInfo = D('Cinema')->getGroupInfo('cinemaList', array('id'=>$cinemaGroupId));
            $this->cinemaList = ',' . $groupIdInfo['cinemaList'] . ',';
            $this->display('setCinemaGroup');
        }
        
    }

    /**
    * 添加影院分组
    * @param $groupName:分组名称;
    * @return json
    * @author 宇
    */
    public function addGroup()
    {
        if(IS_AJAX){
            $data = I('request.data');
            if(empty($data['groupName'])){
                $this->error('分组名称不能为空！');
            }
            $data['channelConfig'] = json_encode($data['channelConfig']);
            $data['payWay'] = json_encode($data['payWay']);
            if(D('Cinema')->addGroup($data)){
                clearCache('groupList');
                clearCache('getGroup');
                $this->success('恭喜您，影院分组添加成功！');
            }else{
                $this->error('很遗憾，影院分组添加失败！');
            }
        }else{
            $this->groupList = S('groupList' . admin_is_login());
            if(!empty($groupList)){
               $this->groupList = D('Cinema')->getGroup();
                unset($groupList[-1]);
                S('groupList' . admin_is_login(), $groupList, 0); 
            }
            $groupInfo['token'] = $groupInfo['token'] ? $groupInfo['token'] : random(6,1);
            $this->data = $groupInfo;
            $this->display('groupForm');
        }
    }

    /**
    * 更新影院分组名称
    * @param $groupName:分组名称;
    * @return json
    * @author 宇
    */
    public function setMemberGroupName($cinemaGroupId)
    {   
        if(IS_AJAX){
            $data = I('request.data');
            if(empty($data['groupName'])){
                $this->error('分组名称不能为空！');
            }
            $data['channelConfig'] = json_encode($data['channelConfig']);
            $data['payWay'] = json_encode($data['payWay']);


            $upload = new \Think\Upload(); // 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     C('__UPLOAD__'); // 设置附件上传根目录
            $upload->savePath  =     'Cinema/Logo/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();

            // print_r($info);
            if (!empty($info['image'])) {
                $data['image'] = $info['image']['savepath'].$info['image']['savename'];
            }

            if(D('Cinema')->setMemberGroup($data, array('id' => $cinemaGroupId))){
                    clearCache('groupList');
                    clearCache('getGroup');
                    $this->success('恭喜您，影院分组修改成功！');
            }else{
                $this->error('很遗憾，影院分组修改失败！');
            }
        }else{
            $this->groupList = S('groupList' . admin_is_login());
            if(!empty($groupList)){
               $this->groupList = D('Cinema')->getGroup();
                unset($groupList[-1]);
                S('groupList' . admin_is_login(), $groupList, 0); 
            }

            $this->memberGroupList = D('Cinema')->getMemberGroupInfoById($cinemaGroupId);

            print_r($memberGroupList);

            $groupInfo = D('Cinema')->getGroupInfo('', array('id'=>$cinemaGroupId));
            $groupInfo['channelConfig'] = json_decode($groupInfo['channelConfig'], true);
            $groupInfo['payWay'] = json_decode($groupInfo['payWay'], true);
            $groupInfo['token'] = $groupInfo['token'] ? $groupInfo['token'] : random(6,1);
            $this->data = $groupInfo;
            $this->display('groupForm');
        }
    }
    /**
    * 删除影院分组
    * @param $groupName:分组名称;
    * @return json
    * @author 宇
    */
    public function delCinemaGroup($cinemaGroupId)
    {
        if(D('Cinema')->delMCinemaGroupById($cinemaGroupId)){
            clearCache('groupList');
            clearCache('getGroup');
            $this->success('恭喜您，影院分组删除成功！', $cinemaList);
        }else{
            $this->error('很遗憾，影院分组删除失败！');
        }
    }

    public function delCinemaMemberGroup($groupId)
    {
         if(D('Cinema')->delCinemaMemberGroupByid($groupId)){
            $this->success('恭喜您，影院会员分组删除成功！', $cinemaList);
        }else{
            $this->error('很遗憾，影院会员分组删除失败！');
        }
    }

    /**
    * 添加影院分组
    * @param $groupName:分组名称;
    * @return json
    * @author 宇
    */
    public function addMemberGroup()
    {
        if(IS_AJAX){
            $type = intval(trim(I('request.type'))); 
            $groupName = trim(I('request.groupName')); 
            $cinemaGroupId = intval(trim(I('request.cinemaGroupId'))); 
            if(empty($groupName) || $cinemaGroupId == 0){
                $this->error('很遗憾，分组名称不能为空！');
            }
            $data['cinemaGroupId'] = $cinemaGroupId;
            $data['groupName'] = $groupName;
            $data['type'] = $type;
            if(D('Cinema')->addMemberGroup($data)){
                clearCache('groupList');
                clearCache('getGroup');
                $this->success('恭喜您，会员卡分组添加成功！');
            }else{
                $this->error('很遗憾，会员卡分组添加失败！');
            }
        }
    }

    /**
    * 会员卡信息列表
    * @param array();
    * @return true/false
    * @author 宇
    */

    public function cardTypeList()
    {   
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));

        $cinemaCode = I('request.cinemaCode');
        if(!empty($cinemaCode)){
            $map['cinemaCode'] = $cinemaCode;
            
            $this->assign('cinemaCode',$cinemaCode);
        }
        $memberGroupId = I('request.memberGroupId');
        
        if(!empty($memberGroupId)){
            if ($memberGroupId == 1) {
                $map['memberGroupId'] = array('neq', 0);
            }else if($memberGroupId == 2){
                $map['memberGroupId'] = array('eq', 0);
            }
            $this->assign('memberGroupId',$memberGroupId);
        }

        $this->cinemaGroupId = I('request.cinemaGroupId');
        $map['cinemaGroupId'] = $this->cinemaGroupId;
        $this->cinemaMemberGroup = D('Cinema')->getMemberGroupInfoById($this->cinemaGroupId);


        $count = D ( 'Cinema' )->getCinemaMemberTypeCount ($map);
        $allPage = ceil ( $count / $this->limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, array('cinemaCode' => $cinemaCode, 'memberGroupId'=>$memberGroupId, 'cinemaGroupId'=>$this->cinemaGroupId));
        }
        $this->assign('page',$showPage);
        $cinemaMemberTypeList = D('Cinema')->getCinemaMemberType('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit);
        $this->assign('cinemaMemberTypeList',$cinemaMemberTypeList);


        $cinemaList = D('Cinema')->getCinemaList('cinemaCode,cinemaName');
        foreach ($cinemaList as $key => $value) {
            $newCinemaList[$value['cinemaCode']] = $value;
        }
        $this->cinemaList = $newCinemaList;
        $this->display();
    }


    public function ajaxGetCardTypeList()
    {   
        $cinemaCode = I('request.cinemaCode');
        $cinemaGroupId = I('request.cinemaGroupId');

        if (IS_AJAX) {
            $user = D('ZMUser');
            if(!empty($cinemaCode)){
                $cardType = $user->getCardType($cinemaCode);
                // print_r($cardType);
                foreach ($cardType['MemberTypes'] as $key => $value) {
                    // print_r($value);
                    $data['memberType']= $value['MemberType'];
                    $data['memberTypeName']= $value['MemberTypeName'];
                    $data['cinemaCode']= $cinemaCode;
                    $data['cinemaGroupId']= $cinemaGroupId;
                    D('Cinema')->aotuAddCinemaMemberType($data);
                }
            }else{
                $cinemaList = D('Cinema')->getCinemaList();
                foreach ($cinemaList as $key => $value) {
                    $cardType = $user->getCardType($value['cinemaCode']);
                    foreach ($cardType['MemberTypes'] as $cardKey => $cardValue) {
                        $data['memberType']= $cardValue['MemberType'];
                        $data['memberTypeName']= $cardValue['MemberTypeName'];
                        $data['cinemaCode']= $value['cinemaCode'];
                        $data['cinemaGroupId']= $cinemaGroupId;
                        D('Cinema')->aotuAddCinemaMemberType($data);
                    }
                }
            }
        }
        $this->success('恭喜您，获取影院会员列表成功！');
    }

    public function ajaxSeyCardTypeList()
    {
        $name = I('request.name');
        $value = I('request.value');
        $memberId = intval(I('request.memberId'));
        if(empty($name) || $memberId == 0){
            $this->error('很遗憾，参数不正确定！');
        }
        $data[$name] = $value;
        $map['id'] = $memberId;

        if(D('Cinema')->setCinemaMemberType($data, $map)){
            $this->success('恭喜您，会员卡设置成功！');
        }else{
            $this->error('很遗憾，会员卡设置失败！');
        }

        
    }

}