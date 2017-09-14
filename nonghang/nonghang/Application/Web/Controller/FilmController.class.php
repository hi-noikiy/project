<?php
namespace Web\Controller;
use Think\Controller;
class FilmController extends InitController {

	/**
	 * 影片具体说明页
	 */
	public function filmdetail(){
		$mode3=D('Film');
		$data=array();
		$data['id']=I('id');
		$list3=$mode3->film_getlist($data,4);
		$imgsarray=explode(";",$list3['imgs']);
		if(is_array($imgsarray)){
			$imaslist=array();
			foreach($imgsarray as $v){
				if($v){
					if(file_exists('./Uploads/'.$v)){
						$imaslist[]=C('IMG_URL').'Uploads/'.$v;
					}
				}
			}
			$list3['imaslist']=$imaslist;
		}
		$this->assign($list3);
		$this->assign('pageId', 'page-filmdetail');
		$this->display('filmdetail');
    }

    /**
     * 影片详细
     */
    public function filminfo(){
    	$model=D('plan');
    	$mode2=D('Cinema');
    	$mode3=D('Film');
    	$data=array();
    	$cinemaCode=I('request.cinemaCode');
    	if(empty($cinemaCode)){
    		$cinemaCode=session('cinemaCode');
    	}else{
    		session('cinemaCode',$cinemaCode);
    	}
    	if(isset($_REQUEST['filmNo'])){
	    	$data['filmNo']=I('filmNo');
	    	$this->assign('filmNo',I('filmNo'));
    	}
    	if(isset($_REQUEST['id'])){
	    	$data['id']=I('id');
	    	$this->assign('id',I('id'));
    	}
		$list3=$mode3->film_getlist($data,4);
   		if(file_exists('./Uploads/'.$list3['image'])){
			$list3['image']=C('IMG_URL').'Uploads/'.$list3['image'];			
		}else{
			$list3['image']=C('FILM_IMG_URL');
		}
     	if(file_exists('./Uploads/'.$list3['prevsImg'])){
			$list3['prevsImg']=C('IMG_URL').'Uploads/'.$list3['prevsImg'];			
		}else{
			$list3['prevsImg']=C('FILM_IMG_URL');
		}	
		$this->film_list=$list3;
//		$data=array();
//    
//		
//		$data['cinemaCode']=$cinemaCode;
//		$data['start_startTime']=time();		
//		$data['end_startTime']=mktime(0, 0, 0, date('m'), date('j'), date('Y'));
//		$data['filmNo']=I('filmNo');	
//		$data['sort']='startTime asc';
//		$list1=$model->cinema_plan_getlist($data);	
//		
//		foreach($list1 as $k=>$v) {			
//			$list1[$k]['cc']=date('H:i',$v['startTime']);		
//		}
//		
//		dump($list1);
		
		$user=session('ftuser');
    	$user=$this->getBindUserInfo($user);
    	if(empty($user)){
    		$mystr=$this->wwwInfo['defaultLevel'];
    	}else{
    		$mystr=$user['memberGroupId'];
    		$data['hasuser']=1;
    	}
    	$time=date('Ymd');
    	$filmNo=I('filmNo');
    	$list1=D('plan')->findplans($time,$cinemaCode,$filmNo,$mystr);
    	
//  	dump($list1);
		$this->assign('planlist', $list1);
		$plantime=$model->gettime();
		$this->assign('plantime', $plantime);
    	$cinema_list=$mode2->cinema_getlist();
    	$map['cinemaCode']=$cinemaCode;
    	$films=D('plan')->getcinemaFilms($cinemaCode,$filmNo);
    	$this->assign('cinema_list', $cinema_list);
    	$this->assign('pageId', 'page-filminfo');
    	$this->display('filminfo');
    }

    /**
     * 热映影片展示
     */
    public function filmlist(){
    	$cinemaCode=I('cinemaCode');
    	if(empty($cinemaCode)){
    		$cinemaCode=session('cinemaCode');
    	}else{
    		session('cinemaCode',$cinemaCode);
    	}
    	$cinema=D('cinema')->find($cinemaCode);
    	$this->assign('cinema',$cinema);
    	$sql='1';
    	$films=D('plan')->getFilms_sort($sql);
//   	dump($films);
    	$this->assign('films',$films);
    	$this->assign('pageId', 'page-filmlist');
    	$this->display();
    }
    
    
    /**
     * 热映影片展示
     */
    public function search_filmplan(){
    	$cinemaCode=I('request.cinemaCode');
    	if(empty($cinemaCode)){
    		$cinemaCode=session('cinemaCode');
    	}else{
    		session('cinemaCode',$cinemaCode);
    	}
    	$time=I('startTime');
    	$filmNo=I('filmNo');
    	if(empty($time)){
    		$data['planTime']=$planTime=D('Plan')->gettime(array('cinemaCode'=>$cinemaCode,'filmNo'=>$filmNo));//时间列表
    		$time=$planTime[0]['time'];
    	}
    	$user=session('ftuser');
    	$user=$this->getBindUserInfo($user);
    	if(empty($user)){
    		$mystr=$this->wwwInfo['defaultLevel'];
    	}else{
    		$mystr=$user['memberGroupId'];
    		$data['hasuser']=1;
    	}
    	$data['films']=D('plan')->findplans($time,$cinemaCode,$filmNo,$mystr);
    	$data['count']=D('plan')->countObj(date('Ymd'),$cinemaCode,$filmNo,$mystr);
    	echo json_encode($data);

    }

    /**
     * 即将上映
     */
    public function soonlist(){
    	$tarr['publishDate']=array('egt',time());
    	$tarr['filmNo']=array('eq','');
    	$films=D('film')->getList($tarr);
    	foreach ($films as $k=>$v){
    		$t[$k]=explode('.', number_format($v['score']/10,1));
    		$films[$k]['f']=$t[$k][0];
    		$films[$k]['s']=$t[$k][1];
    		$films[$k]['publishDate']=date('n月j日',$v['publishDate']);
	    	if(file_exists('./Uploads/'.$films[$k]['image'])){
				$films[$k]['image']=C('IMG_URL').'Uploads/'.$films[$k]['image'];			
			}else{
				$films[$k]['image']=C('FILM_IMG_URL');
			}
    		
    	}
    	$this->assign('films',$films);
    	$this->assign('pageId', 'page-filmlist');
    	$this->display('soonlist');
    }

}