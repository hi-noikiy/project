<?php
class VgShopitemController extends Controller{
	
	public function _condition(&$condition){
	
		$condition=array();
		if(isset($_POST['name']) && !empty($_POST['name']))
			$condition[]="name like '{$_POST['name']}%'";
	
		$menuInfo = VgMenu::model()->getInfo();
		$brandInfo = VgBrand::model()->getInfo();
		
		$condition['param'] = array('menuInfo'=>$menuInfo, 'brandInfo'=>$brandInfo);
	}
	
	
	public function actionSetprice(){
		$shopitem = new VgShopitem;
		$shopitemPrice = new VgShopitemPrice;
		$shopitemLevel = VgShopitemLevel::model();
		
		$menuIdArr = $shopitemPrice->findAll(array('select'=>array('menuid','name'), 'group'=>'menuid'));
		
		
		$snapicon = isset($_POST['snapicon']) ? intval($_POST['snapicon']) : 0;
		$sort = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
		
		$priceInfo = $shopitemPrice->findAll(array('condition'=>'menuid=:menuid', 'params'=>array(':menuid'=>$sort)));
		
		
		
		$criteria = new CDbCriteria;
		$criteria->select = 'id, name, icon';
		$criteria->order = 'id asc';
		$criteria->condition = 'snapicon=:snapicon and (sort=:sort or sort2=:sort2)';
		$criteria->params = array(':snapicon'=>$snapicon, ':sort'=>$sort, ':sort2'=>$sort);
		
		$criteria->limit = '50';
		$info = $shopitem->findAll($criteria);
		$configured = 0;
		if($info){
			$levelInfo = $shopitemLevel->findAll(array(
					'condition'=>'menuid=:menuId and snapicon=:snapicon', 
					'params'=>array(':menuId'=>$sort, ':snapicon'=>0)
				));
			foreach ($info as $k => $v){
				$info[$k]['selected'] = '';
				$info[$k]['price'] = '';
				foreach ($levelInfo as $vv){
					if($v->id == $vv->shopitem_id){
						$info[$k]['checked'] = 'checked';
						$info[$k]['price'] = $vv['price'];
					}
				}
			}	
			$configured = count($levelInfo);
		}
		
		
		
		$this->renderPartial('setprice', array(
				'info'=>$info, 
				'menuIdArr'=>$menuIdArr, 
				'priceInfo'=>$priceInfo,
				'sort' => $sort,
				'snapicon' => $snapicon,
				'configured' => $configured,
			));
	}
}