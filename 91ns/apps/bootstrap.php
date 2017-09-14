<?php

final class Bootstrap
{
    protected $config      = null;
    protected $di          = null;
    protected $application = null;
    protected $loader      = null;
    
    public function __construct()
    {
        define("APP_PATH", dirname(__DIR__));
    }

    protected function initConf($confPath)
    {
        //加载配置
        $this->config = new \Phalcon\Config(require_once($confPath));

        //加载基于composer的第三方库
        require_once APP_PATH."/vendor/autoload.php";
    }
    
    public function exeFrontend()
    {
        try{
            $this->loader = new \Phalcon\Loader();
            $this->di = new \Phalcon\DI\FactoryDefault();

            //加载配置
            $this->initConf(APP_PATH.'/apps/config/default-frontend.php');

            $this->application = new \Phalcon\Mvc\Application();
            $this->application->setDI($this->di);

            //注册命名空间及服务
            $this->load(APP_PATH.'/apps/loader/default-frontend.php');

            //保证后面能拿到
            $this->di->setShared('bootstrap', $this);     
            $this->di->setShared('config', $this->config);       

            //加载默认的时区，解决时间不对的情况
            date_default_timezone_set('Asia/Shanghai');             
            echo $this->application->handle()->getContent();
        }
        catch (Exception $e) {
            $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir.'/error.log');
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }       
    }

    public function exeBackend()
    {
        try{
            $this->loader = new \Phalcon\Loader();
            $this->di = new \Phalcon\DI\FactoryDefault();

            //加载配置
            $this->initConf(APP_PATH.'/apps/config/default-backend.php');

            $this->application = new \Phalcon\Mvc\Application();
            $this->application->setDI($this->di);

            //注册命名空间及服务
            $this->load(APP_PATH.'/apps/loader/default-backend.php');

            //保证后面能拿到
            $this->di->setShared('bootstrap', $this);     
            $this->di->setShared('config', $this->config);       

            //加载默认的时区，解决时间不对的情况
            date_default_timezone_set('Asia/Shanghai');             
            echo $this->application->handle()->getContent();
        }
        catch (Exception $e) {
            $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir.'/error.log');
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }
    }

    public function execRest()
    {        
        try{
            $this->loader = new \Phalcon\Loader();
            $this->di = new \Phalcon\DI\FactoryDefault();

            //加载配置
            $this->initConf(APP_PATH.'/apps/config/default-rest.php');

            $this->application = new \Phalcon\Mvc\Micro();
            $this->application->setDI($this->di);

            //注册命名空间及服务
            $this->load(APP_PATH.'/apps/loader/default-rest.php');

            //保证后面能拿到
            $this->di->setShared('bootstrap', $this);     
            $this->di->setShared('config', $this->config);       

            //加载默认的时区，解决时间不对的情况
            date_default_timezone_set('Asia/Shanghai');             
            $this->application->handle();
        }
        catch (Exception $e) {
            $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir.'/error.log');
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }
    }

    public function execInvestigator()
    {        
        try{
            $this->loader = new \Phalcon\Loader();
            $this->di = new \Phalcon\DI\FactoryDefault();

            //加载配置
            $this->initConf(APP_PATH.'/apps/config/default-investigator.php');

            $this->application = new \Phalcon\Mvc\Application();
            $this->application->setDI($this->di);

            //注册命名空间及服务
            $this->load(APP_PATH.'/apps/loader/default-investigator.php');

            //保证后面能拿到
            $this->di->setShared('bootstrap', $this);     
            $this->di->setShared('config', $this->config);       

            //加载默认的时区，解决时间不对的情况
            date_default_timezone_set('Asia/Shanghai');             
            echo $this->application->handle()->getContent();
        }
        catch (Exception $e) {
            $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir.'/error.log');
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }
    }

    public function execInvestigator2()
    {        
        try{
            $this->loader = new \Phalcon\Loader();
            $this->di = new \Phalcon\DI\FactoryDefault();

            //加载配置
            $this->initConf(APP_PATH.'/apps/config/default-investigator2.php');

            $this->application = new \Phalcon\Mvc\Application();
            $this->application->setDI($this->di);

            //注册命名空间及服务
            $this->load(APP_PATH.'/apps/loader/default-investigator2.php');

            //保证后面能拿到
            $this->di->setShared('bootstrap', $this);     
            $this->di->setShared('config', $this->config);       

            //加载默认的时区，解决时间不对的情况
            date_default_timezone_set('Asia/Shanghai');             
            echo $this->application->handle()->getContent();
        }
        catch (Exception $e) {
            $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir.'/error.log');
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }
    }

    public function exeFrontend2()
    {
        try{
            $this->loader = new \Phalcon\Loader();
            $this->di = new \Phalcon\DI\FactoryDefault();

            //加载配置
            $this->initConf(APP_PATH.'/apps/config/default-frontend2.php');

            $this->application = new \Phalcon\Mvc\Application();
            $this->application->setDI($this->di);

            //注册命名空间及服务
            $this->load(APP_PATH.'/apps/loader/default-frontend2.php');

            //保证后面能拿到
            $this->di->setShared('bootstrap', $this);
            $this->di->setShared('config', $this->config);

            //加载默认的时区，解决时间不对的情况
            date_default_timezone_set('Asia/Shanghai');
            echo $this->application->handle()->getContent();
        }
        catch (Exception $e) {
            $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir.'/error.log');
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }
    }

    public function exeMobile()
    {
        try{
            $this->loader = new \Phalcon\Loader();
            $this->di = new \Phalcon\DI\FactoryDefault();

            //加载配置
            $this->initConf(APP_PATH.'/apps/config/default-mobile.php');

            $this->application = new \Phalcon\Mvc\Application();
            $this->application->setDI($this->di);

            //注册命名空间及服务
            $this->load(APP_PATH.'/apps/loader/default-mobile.php');

            //保证后面能拿到
            $this->di->setShared('bootstrap', $this);
            $this->di->setShared('config', $this->config);

            //加载默认的时区，解决时间不对的情况
            date_default_timezone_set('Asia/Shanghai');
            echo $this->application->handle()->getContent();
        }
        catch (Exception $e) {
            $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir.'/error.log');
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }
    }

    public function load($file)
    {
        $loader      = $this->loader;
        $config      = $this->config;
        $application = $this->application;
        $di          = $this->di;
        return require $file;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getApplication()
    {
        return $this->application;
    }
}
