<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-10-13
 * Time: 下午9:26
 */
include 'path.php';
$db_source = db('gamedata');
$month = date('m', strtotime('-1 month'));
$daytime = date('1ym01', $_SERVER['REQUEST_TIME']);
$sql_list = array();
$sql_list['dayonline']['table_create'] = <<<SQL
CREATE TABLE `dayonline_$month` (
  `userid` int(4) unsigned NOT NULL default '0',
  `accountid` int(4) unsigned NOT NULL default '0',
  `online` int(4) unsigned NOT NULL default '0',
  `serverid` int(4) unsigned NOT NULL default '0',
  `daytime` int(4) unsigned NOT NULL default '0',
  `viplev` tinyint(1) NOT NULL default '0',
  `fenbaoid` int(4) unsigned NOT NULL default '0',
  `lev` smallint(4) unsigned NOT NULL default '0',
  `createtime` int(4) unsigned NOT NULL default '0',
  `total_rmb` int(4) unsigned NOT NULL default '0',
  `active` int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`,`serverid`,`daytime`),
  KEY `idx_accountid` (`accountid`),
  KEY `idx_lev` (`lev`)
) TYPE=MyISAM;
SQL;
$sql_list['dayonline']['table_insert'] = <<<SQL
insert into dayonline_$month select * from dayonline where daytime<$daytime
SQL;
$sql_list['dayonline']['table_clear'] = <<<SQL
delete from dayonline where daytime<$daytime
SQL;

$sql_list['give_emoney']['table_create'] = <<<SQL
CREATE TABLE `give_emoney` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `idUser` int(4) unsigned NOT NULL default '0',
  `serverid` int(4) unsigned NOT NULL default '0',
  `daytime` int(4) unsigned NOT NULL default '0',
  `emoney` int(4) unsigned NOT NULL default '0',
  `fenbaoid` int(4) unsigned NOT NULL default '0',
  `userlev` smallint(2) unsigned NOT NULL default '0',
  `type` int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_daytime_type` (`daytime`,`type`),
  KEY `idx_serverid_userid` (`serverid`,`idUser`)
) TYPE=MyISAM;
SQL;
$sql_list['dayonline']['table_insert'] = <<<SQL
insert into give_emoney_$month select * from give_emoney where daytime<$daytime
SQL;
$sql_list['dayonline']['table_clear'] = <<<SQL
delete from give_emoney where daytime<$daytime
SQL;

foreach($sql_list as $table=>$schemas) {
    foreach($schemas as $schema) {
        $stmt = $db_source->prepare($del);
        $stmt->execute();
    }
}