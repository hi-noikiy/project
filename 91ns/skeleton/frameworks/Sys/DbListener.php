<?php

namespace Micro\Frameworks\Sys;

use Phalcon\DI\FactoryDefault;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Db\Profiler;

class DbListener
{
    protected $_profiler;
    protected $_logger;
    protected $_profile;

    /**
     * Creates the profiler and starts the logging
     */
    public function __construct()
    {
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');
        $this->_profiler = new Profiler();
        $this->_logger = new FileLogger($config->directory->logsDir.'/db.log');

        $this->_profile = false;
    }

    /**
     * This is executed if the event triggered is 'beforeQuery'
     */
    public function beforeQuery($event, $connection)
    {
        if ($this->_profile) {
            $this->_profiler->startProfile($connection->getSQLStatement());
        }
    }

    /**
     * This is executed if the event triggered is 'afterQuery'
     */
    public function afterQuery($event, $connection)
    {
        $sql = $connection->getSQLStatement();
        if (strpos($sql, 'SELECT') === false) {
            if (strpos($sql, 'DESCRIBE') === false) {
                $variables = $connection->getSQLVariables();
                if ($variables) {
                    $this->_logger->log($sql . ' [' . join(',', $variables) . ']', \Phalcon\Logger::INFO);
                } else {
                    $this->_logger->log($sql, \Phalcon\Logger::INFO);
                }
            }
        }

        if ($this->_profile) {
            $this->_profiler->stopProfile();
            
            foreach ($this->getProfiler()->getProfiles() as $profile) {
                $this->_logger->log("SQL Statement: ".$profile->getSQLStatement(), \Phalcon\Logger::INFO);
                $this->_logger->log("Start Time: ".$profile->getInitialTime(), \Phalcon\Logger::INFO);
                $this->_logger->log("Final Time: ".$profile->getFinalTime(), \Phalcon\Logger::INFO);
                $this->_logger->log("Total Elapsed Time: ".$profile->getTotalElapsedSeconds(), \Phalcon\Logger::INFO);
            }
        }
    }

    public function getProfiler()
    {
        return $this->_profiler;
    }
}
