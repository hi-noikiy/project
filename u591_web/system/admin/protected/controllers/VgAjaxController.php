<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 服饰ajax
* ==============================================
* @date: 2016-10-23
* @author: luoxue
* @version:
*/
class VgAjaxController extends Controller{
	public function init(){}
	
	
	public function actionShopitemLevel(){
		if(!isset($_POST['shopitemId']) || !isset($_POST['level']) || !isset($_POST['menuid']))
			exit(json_encode(array('status'=>1,'msg'=>'params error!')));	
		$shopitemId = intval($_POST['shopitemId']);
		$level = intval($_POST['level']);
		$menuid = intval($_POST['menuid']);
		$model = new VgShopitemLevel();
		$shopItemModel = VgShopitem::model();
		$rs = $model->find(array('condition'=>'shopitem_id=:shopitemId', 'params'=>array(':shopitemId'=>$shopitemId)));
		if(empty($rs)){
			$shopitemPriceModel = VgShopitemPrice::model();
			
			$result = $shopitemPriceModel->find(array(
					'condition'=>'menuid=:menuid and level=:level', 
					'params'=>array(':menuid'=>$menuid, ':level'=>$level)
				));
			//查询配置表
			if(empty($result))
				exit(json_encode(array('status'=>1,'msg'=>'percent or price parmas error!')));
			
			$percent = $result->percent; //百分比
			//价格区间
			$price1 = $result->price1;
			$price2 = $result->price2;
			
		
			$total = $this->getShopitemTotal($menuid, $shopItemModel);
			
			$existingTotal = $this->getShopitemExistingTotal($menuid, $level, $model);
		
			if(round(($existingTotal)/$total, 4)*100  >=$percent)
				exit(json_encode(array('status'=>1,'msg'=>"超出$percent%")));
			
			$price = mt_rand($price1, $price2);
			$addtime = time();
			//save data
			$model->menuid = $menuid;
			$model->shopitem_id = $shopitemId;
			$model->level = $level;
			$model->price = $price;
			$model->addtime = $addtime;
			//$sql = "insert into {{shopitem_level}}(menuid, shopitem_id, level, price, addtime) values('$menuid', '$shopitemId', '$level', '$price', '$addtime')";
			if($model->save())
				exit(json_encode(array('status'=>0,'msg'=>"", 'data'=>array('price'=>$price, 'total'=>$total, 'setTotal'=>$existingTotal+1, 'percent'=>round(($existingTotal+1)/$total, 4)*100))));
			else
				exit(json_encode(array('status'=>1,'msg'=>"insert sql error!")));
		} else {
			$count = $model->deleteAll(array(
					'condition'=>'shopitem_id=:shopitemId and level=:level', 
					'params'=>array(':shopitemId'=>$shopitemId, ':level'=>$level)
				));
			if($count > 0){
				$this->updateShopitem($shopitemId, $shopItemModel);
				exit(json_encode(array('status'=>0,'msg'=>"", 'data'=>array('price'=>''))));
			}
			exit(json_encode(array('status'=>2,'msg'=>"移除失败！")));
		}
	}
	protected function updateShopitem($id, $model){
		$model->updateByPk($id, array('price'=>0, 'snapicon'=>0));
	}
	protected function getShopitemTotal($menuId, $model){
		//限制50件，如果大于50取50,
		$sql = "select count(id) as count from {{vg_shopitem}} where (sort=$menuId or sort2=$menuId) and snapicon=0";
		$count = $model->countBySql($sql);
			
		return $count > 50 ? 50 : $count;
	}
	
	protected function getShopitemExistingTotal($menuId, $level, $model){
		$sql = "select count(id) as count from {{vg_shopitem_level}} where menuid=$menuId and level='$level' and snapicon=0";
		$count = $model->countBySql($sql);
		return $count;
	}
	/*
	 * 修改价格
	 */
	public function actionSetShopitemLevelPrice(){
		if(!isset($_POST['levelId']) || !isset($_POST['price']))
			exit(json_encode(array('status'=>1,'msg'=>'params error!')));
		$levelId = intval($_POST['levelId']);
		$price = intval($_POST['price']);
		$shopitemLevelModel = VgShopitemLevel::model();
		$rs = $shopitemLevelModel->updateAll(array('price'=>$price), 'shopitem_id=:shopitemId', array(':shopitemId'=>$levelId));
		
		if($rs)
			exit(json_encode(array('status'=>0,'msg'=>"修改成功！")));
		exit(json_encode(array('status'=>2,'msg'=>"修改失败！")));
	}
	/*
	 * 同步配置
	 */
	public function actionShopitemSynchronize(){
		if(!isset($_POST['menuid']))
			exit(json_encode(array('status'=>1,'msg'=>'params error!')));
		$menuid = intval($_POST['menuid']);
		
		$shopitemLevelModel = VgShopitemLevel::model();
		$shopitemModel = VgShopitem::model();
		$levelInfo = $shopitemLevelModel->findAll(array(
				'condition'=>'menuid=:menuId and snapicon=:snapicon', 
				'params'=>array(':menuId'=>$menuid, ':snapicon'=>0)
			));	
		if(!empty($levelInfo)){
			foreach ($levelInfo as $v){
				$id = $v->shopitem_id;
				$price = $v->price;
				$count = $shopitemModel->updateByPk($id, array('price'=>$price, 'snapicon'=>1, 'emoney'=>0));
				if($count > 0){
					$this->updateShopitemLevel($id, $shopitemLevelModel);
				}
				
			}
			exit(json_encode(array('status'=>0,'msg'=>'设置 成功')));
		}
		exit(json_encode(array('status'=>1,'msg'=>'定价数据错误！')));
	}
	protected function updateShopitemLevel($shopitemId, $model){
		$model->updateAll(array('snapicon'=>1), 'shopitem_id=:shopitemId', array(':shopitemId'=>$shopitemId));
	}
}