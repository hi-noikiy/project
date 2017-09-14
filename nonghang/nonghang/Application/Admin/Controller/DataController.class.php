<?php
/**
 * 数据分析
 */

namespace Admin\Controller;
use Think\Controller;
class DataController extends AdminController {

	/**
     *添加上传图片
     */
    public function datalist(){
    	

    	$mode=D('BoxOfficeData');
//    	$list=$mode->getlist('',2);
    	//$list=$mode->data_getlist('',0,$this->limit);
    	//$pageData=array();
    	//$this->assign('pageData',$pageData);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));

    	//dump($list);
    	$count = M ( 'BoxData' )->count();
       	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    	    $showPage = $this->getPageList ( $count, $this->limit);
    	}
    	
    	$list=$mode->data_getlist('',$startLimit,$this->limit);
    	foreach($list as $k=>$v) {
    	    $data=array();
    	    $data['times']=$v['times'];
    	    $list[$k]['count']=$mode->getlist($data,3);
    	     
    	}
    	
    	$this->assign('page',$showPage);
		$this->assign('list',$list);

    	$this->display ();
    	
    }
	/**
     *添加上传图片
     */
    public function data_add(){   	
    	if(isset($_REQUEST)&&!empty($_REQUEST)){
    		$mode=D('BoxOfficeData'); 
    		
    		if(!isset($_REQUEST['times'])||$_REQUEST['times']=='') {
	    		echo '<script>';
	    		echo 'parent.alert("排期不能为空！");';
	    		echo '</script>';
				exit;
    		
    		}
    		$data=array();
    		$data['times']=$_REQUEST['times'];	
    		$ret=$mode->data_getlist($data);
    		if($ret) {
    			echo '<script>';
	    		echo 'parent.alert("排期已经存在！");';
	    		echo '</script>';
				exit;  		
    		}
    		
    		$dataarray=$_REQUEST;
    		$upload = new \Think\Upload(); // 实例化上传类
            $upload->maxSize   =     10000000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','mp3');// 设置附件上传类型
//          $upload->rootPath  =     'zmaxfilm/Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     '/data/'; // 设置附件上传（子）目录
            // 上传文件
		    $info   =   $upload->upload();
            if($info['img']){
               $dataarray['storePath']= $_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['img']['savepath'].$info['img']['savename'];
               $dataarray['relativePath']=C('WHOLE_UPLOAD').$info['img']['savepath'].$info['img']['savename'];
            }
            
            if($info['mp3']){
                $dataarray['music'] = C('WHOLE_UPLOAD').$info['mp3']['savepath'].$info['mp3']['savename'];
            }
//            dump($dataarray);
			$mode->add_model($dataarray);
			echo '<script>';
    		echo 'parent.alert("提交成功");';
    		echo 'setTimeout(function(){parent.location.href="'.U('datalist').'"},1000);';
    		echo '</script>';
			exit;
		
		}
    	$this->display ();	
    }
	/**
     *添加上传图片
     */
    public function data_delete(){  	
    	$mode=D('BoxOfficeData');
    	$data=array();
    	$data['times']=$_POST['times'];
    	$ret=$mode->delete_model($data);
    	if($ret) {
    		echo '{"statusCode":"1", "message":"操作成功"}';
    	}else {   	
    		echo '{"statusCode":"0", "message":"操作失败"}';
    	}	
    }
	/**
     *修改票房数据
     */
    public function data_edit(){ 

        $mode=D('BoxOfficeData');
        
    	if(isset($_POST['updata'])&&!empty($_REQUEST))
    	{
    		
    		if(!isset($_REQUEST['times'])||$_REQUEST['times']=='') {
	    		echo '<script>';
	    		echo 'parent.alert("排期不能为空！");';
	    		echo '</script>';
				exit;
    		
    		}
    		    	
    		$data=array();
     		$data['times']=$_REQUEST['times'];
     		$edm=$mode->data_getlist($data);
     		foreach ($edm as $k => $v){
     		    $edm = $v;
     		}
//             echo '<pre>';
//             print_r($v);
//             echo '<pre>';
//     		if($ret) {
//     			echo '<script>';
// 	    		echo 'parent.alert("排期已经存在！");';
// 	    		echo '</script>';
// 				exit;  		
//    		    }

    		    $dataarray=$_REQUEST;
    		    $upload = new \Think\Upload(); // 实例化上传类
    		    $upload->maxSize   =     10000000 ;// 设置附件上传大小
    		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','mp3');// 设置附件上传类型
    		    //          $upload->rootPath  =     'zmaxfilm/Uploads/'; // 设置附件上传根目录
    		    $upload->savePath  =     '/data/'; // 设置附件上传（子）目录
    		    // 上传文件

    		    $info   =   $upload->upload();
    		    $dataarray['id']=$v['id'];

    		    if($info['img']){
    		        
    		        $dataarray['storePath']= $_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['img']['savepath'].$info['img']['savename'];
    		        $dataarray['relativePath']=C('WHOLE_UPLOAD').$info['img']['savepath'].$info['img']['savename'];
     		    }
    		    
     		    if($info['mp3']){
    		        $dataarray['music'] = C('WHOLE_UPLOAD').$info['mp3']['savepath'].$info['mp3']['savename'];    		        
    		    }else {
     			echo '<script>';
 	    		echo 'parent.alert("上传音乐有错,请适当减少音乐大小！");';
 	    		echo '</script>';
 				exit;
    		    }
    		    
    		    
			$mode->update_model($dataarray);
			echo '<script>';
    		echo 'parent.alert("提交成功");';
    		echo 'setTimeout(function(){parent.location.href="'.U('datalist').'"},1000);';
    		echo '</script>';
			exit;
		}else{
		    $data=array();
		    $data['times']=$_GET['times'];
		    $list=$mode->getlist($data);
		    $ret=$mode->data_getlist($data);
		    foreach ($ret as $k => $vt){
		        $ret = $vt;
		    }
		    $this->assign('times',$_REQUEST['times']);
		    $this->assign('title',$vt['title']);
		    $this->assign('detail',$vt['detail']);
		    $this->assign('img',$vt['relativePath']);
		    $this->assign('mp3',$vt['music']);
		    $this->assign('list',$list);
		    $this->display ();
		}

		
    }
	
  	/**
     *添加上传图片
     */
    public function addUpload() {
//    	$targetFolder = '/Uploads'; // Relative to the root
    	$targetFolder = C('WHOLE_UPLOAD'); // Relative to the root
    	if (! empty ( $_FILES ) ) {
    		$tempFile = $_FILES ['dataImage'] ['tmp_name'];
    		$targetPath = $_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/'.CPUID;
    		if(!is_dir($targetPath)){
    			mkdir($targetPath);
    		}
    		$name=iconv("utf-8","gbk",$_FILES ['dataImage'] ['name']);
    		$targetFile = rtrim ( $targetPath, '/' ) . '/' . $name;   			
    		// Validate the file type
    		$fileTypes = array ('jpg','jpeg','gif','png' ); // File extensions
    		$fileParts = pathinfo ( $_FILES ['dataImage'] ['name'] );
    		if (in_array ( $fileParts ['extension'], $fileTypes )) {
    			move_uploaded_file($tempFile,$targetFile);
    			echo $_FILES ['dataImage'] ['name'];
    		} else {
    			echo 'Invalid file type.';
    		}
    	}
    }
 /**
     * 删除添加图片
     */
    function delpic(){
    	$pic=iconv('utf-8','gbk',I('pic'));
    	$picurl='./Uploads/'.CPUID.'/'.$pic;
    	@unlink($picurl);
    }
    /**
     * 删除修改图片
     */
    function delpic_d(){
    	
    	$mode=D('BoxOfficeData');
    	if(isset($_POST['id'])) {
    		$data=array();
    		$data['id']=$_POST['id'];
    		$mode->delete_model($data);
    		
    	}
    	

    	
    }



	
}