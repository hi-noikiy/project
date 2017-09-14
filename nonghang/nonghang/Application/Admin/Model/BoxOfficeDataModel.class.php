<?php

namespace Admin\Model;
use Think\Model;

class BoxOfficeDataModel extends Model {
	var $tablename="box_office_data";
    /**
     *
     *功能：添加信息
     *
     */
    private function add_basedata($dataarray,$flag="1") {
        $userbase = M($this->tablename);
        switch ($flag) {
            case 1:
                $ret = $userbase->add($dataarray);
                break;
            case 2:
                $ret = $userbase->addAll($dataarray);
                break;
            default:
                $ret = $userbase->add($dataarray);
                break;
        }
        if($ret) {
            return $ret;//返回用户id
        }else {
            return false;
        }
    }
    /**
     *
     *功能：删除信息
     *返回：成功：true
     *           失败：false
     *
     */
    private function delete_basedata($dataarray) {
    	if(isset($dataarray['id'])) {
    		$wherearray['id']=array('EQ',$dataarray['id']);
    		$data=array();
    		$data['id']=$dataarray['id'];
    		$ret=$this->getlist($data,4);
    		unlink($ret['storePath']);
    	}
   		if(isset($dataarray['times'])) {
    		$wherearray['times']=array('EQ',$dataarray['times']);  		
			$targetFolder = C('WHOLE_UPLOAD'); // Relative to the root
	    	$newstorePath =	$_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/data/'.$dataarray['times'].'/';
    		$dir=$newstorePath;
	   		//先删除目录下的文件：
			  $dh=opendir($dir);
			  while ($file=readdir($dh)) {
			    if($file!="." && $file!="..") {
			      $fullpath=$dir."/".$file;
			      if(!is_dir($fullpath)) {
			          unlink($fullpath);
			      } else {
			          deldir($fullpath);
			      }
			    }
			  }
			 
			  closedir($dh);
			  //删除当前文件夹：
			  rmdir($dir);
    	}
        $userbase = M($this->tablename);
        $ret=$userbase->where($wherearray)->delete();
        if($ret) {
            return 1;//返回删除成功
        }else {
            return false;
        }
    }
    /**
     *
     *功能：修改信息
     *返回：成功：true
     *           失败：false
     *
     */
    private function update_basedata($dataarray) {
        $wherearray['id']=array('EQ',$dataarray['id']);
        $userbase = M($this->tablename);
        $ret=$userbase->where($wherearray)->save($dataarray);
        if($ret) {
            return 1;
        }else {
            return false;
        }
    }

