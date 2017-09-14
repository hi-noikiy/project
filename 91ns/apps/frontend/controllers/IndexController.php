<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class IndexController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '首页';
            $this->view->ns_name = '91ns';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        //渠道来源
        $log = new \Micro\Frameworks\Logic\Base\BaseStatistics();
        $source = $log->getSource();
        if ($source != NULL) {
            $this->view->ns_source = $source;
        }
    }

    public function testAction() {
        if (!empty($_REQUEST['act'])) {
            $telphone = $_REQUEST['telphone'];
            $content = "您的验证码是：222222, 有效期是10分钟。";
            $data['mobilePhoneNumber'] = $telphone;
            $data['content'] = $content;
            $url = 'https://api.bmob.cn/1/requestSms';
            $id = 'dbbe69b492b1d71e500aa421d4050cb9';
            $key = 'dc9ca841d0a218953d84b9b4f90765e0';
            $header = array(
                'X-Bmob-Application-Id:' . $id,
                'Content-Type:' . 'application/json',
                'X-Bmob-REST-API-Key:' . $key,
            );
            $data=  json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
            print_R($result);
            echo "<br/>ok.thank you.you can close it now.";
            exit;
        }
        $str = "<html><form action=''>"
                . "it is just a test<br/><br/>telphone:<input name='telphone' type='text'size=10/>"
                . "<br/><br/><input type='hidden' name='act' value='1'><input type='submit'></html>";
        echo $str;
        exit;
    }

}