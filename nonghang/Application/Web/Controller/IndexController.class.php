<?php
namespace Web\Controller;
use Think\Controller;
class IndexController extends InitController {
    /**
     * 首页展示
     */
    public function index(){
    	$model=D('Banner');
    	$data=array();
    	$data['type']='0';
    	$list=$model->banner_getlist($data);
    	$data=array();
    	$data['type']='2';
    	$data['home']='0';
    	$data['firstRow']='0';
    	$data['listRows']='2';
    	$list1=$model->banner_getlist($data,2);    	
    	$this->assign('list',$list);
    	$this->assign('list1',$list1);   	
    	$user=session('ftuser');
    	$sql='1';
    	if($cinemaCode)
    	$sql='cinemaCode="'.$cinemaCode.'"';
    	$films=D('plan')->getFilms($sql);
//    	dump($films);
    	$this->assign('films',$films);

    	
    	$tarr['publishDate']=array('egt',time());
    	$tarr['filmNo']=array('eq','');
    	$films=D('film')->getList($tarr);
    	foreach ($films as $k=>$v){
    		$t[$k]=explode('.', number_format($v['score']/10,1));
    		$films[$k]['f']=$t[$k][0];
    		$films[$k]['s']=$t[$k][1];
    		$films[$k]['image']=C('IMG_URL').'Uploads/'.$v['image'];
    		$films[$k]['publishDate']=date('n月j日',$v['publishDate']);
    	}
    	
//    	dump($films);
    	$this->assign('films2',$films);
    	
        $this->assign('pageId', 'page-index');
        
       
  
       $this->display();
    }
    /**
     *意见反馈展示
     */
    public function feedback(){
    	
    	
//    	dump($_SESSION);
    	
    	$this->assign('mobile',$_SESSION['ftuser']['mobile']);
        $this->assign('pageId', 'page-feedback');
       $this->display();
    }
    /**
     *意见反馈添加
     */
    public function feedback_add(){
    	$model=D('Feedback');
    	$data=array();
    	$data['mobile']=I('mobile');
    	$data['content']=I('content');
    	
   		 if(!I('mobile')){
    	
    		$this->error('请输入手机号！');
    	}
    	if(!I('content')){   	
    		$this->error('请输入建议');
    	}
    	if(iconv_strlen(I('content'),"UTF-8")>200) {
    			$this->error('请输入少于200个字符');	
    	}
    	
//    	$data['uid']=$_SESSION['ftuser']['id'];
    	$data['time']=time();
    	
   		$ret=$model->add_model($data);   	
    	$this->success('成功');	
    }
    public function feedbackstatus(){
        $this->assign('pageId', 'page-feedbackStatus');
       $this->display();
    }
	public function pduserlogin(){
		
		
		if(isset($_SESSION['ftuser'])){
			$this->success('有登录');	
		
		}else{
		
			$this->error('没有登录');	
		
		}
		
      
    }
    
    
}