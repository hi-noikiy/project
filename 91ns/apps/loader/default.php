<?php

mb_internal_encoding("UTF-8");

use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DatabaseConnection;
use Phalcon\Mvc\Collection\Manager as CollectionManager;
use Phalcon\Mvc\Model\Manager as ModelManager;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Mvc\Model\Metadata\Memory as MemoryMetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Session\Adapter\Libmemcached as SessionDAdapter;
use Phalcon\Cache\Frontend\None as FrontendNone;
use Phalcon\Cache\Frontend\Data as FrontendData;
use Phalcon\Cache\Backend\File as FileCache;
//use Phalcon\Cache\Backend\Memcache as MemcacheCache;
use Phalcon\Cache\Backend\Libmemcached as MemcacheCache;
use League\Monga;


//注册自定义命名空间
$loader->registerNamespaces(
    array(
       // 'Micro\Controllers'         => $config->directory->controllersDir,
       // 'Micro\Models'              => $config->directory->modelsDir,    
       // 'Micro\Views'               => $config->directory->viewsDir, 
       'Micro\Frameworks'          => $config->skeleton->frameworksDir,  
       'Micro'                     => $config->skeleton->libraryDir,    
    )
)->register();

use Micro\Frameworks\Filesystem\Local;
use Micro\Frameworks\Filesystem\Oss;
use Micro\Frameworks\Filesystem\Manager;
use Micro\Frameworks\Activation\Activator;
use Micro\Frameworks\Thumbnail\Generator as ThumbnailGenerator;
use Micro\Frameworks\PushService\PushServer;
use Micro\Frameworks\Path\Generator as PathGenerator;
use Micro\Frameworks\LBS\Manager as LBSManager;
use Micro\Frameworks\Movement\Manager as MovementManager;
use Micro\Frameworks\UID\Generator as UIDGenerator;
use Micro\Frameworks\Sys\DbListener as DbListener;
use Micro\Frameworks\Geetest\Manager as GeetestManager;
use Micro\Frameworks\OAuth\OAuthFactory as OAuthFactory;

use Micro\Frameworks\Logic\Comm\Communicator as Communicator;
use Micro\Frameworks\Logic\Comm\CommConfig as CommConfig;
use Micro\Frameworks\Logic\Validation\Validator;
use Micro\Frameworks\Logic\Room\RoomModule as RoomModule;
use Micro\Frameworks\Logic\Configs\ConfigMgr as ConfigMgr;
use Micro\Frameworks\Logic\Message\MessageMgr as MessageMgr;
//use Micro\Frameworks\Logic\Version\VersionMgr as VersionMgr;
use Micro\Frameworks\Logic\User\UserMgr as UserMgr;
use Micro\Frameworks\Logic\Family\FamilyMgr as FamilyMgr;
use Micro\Frameworks\Logic\Dynamics\DynamicsMgr as dynamicsMgr;
use Micro\Frameworks\Logic\Push\PushMgr as pushMgr;
use Micro\Frameworks\Logic\Game\GameMgr as gameMgr;
use Micro\Frameworks\Logic\User\UserAuth as UserAuth;
use Micro\Frameworks\Logic\Base\BaseCode as BaseCode;
use Micro\Frameworks\Logic\Rank\Rank as Rank;
use Micro\Frameworks\Logic\Corn\Corn as Corn;
use Micro\Frameworks\Pay\PayGatewayFactory as PaymentGateway;
use Micro\Frameworks\Pay\PayPushs as PayPushs;
use Micro\Frameworks\Pay\IosPay\IosPay as IosPay;
use Micro\Frameworks\Logic\Task\TaskMgr as TaskMgr;
use Micro\Frameworks\Logic\Record\RecordMgr as RecordMgr;
use Micro\Frameworks\Logic\Sign\SignMgr as SignMgr;
use Micro\Frameworks\Pay\InnerPay\InnerPay as InnerPay;
use Micro\Frameworks\Normal\NormalLib as NormalLib;
use Micro\Frameworks\Logic\Activity\ActivityMgr as ActivityMgr;
use Micro\Frameworks\Logic\Treasure\TreasureMgr as TreasureMgr;
use Micro\Frameworks\Logic\Show\ShowMgr as ShowMgr;
use Micro\Frameworks\Logic\Group\GroupMgr as GroupMgr;
use Micro\Frameworks\Logic\DiceGame\DiceGameMgr as DiceGameMgr;
use Micro\Frameworks\Logic\AppRoom\AppRoomMgr as AppRoomMgr;

////////////////////////////
//注册services

