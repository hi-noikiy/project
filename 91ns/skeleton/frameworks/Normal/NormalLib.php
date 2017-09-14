<?php

/**
 * 网站公用方法
 */

namespace Micro\Frameworks\Normal;

use Phalcon\DI\FactoryDefault;

class NormalLib {

    protected $di;
    protected $config;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
    }

    /*
     * 生成二维码
     * $value:二维码内容
     * $filename:二维码存放路径
     * $logo:二维码中间的logo图片
     */

    public function getQrcode($value, $filename, $logo = '') {
        include $this->config->miscellaneous->qrcode;
        $qrcode = new \QRcode();
        $errorCorrectionLevel = 'L'; //容错级别   
        $matrixPointSize = 6; //生成图片大小   
        //生成二维码图片   
        $QR = $filename;
        $qrcode->png($value, $QR, $errorCorrectionLevel, $matrixPointSize, 2);
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR); //二维码图片宽度   
            $logo_width = imagesx($logo); //logo图片宽度   
            $logo_height = imagesy($logo); //logo图片高度   
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小   
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片 
        $newImage = $filename;
        imagepng($QR, $newImage);

        return $newImage;
    }

    /**
     * 获取配置信息
     *
     */
    public function getConfigs() {
        //读取缓存
        $cacheKey = 'consume_configs';
        $cacheResult = $this->getCache($cacheKey);
        if ($cacheResult) {
            return $cacheResult;
        }

        //座驾
        $carData = \Micro\Models\CarConfigs::find();
        $carConfigs = array();
        foreach ($carData as $key => $val) {
            $tmp['id'] = $val->id;
            $tmp['name'] = $val->name;
            $tmp['configName'] = $val->configName;
            $tmp['typeId'] = $val->typeId;
            $tmp['price'] = $val->price;
            $carConfigs[$val->id] = $tmp;
            unset($tmp);
        }
        //礼物
        $giftData = \Micro\Models\GiftConfigs::find();
        $giftConfigs = array();
        foreach ($giftData as $k => $v) {
            $tmp['id'] = $v->id;
            $tmp['name'] = $v->name;
            $tmp['configName'] = $v->configName;
            $tmp['typeId'] = $v->typeId;
            $tmp['price'] = $v->cash;
            $tmp['coin'] = $v->coin;
            $giftConfigs[$v->id] = $tmp;
            unset($tmp);
        }
        //节目
        $showConfigs = array(
            '17' => array('name' => '节目', 'configName' => 'jm', 'price' => 500),
            '18' => array('name' => '节目', 'configName' => 'jm', 'price' => 1000),
            '19' => array('name' => '节目', 'configName' => 'jm', 'price' => 2000),
            '20' => array('name' => '节目', 'configName' => 'jm', 'price' => 3000),
            '21' => array('name' => '节目', 'configName' => 'jm', 'price' => 5000)
        );

        $configs = array();
        $configs[$this->config->consumeType->buyVip] = array('1' => array('name' => '普通vip', 'configName' => '', 'price' => 30000), '2' => array('name' => '至尊vip', 'configName' => '', 'price' => 100000));
        $configs[$this->config->consumeType->buyCar] = $carConfigs;
        $configs[$this->config->consumeType->buyGuard] = array('1' => array('name' => '黄金守护', 'configName' => '', 'price' => 66666), '2' => array('name' => '白银守护', 'configName' => '', 'price' => 22222),'3' => array('name' => '铂金守护', 'configName' => '', 'price' => 99999));
        $configs[$this->config->consumeType->grabSeat] = array('0' => array('name' => '沙发', 'configName' => 'qz', 'price' => 100));
        $configs[$this->config->consumeType->sendGift] = $giftConfigs;
        $configs[$this->config->consumeType->sendRoomBroadcast] = array('1' => array('name' => '银喇叭', 'configName' => 'ylb', 'price' => 200));
        $configs[$this->config->consumeType->sendAllRoomBroadcast] = array('1' => array('name' => '金喇叭', 'configName' => 'jlb', 'price' => 500));
        $configs[$this->config->consumeType->sendStar] = array('0' => array('name' => '魅力星', 'configName' => '', 'price' => 0));
        $configs[$this->config->consumeType->giveVip] = array('1' => array('name' => '普通vip', 'configName' => '', 'price' => 30000), '2' => array('name' => '至尊vip', 'configName' => '', 'price' => 100000));
        $configs[$this->config->consumeType->giveCar] = $carConfigs;
        $configs[$this->config->consumeType->sendGiftByCoin] = $giftConfigs; //聊豆礼物配置
        $configs[$this->config->consumeType->buyShow] = $showConfigs;

        //设置缓存
        $liftTime = 3600; //有效期1小时
        $this->setCache($cacheKey, $configs, $liftTime);

        return $configs;
    }

    /*
     * 生成excel表格
     * $fileName 文件名称
     */

    public function toExcel($fileName = '', $fileData) {
        //模拟数据如下：
//        $fileData[1]['sheetName'] = 'Sheet1';
//        $fileData[1]['list'] = array(array("A1的值", "B1的值"), array("A2的值", "B2的值"), array("A3的值", "B3的值"));
//
//        $fileData[2]['sheetName'] = 'Sheet2';
//        $fileData[2]['list'] = array(array("A1的值", "B1的值"), array("A2的值", "B2的值"), array("A3的值", "B3的值"));
        //创建新的PHPExcel对象 
        $objPHPExcel = $this->di->get("phpExcel");

        $sheetIndex = 0;
        foreach ($fileData as $val) {
            //设置sheet
            $objPHPExcel->setActiveSheetIndex($sheetIndex);
            //写入数据
            $column = 1;
            $objActSheet = $objPHPExcel->getActiveSheet();
            $data = $val['list'];
            foreach ($data as $key => $rows) { //行写入         
                $span = ord("A");
                foreach ($rows as $keyName => $value) {// 列写入         
                    $j = chr($span);
                    $objActSheet->setCellValue($j . $column, $value);
                    //设置宽度
                    $objActSheet->getColumnDimension(chr($span))->setAutoSize(true);
                    $span++;
                }
                $column++;
            }
            //重命名表   
            $objPHPExcel->getActiveSheet()->setTitle($val['sheetName']);


            $sheetIndex++;
            //创建一个新的工作空间(sheet)
            $objPHPExcel->createSheet();
        }

        //设置活动单指数到第一个表,所以Excel打开这是第一个表   
        $objPHPExcel->setActiveSheetIndex(0);

        //将输出重定向到一个客户端web浏览器    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\".xlsx");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output'); //文件通过浏览器下载 
        exit;
    }

    //设置缓存
    public function setCache($key, $value, $lifetime = '') {
        if ($this->config->application->dataCache) {//开启缓存
            if (extension_loaded('memcached')) {
                $cache = $this->di->get('memcache');
                !$lifetime && $lifetime = $this->config->memcache->lifetime;
                $cache->save($key, $value, $lifetime);
                return;
            }
        }
        return;
    }

    //获取缓存
    public function getCache($key) {
        if ($this->config->application->dataCache) {//开启缓存
            if (extension_loaded('memcached')) {
                $cache = $this->di->get('memcache');
                return $cache->get($key);
            }
        }
        return;
    }

    //删除缓存
    public function delCache($key) {
        if ($this->config->application->dataCache) {//开启缓存
            if (extension_loaded('memcached')) {
                $cache = $this->di->get('memcache');
                return $cache->delete($key);
            }
        }
        return;
    }

    //获取客户端ip
    public function getip() {
        $unknown = 'unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        /*
          处理多层代理的情况
          或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
         */
        if (false !== strpos($ip, ','))
            $ip = reset(explode(',', $ip));
        return $ip;
    }

    //生成随机字符串
    public function getRandStr($num = 10, $pre = '') {
        $re = '';
        $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        while (strlen($re) < $num) {
            $re .= $s[rand(0, strlen($s) - 1)]; //从$s中随机产生一个字符 
        }
        return $pre . $re;
    }

    /*
     * * 判断是否手机浏览器
     * @return boolean
     */

    function isMobile() {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    // 获取推荐主播
    public function getHotRoom(){
        try {

            // 获取按照推荐位直播的升序排序
            $recData = \Micro\Models\Rooms::findfirst('isRecommend > 0 and liveStatus = 1 and showStatus = 1 order by isRecommend asc');

            if(!empty($recData)){
                return $recData->uid;
            }

            // 获取非推荐位的观众人数和主播等级降序
            $sql = 'select r.uid from \Micro\Models\Rooms as r left join \Micro\Models\UserProfiles as up on up.uid = r.uid '
                . ' where r.isRecommend = 0 and r.liveStatus = 1 and r.showStatus = 1 order by r.totalNum desc,up.level2 desc limit 1';

            $modelsManager = $this->di->get('modelsManager');
            $query = $modelsManager->createQuery($sql);
            $result = $query->execute();

            if($result->valid()){
                return $result->toArray()[0]['uid'];
            }

            // 获取推荐位非直播的升序排序
            $recNoLive = \Micro\Models\Rooms::findfirst('isRecommend > 0 and showStatus = 1 order by isRecommend asc');
            if(!empty($recNoLive)){
                return $recNoLive->uid;
            }

            // 随机获取
            $noLive = \Micro\Models\Rooms::findfirst('isRecommend = 0 and showStatus = 1 order by rand() asc');
            if(!empty($noLive)){
                return $noLive->uid;
            }

            return 0;
        } catch (\Exception $e) {
            $this->errLog('getHotRoom errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    // 百度短地址生成接口
    public function getBaiduShortUrl($url = 'http://www.91ns.com/rooms/hotroom'){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "http://dwz.cn/create.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = array('url'=>$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $strRes = curl_exec($ch);
        curl_close($ch);

        if(!$strRes){ //无响应
            return false;
        }
        $arrResponse = json_decode($strRes, true);

        return $arrResponse;
        if($arrResponse['status']==0)
        {
            /**错误处理*/
            echo iconv('UTF-8','GBK',$arrResponse['err_msg'])."\n";
        }
            /** tinyurl */
            var_dump($arrResponse);
            echo $arrResponse['tinyurl']."\n";
    }
    
    //根据ip获得城市
    public function getIpPlace($ip) {
        try {
            if (!$ip) {
                return;
            }
            $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
            if (empty($res)) {
                return;
            }
            $jsonMatches = array();
            preg_match('#\{.+?\}#', $res, $jsonMatches);
            if (!isset($jsonMatches[0])) {
                return;
            }
            $json = json_decode($jsonMatches[0], true);
            if (isset($json['ret']) && $json['ret'] == 1) {
                $json['ip'] = $ip;
                unset($json['ret']);
            } else {
                return;
            }
            return $json;
        } catch (\Exception $e) {
            $this->errLog('getIpPlace errorMessage = ' . $e->getMessage());
            return;
        }
    }

    //获取
    public function getRewardIdRandom(){
        $rewardConfig = $this->config->liveBagConfig;
        $list = array();
        $startNum = 1;
        $sum = 0;
        foreach ($rewardConfig as $k => $v) {
            $sum += $v['ratioNum'];
            $tmpArr = array_fill($startNum, $v['ratioNum'], $v['id']);
            $startNum += $v['ratioNum'];
            $list += $tmpArr;
        }

        $randNum = rand(1,$sum);
        $rewardId = $list[$randNum];

        return $rewardId;
        
    }

    /**
     * 获取配置信息
     *
     */
    public function getBasicConfigs($key = '') {
        //读取缓存
        $cacheKey = 'base_configs';
        $cacheResult = $this->getCache($cacheKey);
        if ($cacheResult) {
            if($key){
                return isset($cacheResult[$key]) ? $cacheResult[$key] : '';
            }
            return $cacheResult;
        }

        $baseData = \Micro\Models\BaseConfigs::find();
        $baseConfigs = array();
        if(!empty($baseData)){
            foreach ($baseData as $k => $val) {
                $baseConfigs[$val->key] = $val->value;
            }
        }
            
        //设置缓存
        $liftTime = 3600; //有效期1小时
        $this->setCache($cacheKey, $baseConfigs, $liftTime);

        if($key){
            return isset($baseConfigs[$key]) ? $baseConfigs[$key] : '';
        }

        return $baseConfigs;
    }
    
    
    //抽奖随机算法
    //$arr =array('a'=>20,'b'=>30,'c'=>50);//a奖概率20%，b奖概率30%，c奖概率50% 
    public function getProRand($arr) {
        $pro_sum = array_sum($arr);
        $rand_num = mt_rand(1, $pro_sum);
        $tmp_num = 0;
        foreach ($arr as $k => $val) {
            if ($rand_num <= $val + $tmp_num) {
                $n = $k;
                break;
            } else {
                $tmp_num+=$val;
            }
        }
        return $n;
    }


    /**
     * 获取富豪等级配置信息
     * @param int $key 富豪等级
     * @return array
     */
    public function getRicherConfigs($key = 0) {
        //读取缓存
        $cacheKey = 'richer_level_configs';
        $cacheResult = $this->getCache($cacheKey);
        if ($cacheResult) {
            if($key){
                return isset($cacheResult[$key]) ? $cacheResult[$key] : 0;
            }
            return $cacheResult;
        }

        $baseData = \Micro\Models\RicherConfigs::find();
        $baseConfigs = array();
        if(!empty($baseData)){
            foreach ($baseData as $k => $val) {
                $baseConfigs[$val->level] = $val->name;
            }
        }

        //设置缓存
        $liftTime = 86400; //有效期1day
        $this->setCache($cacheKey, $baseConfigs, $liftTime);

        if($key){
            return isset($baseConfigs[$key]) ? $baseConfigs[$key] : '';
        }

        return $baseConfigs;
    }

}

?>