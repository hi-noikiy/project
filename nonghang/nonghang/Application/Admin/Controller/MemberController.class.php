<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class MemberController extends AdminController {
    
    public function memberList(){

    	$data['type']=$type=I('type');
    	if(empty($data['type'])){
    		$data['type']=0;
    	}
    	$data['loginNum']=$loginNum=I('loginNum');
    	$this->assign('data',$data);
    	if($data['type']=='0'){
    		if(!empty($loginNum)){
    			$map['cardNum']=array('like','%'.$loginNum.'%');
    		}else{
    			$map['cardNum']=array('neq','');
    		}
    	}else{
    		if(!empty($loginNum)){
    			$map['mobile']=array('like','%'.$loginNum.'%');
    		}else{
    			$map['mobile']=array('neq','');
    		}
    	}
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));

    	$count = D('Member')->memberCount ('id', $map);
    	$allPage = ceil($count / $this->limit);
    	$curPage = $this->curPage ($nowPage, $allPage);
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList($count, $this->limit, $data);
    	}
    	$this->assign('page',$showPage);
    	$memberList = D('Member')->getMemberList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'bindTime asc');

    	$this->assign('memberList',$memberList);


    	$cinemaList = D('Cinema')->getCinemaList();
    	$this->assign('cinemaList',$cinemaList);

        $this->display();

    } 
    /**
     * 系统消费记录
     */
    function record(){
    	$cardId=I('cardId');
    	$member=D('member')->where('cardNum='.$cardId)->find();
    	$array=array(
    			'cinemaCode'	=>$member['businessCode'],
    			'loginNum'=>$cardId,
    			'startDate'=>date('Y-m-d',time()-24*60*60*30),
    			'endDate'=>date('Y-m-d'),
    			'pageSize'=>100,
    			'pageNum'=>1,
    			'passWord'=>decty($member['pword']),
    	);
    	$tinfo=D('ZMUser')->queryMemberFlowInfo($array);
    	if($tinfo['ResultCode']=='0'){
    		if(!empty($tinfo['TransFlowVOs'])){
    			if(!$tinfo['TransFlowVOs']['TransFlowVO'][0]){
    				$data=$tinfo['TransFlowVOs'];
    			}else{
    				$data=$tinfo['TransFlowVOs']['TransFlowVO'];
    			}
    		}
    	}
    	$this->assign('cardId',$cardId);
    	$this->assign('data',$data);
    	$this->display();
    }

}