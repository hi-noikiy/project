<?php
namespace app\war\controller;

use think\Controller;
use think\Url;

class Common extends Controller
{
    /**
     * 基础控制器初始化
     * @author zjw
     */
    public function _initialize()
    {
        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', request()->controller());
        define('ACTION_NAME', request()->action());
        url::root('/public/index.php');
        
        $box_is_pjax = $this->request->isPjax();
        $this->assign('box_is_pjax', $box_is_pjax);
        $config = new \app\index\model\Config();
        /*$countrycode = '';
        if(isset($_REQUEST['us'])) $countrycode = 'us';
        if(isset($_REQUEST['cn'])) $countrycode = 'cn';
        if(isset($_REQUEST['vn'])) $countrycode = 'vn';
        if(isset($_REQUEST['ru'])) $countrycode = 'ru';
        if($countrycode){
        	 $_SESSION['lang']=$countrycode;
        }
        $lang = $_SESSION['lang'];
        if($lang){
        	$countrycode = $lang;
        }else{
        	if(!$countrycode) $countrycode= strtolower(ip_info($ip,'countrycode'));
        	$_SESSION['lang']=$countrycode;
        }
        if(!$countrycode || $countrycode=='cn') $countrycode='';
        define('_LATER', $countrycode);
        $later = $countrycode?'_'.$countrycode:'';
        define('_LATERS', $later);
		$this->assign('lang', $countrycode);*/
        define('_LATER', 'war');
		define('_LATERS', 'war');
        $cwhere['system'] = 'war';
        $configList = $config->where($cwhere)->select();
        foreach ($configList as $val){
            if(stripos($val['key'],'qq_group') !== false){
                $qq_group[$val['key']]=$val;
            }else if(stripos($val['key'],'qq_service') !== false){
                $qq_service[$val['key']]=$val;
            }else if($val['key']=='service_phone'){
                $this->assign('service_phone', $val);
            }else if($val['key']=='service_online'){
                $this->assign('service_online', $val);
            }else if($val['key']=='baidu_bbs'){
                $this->assign('baidu_bbs', $val);
            }else if($val['key']=='qrcode_wechart'){
                $this->assign('qrcode_wechart', $val);
            }else if($val['key']=='qrcode_wb'){
                $this->assign('qrcode_wb', $val);
            }else if($val['key']=='copyright'){
                $this->assign('copyright', $val);
            }else if($val['key']=='contact_us'){
                $this->assign('contact_us', $val);
            }else if($val['key']=='fuli_url'){
                $this->assign('fuli_url', $val);
            }else if($val['key']=='lingqu_url'){
                $this->assign('lingqu_url', $val);
            }else if($val['key']=='qrcode_game_wechart'){
                $this->assign('qrcode_game_wechart', $val);
            }else if($val['key']=='privilege_code'){
                $this->assign('privilege_code', $val);
            }else if($val['key']=='link_wechart'){
                $this->assign('link_wechart', $val);
            }else if($val['key']=='link_qq'){
                $this->assign('link_qq', $val);
            }else if($val['key']=='link_wb'){
                $this->assign('link_wb', $val);
            }
        }
        // 游戏
        $game = new \app\index\model\Game();
        $where['name_en'] = 'War';
        $pokemon = $game->where($where)->find();
        $this->assign('pokemon', $pokemon);
        $this->assign('qq_group', $qq_group);
        $this->assign('qq_service', $qq_service);
    }
}