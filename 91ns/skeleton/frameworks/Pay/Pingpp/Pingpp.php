<?php

namespace Micro\Frameworks\Pay\Pingpp;

use Phalcon\DI\FactoryDefault;
use Micro\Models\DeviceInfo;
class Pingpp {
	protected $di;
	protected $config;
	protected $host;
	protected $appKey;
	protected $appId;
	protected $masterSecret;
    protected $public_key_path;
    protected $status;
    protected $request;
    protected $validator;
    protected $userAuth;
    protected $modelsManager;
	public function __construct() {
        $this->di = FactoryDefault::getDefault();
		$this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->request = $this->di->get('request');
        $this->validator = $this->di->get('validator');
        $this->userAuth = $this->di->get('userAuth');
        $this->modelsManager = $this->di->get('modelsManager');
		require_once $this->config->miscellaneous->pingpp;
		$this->appKey = $this->config->pingpp->key;
        $this->appId = $this->config->pingpp->appId;
        $this->public_key_path = $this->config->pingpp->public_key_path;
    }

    public function addWxOrder($input_data, $orderNo){
        if (empty($input_data['channel']) || empty($input_data['amount'])) {
            return FALSE;
        }

        $channel = strtolower($input_data['channel']);
        $amount = $input_data['amount'];
        \Pingpp\Pingpp::setApiKey($this->appKey);
        try {
            $ch = \Pingpp\Charge::create(
                array(
                    'subject'   => '91ns平台充值',
                    'body'      => '91ns平台充值',
                    'amount'    => $amount,
                    'order_no'  => $orderNo,
                    'currency'  => 'cny',
//                    'extra'     => array('open_id' => 'Openid'),
                    'channel'   => $channel,
                    'client_ip' => $_SERVER['REMOTE_ADDR'],
                    'app'       => array('id' => $this->appId),
                )
            );

            return $ch;
        } catch (\Pingpp\Error\Base $e) {
            return $e->getMessage();
        }
    }

    public function checkOrder($data, $sign){
        return $this->verify_signature($data, $sign, $this->public_key_path);
    }

    public function verify_signature($raw_data, $signature, $pub_key_path) {
        $pub_key_contents = file_get_contents($pub_key_path);
        return openssl_verify($raw_data, base64_decode($signature), $pub_key_contents, OPENSSL_ALGO_SHA256);
    }
}