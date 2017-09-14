<?php

/*
 log文件夹下目录格式:
    YYYYMMDD/LevelType/moduleType.log
 */

namespace Micro\Frameworks\Logic\Log;

use Phalcon\DI\FactoryDefault;

class LogObject{
    public $ModuleType => array(
        'SYS'       => 1,
        'DB'        => 2,
        'LOGIC'     => 3,
        'CLIENT'    => 4,
    );

    // db
    // logic
    // client

    public $LevelType = array(
        'DEBUG'     => 1,
        'INFO'      => 2,
        'NOTICE'    => 3,
        'WARNING'   => 4,
        'ERROR'     => 5,
    );

    protected $di;
    protected $config;
    protected $logArray;

    public function __construct(){
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->logArray = array();
    }

    public function initLogger() {
        $logFilePath = $this->getLogPath($moduleType, $levelType);
    }

    /* levelType
    debug
    info
    notice
    warning
    error
    critical
    alert
    emergency
    */

    public function writeLog($moduleType, $levelType, $logInfo) {
        /* 判断module，得出要将日志写到哪个文件中
         生成要写到的日志文件的路径，这里面要判断日期文件夹是否生成 */
        $logFilePath = $this->getLogPath($moduleType, $levelType);

        $logger = new \Phalcon\Logger\Adapter\File($logFilePath);

        // 写入日志内容
        switch ($levelType) {
            case $this->LevelType['DEBUG']:
                $logger->debug($logInfo);
                break;
            case $this->LevelType['INFO']:
                $logger->info($logInfo);
                break;
            case $this->LevelType['NOTICE']:
                $logger->notice($logInfo);
                break;
            case $this->LevelType['WARNING']:
                $logger->warning($logInfo);
                break;
            case $this->LevelType['ERROR']:
                $logger->error($logInfo);
                break;
            default:
                $logger->log($logInfo);
                break;
        }
    }

    private function getLogPath($moduleType, $levelType) {
        $logDir = $this->getLogDir($levelType);
        $logFile = "other";

        switch ($moduleType) {
            case $this->ModuleType['SYS']:
                $logFile = "sys";
                break;
            case $this->ModuleType['DB']:
                $logFile = "db";
                break;
            case $this->ModuleType['LOGIC']:
                $logFile = "logic";
                break;
            case $this->ModuleType['CLIENT']:
                $logFile = "client";
                break;
            default:
                break;
        }
        $logFile = $logFile.".log";
        return $logFile;
    }

    private function getLogDir($levelType) {
        $currentDay = date("Ymd");  //YYYYMMDD
        $parentDir = $this->config->directory->logsDir.'/'.$currentDay;

        $logSubPath = "other";
        switch ($levelType) {
            case $this->LevelType['DEBUG']:
                $logSubPath = "debug";
                break;
            case $this->LevelType['INFO']:
                $logSubPath = "info";
                break;
            case $this->LevelType['NOTICE']:
                $logSubPath = "notice";
                break;
            case $this->LevelType['WARNING']:
                $logSubPath = "warning";
                break;
            case $this->LevelType['ERROR']:
                $logSubPath = "error";
                break;
            
            default:
                break;
        }


        $logDir = $parentDir.'/'.$logSubPath;

        if(is_dir($parentDir)) {
            if(!is_dir($logDir)) {
                mkdir($logDir, 0777);
            }
        }
        else {
            mkdir($parentDir, 0777);
            mkdir($logDir, 0777);
        }

        return $logDir;
    }
}
