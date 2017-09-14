<?php
/**
 * 营销产品
 */

namespace Admin\Controller;
use Think\Controller;
class ShakeController extends AdminController {

	/**
     *票券列表
     */
    public function prizelist(){
    	$nprizes=D('prize')->getList();
    	foreach ($nprizes as $k=>$v){
    		$prizes[$v['id']]=$v;
    	}
    	foreach ($prizes as $k=>$v){
    		$cinema=M('cinema')->find($v['cinemaCode']);
    		$prizes[$k]['cinemaName']=$cinema['cinemaName'];
    		if($v['type']!='1'){
    			$prizes[$k]['typeStr']='券';
    		}else{
    			$prizes[$k]['typeStr']='物品';
    		}
    		if($v['remainNum']==0&&$v['odds']>0){
    			$prizes[$k]['odds']=0;
    			$tprizes=D('prize')->getList('id',array('remainNum'=>array('neq',0),'odds'=>array('neq',0)),0,1,'priority desc');
    			$prizes[$tprizes[0]['id']]['odds']+=$v['odds'];
    			unset($tprizes);
    		}
    		unset($cinema);
    	}
    	$this->assign('prizes',$prizes);
        $this->display();
    }


    public function reSize()
    {
        $nprizes=D('prize')->getList();

        foreach ($nprizes as $key => $value) {
            $value['remainNum'] = $value['dateNum'];
            $value['allRemainNum'] = 0;
            M('prizeZr')->save($value);
        }

        // print_r($nprizes);
    }

    /**
     * 编辑奖品
     */
    public function prizefrom(){
    	if(IS_POST){
    		$id=I('id');
    		$data=I('data');
    		if(empty($data['name'])){
    			$this->msg('名称不能为空');
    		}
    		if($data['type']!='1'){
    			$data['type']=0;
    			if(empty($data['voucherType'])){
    				$this->msg('请选择券类型');
    			}
    			if(empty($data['startTime'])){
    				$this->msg('请填写开始日期');
    			}
    			if(empty($data['endTime'])){
    				$this->msg('请填写截至日期');
    			}
    			if($data['endTime']<=$data['startTime']){
    				$this->msg('截至日期不能小于开始日期');
    			}
    			$data['startTime']=strtotime($data['startTime']);
    			$data['endTime']=strtotime($data['endTime']);
    			if(empty($data['cinemaCode'])){
    				$this->msg('请选择归属影院');
    			}
    		}else{
                $data['startTime']=strtotime($data['startTime']);
                $data['endTime']=strtotime($data['endTime']);
    			unset($data['voucherType']);
    		}
    		$partner1='/^(-1)|([1-9]\d*)$/';
    		$partner2='/^0|([1-9]\d*)$/';
    		if(!preg_match($partner1,$data['allNum'])){
    			$this->msg('请填写正确数量!');
    		}
    		if(!preg_match($partner2,$data['odds'])){
    			$this->msg('请填写正确中奖概率!');
    		}
    		if(!empty($id)){
    			$map['id']=array('neq',$id);
    		}
            $map['cinemaCode'] = $data['cinemaCode'];
    		$prize=D('prize')->getPrize('sum(odds) as aodds',$map);
    		if($prize['aodds']+$data['odds']>$this->dodds){
    			$this->msg('目前概率已经存在'.$prize['aodds'].',请保证总概率不高于'.$this->dodds);
    		}
    		if(!preg_match($partner2,$data['priority'])){
    			$this->msg('请填写正确优先级!');
    		}
    		if(empty($id)){
    			$data['remainNum']=$data['dateNum'];
    			if(M('prizeZr')->add($data)){
    				$this->msg('添加成功',1,2,'reload');
    			}
    		}else{
    			$data['id']=$id;
    			if(M('prizeZr')->save($data)!==false){
    				$this->msg('修改成功',1,2,'reload');
    			}
    		}
    	}else{
    		$id=I('id');
    		if(!empty($id)){
    			$this->data = M('prizeZr')->find($id);
    		}
    		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
    		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
    		$typeListMap['cinemaGroupId'] = array('IN', $arrayCinemaGroupId);
    		$this->cinemaList=D('cinema')->getCinemaList();
    		$this->voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, typeName', $typeListMap);
    		$this->display();
    	}
    }
   
    /**
     * 删除奖品
     */
    public function delprize(){
    	$id=I('id');
    	if(M('prizeZr')->delete($id)){
    		$this->success('删除成功');
    	}else{
    		$this->error('删除失败');
    	}
    }


    public function winners()
    {

        $limit=$this->limit;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $count = D ( 'prizeLogZr' )->count ();

        echo $count;

        $allPage = ceil ( $count / $limit );
        $curPage = $this->curPage ( $nowPage, $allPage );

        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit );
        }
        $this->assign('page',$showPage);

        $this->winnerList = D('prizeLogZr')->limit(($nowPage - 1) * $this->limit . ',' . $this->limit)->select();
        // print_r($winnerList);
        $this->display();
    }
	
}