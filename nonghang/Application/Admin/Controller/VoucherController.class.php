<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class VoucherController extends AdminController {

	public $typeConfig = array('0'=>'兑换券','1'=>'立减券','2'=>'卖品券');



	public function report()
	{


		$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
		$count = D ( 'Voucher' )->getVoucehrOrderCount ('orderId', $vocherMap);
		$allPage = ceil ( $count / $this->limit);
		$curPage = $this->curPage ( $nowPage, $allPage );
		$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $pageData );
    	}
    	$this->page = $showPage;


		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName', '');
		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup); 

		$voucherTypeMap['cinemaGroupId'] = array('in', $arrayCinemaGroupId);
		$voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, cinemaGroupId, typeName, typeValue, typeClass', $voucherTypeMap);
		foreach ($voucherTypeList as $key => $value) {
			$newVoucherTypeList[$value['typeId']] = $value;
		}
		$this->voucherTypeList = $newVoucherTypeList;

		$this->batchNumList = D('Voucher')->getBatchNumList('batchNum', $vocherMap);

		$this->cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName, cinemaGroupId');
		// print_r($this->cinemaList);

		$this->orderList = D('Voucher')->getVoucherOrderList('', $vocherMap, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'addTime desc, orderId desc'); 
		
		// print_r($this->orderList);
		$this->display();
	}

	public function voucherlist()
	{

		$this->searchBatchNum = I('request.searchBatchNum');
		$this->searchStartVoucherId = I('request.searchStartVoucherId');
		$this->searchEndVoucherId = I('request.searchEndVoucherId');
		$this->searchCinemaCode = I('request.searchCinemaCode');
		$this->searchVoucherNumber = I('request.searchVoucherNumber');
		$this->searchTypeId = I('request.searchTypeId');
		$this->status = is_numeric(I('request.status')) ? I('request.status') : -1;



		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
		// print_r($arrayCinemaGroupId);
		$typeListMap['cinemaGroupId'] = array('IN', $arrayCinemaGroupId);
		$this->voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, typeName', $typeListMap); 

		$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
		
		$pageData['status'] = $this->status;
		$pageData['searchBatchNum'] = $this->searchBatchNum;
		$pageData['searchStartVoucherId'] = $this->searchStartVoucherId;
		$pageData['searchEndVoucherId'] = $this->searchEndVoucherId;
		$pageData['searchCinemaCode'] = $this->searchCinemaCode;
		$pageData['searchVoucherNumber'] = $this->searchVoucherNumber;
		$pageData['searchTypeId'] = $this->searchTypeId;

		if ($this->searchBatchNum != -1 && !empty($this->searchBatchNum)) {
			$vocherMap['batchNum'] = $this->searchBatchNum;
		}

		if ($this->searchTypeId != -1 && !empty($this->searchTypeId)) {
			$vocherMap['typeId'] = $this->searchTypeId;
		}

		if ($this->searchCinemaCode != -1 && !empty($this->searchCinemaCode)) {
			$vocherMap['cinemaCode'] = $this->searchCinemaCode;
		}

		if (!empty($this->searchStartVoucherId) && !empty($this->searchEndVoucherId)) {
			$vocherMap['voucherId'] = array(array('egt',$this->searchStartVoucherId),array('elt',$this->searchEndVoucherId), 'and') ;
		}

		if (!empty($this->searchVoucherNumber)) {
			$vocherMap['voucherNumber'] = array('like', '%' . $this->searchVoucherNumber . '%');
		}

		if ($this->status != -1) {
			$vocherMap['status'] = $this->status;
		}

		// print_r($vocherMap);

		$this->count=$count = D ( 'Voucher' )->getVoucehrCount ('voucherId', $vocherMap);
		$allPage = ceil ( $count / $this->limit);
		$curPage = $this->curPage ( $nowPage, $allPage );
		$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $pageData );
    	}
    	$this->page = $showPage;


		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName', '');
		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup); 

		$voucherTypeMap['cinemaGroupId'] = array('in', $arrayCinemaGroupId);
		$voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, cinemaGroupId, typeName, typeValue, typeClass', $voucherTypeMap);
		foreach ($voucherTypeList as $key => $value) {
			$newVoucherTypeList[$value['typeId']] = $value;
		}
		$this->voucherTypeList = $newVoucherTypeList;

		$this->batchNumList = D('Voucher')->getBatchNumList('batchNum', $vocherMap);

		$this->cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName, cinemaGroupId');
		// print_r($this->cinemaList);

		$this->voucherList = D('Voucher')->getVoucherList('', $vocherMap, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'addTime desc, voucherId desc'); 
		$this->assign('pageData',$pageData);
		$this->display();
	}
	/**
	 * 打印订单信息
	 *
	 * @param string $orderCode
	 * @param string $mobile
	 * @param string $cinemaCode
	 * @param string $status
	 * @param string $mobile
	 */
	public function orderPort(){
		$title=array("0"=>"票券ID","1"=>"票券编码","2"=>"生效时间","3"=>"到期时间","4"=>"生成时间","5"=>"归属影城","6"=>"适用影城","7"=>"票券名称","8"=>"销售状态","9"=>"使用状态");
		$this->searchBatchNum = I('request.searchBatchNum');
		$this->searchStartVoucherId = I('request.searchStartVoucherId');
		$this->searchEndVoucherId = I('request.searchEndVoucherId');
		$this->searchCinemaCode = I('request.searchCinemaCode');
		$this->searchVoucherNumber = I('request.searchVoucherNumber');
		$this->searchTypeId = I('request.searchTypeId');
		$this->status = is_numeric(I('request.status')) ? I('request.status') : -1;

		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
		// print_r($arrayCinemaGroupId);
		$typeListMap['cinemaGroupId'] = array('IN', $arrayCinemaGroupId);
		$this->voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, typeName', $typeListMap); 

		if ($this->searchBatchNum != -1 && !empty($this->searchBatchNum)) {
			$vocherMap['batchNum'] = $this->searchBatchNum;
		}

		if ($this->searchTypeId != -1 && !empty($this->searchTypeId)) {
			$vocherMap['typeId'] = $this->searchTypeId;
		}

		if ($this->searchCinemaCode != -1 && !empty($this->searchCinemaCode)) {
			$vocherMap['cinemaCode'] = $this->searchCinemaCode;
		}

		if (!empty($this->searchStartVoucherId) && !empty($this->searchEndVoucherId)) {
			$vocherMap['voucherId'] = array(array('egt',$this->searchStartVoucherId),array('elt',$this->searchEndVoucherId), 'and') ;
		}

		if (!empty($this->searchVoucherNumber)) {
			$vocherMap['voucherNumber'] = array('like', '%' . $this->searchVoucherNumber . '%');
		}

		if ($this->status != -1) {
			$vocherMap['status'] = $this->status;
		}



		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName', '');
		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup); 

		$voucherTypeMap['cinemaGroupId'] = array('in', $arrayCinemaGroupId);
		$voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, cinemaGroupId, typeName, typeValue, typeClass', $voucherTypeMap);
		foreach ($voucherTypeList as $key => $value) {
			$newVoucherTypeList[$value['typeId']] = $value;
		}
		$this->voucherTypeList = $newVoucherTypeList;

		$this->batchNumList = D('Voucher')->getBatchNumList('batchNum', $vocherMap);

		$this->cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName, cinemaGroupId');
		// print_r($this->cinemaList);

		$voucherList = D('Voucher')->getVoucherList('voucherId,typeId,voucherNumber,belongCinemaCode,cinemaCode,startTime,endTime,addTime,status', $vocherMap); 
		foreach ($voucherList as $k=>$v){
			$voucherList[$k]['startTime']=date('Y-m-d',$v['startTime']);
			$voucherList[$k]['endTime']=date('Y-m-d',$v['endTime']);
			$voucherList[$k]['addTime']=date('Y-m-d',$v['addTime']);
			$voucherList[$k]['voucherName']=$this->voucherTypeList[$v['typeId']]['typeName'];
			if($v['status']=='0'){
				$voucherList[$k]['status1']='未出售';
			}else{
				$voucherList[$k]['status1']='已出售';
			}
			if($v['status']=='0'){
				$voucherList[$k]['status2']='未生效';
			}elseif($v['status']=='1'){
				$voucherList[$k]['status2']='已生效';
			}else{
				$voucherList[$k]['status2']='已使用';
			}
			unset($voucherList[$k]['typeId']);
			unset($voucherList[$k]['belongCinemaCode']);
			unset($voucherList[$k]['cinemaCode']);
			unset($voucherList[$k]['status']);
		}
		exportexcel($voucherList,$title);
	}

	public function sellVoucher()
	{
		if (IS_AJAX) {


			$sessionName = I('request.sessionName');
			if (empty($sessionName)) {
				$voucherList = I('request.voucherList');

				$randSessionName = 'randSessionName' . rand(1000000,999999);
				if (!empty($voucherList)) {
					session($randSessionName, $voucherList);
					$this->success('票券类型添加成功！', $randSessionName);
				}else{
					$searchBatchNum = I('request.searchBatchNum');
					$searchStartVoucherId = I('request.searchStartVoucherId');
					$searchEndVoucherId = I('request.searchEndVoucherId');
					$searchCinemaCode = I('request.searchCinemaCode');
					$searchVoucherNumber = I('request.searchVoucherNumber');

					if ($searchBatchNum != -1 && !empty($searchBatchNum)) {
						$vocherMap['batchNum'] = $searchBatchNum;
					}

					if ($searchCinemaCode != -1 && !empty($earchCinemaCode)) {
						$vocherMap['cinemaCode'] = $searchCinemaCode;
					}

					if (!empty($searchStartVoucherId) && !empty($searchEndVoucherId)) {
						$vocherMap['voucherId'] = array(array('egt',$searchStartVoucherId),array('elt',$searchEndVoucherId), 'and') ;
					}

					if (!empty($searchVoucherNumber)) {
						$vocherMap['voucherNumber'] = array('like', '%' . $searchVoucherNumber . '%');
					}

					$vocherMap['status'] = 0;
					$voucherList = D('Voucher')->getVoucherList('voucherId', $vocherMap); 
					foreach ($voucherList as $key => $value) {
						$newVoucherList[] = $value['voucherId'];
					}
					session($randSessionName, $newVoucherList);
					$this->success('票券出售订单生成成功！', $randSessionName);
				}
			}else{
				$voucherList = session($sessionName);


				$data = I('request.data');
				if (empty($data['startTime'])) {
					$this->error('请选择生效时间！');
				}

				if (empty($data['endTime'])) {
					$this->error('请选择到期时间！');
				}

				if (empty($data['remark'])) {
					$this->error('售券备注不能为空！');
				}

				foreach ($data as $key => $value) {
					if ($value == -1) {
						unset($data[$key]);
					}
				}


				$voucherOrderData['sellNum'] = count($voucherList);
				$voucherOrderData['remark'] = $data['remark'];
				$voucherOrderData['addTime'] = time();
				$voucherOrderData['adminId'] = CPUID;
				$sellId = D('Voucher')->addVoucherOrder($voucherOrderData);

				if (!empty($data['typeId'])) {
					$setData['status'] = $data['typeId'];
				}
				$setData['status'] = 1;
				$setData['startTime'] = strtotime($data['startTime']);
				$setData['endTime'] = strtotime($data['endTime']);
				$setData['sellOrderId'] = $sellId;
				$setData['cinemaCode'] = implode(',', $data['cinemaCode']);
				
				

				$setMap['voucherId'] = array('IN', $voucherList);

				if (D('Voucher')->setVoucherList($setData, $setMap)) {
					$this->success('票券出售成功！');
				}else{
					$this->error('票券出售失败！');
				}

				print_r($voucherList);
			}
		}else{

			$this->cinemaGroup = D('Cinema')->getGroup('id, groupName', '');
			$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup); 

			$voucherTypeMap['cinemaGroupId'] = array('in', $arrayCinemaGroupId);
			$voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, cinemaGroupId, typeName, typeValue, typeClass', $voucherTypeMap);
			foreach ($voucherTypeList as $key => $value) {
				$newVoucherTypeList[$value['typeId']] = $value;
			}
			$this->voucherTypeList = $newVoucherTypeList;
			$this->sessionName = I('request.sessionName');
			$this->voucherList = session($this->sessionName);
			$this->display('sellFrom');
		}
	}


	public function vouchertype()
	{
		$this->cinemaGroupId=$cinemaGroupId=I('cinemaGroupId');
		$this->typeName=$typeName=I('typeName');
		if(!empty($typeName)){
			$map['typeName']=array('like','%'.$typeName.'%');
		}
		if(!empty($cinemaGroupId)&&$cinemaGroupId!='-1'){
			$map['cinemaGroupId']=$cinemaGroupId;
		}
		$this->voucherTypeList = D('Voucher')->getVoucherTypeList('',$map);
		$this->cinemaList = D('Cinema')->getCinemaList();

		$this->cinemaGroup = D('Cinema')->getGroup();
		$this->assign('typeConfig',$this->typeConfig);
		$this->display();
	}


	/**
    * 添加票券类型
    * @param null;
    * @return null
    * @author 宇
    */
	public function addVoucherType()
	{
		if (IS_AJAX) {
			$data = I('request.data');
			if (empty($data['typeName'])) {
				$this->error('票券类型名称不能为空！');
			}
			if (empty($data['typeValue'])) {
				$this->error('请输入票券类型的价值');
			}
			if (!isset($data['typeClass']) || empty($data['typeClass']) && $data['typeClass'] != 0) {
				$this->error('请选择票券所属类型！');
			}
			//开始添加数据
			if (D('Voucher')->addVoucherType($data)) {
				$this->success('票券类型添加成功！');
			}else{
				$this->error('票券类型添加失败！');
			}
			
		}else{
			$this->cinemaGroup = D('Cinema')->getGroup( 'id, groupName', '');
			$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup); 
			$sortOrderMap['cinemaGroupId'] = array('IN', $arrayCinemaGroupId);
			$this->sortOrder = intval(D('Voucher')->getSortOrder($sortOrderMap)) + 1;
			$this->display('type_form');
		}
	}


	/**
    * 添加票券类型
    * @param null;
    * @return null
    * @author 宇
    */
	public function editVoucherType($typeId)
	{
		if (IS_AJAX) {
			$data = I('request.data');
			if (empty($data['typeName'])) {
				$this->error('票券类型名称不能为空！');
			}
			if (empty($data['typeValue'])) {
				$this->error('请输入票券类型的价值');
			}
			if (!isset($data['typeClass']) || empty($data['typeClass']) && $data['typeClass'] != 0) {
				$this->error('请选择票券所属类型！');
			}
			//开始添加数据
			if (D('Voucher')->editVoucherType($data, array('typeId' => $typeId))) {
				$this->success('票券类型修改成功！');
			}else{
				$this->error('票券类型修改失败！');
			}
			
		}else{

			$this->voucherTypeInfo = D('Voucher')->getVoucherTypeByTypeId($typeId);
			$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
			$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup); 
			$this->display('type_form');
		}
	}


	
	/**
    * 删除票券类型
    * @param null;
    * @return null
    * @author 宇
    */
	public function delVoucherType($typeId)
	{
		if (IS_AJAX) {
			if (D('Voucher')->delVoucherTypeByTypeId($typeId)) {
				$this->success('票券类型删除成功！');
			}else{
				$this->error('票券类型删除失败！');
			}
		}
	}
	
	/**
	 * 删除方案
	 * @param null;
	 * @return null
	 * @author 宇
	 */
	public function delVoucherSetting($id)
	{
		if (IS_AJAX) {
			if (D('voucherSetting')->delete($id)) {
				$this->success('方案删除成功！');
			}else{
				$this->error('方案删除失败！');
			}
		}
	}

	/**
    * 生成票券
    * @param null;
    * @return null
    * @author 宇
    */
	public function addVoucher($value='')
	{
		if (IS_AJAX) {
			$data = I('request.data');

			if (empty($data['cinemaGroupId'])) {
				$this->error('请选择所属影院分组！');
			}

			if (empty($data['voucherType'])) {
				$this->error('请选择票券类型');
			}

			if (empty($data['addType'])) {
				$this->error('请选择票券生成方式');
			}

			if (empty($data['startNum']) && $data['addType'] == 1) {
				$this->error('请输入票券起始编号');
			}

			if (empty($data['voucherBit']) && $data['addType'] == 2) {
				$this->error('请输入票券生成位数');
			}

			if (empty($data['voucherSum'])) {
				$this->error('请输入票券生成数量');
			}

			if (empty($data['belongCinemaCode'])) {
				$this->error('请选择归属影城');
			}


			$autoData['batchNum'] = date('YmdHis') . '-' . rand(100,999);
			$addtime = time();
			$flag = true;
			$i = 1;
			$voucher = 1;

			
			$autoData['cinemaGroupId'] = $data['cinemaGroupId'];
			do {
				$autoData['typeId'] = $data['voucherType'];

				if ($data['addType'] == 1) {//顺序生成
					$autoData['voucherNumber'] = $data['voucherPre'] . ($data['startNum']+$voucher);
				}elseif ($data['addType'] == 2) {//随机生成
					$autoData['voucherNumber'] = $data['voucherPre'] . random($data['voucherBit'], 1);
				}
				
				$autoData['belongCinemaCode'] = $data['belongCinemaCode'];
				$autoData['addTime'] = $addtime;
				$voucher++;
				if(D('Voucher')->autoAddVoucher($autoData)){
					$i++;
				}
				if ($i > $data['voucherSum'] || $voucher >=100) {
					$flag = false;
				}
			} while ($flag);
			
			$this->success('票券生成成功！');
		}else{
			$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
			$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
			// print_r($arrayCinemaGroupId);
			$typeListMap['cinemaGroupId'] = array('IN', $arrayCinemaGroupId);
			$this->voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, typeName', $typeListMap); 
			// print_r($this->voucherTypeList);
			$this->display('voucher_form');
		}
		
	}

	/**
    * 获取票券名称列表
    * @param null;
    * @return null
    * @author 宇
    */
	public function getVoucherName($cinemaGroupId)
	{
		if (IS_AJAX) {
			$cinemaGroupId = I('request.cinemaGroupId');
			
			$cinemaList = D('Cinema')->getCinemaListByCinemaGroupId('cinemaCode, cinemaName', $cinemaGroupId);
			
			$typeListMap['cinemaGroupId'] = $cinemaGroupId;
			$voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, typeName', $typeListMap); 
			

			if (empty($cinemaList)) {
				$this->error('该分组下无影院信息');
			}

			if (empty($voucherTypeList)) {
				$this->error('该分组下无票券类型');
			}

			$data['cinemaList'] = $cinemaList;
			$data['voucherTypeList'] = $voucherTypeList;
			$this->success('', $data);
			// print_r($voucherTypeList);

			// if (D('Voucher')->delVoucherTypeByTypeId($typeId)) {
			// 	$this->success('票券类型删除成功！');
			// }else{
			// 	$this->error('票券类型删除失败！');
			// }
		}
	}

	/**
    * 设置方案列表
    * @param null;
    * @return null
    * @author 宇
    */
	public function settingList()
    {
    	$configName=I('configName');
    	if(!empty($configName)){
    		$typeListMap['configName']=array('like','%'.$configName.'%');
    	}
    	$this->configName=$configName;
        $this->voucherSettingList = D('Voucher')->getVoucherSettingList('', $typeListMap); 
        $this->display('setting_list');
    }


    /**
    * 添加设置方案
    * @param null;
    * @return null
    * @author 宇
    */
    public function addSetting()
    {

    	if (IS_AJAX) {
    		$configName = I('request.configName');
    		$configValue = I('request.configValue');
    		$data['configName'] = $configName;
    		$data['configValue'] = serialize($configValue);

    		if (empty($configName)) {
				$this->error('请输入方案名称！');
			}

			if (D('Voucher')->addVoucherSetting($data)) {
				$this->success('票券设置方案添加成功！');
			}else{
				$this->error('票券设置方案添加失败！');
			}

    	}else{
    		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
			$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
			// print_r($arrayCinemaGroupId);
			$typeListMap['typeClass'] = array('neq', 2);
			$typeListMap['cinemaGroupId'] = array('IN', $arrayCinemaGroupId);
			$this->voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, typeName, typeClass', $typeListMap); 
	    	

	    	$weekList[0] = '星期日';
	    	$weekList[1] = '星期一';
	    	$weekList[2] = '星期二';
	    	$weekList[3] = '星期三';
	    	$weekList[4] = '星期四';
	    	$weekList[5] = '星期五';
	    	$weekList[6] = '星期六';
	    	$this->assign('weekList',$weekList);
	    	$this->display('setting_from');
    	}

    }
	
	/**
    * 修改设置方案
    * @param null;
    * @return null
    * @author 宇
    */
    public function editSetting($id)
    {
    	if (IS_AJAX) {
    		$configName = I('request.configName');
    		$configValue = I('request.configValue');
    		$data['configName'] = $configName;
    		$data['configValue'] = serialize($configValue);

    		$map['id'] = intval(I('request.id'));
    		if (empty($configName)) {
				$this->error('请输入方案名称！');
			}

			if (D('Voucher')->editVoucherSetting($data, $map)) {
				$this->success('票券设置方案修改成功！');
			}else{
				$this->error('票券设置方案修改失败！');
			}
    	}else{

    		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
			$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
			// print_r($arrayCinemaGroupId);
			$typeListMap['cinemaGroupId'] = array('IN', $arrayCinemaGroupId);
			$typeListMap['typeClass'] = array('neq', 2);
			$this->voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, typeName, typeClass', $typeListMap); 

    		$setingInfo = D('Voucher')->getSetingInfoById($id);
    		$setingInfo['configValue'] = unserialize($setingInfo['configValue']);
    		$weekList[0] = '星期日';
	    	$weekList[1] = '星期一';
	    	$weekList[2] = '星期二';
	    	$weekList[3] = '星期三';
	    	$weekList[4] = '星期四';
	    	$weekList[5] = '星期五';
	    	$weekList[6] = '星期六';
	    	$this->weekList = $weekList;
    		$this->setingInfo = $setingInfo;
    		$this->display('setting_from');
    	}

    }
	

	public function setlist()
	{


			$pageData = I('request.serach');
			if (empty($pageData)) {
				$pageData['setingId'] = I('request.setingId');
				$pageData['cinemaCode'] = I('request.cinemaCode');
				$pageData['filmNo'] = I('request.filmNo');
				$pageData['startData'] = I('request.startData');
				$pageData['endData'] = I('request.endData');	
			}

			foreach ($pageData as $key => $value) {
				if ($value == -1) {
					unset($pageData[$key]);
				}
			}

			$setingId   = $pageData['setingId'];
			$cinemaCode = $pageData['cinemaCode']; 
			$filmNo     = $pageData['filmNo'];
			$startData  = $pageData['startData']; 
			$endData    = $pageData['endData'];
			$this->pageData = $pageData;

			// print_r($this->pageData);

	    	if(!empty($cinemaCode)){
	    		$map['cinemaCode']=$cinemaCode;
	    	}

	    	if(!empty($setingId) || is_numeric($setingId)){
	    		$map['setingId'] = $setingId;
	    	}
	    	if(!empty($filmNo)){
	    		$map['filmNo']=$filmNo;
	    	}
	    	$map['startTime']= array('egt',strtotime(date('Ymd',time())));



	    	if(!empty($startData)&&!empty($endData)){
	    		$map['startTime']= array(array('egt',strtotime($startData)),array('elt',strtotime($endData)+24*60*60),$map['startTime']);
	    	}elseif(!empty($startTime)){
	    		$map['startTime']= array(array('egt',strtotime($startTime)),$map['startTime']);
	    	}elseif(!empty($endData)){
	    		$map['startTime']= array(array('elt',strtotime($endData)+24*60*60),$map['startTime']);
	    	} 	
	    	$filmList=D('Plan')->getPlanFilms();
	    	$cinemaList=D('cinema')->getCinemaList();
	    	$this->assign('cinemaList',$cinemaList);
	    	$this->assign('filmList',$filmList);
	    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
	    	$upPlanDate =date('"Y-m-d"');
	    	for ($i=1; $i < 5 ; $i++) { 
	    		$upPlanDate .= date(',"Y-m-d"', time() + 3600 * 24 * $i);
	    	}


	    	$count = D ( 'Plan' )->count ($map);
	    	$allPage = ceil ( $count / $this->limit);
	    	$curPage = $this->curPage ( $nowPage, $allPage );
	    	$startLimit = ($curPage - 1) * $this->limit;
	    	if ($count > $this->limit) {
	    		$showPage = $this->getPageList ( $count, $this->limit, $pageData );
	    	}
	    	$this->assign('page',$showPage);
	    	$planList = D('Plan')->getPlanList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'startTime asc');

	    	// print_r($planList);

	        $voucherSettingList = D('Voucher')->getVoucherSettingList('id, configName', $typeListMap); 
	        $newVoucherSettingList[0] = array('configName'=>'未设置');
	        foreach ($voucherSettingList as $key => $value) {
	        	$newVoucherSettingList[$value['id']] = $value;
	        }
	        $this->voucherSettingList = $newVoucherSettingList;

	        $this->cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaGroupId, cinemaName');
	        $this->filmList=D('Plan')->getPlanFilms();

	        // print_r($this->filmList);
	    	$this->assign('planList',$planList);
	    	$this->assign('upPlanDate',$upPlanDate);
	    	$this->display();		
	}


	public function updateSetingId()
	{

		$featureAppNoList = I('request.featureAppNoList');
		$newSetingId = I('request.newSetingId');
		
		if (empty($featureAppNoList)) {
			$pageData = I('request.serach');
			$newSetingId = $pageData['newSetingId'];
			foreach ($pageData as $key => $value) {
				if ($value == -1) {
					unset($pageData[$key]);
				}
			}

			if(!empty($pageData['cinemaCode'])){
	    		$map['cinemaCode']=$pageData['cinemaCode'];
	    	}

	    	if(!empty($pageData['setingId']) || is_numeric($pageData['setingId'])){
	    		$map['setingId'] = $pageData['setingId'];
	    	}
	    	if(!empty($pageData['filmNo'])){
	    		$map['filmNo']=$pageData['filmNo'];
	    	}
	    }else{
	    	if ($newSetingId == -1 || !is_numeric($newSetingId)) {
				$this->error('请选择票券方案！');
			}
	    	$map['featureAppNo']= array('in', $featureAppNoList);
	    }

	    $map['startTime']= array('egt',strtotime(date('Ymd',time())));
    	$data['setingId'] = $newSetingId;



    	if (D('Plan')->setCinemaPlan($data, $map)) {
			$this->success('票券设置方案设置成功！');
		}else{
			$this->error('票券设置方案设置失败！');
		}
	}
}