//注册URL组件
$di->set(
    'url',
    function () use ($config) {
        $url = new UrlResolver();
        if (!$config->application->debug) {
            $url->setBaseUri($config->url->production->baseUri);
            $url->setStaticBaseUri($config->url->production->staticBaseUri);
        }
        else{
            $url->setBaseUri($config->url->development->baseUri);
            $url->setStaticBaseUri($config->url->development->staticBaseUri);
        }
        return $url;
    },
    true
);


//注册数据库Metadata
$di->set(
    'modelsMetadata',
    function () use ($config) {
        //if ($config->application->debug) {
            return new MemoryMetaDataAdapter();
        //}
        return new MetaDataAdapter(array(
            'metaDataDir' => $config->directory->cacheDir.'/metaData/'
        ));
    },
    true
);

//注册mysql管理器
$di->set(
    'modelsManager', 
    function() use ($config){
        $modelsManager = new ModelManager();
        return $modelsManager;
    }
);


//注册redis数据库
$di->set(
    'redis', 
    function() use ($config) {
        $redis = new redis();
//        $redis->connect($config->redis->host, $config->redis->port);
        $redis->pconnect($config->redis->host, $config->redis->port);
        return $redis;
    }, 
    true
);

/**
 * 注册mongo数据库
 */
$di->setShared(
    'mongo', 
    function() use ($config) {   
        $client = new MongoClient($config->mongo->host.':'.$config->mongo->port, array("connect" => TRUE));
        $connection = Monga::connection($client);
        $database = $connection->database($config->mongo->dbname);
        return $database; 
    }
);


//注册mysql数据库
$di->set(
    'db',
    function () use ($config) {
        $connection = new DatabaseConnection($config->mysql->toArray());
        //$debug = $config->application->debug;
        //if ($debug)
        {
            $eventsManager = new EventsManager();

            /*$logger = new FileLogger($config->directory->logsDir.'/db.log');
            $eventsManager->attach(
                'db',
                function ($event, $connection) use ($logger) {
                    if ($event->getType() == 'beforeQuery') {
                        $variables = $connection->getSQLVariables();
                        if ($variables) {
                            $logger->log($connection->getSQLStatement() . ' [' . join(',', $variables) . ']', \Phalcon\Logger::INFO);
                        } else {
                            $logger->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);
                        }
                    }
                }
            );*/

            $dbListener = new DbListener();
            $eventsManager->attach('db', $dbListener);

            $connection->setEventsManager($eventsManager);
        }
        return $connection;
    }
);


//注册数据库缓存
$di->set(
    'modelsCache',
    function () use ($config) {
        $frontCache = new FrontendData(array(
            "lifetime" => 86400 * 30
        ));
        return new FileCache($frontCache, array(
            "cacheDir" => APP_PATH . "/modeldata/",
            "prefix"   => "model-cache-data-"
        ));        
    }
);


//注册session模块
$di->set(
    'session',
    function () use ($config) {
        if ($config->application->debug) {
            $session = new SessionAdapter();
            $session->start();
            return $session;
        }
        
        // memcached
        $session = new SessionDAdapter(array(
            'servers' => array(
//                array('host' => '547f5a6bb03044d1.m.cnhzaliqshpub001.ocs.aliyuncs.com', 'port' => 11211, 'weight' => 1),
                array('host' => '10.162.66.46', 'port' => 11211, 'weight' => 1),
            ),
            'client' => array(
                Memcached::OPT_HASH => Memcached::HASH_MD5,
                Memcached::OPT_PREFIX_KEY => 'prefix.',
            ),
           'lifetime' => 3600,
           'prefix' => 'my_'
        ));

        $session->start();
        return $session;
    },
    true
);

//注册加密密钥
$di->set('crypt', function() {
    $crypt = new Phalcon\Crypt();
    $crypt->setKey('#1dj8$=dp?.ak//j1V$'); //Use your own key!
    return $crypt;
});

//注册cookies
$di->set('cookies', function() {
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(true);
    return $cookies;
});

/**
 * 注册安全插件，用于密码散列和防跨域请求伪造攻击
 */
$di->setShared(
    'security', 
    function(){
    $security = new Phalcon\Security();
    //Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);
    return $security;
});

/**
 * 过滤插件，'过滤输入，避免输出'的filter
 * string/email/int/alphanum/striptags/trim/lower/upper
 */
$di->setShared(
    'filter', 
    function(){
    $filter = new Phalcon\Filter();
    return $filter;
});

/**
 * 注册通讯插件
 */
$di->setShared(
    'comm', 
    function() use ($config) {
        $communicator = new Communicator();
        $communicator->init($config->charserver->nodejsserver, $config->charserver->nodejsport);
        return $communicator;
    }
);

/**
 *
 */
