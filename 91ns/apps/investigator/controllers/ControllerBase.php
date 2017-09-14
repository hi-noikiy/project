<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller {

    protected function initialize() {
        //$this->tag->prependTitle('91ns | 后台管理');
        $this->tag->setTitle('91ns | 后台管理');
        $this->view->setTemplateAfter("main");  //use views/layouts/main.volt
        //$this->view->cleanTemplateAfter();

        $this->userSessionInfo();
        $this->invMgrBase->checkLogin(0);

        if (!$this->request->isAjax() && $this->uid) {
            $this->view->setVar('moduleList', $this->getModule());
            $this->view->setVar('currentModule', $this->getCurrentAction());
        } 
    }

    protected function forward($uri) {
        $uriParts = explode('/', $uri);
        $params = array_slice($uriParts, 2);
        return $this->dispatcher->forward(
                        array(
                            'controller' => $uriParts[0],
                            'action' => $uriParts[1],
                            'params' => $params
                        )
        );
    }

    protected function proxyError() {
        $this->status->ajaxReturn($this->status->getCode('PROXY_ERROR'));
    }

    public static function codeReturn($data, $info = '', $code = 0) {
        $result = array();
        $result['code'] = $code;
        $result['info'] = $info;
        $result['data'] = $data;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        die;
    }

    //当前模块
    private function getCurrentAction() {
        $control = '';
        $action = '';
        $_url = $this->request->get('_url');
        $urlArr = explode("/", $_url);
        $i = 0;
        foreach ($urlArr as $val) {
            $i == 1 && $control = $val;
            $i == 2 && $action = $val;
            $i++;
        }
        !$control && $control = 'index';
        !$action && $action = 'index';
        return array($control, $action);
    }

    //模块列表
    private function getModule() {
        return $this->invMgrBase->getModule();
    }

    //后台用户信息
    protected function userSessionInfo() {

        $this->uid = $this->session->get($this->config->userSession->invUid);

        if ($this->uid != NULL) {
            $this->view->uid = $this->uid;
            $this->view->username = $this->session->get($this->config->userSession->invUsername);
        } else {
            $this->view->uid = 0;
            $this->view->username = '';
        }
    }

    //页面重定向，url地址变了
    protected function redirect($uri) {
        $this->response->redirect($uri);
    }
    
    /**
     * 导出数据为excel表格
     *@param $data    一个二维数组,结构如同从数据库查出来的数组
     *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
     *@param $filename 下载的文件名
     */
function exportexcel($data=array(),$title=array(),$filename='report'){
    	header("Content-Type: application/vnd.ms-excel;");
    	header("Content-Disposition: attachment; filename=\"" . $filename . ".xls");
    	echo '<?xml version="1.0"?>' . "\n" . '
    <?mso-application progid="Excel.Sheet"?>' . "\n" . '
    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n" . '
    xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n" . '
    xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n" . '
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n" . '
    xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
    	/*$pagenum=5000;
    	$ai=ceil(count($mdata)/$pagenum);
    	for($i=0;$i<$ai;$i++){
    		$bbb[] = array_slice($mdata, $i * $pagenum ,$pagenum);
    	}
    	foreach ($bbb as $ke=>$data){
    		echo '<Worksheet ss:Name="Sheet'.$ke.'">' . "\n" . 
    '<Table>' . "\n";
    		//导出xls 开始
    		if (!empty($title)){
    			$title_str = "<Row>\n";
    			foreach ($title as $k => $v) {
    				if(is_array($v)){
    					echo '<Column ss:Width="' . $v[1] . '"/>' . "\n";
    					$title_str .=  '<Cell><Data ss:Type="String">' .  $v[0] . '</Data></Cell>' . "\n";
    				}else{
    					$title_str .=  '<Cell><Data ss:Type="String">' .  $v . '</Data></Cell>' . "\n";
    		
    				}
    		
    			}
    			$title_str .=  "</Row>\n";
    		}
    		echo $title_str;
    		if (!empty($data)){
    			foreach($data as $key=>$val){
    				$cells = '';
    				echo "<Row>\n";
    				foreach ($val as $ck => $cv) {
    					echo  '<Cell><Data ss:Type="String">' .  $cv . '</Data></Cell>'. "\n";
    				}
    				echo  "</Row>\n";
    			}
    		}
    		echo '  </Table>' . "\n" . '
    </Worksheet>' . "\n" ;
    	}
    	echo '</Workbook>';*/
   echo '<Worksheet ss:Name="Sheet1">' . "\n" . '
    <Table>' . "\n";
    
    	//导出xls 开始
    	if (!empty($title)){
    		$title_str = "<Row>\n";
    		foreach ($title as $k => $v) {
    			if(is_array($v)){
    				echo '<Column ss:Width="' . $v[1] . '"/>' . "\n";
    				$title_str .=  '<Cell><Data ss:Type="String">' .  $v[0] . '</Data></Cell>' . "\n";
    			}else{
    				$title_str .=  '<Cell><Data ss:Type="String">' .  $v . '</Data></Cell>' . "\n";
    
    			}
    
    		}
    		$title_str .=  "</Row>\n";
    	}
    	echo $title_str;
    	if (!empty($data)){
    		foreach($data as $key=>$val){
    			$cells = '';
    			echo "<Row>\n";
    			foreach ($val as $ck => $cv) {
    				echo  '<Cell><Data ss:Type="String">' .  $cv . '</Data></Cell>'. "\n";
    			}
    			echo  "</Row>\n";
    		}
    	}
    	echo '  </Table>' . "\n" . '
    </Worksheet>' . "\n" . '
    </Workbook>';
    	exit;
    }
    

}
