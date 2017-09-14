<?php

function getDbType($typeid) {
    //IP地址，用户名,密码，数据库名称,端口号（默认3306，可不填）
    $arrayDbTypes = array(
        1  => array("192.168.1.111","root","","longwei",5900),
        2  => array("117.135.138.94","gameuser","rio8t89o,690.60fk","game2",3316),
        3  => array("127.0.0.1","root","root","game3"),
        4  => array("117.135.138.94","gameuser","rio8t89o,690.60fk","game2",3316),
        6  => array("192.168.1.129","root","","longwei"),
        7  => array("117.135.138.90","gameuser","rio8t89o,690.60fk","game7",3316),
        8  => array("192.168.1.100","gameuser","rio8t89o,690.60fk","game8",3316),
        12 => array("192.168.1.90","root","1","mtk"),
        13 => array("117.135.138.94","gameuser","rio8t89o,690.60fk","game13",3316),
        14 => array("117.135.138.94","root","rio8t89o,690.60fk","game14", 3316),
        70 => array("192.168.1.100","gameuser","rio8t89o,690.60fk","gameyd"),//移动测试库
        81 => array("kdbtacc.u591776.com","gameaccuser","rif,g8td,650.6uj90","account",3316),//帐号库
        87 => array("127.0.0.1","root","root","union"),//WAP网站
        'u591_local' => array("127.0.0.1","root","root","u591"),//WAP网站
        'analysis' => array("localhost","root","root","u591"),//运营服务器-统计结果保存数据库
        'pay_237' => array("210.71.245.237","root","root","u591"),//pay_log count
        'u591_new' => array("localhost","root","root","u591_new"),//运营服务器-统计结果保存数据库
        
        'analysis_ios' => array("localhost","root","root","u591_ios"),//运营服务器-统计结果保存数据库
        'gamedata' => array("localhost","root","t,i7.8fg6sh,5i","kdgamedata",'3316'),//运营服务器-4.0数据库
        'gamedata_ios' => array("localhost","root","t,i7.8fg6sh,5i","kdgamedata_ios",'3316'),//运营服务器-苹果统计数据源
        'test_old_db' => array("localhost","root","t,i7.8fg6sh,5i","test_old_db",'3316'),//旧的数据库
        'gamedata_bak' => array("localhost","root","t,i7.8fg6sh,5i","db_bak_10",'3316'),//旧的数据库
        'u591_bak' => array("localhost","root","root","u591_bak"),//运营服务器-统计结果保存数据库
        89 => array("127.0.0.1","root","root","discuz"),//TWdiscuz库

    );
    return $arrayDbTypes[$typeid];
}

function db($dbTypeId, $char='GBK'){
    if( !$dbTypeId ) {
        echo $dbTypeId;
        return false;
    }
    $dbArray = getDbType($dbTypeId);
    if (!isset($dbArray)) {
        throw new Exception('数据库连接不存在！');
    }

    $port = isset($dbArray[4]) ? intval($dbArray[4]) : 3306;
    $dsn = "mysql:dbname={$dbArray[3]};host={$dbArray[0]};port={$port}";
    $pdo = new PDO($dsn, $dbArray[1], $dbArray[2]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    if($port!==3316 && !is_numeric($dbTypeId)) {
        $pdo->exec("SET NAMES 'utf8';");
    }
    return $pdo;

}