    /**
     *
     *功能：添加信息
     *
     */
    public function add_model($dataarray) {
    	  	
    	$userbase = M('box_data');
    	
    	$data=array() ;
    	$data['times']=$dataarray['times'];
    	$data['title']=$dataarray['title'];
    	$data['detail']=$dataarray['detail'];
    	//$data['music']=$dataarray['mp3'];
    	
    	if(isset($dataarray['relativePath']))
    	$data['relativePath']=$dataarray['relativePath'];
    	if(isset($dataarray['storePath']))
    	$data['storePath']=$dataarray['storePath'];
        if(isset($dataarray['music']))
        $data['music']=$dataarray['music'];
        $userbase->add($data);
    	
  	
//    	$targetFolder = '/Uploads'; // Relative to the root
    	$targetFolder = C('WHOLE_UPLOAD'); // Relative to the root
    	$relativePath =$targetFolder.'/data/'.$dataarray['times'].'/';
    	$oldstorePath = $_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/'.CPUID.'/';
    	
    	$newstorePath =	$_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/data/'.$dataarray['times'].'/';
    

	    if(!is_dir($newstorePath)) {
	       mkdir($newstorePath,0777,true);
	    }
    	
    	$datas=array();
    	foreach($dataarray['dataImage'] as $v) {
    		$data=array();
    		$data['times']=$dataarray['times'];
    		$data['storePath']=$newstorePath.$v;
    		$data['relativePath']=$relativePath.$v;
    		$data['pic']=$v;
//    		$this->add_basedata($data);
    		$datas[]=$data;
    		
    		copy($oldstorePath.$v,$newstorePath.$v);
    		
    		unlink($oldstorePath);
    	
    	}
//    	dump($datas);
        $ret=$this->add_basedata($datas,2);
        return $ret;
    }
    /**
     *
     *删除信息
     *
     */
    public function delete_model($dataarray) {
    	
    		if(isset($dataarray['times'])) {
    			
    			$userbase =  M('box_data');
    			
    			$wherearray['times']=array('EQ',$dataarray['times']);
    			
        		$ret1=$userbase->where($wherearray)->delete();
    			
    			
    		}

        $ret=$this->delete_basedata($dataarray);
        return $ret||$ret1;
    }
    /**
     *
     *修改信息
     *
     */
    public function update_model($dataarray) {
    	
    	$userbase = M('box_data');
    	
    	$data=array() ;
    	$wherearray['id']=array('EQ',$dataarray['id']);
    	$data['title']=$dataarray['title'];
    	$data['detail']=$dataarray['detail'];
    	if(isset($dataarray['relativePath']))
    	$data['relativePath']=$dataarray['relativePath'];
    	if(isset($dataarray['storePath']))
    	$data['storePath']=$dataarray['storePath'];
    	if(isset($dataarray['music']))
    	$data['music']=$dataarray['music'];
        $ret=$userbase->where($wherearray)->save($data); 
        
    	
//    	$targetFolder = '/Uploads'; // Relative to the root
    	$targetFolder = C('WHOLE_UPLOAD');// Relative to the root
    	$relativePath =$targetFolder.'/data/'.$dataarray['times'].'/';
    	$oldstorePath = $_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/'.CPUID.'/';   	
    	$newstorePath =	$_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/data/'.$dataarray['times'].'/';
	    if(!is_dir($newstorePath)) {
	       mkdir($newstorePath,0777,true);
	    }
    	if($dataarray['oldtimes']!=$dataarray['times']){   		
    		$data=array();   		
    		$data['times']=$dataarray['oldtimes'];
    		$oldstorePath1 = $_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/data/'.$dataarray['oldtimes'].'/';   		
    		$ret=$this->getlist($data);
    		foreach($ret as $v) {
    			$data=array();
    			$data['id']=$v['id'];
	    		$data['times']=$dataarray['times'];
	    		$data['storePath']=$newstorePath.$v['pic'];
	    		$data['relativePath']=$relativePath.$v['pic'];	
	    		$this->update_basedata($data);
    			copy($v['storePath'],$newstorePath.$v['pic']);    			
    			unlink($v['storePath']);    			
    		}     		
    		rmdir($oldstorePath1);   	
    	}
    	$datas=array();
    	foreach($dataarray['dataImage'] as $v) {
    		$data=array();
    		$data['times']=$dataarray['times'];
    		$data['storePath']=$newstorePath.$v;
    		$data['relativePath']=$relativePath.$v;
    		$data['pic']=$v;
    		$datas[]=$data;		
    		copy($oldstorePath.$v,$newstorePath.$v);  	
    	}
        $ret=$this->add_basedata($datas,2);
        return $ret;
    	
//    	if('')
//    	
//        $ret=$this->update_basedata($dataarray);
//        return $ret;
    }
    /**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
      	if(isset($dataarray['times'])) {    
        	$wherearray['times']=array('eq',$dataarray['times']);            
        }
        $database = M($this->tablename);
        switch($flag) {
            case '1':
                $info=$database->where($wherearray)->order($dataarray['sort'])->select();
                break;
            case '2'://分页操作
                $info=$database->where($wherearray)->order($dataarray['sort'])->limit($dataarray['firstRow'].','.$dataarray['listRows'])->select();
                break;
            case '3'://获取个数
                $info=$database->where($wherearray)->count();
                break;
            case '4'://获取单条
                $info=$database->where($wherearray)->find();
                break;
            case '5'://选择字段查询操作
                $info=$database->where($wherearray)->field($dataarray['getField'])->select();
                break;              
            case '6'://选择字段查询操作            	
            	$info=$database->field("count(*) as count,times")->group("times")->select();
//                $info=$database->where($wherearray)->field($dataarray['getField'])->select();
                break;
            default:
                $info=$database->where($wherearray)->select();
                break;
        }
//      echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
    
 	public function data_getlist($dataarray=array(),$start=0,$limit=10) {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
      	if(isset($dataarray['times'])) {    
        	$wherearray['times']=array('eq',$dataarray['times']);            
        }
 		if(isset($dataarray['neqid'])) {    
        	$wherearray['id']=array('neq',$dataarray['neqid']);            
        }
        $database = M('boxData');
        $info=$database->where($wherearray)->order('id DESC')->limit($start,$limit)->select();
//      echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
    
    
    
}