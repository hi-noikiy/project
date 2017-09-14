<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class FilmController extends AdminController {

	/**
	 * 影片首页
	 */
    public function filmlist(){
    	$pageData['filmName']=$filmName = I('filmName' , '' , 'htmlspecialchars' );
    	$this->assign('pageData',$pageData);
    	if($filmName){
    		$map['filmName'] = array('like',"%$filmName%");
    	}
    	$limit=$this->limit;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$pageData['status'] = $this->status = intval(I('status'));

        $hotFilmList = S('hotFilmList');
        if (empty($hotFilmList)) {
            $filmMap['startTime']= array('egt',strtotime(date('Ymd',time())));
            $filmNoList = D('Plan')->getPlanListGroupByFilmNo('filmNo', $filmMap);
            foreach ($filmNoList as $key => $value) {
                $hotFilmList[] = $value['filmNo'];
            }
            S('hotFilmList', $hotFilmList, 3600);
        }
        

        if ($this->status == 0) {
            $map['filmNo'] = array('IN', $hotFilmList);
        }elseif ($this->status == 1) {
            $map['publishDate'] = array('egt',strtotime(date('Ymd',time())));
            $map['filmNo'] = array('eq', '');
        }elseif ($this->status == 2) {
            $map['publishDate'] = array('lt',strtotime(date('Ymd',time())));
            $map['filmNo'] = array('not in', $hotFilmList);
        }

        
        // print_r($filmNo);

    	$count = D ( 'Film' )->getFilmCount ($map);
    	$allPage = ceil ( $count / $limit );
    	$curPage = $this->curPage ( $nowPage, $allPage );

    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit );
        }
    	$this->assign('page',$showPage);


        $filmList=D('film')->getFilmList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit);

    	$this->assign('list',$filmList);


        $bindFilmMap['filmNo'] = array('neq', '');
        $bindFilmList = D('Film')->getBindFilmList('filmNo, filmName', $bindFilmMap);
        if($bindFilmList){
            foreach ($bindFilmList as $key => $value) {
                $bindFilmNo[] = $value['filmNo'];
            }
        }
        $noBindFilmMap['startTime'] = array('GT', time());
        if($bindFilmNo){
            $noBindFilmMap['filmNo'] = array('not in', $bindFilmNo);
        }
        $noBindFilmList = D('Plan')->getPlanListGroupByFilmNo('filmNo, filmName, copyType', $noBindFilmMap);
        $this->assign('noBindFilmList',$noBindFilmList);

        $this->display();
    }



    public function setLowestPrice()
    {

        if(IS_AJAX){
            $lowestPrice = I('request.lowestPrice');
            $filmNo = I('request.filmNo');
            $cinemaCode = I('request.cinemaCode');
            $cinemaGroupId = I('request.cinemaGroupId');

            $data['lowestPrice'] = $lowestPrice;
            $data['filmNo'] = $filmNo;
            $data['cinemaCode'] = $cinemaCode;


            if(D('Film')->aotuSetLowestPrice($data)){
            	D('plan')->setCinemaPlan(array('lowestPrice'=>$lowestPrice),array('filmNo'=>$filmNo,'cinemaCode'=>$cinemaCode));
                $this->success('恭喜您，影片保护价设置成功！', $cinemaList);
            }else{
                $this->error('很遗憾，影片保护价设置失败！');
            }

        }else{

            $this->cinemaGroupId = I('request.cinemaGroupId');
            

            $groupList = D('Cinema')->getGroup('id, groupName, cinemaList', $groupMap);


            if(!empty($this->cinemaGroupId)){
                foreach ($groupList as $key => $value) {
                    if ($value['id'] == $this->cinemaGroupId) {
                        $cinemaListMap['cinemaCode'] = array('IN', $value['cinemaList']);
                    }
                }
                // $groupMap['id'] = $this->cinemaGroupId;
                
               
            }else{
                $cinemaListMap['cinemaCode'] = array('IN', $groupList[0]['cinemaList']);
                $this->cinemaGroupId = $groupList[0]['id'];
            }


            $this->assign('cinemaGroupId',$this->cinemaGroupId);

            $cinemaList = D('Cinema')->getCinemaList('*', $cinemaListMap);

            $filmListMap['startTime'] = array('GT', time());
            $filmList = D('Plan')->getPlanListGroupByFilmNo('filmNo, filmName, copyType', $filmListMap);

            foreach ($filmList as $key => $value) {
                $lowestPriceMap['filmNo'] = $value['filmNo'];
                // $lowestPriceMap['cinemaGroupId'] = $this->cinemaGroupId;

                $lowestPrice = D('Film')->getLowestPrice('*', $lowestPriceMap);
                $filmList[$key]['lowestPrice'] = $lowestPrice;
            }


            $this->assign('filmList',$filmList);

            $this->assign('groupList',$groupList);
            $this->assign('cinemaList',$cinemaList);
            $this->display();
        }
    }


    public function updateLowestPrice($value='')
    {
        $filmListMap['startTime'] = array('GT', time());
        $filmList = D('Plan')->getPlanListGroupByFilmNo('filmNo, filmName, copyType', $filmListMap);

        foreach ($filmList as $key => $value) {

            $lowestPrice = D('Admin/Film')->getLowestPrice('', array('filmNo'=>$value['filmNo'], 'cinemaCode' =>'35012401' ));
            
            $lowestPrice = D('Film')->getLowestPrice('*', array('filmNo'=> $value['filmNo']));
            
            foreach ($lowestPrice as $k => $v) {
                $data['lowestPrice'] = $v;
                $map['filmNo'] = $value['filmNo'];
                $map['cinemaCode'] = $k;


                D('plan')->setCinemaPlan($data, $map);

            }
            echo '保护价更新成功！';
            // print_r($lowestPrice);
        }
    }

    /**
     * 删除影片
     */
    public function delete(){
    	$id = I('post.id' , '' , 'intval');
    	$film=D('film')->find($id);
    	if(D('film')->delete($id)){
    		unlink(C('__UPLOAD__').$film['image']);
    		unlink(C('__UPLOAD__').$film['prevsImg']);
    		if(!empty($film['imgs'])){
    			$picImgs=explode(';',substr($film['imgs'], 0,-1));
    			foreach ($picImgs as $v){
    				unlink(C('__UPLOAD__').$v);
    			}
    		}
    		$data['status'] = 0;
    	}else{
    		$data['status'] = 1;
    		$data['text'] = '删除失败';
    	}
    	$this->ajaxReturn($data , 'json');
    }
    /**
     * 编辑影片
     */
    public function filmedit(){

        $bindFilmMap['filmNo'] = array('neq', '');
        $bindFilmList = D('Film')->getBindFilmList('filmNo, filmName', $bindFilmMap);
        if($bindFilmList){
            foreach ($bindFilmList as $key => $value) {
                $bindFilmNo[] = $value['filmNo'];
            }
        }
        if($bindFilmNo){
            $noBindFilmMap['filmNo'] = array('not in', $bindFilmNo);
        }
        $noBindFilmMap['startTime'] = array('GT', time());
        $noBindFilmList = D('Plan')->getPlanListGroupByFilmNo('filmNo, filmName, copyType', $noBindFilmMap);
        $this->assign('noBindFilmList',$noBindFilmList);

		if(IS_POST){
			$data=I('data');
			$film=D('film')->find($data['id']);
			$types=I('type');
			$data['publishDate']=strtotime($data['publishDate']);
			$data['type']=implode('/', $types);
			$data['updateUser']=CPUID;
			$data['updateTime']=time();
			$upload = new \Think\Upload(); // 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     C('__UPLOAD__'); // 设置附件上传根目录
			$upload->savePath  =     'film/'; // 设置附件上传（子）目录
			// 上传文件
			$info   =   $upload->upload();



			if($info['image']){
                $image = new \Think\Image();
                $image->open($upload->rootPath.$info['image']['savepath'].$info['image']['savename']);
                $image->thumb(130, 180,\Think\Image::IMAGE_THUMB_CENTER)->save($upload->rootPath.$info['image']['savepath'].$info['image']['savename']);

				$data['image'] = $info['image']['savepath'].$info['image']['savename'];
			}
			if($info['image1']){
				$data['prevsImg'] = $info['image1']['savepath'].$info['image1']['savename'];
			}
			if(D('film')->save($data)){
				if($info['image']){
					unlink(C('__UPLOAD__') . $film['image']);
				}
				if($info['image1']){
					unlink(C('__UPLOAD__') . $film['prevsImg']);
				}
                jsalert('提交成功', U('filmlist'));
			}else{
                jsalert('连接数据库失败');
			}
    	}else{
    		$film=D('film')->find(I('id'));
    		$types=explode('/', $film['type']);
    		foreach ($types as $val){
    			$type[$val]=1;
    		}
    		if(!empty($film['imgs'])){
    			$picImgs=explode(';',substr($film['imgs'], 0,-1));
    			$this->assign ( 'picImgs', $picImgs );
    		}
    		$this->assign('type',$type);
    		$this->assign('film',$film);

            $nowBindFilmMap['startTime'] = array('GT', time());
            $nowBindFilmMap['filmNo'] = array('eq', $film['filmNo']);
            $nowBindFilmList = D('Plan')->getPlanListGroupByFilmNo('filmNo, filmName, copyType', $nowBindFilmMap);


            $this->assign('nowBindFilmList',$nowBindFilmList);
    		$this->display();
    	}
    }
    /**
     * 添加影片
     */
    public function filmadd(){
		$filearr=array();
    	if ($dir_handle = opendir ( 'Uploads/' . CPUID.'/film' )) {
    		while ( false !== ($filename = readdir ( $dir_handle )) ) {
    			if($filename!='.'&&$filename!='..'){
    				$filearr[]=$filename;
    			}
    		}
    		closedir ( $dir_handle );
    	}


        $bindFilmMap['filmNo'] = array('neq', '');
        $bindFilmList = D('Film')->getBindFilmList('filmNo, filmName', $bindFilmMap);
        if($bindFilmList){
            foreach ($bindFilmList as $key => $value) {
                $bindFilmNo[] = $value['filmNo'];
            }
        }
        if($bindFilmNo){
            $noBindFilmMap['filmNo'] = array('not in', $bindFilmNo);
        }
        $noBindFilmMap['startTime'] = array('GT', time());
        $noBindFilmList = D('Plan')->getPlanListGroupByFilmNo('filmNo, filmName, copyType', $noBindFilmMap);
        $this->assign('noBindFilmList',$noBindFilmList);

    	if(IS_POST){  		
    		$data=I('data');
    		$types=I('type');
    		$data['publishDate']=strtotime($data['publishDate']);
			$data['type']=implode('/', $types);
    		$data['updateUser']=CPUID;
    		$data['updateTime']=time();
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
    		$upload->savePath  =     'film/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();



    		if($info['image1']){

    			$data['prevsImg'] = $info['image1']['savepath'].$info['image1']['savename'];
    		}else{
                jsalert('请选择预告片图片');
    		}
    		if($info['image']){
                $image = new \Think\Image();
                $image->open($upload->rootPath.$info['image']['savepath'].$info['image']['savename']);
                $image->thumb(130, 180,\Think\Image::IMAGE_THUMB_CENTER)->save($upload->rootPath.$info['image']['savepath'].$info['image']['savename']);
                $data['image'] = $info['image']['savepath'].$info['image']['savename'];
    		}else{
                jsalert('请选择封面图片');
    		}
    		foreach ($filearr as $val){
    			$copyUrl=$upload->savePath.'still/'.date('Y-m-d').'/' .$val;
                aotumkdir($upload->rootPath .$copyUrl);
                rename(C('__UPLOAD__') . CPUID.'/film/'.$val ,$upload->rootPath .$copyUrl );
    			$data['imgs'] .= $copyUrl.';';
    		}
    		$starturl='Uploads/'.CPUID.'/film';
    		if(D('film')->add($data)){
    			delDirAndFile($starturl);
                jsalert('提交成功', U('filmlist'));
    		}else{
                jsalert('连接数据库失败');
    		}
    	}else {
            foreach ($filearr as $key => $value) {
                if (!strstr($value, 'small_')) {
                    unset($filearr[$key]);
                }else{
                	$filearr[$key] = CPUID.'/film/'. $value;
                }
            }    		
			$this->assign ( 'filearr', $filearr );
			$this->display ();
		} 
    }
    /**
     *修改上传图片
     */
    public function updateUpload(){
    	$data['id']=I('request.id');
    	$film=D('film')->find($data['id']);

        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     'film/still/'; // 设置附件上传（子）目录
        // $image->thumb(150, 150)->save($mimage);
        // 上传文件
        $info   =   $upload->upload();

        $bigImgUrl = $upload->rootPath . $info['postImage']['savepath'] . $info['postImage']['savename'];
        
        $image = new \Think\Image();
        $image->open($bigImgUrl);
        $mimage = $info['postImage']['savepath'] . 'small_' . $info['postImage']['savename'];
        $image->thumb(180, 120,\Think\Image::IMAGE_THUMB_CENTER)->save($upload->rootPath . $mimage);
        $data['imgs'] = $film['imgs'] . $mimage . ';';

        if(D('film')->save($data)){
            echo $mimage;
        }else{
            echo '网络异常无法修改';
        }

    }
    /**
     *添加上传图片
     */
    public function addUpload(){


        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     CPUID . '/film/'; // 设置附件上传（子）目录
        $upload->subName = '';
        // $image->thumb(150, 150)->save($mimage);
        // 上传文件
        $info   =   $upload->upload();

        $bigImgUrl = $upload->rootPath . $info['postImage']['savepath'] . $info['postImage']['savename'];
        
        $image = new \Think\Image();
        $image->open($bigImgUrl);
        $mimage =  $upload->rootPath . $info['postImage']['savepath'] . 'small_' . $info['postImage']['savename'];
        $image->thumb(180, 120,\Think\Image::IMAGE_THUMB_CENTER)->save($mimage);
        echo $mimage;

    }
    /**
     * 删除添加图片
     */
    function delpic(){
    	$picurl = I('pic');
    	unlink(C('__UPLOAD__') . $picurl);
        unlink(C('__UPLOAD__') . str_replace('small_', '', $picurl));
    }
    /**
     * 删除修改图片
     */
    function delpic1(){
    	$pic = I('pic');
    	$data['id']=I('id');
    	$film=D('film')->find($data['id']);
    	$data['imgs'] = str_replace($pic . ';', '', $film['imgs']);
    	if(D('film')->save($data)){
    		unlink(C('__UPLOAD__') . $pic);
            unlink(C('__UPLOAD__') . str_replace('small_', '', $pic));
    		$msg['status']=1;
    	}else{
    		$msg['status']=0;
    		$msg['text']='网络异常无法删除';
    	}
    	echo json_encode($msg);
    }
}