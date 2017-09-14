<?php
namespace Soap\Controller;
use Think\Controller;
class MtxController extends Controller {
    public function index(){
        // wlog(json_encode(I('request.')), 'soap');
        $request = I('request.');
        if (isset($request['wsdl'])) {
            header("Content-type:text/xml");
            echo file_get_contents(C('__UPLOAD__') . 'mtx.wsdl');
        }else{
            $server = new \SoapServer(C('__UPLOAD__') . 'mtx.wsdl', array('soap_version' => SOAP_1_2));
            $server->setClass('\Soap\Model\MtxModel');
            $server->handle();
        }

    }

    public function C()
    {
    	$disco = new \Think\SoapDiscovery('\Soap\Model\MtxModel', 'soap', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] . '/' . MODULE_NAME . '/' . CONTROLLER_NAME);
    	$text = $disco->getWSDL();
    	$myfile = fopen(C('__UPLOAD__') . 'mtx.wsdl', "w");
        chmod(C('__UPLOAD__') . 'mtx.wsdl', 0777);
    	fwrite($myfile, $text);
		fclose($myfile);
    }
}