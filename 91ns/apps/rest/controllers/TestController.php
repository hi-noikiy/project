<?php

namespace Micro\Controllers;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Users;
class TestController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');  // use views/layouts/main.volt
        parent::initialize();
    }

    public function redisLog(){
        $result = $this->redis->subscribe(array("yipTest20150608"),array('self','subscribe_handler'));
        var_dump($result);die;
    }
    public static  function subscribe_handler($redis, $channel, $msg){
        print_r($redis);
        echo $channel;
        echo $msg;
        return true;
    }

    public function index()
    {
        return array( "Pushs" => array("fuland", "taotao", "ruirui") );
    }

    protected function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val)
        {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    public function edit()
    {
        //$file1;
        //$file2;
        //$over = false;

        /*
      $images = array();
      $thumbs = array();
      if($this->request->isPost()){
        if($this->request->hasFiles()){
            $this->movement->publish('123', 'title2', 'this is a content2', $this->request->getUploadedFiles());
        }
      }

      return 111;
      */
        //geetest验证
        if($this->geetest->checkGeetest($this->request))
        {
            echo 'success';
        }
        else
        {
            echo 'fail';
        }
    }

    public function test() {
        // ios pushAPN
        //define('DEVICETOKEN', 'fac2f97bd10e5834c149a98bac577ab966fd48224ca0a75f1591ff9294e4928e');   //pb's iphone 4s
        //define('CLIENTID', '42a1f6bf26d473b6d979fe867d7a429f');         //pb's iphone 4s

        //$ret = $this->pushserver->pushMessageToSingle(CLIENTID, "现行的房产税是第二步利改税以后开征的，1986年9月1");
        //$ret = $this->pushserver->pushAPNMessageToSingle(DEVICETOKEN, "标题弹框内容", "现行的房产税是第二步利改税以后开征的，1986年9月1");
        //var_dump($ret);

        // test Validation
        /*$number = "1a23";
        try {
            //$result = Validator::numeric()->validate($number);
            $result = Validator::numeric()->assert($number);
            var_dump($result);
        }
        catch (\InvalidArgumentException $e) {
            //var_dump($e->getFullMessage());
            var_dump($e->findMessages(array("numeric")));
        }*/
        //die;
        $this->login2();
        //echo '111';
        //echo 'test';
    }

    public function auth1()
    {
        $url = $this->oauth->createOAuth('QQ')->getAuthorizeURL('121212121212');
        header("Location:$url");
    }

    public function auth2()
    {
        $url = $this->oauth->createOAuth('Sina')->getAuthorizeURL('213');
        header("Location:$url");
    }

    public function login2()
    {
        if($this->request->isGet()){
            $code = $this->request->get('code');
            $state = $this->request->get('state');
            if($code != NULL){
                $myAuth = $this->oauth->getOAuth();
                $access = $myAuth->accessToken($state, $code);
                if($access)
                {
                    $user = $myAuth->getUserInfo();
                    var_dump($user);
                }
                exit;
            }
        }
        echo "噢噢，登陆失败鸟";
    }

    public function login()
    {
        if($this->request->isGet()){
            $code = $this->request->get('code');
            if($code != NULL){
                $ret = $this->userAuth->userLoginCallback($code);
                if ($ret['code'] == $this->status->getCode('OK')){
                    $test = $ret['data']['header'];
                    $this->redirect($test);
                }
                else{
                    echo '第三方登录失败'; //跳转到失败页
                }
            }
        }
        echo "噢噢，登陆失败鸟";
    }

    //支付宝即时支付
    public function pay()
    {
        $directPay = $this->payment->getGateway('AlipayExpress');
        $orderid = $this->uid->fguid();
        $options = array(
            'out_trade_no' => $orderid,
            'subject'      => 'test',
            'total_fee'    => '0.01',
        );

        //save orderid uid cash into db by orderid index
        $response = $directPay->purchase($options)->send();
        if($response->isRedirect()){
            $response->redirect();
        }
    }

    //支付宝网银支付
    public function pay2()
    {
        $directPay = $this->payment->getGateway('AlipayBank');
        $orderid = $this->uid->fguid();
        $bankname = '招商银行';
        $options = array(
            'out_trade_no' => $orderid,
            'subject'      => 'test',
            'total_fee'    => '0.01',
            'defaultbank'    => $this->payment->getBankCode($bankname)
        );

        //save orderid uid cash into db by orderid index
        $response = $directPay->purchase($options)->send();
        if($response->isRedirect()){
            $response->redirect();
        }
    }

    //支付宝扫码支付
    public function pay3()
    {
        $directPay = $this->payment->getGateway('AlipayQrcode');
        $orderid = $this->uid->fguid();
        $bankname = '招商银行';
        $options = array(
            'out_trade_no' => $orderid,
            'subject'      => 'test',
            'total_fee'    => '0.01'
        );

        //save orderid uid cash into db by orderid index
        $response = $directPay->purchase($options)->send();
        if($response->isRedirect()){
            $response->redirect();
        }
    }

    //支付return回调，用于客户端页面跳转
    public function verifyReturn()
    {
        if($this->request->isGet()){
            $options = array('request_params' => $this->request->getQuery());
            $complateGateway = $this->payment->getCompleteGateway('Alipay');
            $response = $complateGateway->complete($options)->send();
            if($response->isSuccessful()){
                if($response->isTradeStatusOk()){
                    echo ' tradeOK';  //成功页面跳转
                }
            }
        }
    }

    //支付notify回调，用于服务器真正的通知
    public function verifyNotify()
    {
        if($this->request->isPost()){
            $options = array('request_params' => $this->request->getPost());
            $complateGateway = $this->payment->getCompleteGateway('Alipay');
            $response = $complateGateway->complete($options)->send();

            if($response->isSuccessful()){
                if($response->isTradeStatusOk()){
                    $orderid = $this->request->getPost('out_trade_no'); //商户订单号
                    $tradeid = $this->request->getPost('trade_no'); //支付宝交易号

                    //..............逻辑处理..............
                    //get uid cash by orderid from db
                    //set user info
                    //del the record
                    //save the comsume record into db using orderid tradeid uid buyerid money cash paytime

                    echo "success"; //服务器后台逻辑处理，该值必须返回
                    exit(0);
                }
            }
        }
        echo 'fail'; //服务器后台逻辑处理，该值必须返回
    }



    public function show($slug)
    {
        /*
        require_once $this->config->miscellaneous->qrcode;
        $value="http://www.91ns.com";
        $errorCorrectionLevel = "L";
        $matrixPointSize = "4";
        \QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);
        */

        /*
        require $this->config->miscellaneous->qrcode;
        $data = 'http://www.111cn.net';
        $filename = '1111.png';
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 4;
        \QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        */


        //$content = 'Hello I am the new content';
        //$res = $this->storage->write('xxx.txt', $content);
        //$res = $this->storage->read('xxx.txt');
        //var_dump($res);


        //$this->activator->sendMail('titi539@163.com');


        /*
        $memebers = array('15005996722', '13805006343', '15980606334', '18650091497', '15080496503', '15806026996', '18750676035',
                          '15959112169', '18050281412', '18050164172', '13675027118', '13905923911', '18650468042', '18050179517',
                          '18650360720', '18305963607', '18020883110', '13600801563');



        foreach ($memebers as $key => $value) {
            $this->activator->sendSMS($value);
        }
        */

        //echo "Last modified: ".date("F d Y H:i:s.",1417358165);

        /*
        require_once $this->config->miscellaneous->oss;
        $oss_sdk_service = new \ALIOSS();
        $oss_sdk_service->set_debug_mode(FALSE);
        */

        //$list_object = $oss_sdk_service->list_object('putianmm-oss', array());
        //$get_object = $oss_sdk_service->get_object('putianmm-oss', 'bg.jpg', array());
        //$get_object_meta = $oss_sdk_service->get_object_meta('putianmm-oss', 'bg.jpg');
        //var_dump($get_object_meta->isOK());
        //print_r($get_object->isOK());

        /*
        $oss_sdk_service->get_object('putianmm-oss', 'bg.jpg', array());

        $content = "fuland";
        $upload_file_options = array(
           'content' => $content,
           'length' => strlen($content),
        );
        */

        //$response = $oss_sdk_service->upload_file_by_content('putianmm-oss', 'fuland.txt',$upload_file_options);
        //print_r($response->header['_info']['size_upload']);
        //var_dump($response->body);
        //var_dump($response->status);
        //print_r($result);

        //$is_object_exist = $oss_sdk_service->is_object_exist('putianmm-oss', 'fuland.txt');
        //$delete_object = $oss_sdk_service->delete_object('putianmm-oss', 'fuland.txt');
        //var_dump($delete_object->isOK());

        //$response_upload_file_by_file = $oss_sdk_service->upload_file_by_file('putianmm-oss', 'taotao.txt', 'E:\\xxx.txt');
        //print_r($response_upload_file_by_file);

        //$get_object_meta = $oss_sdk_service->get_object_meta('putianmm-oss', 'taotao.txt');
        //var_dump($get_object_meta->header['_info']['filetime']);

        //$result = $this->storage->delete('files/test.txt');
        //var_dump($result);

        /*
        $thumb = $this->thumbGenerator->getThumbnail('data/test.jpg', 'feed-1x');
        echo $thumb;
        */

        //$this->mongo->createCollection('mongotest');

        //$this->mongo->dropDb('yy');


        /*
        $this->mongo->insert('collectionName', [
            '_id'   => new  \MongoId("5405a4e5acff6a9f22000000"),
            'name'  =>  'Alex',
            'age'   =>  22,
            'likes' =>  ['whisky', 'gin']
        ]);
        */



        /*
        $this->mongo->where(['name' => 'Alex'])
            ->set([
                'country' => 'UK',
                'job' => 'Developer'
            ])
            ->push('likes', ['PHP', 'coffee'])
            ->update('collectionName');
        */

        /*
        $this->mongo->where(['name' => 'Alex'])
            ->delete('collectionName');
        */

        //$result = $this->mongo->select(array(), array('job'))->get('collectionName');
        //$result = $this->mongo->where('country', 'UK')->get('collectionName');

        /*
        $this->mongo->insert('test_select', array(
            'name'  =>  'Buckingham Palace',
            'latlong'   =>  array(51.501, -0.142)
        ));

        $this->mongo->addIndex('test_select', array('latlong' => '2d'));
        $result1 = $this->mongo->whereNear('latlong', array(51.501, -0.142))->get('test_select');
        $result2 = $this->mongo->whereNear('latlong', array(51.501, -0.142), 10)->get('test_select');
        $result3 = $this->mongo->whereNear('latlong', array(51.501, -0.142), 10, true)->get('test_select');
        */

        //$result = $this->mongo->unsetField('name')->update('test_select');
        //$result = $this->mongo->addToSet('likes', 'vodka')->update('test_select');
        //$result = $this->mongo->addToSet('likes', array('martini', 'champagne'))->update('test_select');

        //$result = $this->mongo->pop('likes')->update('test_select');
        //$result = $this->mongo->pull('likes', 'champagne')->update('test_select');

        /*
        $result = $this->mongo->command(array(
            'distinct'  =>  'test_select',
            'key'   =>  'age'
        ));
        */

        //$result = $this->mongo->listIndexes('test_select');


        //$result = $this->mongo->where(array('wife.name' => 'taotao'))
        //->get('test_select');

        //$lastQuery = $this->mongo->lastQuery();

        /*
        $result = $this->mongo->insert('test_select', [
            'name'  =>  'Alex',
            'age'   =>  22,
            'likes' =>  ['whisky', 'gin']
        ]);
        */

        /*
        $id = new \MongoId('54ca7b802115bb302900002e');
        $result = $this->mongo->where('_id', $id)->get('test_select');
        var_dump($result);
        */


        /*
        $result = $this->mongo->where(['name' => 'Alex'])
            ->set([
                'name' => 'taotao',
                'country' => 'UK',
                'job' => 'Developer'
            ])
            ->update('collectionName', array('upsert'=>1));
            */

        //$this->lbs->updateCoordinate('1243', 121.47, 31.27145);
        //$this->lbs->updateCoordinate('1245', 102.123, 104.4);
        //$this->lbs->getNearby('1243', 10, 2);
        //$this->lbs->getWithBox(121.44, 31.25 ,  121.5005, 31.2846 );
        //$result = $this->lbs->getWithCircle(121.44, 31.25,  10);
        //var_dump($result);

        //$point = new \GeoJson\Geometry\Point([1, 1]);
        //$json = json_encode($point);
        //var_dump($json);

        /*
        $addr = 'http://api.map.baidu.com/geoconv/v1/?coords=26.0978783776756,119.3039273818968&from=1&to=5&ak=';
        $addr2 = $addr.'EaRM9ChEzv4gCd6PnrWX4hZG';
        */


        //$param['coords'] = '119.3039273818968,26.0978783776756'; //to '119.31525070313,"y":26.100799944527'
        //$param['coords'] = '119.305386, 26.093064'; //to 119.31671816314,26.095971125752 铭豪酒店
        //$param['coords'] = '119.307791, 26.093527'; //to 119.31913862679,26.096383902846 金泉路口(温泉支路)
        //$param['coords'] = '119.308012, 26.093987'; //to 119.31936100659,26.096841663584 851大楼
        //$param['coords'] = '119.310525, 26.094136'; //to 119.32188366929,26.096951357422 温泉支路路口(六一路)

        /*
        $param['coords'] = '119.310525, 26.094136'; //to 119.32188366929,26.096951357422 温泉支路路口(六一路)
        $param['ak'] = 'EaRM9ChEzv4gCd6PnrWX4hZG';
        $addr = 'http://api.map.baidu.com/geoconv/v1/';
        $response = Requests::post($addr, array(), $param);
        var_dump($response->body);
        */


        //$this->lbs->updateCoordinate('1', 119.31671816314,26.095971125752); //铭豪酒店
        //$this->lbs->updateCoordinate('2', 119.31913862679,26.096383902846); //金泉路口(温泉支路)
        //$this->lbs->updateCoordinate('3', 119.31936100659,26.096841663584); //851大楼
        //$this->lbs->updateCoordinate('4', 119.32188366929,26.096951357422); //温泉支路路口(六一路)


        //$result = $this->lbs->getNearby([119.31671816314, 26.095971125752], 2);
        //print_r($result);

        //$result = $this->lbs->updateCoordinate('5', 119.32188366929,26.096951357422); //温泉支路路口(六一路)
        //print_r($result);

        //////////////////////////////////////////////////////////////////////////////////////////
        //new

        //$collection = $this->mongo->collection('collection_name');

        /*
        $insertIds = $collection->insert(array(
            array(
                'name' => 'John',
                'surname' => 'Doe',
                'nick' => 'The Unknown Man',
                'age' => 20,
            ),
            array(
                'name' => 'Frank',
                'surname' => 'de Jonge',
                'nick' => 'Unknown',
                'nik' => 'No Man',
                'age' => 23,
            ),
        ));
        */

        /*
        $collection->update(function ($query) {
            $query->increment('age')
                ->remove('nik')
                ->set('nick', 'FrenkyNet');
        });
        */

        //$frank = $collection->findOne(array('name' => 'Frank'));

        //var_dump($frank);


        //$ret = $this->lbs->getCoordinate('1');
        //var_dump($ret);

        //$this->activator->sendMail('luo982748666@sina.com');
        //$this->activator->sendMail('982748666@qq.com');
        //$this->activator->sendMail('420739950@qq.com');
        //$this->activator->sendMail('luo982748666@126.com');
        //$this->activator->sendMail('18050281412@163.com');
        //$this->activator->sendMail('18050281412@sohu.com');
        //$this->activator->sendMail('luo982748666@sina.com');
        //return 111;

        //$this->movement->publish('123', 'title2', 'content2', array(1,2,3));\



        //return $this->uid->fguid();

        //$this->movement->delete('123', 0);

        //print_r($this->movement->getMovements('123'));
        //return 111;


        //$img = new \Securimage();
        //$img->show();


        //return 111;
        echo '1111';
        //$is =  $this->deviceDetect->isMobile();
        //$is =  $this->deviceDetect->isTablet();
        ///var_dump($is);
        //echo $this->deviceDetect->version('Android');

    }
}