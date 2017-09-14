<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController{
	public $gameId = 8;
	public $title;
	public $keyword;
	public $desc;
	public $cate;
    public $appKey = '0dbddcc74ed6e1a3c3b9708ec32d0532';
    public $accountInfo;
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	//获取session
	public function getSession($name){
		return Yii::app()->session[$name];
	}
	//set session
    public function setSession($name, $val){
        Yii::app()->session[$name] = $val;
    }

	public function init(){
        $this->layout = false;//'dwz';
		$category = new Category;
		$this->cate = $category->getCategoryInfo($this->gameId);
        $this->accountInfo = $this->getSession('accountInfo');

		//var_dump(CJSON::encode($this->cate));
	}

    protected function httpBuidQuery($array, $appKey){
        if (!is_array($array))
            return false;
        if (!$appKey) return false;
        ksort($array);
        $md5Str = http_build_query($array);
        $mySign = md5(urldecode($md5Str) . $appKey);
        return $mySign;
    }
    //curl发送请求
    protected function https_post($url, $data, $i = 0){
        $i++;
        $str = '';
        if ($data) {
            foreach ($data as $key => $value) {
                $str .= $key . "=" . $value . "&";
            }
        }
        $curl = curl_init($url); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if ($str) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $str); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
        // curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($curl);
        $Err = curl_error($curl);
        if (false === $tmpInfo || !empty($Err)) {
            if ($i == 1)
                return $this->https_post($url, $data, $i);
            curl_close($curl);
            return $Err;
        }
        curl_close($curl);
        return $tmpInfo;
    }

    protected function getServer(){
        $url = "http://gunweb.u591.com:83/interface/website/getServer.php";
        $array = array();
        $array['game_id'] = $this->gameId;
        $mySign = $this->httpBuidQuery($array, $this->appKey);
        $array['sign'] = $mySign;
        $result = $this->https_post($url, $array);
        $resultArr = json_decode($result, true);
        if(isset($resultArr['status']) && $resultArr['status'] == 0)
            return $resultArr['data'];
        return false;
    }
}