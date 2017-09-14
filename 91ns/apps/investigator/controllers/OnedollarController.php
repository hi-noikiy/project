<?php

namespace Micro\Controllers;
use Micro\Frameworks\Logic\Investigator\InvBase;

class OnedollarController extends ControllerBase {

    public function initialize() {
    	/*$nologinarr=array('/onedollar/akeysend');
    	if(!in_array($_GET['_url'], $nologinarr)){
    		parent::initialize();
    	}*/
    	parent::initialize();
    }

    public function indexAction() {
        $this->redirect('onedollar/bettingRes');
    }

    public function bettingResAction() {
        
    }

    public function allocateWineAction() {
        $wineConfigs = array(
            '100'=>'100元酒水券',
            '300'=>'300元酒水券',
            '500'=>'500元酒水券',
            '1000'=>'1000元酒水券',
            '2000'=>'2000元酒水券',
            '3000'=>'3000元酒水券'
        );
        $this->view->wineList = $wineConfigs;
    }
    
    /**
     * 一键发放商品
     * 
     * @author 王涛
     */
    public function akeySendAction() {
    	$wineConfigs = array('100' , '300' , '500' , '1000' , '2000' , '3000');
    	$uid = $_POST['uid'];
    	$isValid = $this->validator->validate(array('uid' => $uid));
    	if (!$isValid) {
    		return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
    	}
    	$signAnchor = \Micro\Models\SignAnchor::findFirst('uid = '. $uid . ' and (status = 1 or  status = 2)'); //判断用户是否为正常主播
    	if(!empty($signAnchor)){
    		$goodsConfigs = \Micro\Models\GoodsConfigs::findFirst('type = '. $uid . ' and isShow = 0 '); //判断用户是否有分配物品
    		if(!empty($goodsConfigs)){
    			$result['code'] = 1;
    			$result['info'] = '该主播已经分配了商品，无法一键发放。';
    		}else{ //分配物品
    			$flag = 0;
    			$bprlsarr=array();
    			foreach ($wineConfigs as $key => $value){
    				$goodsConfigss[$value] = \Micro\Models\GoodsConfigs::findFirst('type=0 and isShow=0 and price=' . ($value * 1.06));
    				if(empty($goodsConfigss[$value])){ //其中一种不成功全部回滚
    					$wineConfigsBacks = array_slice($wineConfigs, 0, $key);
    					foreach ($wineConfigsBacks as $v){  //取消酒水分配
    						$goodsConfigss[$v]->type = 0;
    						$goodsConfigss[$v]->save();
    					}
    					foreach ($bprlsarr as $v){  //删除日志记录
    						\Micro\Models\BetPointsResultLog::findFirst($v)->delete();
    					}
    					$result['code'] = 1;
    					$result['info'] = $value . '元酒水券无库存，无法发放！';
    					$flag = 1;
    					break;
    				}else{
    					$goodsConfigss[$value]->type = $uid;
    					$goodsConfigss[$value]->save();
    					$bprl = new \Micro\Models\BetPointsResultLog();
    					$bprl->save(array(
    							"uid" => 0,
    							"times" => 1,
    							"type" => $goodsConfigss[$value]->id,
    							'createTime'=>time(),
    							'remark'=>'',
    							'status'=>0,
    							'openTime'=>0,
    					));
    					$bprlsarr[]=$bprl->id;
    				}
    			}
    			if(empty($flag)){
    				$result['code'] = 0;
    				$result['info'] = '发放成功';
    			}
    		}
    	}else{
    		$result['code'] = 1;
    		$result['info'] = '该用户不是正常主播，无法分配商品';
    	}
    	return $this->status->newAjaxReturn($result);
    }
    
    /**
     * 导出夺宝记录
     * 
     * @author 王涛
     */
    public function getBetResultLogsExcelAction(){
    	$list = array();
    	try{
            $sql = 'select gc.name,gc.id,gc.type,bpr.times,ui.uid,ui.telephone,FROM_UNIXTIME(bpr.openTime,"%Y-%m-%d %H:%i:%s") as time'
                . ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bpr.uid '
                . ' left join \Micro\Models\GoodsConfigs as gc on bpr.type = gc.id '
                . ' where bpr.status = 1 order by bpr.openTime desc';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $resarr=$res->toArray();
    		$title=array("0"=>"商品名称","1"=>"商品ID","2"=>"归属ID","3"=>"期数","4"=>"夺宝者ID","5"=>"手机号","6"=>"夺宝时间");
    		$this->exportexcel($resarr,$title);
    	} catch (\Exception $e) {
    		return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    	}
    }

    public function movieAction(){

    }
}