$di->setShared(
    'commConfig',
    function() use($config) {
        $commConfig = new CommConfig();
        return $commConfig;
    }
);

//$di->setShared(
//    'versionMgr',
//    function(){
//        $versionMgr = new VersionMgr();
//        return $versionMgr;
//    }
//);

/**
 * 注册错误处理组件
 */
$di->setShared(
    'status', 
    function(){
        $statusCode = include APP_PATH . "/apps/statuscode.php";
        return $statusCode;
    }
);

/**
 * 动态
 */
$di->setShared(
    'dynamicsMgr',
    function(){
        $dynamicsMgr = new dynamicsMgr();
        return $dynamicsMgr;
    }
);

/**
 * 推送
 */
$di->setShared(
    'pushMgr',
    function(){
        $pushMgr = new pushMgr();
        return $pushMgr;
    }
);

/**
 * 游戏
 */
$di->setShared(
    'gameMgr',
    function(){
        $gameMgr = new gameMgr();
        return $gameMgr;
    }
);

/**
 * 注册acl缓存
 */
$di->setShared(
    'aclsCache', 
    function() use ($config) {
        $frontCache = new FrontendData(array(
            "lifetime" => 86400 * 30
        ));
        
        return new FileCache($frontCache, array(
            "cacheDir" => $config->directory->cacheDir."/acldata/",
            "prefix"   => "acl-cache-data-"
        ));             
        /*
        return new MemcacheCache($frontCache, array(
            "host" => "localhost",
            "port" => "11211",
            "prefix"   => "acl-cache-data-"
        ));
        */       
    }
);

/**
 * 注册存储插件
 */
$di->setShared(
    'storage', 
    function() use ($config) {        
        if (!$config->application->debug) {
            require_once $config->miscellaneous->oss;
            $services = new \ALIOSS(); 
            $services->set_debug_mode(FALSE); 
            $adapter = new Oss($services, $config->storage->remoteDir);
        }
        else{
            $adapter = new Local($config->storage->localDir, true);
        }
        $filesystem = new Manager($adapter);
        return $filesystem;
    }
);


/**
 * 注册存储插件
 */
$di->setShared(
    'storageCdn', 
    function() use ($config) {        
        require_once $config->miscellaneous->oss;
        $services = new \ALIOSS(); 
        $services->set_debug_mode(FALSE); 
        $adapter = new Oss($services, $config->storage->remoteDir);
        $filesystem = new Manager($adapter);
        return $filesystem;
    }
);

/**
 * 账号激活器()
 */
$di->setShared(
    'activator', 
    function() use ($config) {
        return new Activator();
    }
);

/**
 * 账号激活器()
 */
$di->setShared(
    'thumbGenerator', 
    function() use ($config) {
        return new ThumbnailGenerator();
    }
);

/**
 * 验证器
 */
$di->setShared(
    'validator', 
    function() use ($config) {
        return new Validator();
    }
);

/**
 * 注册验证器缓存
 */
$di->setShared(
    'validatorsCache', 
    function() use ($config) {
        $frontCache = new FrontendData(array(
            "lifetime" => 86400 * 30
        ));
        
        return new FileCache($frontCache, array(
            "cacheDir" => $config->directory->cacheDir."/validdata/",
            "prefix"   => "valid-cache-data-"
        ));             
        /*
        return new MemcacheCache($frontCache, array(
            "host" => "localhost",
            "port" => "11211",
            "prefix"   => "acl-cache-data-"
        ));
        */       
    }
);

/**
 * 推送消息服务
 */
$di->setShared(
    'pushserver', 
    function() use ($config) {
        return new PushServer(0);
    }
);

/**
 * 推送消息服务appstore
 */
$di->setShared(
    'pushserverappstore',
    function() use ($config) {
        return new PushServer(1);
    }
);

/**
 * 定位服务
 */
$di->setShared(
    'lbs', 
    function() use ($config) {
        return new LBSManager();
    }
);


/**
 * uid生成服务
 */
$di->setShared(
    'uid', 
    function() use ($config) {
        return new UIDGenerator();
    }
);


/**
 * 个人动态服务器
 */
$di->setShared(
    'movement', 
    function() use ($config) {
        return new MovementManager();
    }
);


/**
 * 第三方登录相关
 */
$di->setShared(
    'oauth', 
    function() use ($config) {
        return new OAuthFactory();
    }
);

/**
 * 极限验证相关
 */
$di->setShared(
    'geetest', 
    function() use ($config) {
        return new GeetestManager();
    }
);

/**
 * 路径生成器
 */
$di->setShared(
    'pathGenerator', 
    function() use ($config) {
        return new PathGenerator();
    }
);


