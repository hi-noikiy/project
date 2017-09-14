<?php

namespace Micro\Frameworks\Logic\Comm;

use Phalcon\DI\FactoryDefault;

class CommConfig {
    protected $di;
    protected $comm;
    protected $status;
    protected $baseCode;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->comm = $this->di->get('comm');
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->baseCode = $this->di->get('baseCode');
    }

    ///////////////////////////////////////////////////////////////////////////
    //
    // 内部函数
    //
    ///////////////////////////////////////////////////////////////////////////

    private function getAllRobotConfig() {
        $robotVersion = $this->config->robotVersion == '0.0.2'? 'newRobotConfig':'robotConfig';
        $chatServerResult = $this->comm->getRoomConfig();
        if ($chatServerResult == false) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        // 这里需要修改，等新的接口过来
        // $errorCode = $chatServerResult['code'];
        // if ($errorCode != 0)    //非0表示失败
        // {
        //     return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($chatServerResult));
        // }
        $resultArray = $chatServerResult;
        if (!array_key_exists($robotVersion, $resultArray)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        $result = $resultArray[$robotVersion];
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    private function setRobotConfig($jsonData) {
        $chatServerResult = $this->comm->setRobotConfig(json_encode($jsonData));
        if ($chatServerResult == false) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        // 这里需要修改，等新的接口过来
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    private function _getCallbackConfig() {
        $chatServerResult = $this->comm->getRoomConfig();
        if ($chatServerResult == false) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        // 这里需要修改，等新的接口过来
        // $errorCode = $chatServerResult['code'];
        // if ($errorCode != 0)    //非0表示失败
        // {
        //     return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($chatServerResult));
        // }
        $resultArray = $chatServerResult;
        if (!array_key_exists('callbackConfig', $resultArray)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        $result = $resultArray['callbackConfig'];
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    private function setCallbackConfig($jsonData) {
        $chatServerResult = $this->comm->setCallbackConfig(json_encode($jsonData));
        if ($chatServerResult == false) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        // 这里需要修改，等新的接口过来
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    ///////////////////////////////////////////////////////////////////////////
    //
    // 对外接口
    //
    ///////////////////////////////////////////////////////////////////////////

    public function getRobotRule() {
        $resultData = $this->getAllRobotConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        $robotConfig = $resultData['data'];
        $increaseArray = array();
        $reduceArray = array();

        if (array_key_exists('increase', $robotConfig)) {
            $increase = $robotConfig['increase'];
            if (is_array($increase)) {
                for ($i= 0; $i<count($increase); $i++){
                    $data['minCount'] = $increase[$i]['minCount'];
                    $data['maxCount'] = $increase[$i]['maxCount'];
                    $data['unitPerUser'] = $increase[$i]['unitPerUser'];
                    $data['unitPerTimes'] = $increase[$i]['unitPerTimes'];
                    array_push($increaseArray, $data);
                }
            }
        }

        if (array_key_exists('reduce', $robotConfig)) {
            $reduce = $robotConfig['reduce'];
            if (is_array($reduce)) {
                if($this->config->robotVersion == '0.0.2'){
                    if (array_key_exists('unitPerUser', $reduce)) {
                        $reduceArray['unitPerUser'] = $reduce['unitPerUser'];
                    }
                    if (array_key_exists('timeInterval', $reduce)) {
                        $reduceArray['timeInterval'] = $reduce['timeInterval'];
                    }
                }else{
                    if (array_key_exists('percentPerTimes', $reduce)) {
                        $reduceArray['percentPerTimes'] = $reduce['percentPerTimes'];
                    }
                    if (array_key_exists('percent', $reduce)) {
                        $reduceArray['percent'] = $reduce['percent'];
                    }
                    if (array_key_exists('waitTime', $reduce)) {
                        $reduceArray['waitTime'] = $reduce['waitTime'];
                    }
                }
                // $reduceArray['percentPerTimes'] = $reduce['percentPerTimes'];
                // $reduceArray['percent'] = $reduce['percent'];
                // $reduceArray['waitTime'] = $reduce['waitTime'];
            }
        }

        $result['increase'] = $increaseArray;
        $result['reduce'] = $reduceArray;

        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    //正在直播的房间加机器人的规则
    /*private function getLiveRoomRobotRule() {
        $resultData = $this->getAllRobotConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        $data = $resultData['data']['increase'];
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }*/

    public function addLiveRoomRobotRule($jsonData) {
        if (!is_array($jsonData)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        $resultData = $this->getAllRobotConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!array_key_exists('increase', $resultData['data'])) {
            $resultData['data']['increase'] = array();
        }

        if (!is_array($resultData['data']['increase'])) {
            $resultData['data']['increase'] = array();
        }

        array_push($resultData['data']['increase'], $jsonData);
        return $this->setRobotConfig($resultData['data']);
    }

    // $index : 从0开始
    public function editLiveRoomRobotRule($index, $jsonData) {
        if (!is_array($jsonData)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        $resultData = $this->getAllRobotConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!array_key_exists('increase', $resultData['data'])) {
            $resultData['data']['increase'] = array();
        }

        if (!is_array($resultData['data']['increase'])) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        $count = count($resultData['data']['increase']);
        if ($count <= $index) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        $resultData['data']['increase'][$index] = $jsonData;
        return $this->setRobotConfig($resultData['data']);
    }

    // $index : 从0开始
    public function delLiveRoomRobotRule($index) {
        $resultData = $this->getAllRobotConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!array_key_exists('increase', $resultData['data'])) {
            $resultData['data']['increase'] = array();
        }

        if (!is_array($resultData['data']['increase'])) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        $count = count($resultData['data']['increase']);
        if ($count <= $index) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }

        array_splice($resultData['data']['increase'], $index, 1);
        return $this->setRobotConfig($resultData['data']);
    }

    //直播房间机器人退出的规则
    /*private function getRobotOutRule() {

    }*/

    // public function addRobotOutRule() {

    // }

    // type = 1 waitTime; type = 2; percent; type = 3 percentPerTimes
    public function editRobotOutRule($type, $data) {
        if (!is_numeric($data)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
        }
        if($this->config->robotVersion != '0.0.2'){
            $key = "";
            switch ($type) {
                case 1:
                    $key = "waitTime";
                    break;
                case 2:
                    $key = "percent";
                    break;
                case 3:
                    $key = "percentPerTimes";
                    break;
                default:
                    return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
            }
        }

        $resultData = $this->getAllRobotConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!array_key_exists('reduce', $resultData['data'])) {
            $resultData['data']['reduce'] = array();
        }

        if (!is_array($resultData['data']['reduce'])) {
            $resultData['data']['reduce'] = array();
        }

        if($this->config->robotVersion == '0.0.2'){
            $resultData['data']['reduce'][$type] = $data;
        }else{
            $resultData['data']['reduce'][$key] = $data;
        }
        return $this->setRobotConfig($resultData['data']);
    }

    public function delRobotOutRule($type) {
        if($this->config->robotVersion != '0.0.2'){
            $key = "";
            switch ($type) {
                case 1:
                    $key = "waitTime";
                    break;
                case 2:
                    $key = "percent";
                    break;
                case 3:
                    $key = "percentPerTimes";
                    break;
                default:
                    return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
            }
        }

        $resultData = $this->getAllRobotConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!array_key_exists('reduce', $resultData['data'])) {
            $resultData['data']['reduce'] = array();
        }

        if (!is_array($resultData['data']['reduce'])) {
            $resultData['data']['reduce'] = array();
        }

        if($this->config->robotVersion == '0.0.2'){
            if (!array_key_exists($type, $resultData['data']['reduce'])) {
                return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
            }

            unset($resultData['data']['reduce'][$type]);
        }else{
            if (!array_key_exists($key, $resultData['data']['reduce'])) {
                return $this->status->retFromFramework($this->status->getCode('DATA_FROMATE_ERROR'));
            }

            unset($resultData['data']['reduce'][$key]);
        }
        return $this->setRobotConfig($resultData['data']);
    }

    // 增减房间里面机器人的个数
    public function changeRobotCount($roomId, $count, $time) {
        $chatServerResult = $this->comm->changeRobotCount($roomId, $count, $time);
        if ($chatServerResult == false) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        $errorCode = $chatServerResult['code'];
        if ($errorCode != 0) {
            return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($chatServerResult));
        }

        // 这里需要修改，等新的接口过来
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    public function getCallbackConfig() {
        $resultData = $this->_getCallbackConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!is_array($resultData['data'])) {
            $resultData['data'] = array();
        }

        $result['url'] = $resultData['data']['url'];
        $result['enabled'] = $resultData['data']['enabled'];

        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    // 设置回调地址
    public function setCallbackUrl($url) {
        $resultData = $this->_getCallbackConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!is_array($resultData['data'])) {
            $resultData['data'] = array();
        }

        $resultData['data']['url'] = $url;
        return $this->setCallbackConfig($resultData['data']);
    }

    //设置是否开启回调
    public function setCallbackEnable($enabled) {
        $resultData = $this->_getCallbackConfig();
        if ($resultData['code'] != 0) {
            return $resultData;
        }

        if (!is_array($resultData['data'])) {
            $resultData['data'] = array();
        }

        $resultData['data']['enabled'] = $enabled;
        return $this->setCallbackConfig($resultData['data']);
    }
}