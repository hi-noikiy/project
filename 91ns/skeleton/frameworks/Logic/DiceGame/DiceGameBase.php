<?php
/**
 * Desc: 骰子游戏-基础类
 * Date: 2016/2/19
 */

namespace Micro\Frameworks\Logic\DiceGame;

use Micro\Frameworks\Logic\DiceGame\DiceGameData;
use Micro\Frameworks\Logic\DiceGame\DiceGameConfig;
use Phalcon\DI\FactoryDefault;



class DiceGameBase
{
    protected $di;
    protected $status;
    protected $config;
    protected $validator;
    protected $comm;
    protected $userAuth;
    protected $db;
    protected $normalLib;
    protected $logger;
    protected $modelsManager;



    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->comm = $this->di->get('comm');
        $this->userAuth = $this->di->get('userAuth');
        $this->db = $this->di->get('db');
        $this->normalLib = $this->di->get('normalLib');
        $this->logger = $this->di->get('logger');
        $this->modelsManager = $this->di->get('modelsManager');

    }


    /**
     * @desc 获取骰子游戏的配置信息
     * @return \Phalcon\Config
     */
    protected function getDiceConfig()
    {
        return require('DiceGameConfig.php');
    }

    /**
     * @return \Micro\Frameworks\Logic\DiceGame\DiceGameData
     */
    public function getDiceGameDataObject() {
         return new DiceGameData();
    }


    /**
     * @desc:参数检查
     * @param array $params
     * @return array
     */
    protected function validateParam($params=array()){
        if(empty($params)){
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }

        $postData=array();

        if(isset($params['roomId'])){//房间id
            $postData['roomid'] = $params['roomId'];
        }

        if(isset($params['declarerCash'])){//庄家聊币
            if($params['declarerCash']<$this->diceGameConfig->dice_game_declare_cash_limit){//检查聊币是否低于最低限制值
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
            $postData['cash'] = $params['declarerCash'];
        }

        if(isset($params['stakeType'])){//押注类型
            $stakeType=intval($params['stakeType']);
            if($stakeType<=0||$stakeType>$this->diceGameConfig->dice_game_type_num){
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
        }

        if(isset($params['stakeCash'])){//押注聊币值
            if(!in_array($params['stakeCash'],$this->diceGameConfig->stake_cash_list->toArray())){
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
        }

        if($postData){//参数验证
            $isValid = $this->validator->validate($postData);
            if (!$isValid){
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
        }

        if(isset($params['user'])){//判断用户登录
            if (!$params['user']) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }


    /**
     * @desc 日志
     * @param string $info
     */
    protected function setLog($info='') {
        $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir . '/dicegame.log');
        $logger->error($info);
    }


    /**
     * @desc 广播统一方法
     * @param string $controltype
     * @param int $roomId
     * @param array $broadData
     */
    public function broadcastPackaging($controltype='',$roomId=0,$broadData=array())
    {
        $arraySubData['controltype'] = $controltype;
        $arraySubData['data'] = $broadData;
        $this->comm->roomBroadcast($roomId, $arraySubData);
        return;
    }



}