//注册log模块
$di->setShared(
    'logger',
    function () use ($config) {
        $logger = new FileLogger($config->directory->logsDir.'/logic.log');
        return $logger;
    }
);

//设备监测
$di->setShared(
    'deviceDetect',
    function () use ($config) {
        //$logger = new FileLogger($config->directory->logsDir.'/pay.log');
        //return $logger;

        $detect = new Mobile_Detect();
        return $detect;
    }
);

/**
 * 注册php excel
 */
$di->setShared(
        'phpExcel', function() {
    require_once APP_PATH . "/skeleton/frameworks/PHPExcel/Classes/PHPExcel.php";
    return new PHPExcel();
});

////////////////////////////////////////////////
//
//  以下是业务逻辑功能模块对象
//
////////////////////////////////////////////////

/**
 * 注册房间操作对象
 */
$di->setShared('roomModule', function() use ($config) {
    return new RoomModule();
});

/**
 * 注册配置管理对象
 */
$di->setShared('configMgr', function() use ($config) {
    return new ConfigMgr();
});

/**
 * 注册私信管理对象
 */
$di->setShared('messageMgr', function() use ($config) {
    return new MessageMgr();
});

/**
 * 注册家族管理对象
 */
$di->setShared('familyMgr', function() use ($config) {
    return new FamilyMgr();
});

/**
 * 用户管理对象
 */
$di->setShared('userMgr', function() use ($config) {
    return new UserMgr();
});

/**
 * 注册用户认证对象
 */
$di->setShared('userAuth', function() use ($config) {
    return new UserAuth();
});

/**
 * 基础处理程序
 */
$di->setShared('baseCode', function() use ($config) {
    return new BaseCode();
});

/**
 * 排行榜处理程序
 */
$di->setShared('rankMgr', function() use ($config) {
    return new Rank();
});

/**
 * 支付相关
 */
$di->setShared(
    'payment', 
    function() use ($config) {
        return new PaymentGateway();
    }
);

/**
 * 支付接口
 */
$di->setShared('payPushs', function() use ($config) {
    return new PayPushs();
});

/**
 * 支付接口
 */
$di->setShared('iosPay', function() use ($config) {
    return new IosPay();
});

//支付回调日志
$di->setShared(
    'payLogs',
    function () use ($config) {
        $logger = new FileLogger($config->directory->logsDir.'/pay.log');
        return $logger;
    }
);

/**
 * 推广支付
 */
$di->setShared('innerPay', function() use ($config) {
    return new InnerPay();
});

/**
 * 计划任务处理
 */
$di->setShared('cornMgr', function() use ($config) {
    return new Corn();
});

/**
 * 任务接口
 */
$di->setShared('taskMgr', function() use ($config) {
    return new TaskMgr();
});

/**
 * 记录管理模块（水军聊天记录、用户操作记录等）
 */
$di->setShared('recordMgr', function() use($config) {
    return new RecordMgr();
});

/**
 * 签到模块
 */
$di->setShared('signMgr', function() use ($config) {
    return new SignMgr();
});

/**
 * 公用方法
 */
$di->setShared('normalLib', function() {
    return new NormalLib();
});

/**
 * memcache缓存
 */
$di->set(
    'memcache',
    function() use ($config) {
        $frontCache = new FrontendData(array(
            "lifetime" => $config->memcache->lifetime//默认缓存时间
        ));

        /*return new MemcacheCache($frontCache, array(
            "host" => $config->memcache->host,
            "port" => $config->memcache->port,
            "prefix"=>$config->memcache->prefix,
         ));
*/
        return new MemcacheCache($frontCache,array(
            'servers' => array(
                array('host' => $config->memcache->host, 'port' => $config->memcache->port, 'weight' => 1),
            ),
//            'client' => array(
//                Memcached::OPT_HASH => Memcached::HASH_MD5,
//                Memcached::OPT_PREFIX_KEY => $config->memcache->prefix,
//            ),
         ));
      }
);
/**
 * 各类活动
 */
$di->setShared('activityMgr', function() {
    return new ActivityMgr();
});

/**
 * 一元夺宝
 */
$di->setShared('TreasureMgr', function() {
    return new TreasureMgr();
});

/**
 * 节目
 */
$di->setShared('ShowMgr', function() {
    return new ShowMgr();
});


/**
 * 军团管理
 */
$di->setShared('groupMgr', function() {
    return new GroupMgr();
});
/**
 * 骰宝游戏
 */
$di->setShared('diceGameMgr', function() {
    return new DiceGameMgr();
});

/**
 * app直播间
 */
$di->setShared('appRoomMgr', function() {
    return new AppRoomMgr();
});