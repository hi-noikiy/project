<?php
class upload
{
	public static $allow_img_type=array('jpg','jpeg','gif','png');
	
	public static $allow_attach_type=array('jpg','jpeg','gif','png','pdf','ppt','pptx','pps','doc','docx','zip','rar','txt','xls','xlsx');
	
	public function __construct(){
		
	}
	/**
	 * 上传单个图片
	 *
	 * @param String $fileName Input名称
	 * @param boolean $big 是否要上传大图片
	 * @param boolean $small 是否要上传小图片
	 * @param int $small_width 小图片的最大宽度
	 * @param int $small_height 小图片的最大高度
	 * @return array(name,size,surl,url); name原图片名称 ,size原图片大小, surl已上传的小图片路径和名称, url已上传的大图片路径和名称
	 */
	public function img($fileName,$small=true,$small_width=300,$small_height=240,$big=true){
		global $rootpath;
		$up_file=$_FILES[$fileName];
		unset($file_data);
		$file_data['name']=$up_file['name'];
		$file_data['size']=$up_file['size'];
		$upload=new uploadFile();
		$path='upload/'.date('Y/m/');
		$upload->setAllowFileType(self::$allow_img_type);
		$file=$upload->upload($up_file,$rootpath.$path);
		if(!$file){
			$file_ary=false;
		}else{
			if($small){
				$imgclass=new Image($rootpath.$path.$file);
				$imgclass->resizeImage($small_width,$small_height,1,array(255,255,255));
				$isurl=$imgclass->save(2,null,'_small');
				$file_data['surl']=$path.basename($isurl);
			}
			
			if(!$big){
				unlink($rootpath.$path.$file);
			}else{
				$file_data['url']=$path.$file;
			}
			
			$file_ary=$file_data;
		}
		return $file_ary;
	}
	/**
	 * 上传多图片
	 *
	 * @param String $fileName Input名称
	 * @param boolean $big 是否要上传大图片
	 * @param boolean $small 是否要上传小图片
	 * @param int $small_width 小图片的最大宽度
	 * @param int $small_height 小图片的最大高度
	 * @return 二维数组array(array(name,size,surl,url),.....); name原图片名称 ,size原图片大小, surl已上传的小图片路径和名称 ,url已上传的大图片路径和名称
	 */
	public function multi_img($fileName,$small=true,$small_width=300,$small_height=240,$big=true){
		global $rootpath;
		foreach($_FILES[$fileName]['name'] as $k => $v){
			$up_file['name']=$_FILES[$fileName]['name'][$k];
			$up_file['type']=$_FILES[$fileName]['type'][$k];
			$up_file['tmp_name']=$_FILES[$fileName]['tmp_name'][$k];
			$up_file['error']=$_FILES[$fileName]['error'][$k];
			$up_file['size']=$_FILES[$fileName]['size'][$k];
			unset($file_data);
			$file_data['name']=$up_file['name'];
			$file_data['size']=$up_file['size'];
			$upload=new uploadFile();
			$path='upload/'.date('Y/m/');
			$upload->setAllowFileType(self::$allow_img_type);
			$file=$upload->upload($up_file,$rootpath.$path);
			if($file){
				if($small){
					$imgclass=new Image($rootpath.$path.$file);
					$imgclass->resizeImage($small_width,$small_height,1,array(255,255,255));
					$isurl=$imgclass->save(2,null,'_small');
					$file_data['surl']=$path.basename($isurl);
				}
				if(!$big){
					unlink($rootpath.$path.$file);
				}else{
					$file_data['url']=$path.$file;
				}
				$file_ary[$k]=$file_data;
			}else{
				$file_ary=false;
			}
		}
		return $file_ary;
	}
	/**
	 * 上传单个文件
	 *
	 * @param String $fileName Input名称
	 * @param array $allow_type 允许上传的文件类型
	 * @return array(name,size,url) name原文件名称,size原文件大小,url保存的路径名称
	 */
	public function attach($fileName,$allow_type=null){
		global $rootpath;
		$up_file=$_FILES[$fileName];
		unset($file_data);
		$file_data['name']=$up_file['name'];
		$file_data['size']=$up_file['size'];
		$upload=new uploadFile();
		
		$type=self::$allow_attach_type;
		if($allow_type)$type=$allow_type;
		$upload->setAllowFileType($type);

		$path='upload/'.date('Y/m/');
		$file=$upload->upload($up_file,$rootpath.$path);
		if(!$file){
			$file_ary=false;
		}else{
			$file_data['url']=$path.$file;
			$file_ary=$file_data;
		}
		return $file_ary;
	}
	/**
	 * 上传多个文件
	 *
	 * @param String $fileName Input名称
	 * @param array $allow_type 允许上传的文件类型
	 * @return array(name,size,url) name原文件名称,size原文件大小,url保存的路径名称
	 */
	public function multi_attach($fileName,$allow_type=null){
		global $rootpath;
		foreach($_FILES[$fileName]['name'] as $k => $v){
			$up_file['name']=$_FILES[$fileName]['name'][$k];
			$up_file['type']=$_FILES[$fileName]['type'][$k];
			$up_file['tmp_name']=$_FILES[$fileName]['tmp_name'][$k];
			$up_file['error']=$_FILES[$fileName]['error'][$k];
			$up_file['size']=$_FILES[$fileName]['size'][$k];
			unset($file_data);
			$file_data['name']=$up_file['name'];
			$file_data['size']=$up_file['size'];
			$upload=new uploadFile();
			
			$type=self::$allow_attach_type;
			if($allow_type)$type=$allow_type;
			$upload->setAllowFileType($type);
			
			$path='upload/'.date('Y/m/');
			$file=$upload->upload($up_file,$rootpath.$path);
			if($file){
				$file_data['url']=$path.$file;
				$file_ary[$k]=$file_data;
			}else{
				$file_ary=false;
			}
		}
		return $file_ary;
	}
}
?>