<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class AppController extends AdminController {

    /**
     * APP帐号管理
     *
     * @param string $id
     */
    public function applist($value='')
    {
        $this->cinemaGroup = D('Cinema')->getGroup();
    	$appAccountList=D('App')->getAppAccountList('*');
    	$this->assign('appAccountList',$appAccountList);
    	$this->display();
    }
    
    /**
     * APP帐号修改
     *
     * @param string $id
     */
    public function editAppInfo($appId)
    {
        if (IS_AJAX) {
            $data = I('request.data');
            $map['appId'] = $appId;

            if(D('App')->updateAppAccount($data, $map)){
                $this->success('修改成功!');
            }else{
                $this->error('修改失败！');
            }
        }else{
            $appMap['appId'] = $appId;
            $this->appAccountInfo = D('App')->getAppAccountInfo('*', $appMap);
            $this->cinemaGroup = D('Cinema')->getGroup('id, groupName','');
            $this->display('app_form');  
        }
        
    }

    /**
     * APP推送管理
     *
     * @param string $id
     */
    public function appPush()
    {
    	$this->list=M('appMessage')->order('addtime desc')->limit(10)->select();
        $this->display('appPush');  

    }

    /**
     * 添加APP推送
     *
     * @param string $id
     */
    public function appAppPush()
    {
        if (IS_AJAX) {
            $data = I('request.data');

            if (empty($data['title'])) {
                $this->error('推送标题不能为空！');
            }

            if (empty($data['content'])) {
                $this->error('推送内容不能为空！');
            }

            if ($data['isParam'] == 1) {
                if (empty($data['params'])) {
                    $this->error('推送参数不能为空！');
                }
                foreach ($data['params'] as $key => $value) {
                    $params[$value] = $data['value'][$key];
                }

            }


            $param['title'] = $data['title'];
            $param['content'] = $data['content'];

            $param['custom']['param'] = $params;

  
            if ( in_array('android', $data['pushDevice'])) {
                $androidResult = D('AndroidPush')->pushAllDevices($param);
                if ($androidResult['ret_code'] == 0) {
                    $messageData['title'] = $data['title'];
                    $messageData['content'] = $data['content'];
                    $messageData['param'] = json_encode($params);
                    $messageData['msgType'] = 'android';
                    $messageData['addtime'] = time();
                    D('App')->addMessage($messageData);
                }
            }
            
            if ( in_array('iOS', $data['pushDevice'])) {
                $iOSResult = D('AndroidPush')->pushAllDevices($param);
            }

            if ($androidResult['ret_code'] == 0 && $iOSResult['ret_code'] == 0) {
                $this->success('推送成功！');
            }elseif ($androidResult['ret_code'] != 0) {
                $this->error('安卓：' . $androidResult['err_msg']);
            }elseif ($iOSResult['ret_code'] != 0) {
                $this->error('iOS:' . $iOSResult['err_msg']);
            }


        }else{
           $this->display('appPushForm'); 
        }
        
    }
    /**
     * banner列表
     */
    public function wap(){
    	if(IS_POST){
    		$data=I('data');
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
    		$upload->savePath  =     'app/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
    		if(empty($data['id'])){
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}else{
    				jsalert('请传入图片!');
    				die();
    			}
    			if(D('appBanner')->add($data)){
    				jsalert('添加成功',U());
    			}else{
    				jsalert('添加失败');
    			}
    		}else{
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}
    			$banner=D('appBanner')->find($data['id']);
    			if(D('appBanner')->save($data)){
    				if($info['img']){
    					unlink($upload->rootPath.$banner['img']);
    				}
    				jsalert('修改成功',U());
    			}else{
    				jsalert('修改失败');
    			}
    		}
    	}else{
    		$this->assign('golist',$this->golist);
    		$appList=D('appAccount')->select();
    		$this->assign('appList',$appList);
    		$pageData['appid']=I('appid');
    		$this->assign('pageData',$pageData);
    		if(!empty($pageData['appid'])){
    			$map['appid']=$pageData['appid'];
    		}
    		$map['type']=0;
    		$count = D ( 'app' )->countBanners($map);
    		$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    		$allPage = ceil ( $count / $this->limit);
    		$curPage = $this->curPage ( $nowPage, $allPage );
    		$startLimit = ($curPage - 1) * $this->limit;
    		if ($count > $this->limit) {
    			$showPage = $this->getPageList ( $count, $this->limit, $pageData);
    			$this->assign('page',$showPage);
    		}
    		$banners=D('app')->getBanners($map,$startLimit,$this->limit);
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
    	$banner=D('appBanner')->find($id);
    	if(D('appBanner')->delete($id)){
    		unlink(C('__UPLOAD__').$banner['img']);
    		echo '1';
    	}else{
    		echo '0';
    	}
    }
    
    /**
     * 广告列表
     */
    public function advertising(){
    	if(IS_POST){
    		$data=I('data');
    		$data['type']=1;
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
    		$upload->savePath  =     'app/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
    		if(empty($data['id'])){
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}else{
    				jsalert('请传入图片!');
    				die();
    			}
    			if(D('appBanner')->add($data)){
    				jsalert('添加成功',U());
    			}else{
    				jsalert('添加失败');
    			}
    		}else{
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}
    			$banner=D('appBanner')->find($data['id']);
    			if(D('appBanner')->save($data)){
    				if($info['img']){
    					unlink($upload->rootPath.$banner['img']);
    				}
    				jsalert('修改成功',U());
    			}else{
    				jsalert('修改失败');
    			}
    		}
    	}else{
    		$this->assign('golist',$this->golist);
    		$appList=D('appAccount')->select();
    		$this->assign('appList',$appList);
    		$pageData['appid']=I('appid');
    		$this->assign('pageData',$pageData);
    		if(!empty($pageData['appid'])){
    			$map['appid']=$pageData['appid'];
    		}
    		$map['type']=1;
    		$banners=D('app')->getBanners($map);
    		$this->assign('banners',$banners);
    		$this->display();
    	}
    }
    
    
    /**
     * 成功弹窗
     */
    public function advice(){
    	if(IS_POST){
    		$data=I('data');
    		$data['type']=2;
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
    		$upload->savePath  =     'app/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
    		if(empty($data['id'])){
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}else{
    				jsalert('请传入图片!');
    				die();
    			}
    			if(D('appBanner')->add($data)){
    				jsalert('添加成功',U());
    			}else{
    				jsalert('添加失败');
    			}
    		}else{
    			if($info['img']){
    				$data['img']=$info['img']['savepath'].$info['img']['savename'];
    			}
    			$banner=D('appBanner')->find($data['id']);
    			if(D('appBanner')->save($data)){
    				if($info['img']){
    					unlink($upload->rootPath.$banner['img']);
    				}
    				jsalert('修改成功',U());
    			}else{
    				jsalert('修改失败');
    			}
    		}
    	}else{
    		$this->assign('golist',$this->golist);
    		$appList=D('appAccount')->select();
    		$this->assign('appList',$appList);
    		$pageData['appid']=I('appid');
    		$this->assign('pageData',$pageData);
    		if(!empty($pageData['appid'])){
    			$map['appid']=$pageData['appid'];
    		}
    		$map['type']=2;
    		$banners=D('app')->getBanners($map);
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
    	$banner=D('appBanner')->find(I('id'));
    	echo json_encode($banner);
    }
}