<?php
class tpl extends Smarty {
	function __construct(){
		global $rootpath,$rooturl;
		$this->template_dir = $rootpath . "/template/";
		$this->compile_dir = $rootpath . "/cache/templates/";
		$this->config_dir = $rootpath . "/configs/";
		$this->cache_dir = $rootpath . "/cache/";
		$this->left_delimiter = '{{';
		$this->right_delimiter = '}}'; 
		$this->assign('rooturl',$rooturl);
		$this->assign('roottpl',$rooturl.'template/');
		$this->assign('now',date('Y-m-d H:i'));
	}
}
?>