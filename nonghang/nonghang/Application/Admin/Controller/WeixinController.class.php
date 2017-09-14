<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class WeixinController extends AdminController {


    /**
     * 活动
     */
    public function activitylist(){
		if(IS_POST){
			$data=I('data');
			$data['type']=2;
			$data['updateTime']=time();
			$data['updateUser']=CPUID;
			$upload = new \Think\Upload(); // 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
			$upload->savePath  =     'wap/'; // 设置附件上传（子）目录
			// 上传文件
			$info   =   $upload->upload();
			if(empty($data['id'])){
				if($info['img']){
					$data['img']=$info['img']['savepath'].$info['img']['savename'];
				}else{
					jsalert('请传入图片!');
					die();
				}
				if(D('Banner')->add($data)){
					jsalert('添加成功',U());
				}else{
					jsalert('添加失败');
				}
			}else{
				if($info['img']){
					$data['img']=$info['img']['savepath'].$info['img']['savename'];
				}
				$banner=D('banner')->find($data['id']);
				if(D('Banner')->save($data)){
					if($info['img']){
						unlink(C('__UPLOAD__').$banner['img']);
					}
					jsalert('修改成功',U());
				}else{
					jsalert('修改失败');
				}
			}
		}else{
			$tempCinemaGroupList=D('cinema')->getGroup('id,groupName');
            foreach ($tempCinemaGroupList as $key => $value) {
                $cinemaGroupList[$value['id']] = $value;
            }

            // print_r($cinemaGroupList);

            $this->assign('cinemaGroupList',$cinemaGroupList);
			$pageData['cinemaGroupId']=I('cinemaGroupId');
			$this->assign('pageData',$pageData);
			if(!empty($pageData['cinemaGroupId'])){
				$map['cinemaGroupId']=$pageData['cinemaGroupId'];
			}
			$map['type']=2;
			$banners=D('banner')->getList($map);

			$this->assign('banners',$banners);
			$this->display();
		}
    }
    /**
     * 删除
     *
     * @param string $id
     * @return int
     */
    public function delete(){
    	$id=I('id');
    	$banner=D('banner')->find($id);
    	if(D('banner')->delete($id)){
    		unlink(C('__UPLOAD__').$banner['img']);
    		echo '1';
    	}else{
    		echo '0';
    	}
    }
    /**
     * banner列表
     */
	public function wap(){
		if(IS_POST){
			$data=I('data');
			$data['updateTime']=time();
			$data['updateUser']=CPUID;
			$upload = new \Think\Upload(); // 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
			$upload->savePath  =     'wap/'; // 设置附件上传（子）目录
			// 上传文件
			$info   =   $upload->upload();
			if(empty($data['id'])){
				if($info['img']){
					$data['img'] = $info['img']['savepath'].$info['img']['savename'];
				}else{
					jsalert('请传入图片!');
					die();
				}
				if(D('Banner')->add($data)){
					jsalert('添加成功',U());
				}else{
					jsalert('添加失败');
				}
			}else{
				if($info['img']){
					$data['img'] = $info['img']['savepath'].$info['img']['savename'];
				}
				$banner=D('banner')->find($data['id']);

				if(D('Banner')->save($data)){
					if($info['img']){
						unlink(C('__UPLOAD__').$banner['img']);
					}
					jsalert('修改成功',U());
				}else{
					jsalert('修改失败');
				}
			}
		}else{
			$tempCinemaGroupList=D('cinema')->getGroup('id,groupName');
            foreach ($tempCinemaGroupList as $key => $value) {
                $cinemaGroupList[$value['id']] = $value;
            }

            // print_r($cinemaGroupList);

			$this->assign('cinemaGroupList',$cinemaGroupList);
			$pageData['cinemaGroupId']=I('cinemaGroupId');
			$this->assign('pageData',$pageData);
			if(!empty($pageData['cinemaGroupId'])){
				$map['cinemaGroupId']=$pageData['cinemaGroupId'];
			}
			$map['type']=0;
			$banners=D('banner')->getList($map);
			$this->assign('banners',$banners);
			$this->display();
		}
    }
    /**
     * 广告列表
     */
    public function advertising(){
    	if(IS_POST){
    		$data=I('data');
    		$data['type']=1;
    		$data['updateTime']=time();
    		$data['updateUser']=CPUID;
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
    		$upload->savePath  =     'wap/';// 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
    		if(empty($data['id'])){
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}else{
    				jsalert('请传入图片!');
    				die();
    			}
    			if(D('Banner')->add($data)){
    				jsalert('添加成功',U());
    			}else{
    				jsalert('添加失败');
    			}
    		}else{
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}
    			$banner=D('banner')->find($data['id']);
    			if(D('Banner')->save($data)){
    				if($info['img']){
    					unlink(C('__UPLOAD__').$banner['img']);
    				}
    				jsalert('修改成功',U());
    			}else{
    				jsalert('修改失败');
    			}
    		}
    	}else{
            $tempCinemaGroupList=D('cinema')->getGroup('id,groupName');
            foreach ($tempCinemaGroupList as $key => $value) {
                $cinemaGroupList[$value['id']] = $value;
            }

            // print_r($cinemaGroupList);

            $this->assign('cinemaGroupList',$cinemaGroupList);
            $pageData['cinemaGroupId']=I('cinemaGroupId');
            $this->assign('pageData',$pageData);
            if(!empty($pageData['cinemaGroupId'])){
                $map['cinemaGroupId']=$pageData['cinemaGroupId'];
            }
            $map['type']=1;
            $banners=D('banner')->getList($map);

            $this->assign('banners',$banners);
            $this->display();
        }
    }
    /**
     * 异步获取banner信息
     *
     * @param string $id
     */
    public function getBanner(){
    	$banner=D('banner')->find(I('id'));
    	echo json_encode($banner);
    }

    /**
     * APP帐号管理
     *
     * @param string $id
     */
    public function applist($value='')
    {
    	$appAccountList=D('App')->getAppAccount('*');
    	$this->assign('appAccountList',$appAccountList);
    	$this->display();
    }
}