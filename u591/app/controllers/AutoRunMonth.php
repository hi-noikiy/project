<?php
/**
 * Created by PhpStorm.
 * User: fusha
 * Date: 16-2-29
 * Time: 下午10:07
 *
 * 每日凌晨自动统计程序
 */
set_time_limit(3000);
ini_set('memory_limit', '1024M');
ini_set('display_errors', 'On');
class AutoRunMonth extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

    }


    /**
     * 定时删除表
     */
    function deltable(){
    	$dbsdk = $this->load->database('rootsdk', true);
    	$Ym = date('Ym',strtotime(" -2 month"));
    	$t = date("t",strtotime(" -2 month")); //下月末
    	for($i = 1;$i<=$t;$i++){
    		$sql = "drop table `u_behavior_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `item_trading_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `u_login_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `u_register_process_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `game_user_eudemon_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `game_world_eudemon_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `game_world_user_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `game_process_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    		$sql = "drop table `game_user_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`";
    		$dbsdk->query($sql);
    	}
    	$sql = "drop table `intimacy_".$Ym."`";
    	$dbsdk->query($sql);
    	$sql = "drop table `ad_login_".$Ym."`";
    	$dbsdk->query($sql);
    	unset($dbsdk);
    }
    
    /*
     *  删除 client_bug 等table
     */
    function delsometable(){
    	$dbsdk = $this->load->database('rootsdk', true);    	 
    	$sql = "drop table client_bug";
    	$dbsdk->query($sql);    
    	unset($dbsdk);
    }
    
    /**
     * 每分钟查询时间段内数据
     */
    function warninfo(){
    	$dbsdk = $this->load->database('sdk', true);
    	$sql = "select type,info,count(*) cid from `warninfo` where created_at>".(time()-60).' group by type';
    	$query = $dbsdk->query($sql);
    	$data = array();
		if($query) $data = $query->result_array();
		$game_id="8";
		//$phone="15080458491,13950399115,18605088096,18750127008";
		$phone="15080458491";
		//$phone="15059449082";
		$hainiu_key="0dbddcc74ed6e1a3c3b9708ec32d0532";
		$code="game_id={$game_id}&phone={$phone}{$hainiu_key}";
		$sign=md5($code);
		foreach ($data as $v){
			if($v['cid'] > 1){
				parent::BetterLog('warn',json_encode($v));
				$content = $v['info'];
				/*$result = file_get_contents("http://gunweb.u591.com:83/interface/duanxin/send_content.php?phone=$phone&game_id=$game_id&sign=$sign&content=$content");
				parent::BetterLog('warn',json_encode($result));*/
			}
		}
    }
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run_test()
    {
    	$dbsdk = $this->load->database('sdk', true);
    	$Ym = date('Ym');
    	$t = date("t");
    	/*$Ym = date('Ym',strtotime(" +1 month"));
    	$t = date("t",strtotime(" +1 month")); //下月末*/
    	
    //段位分析
    	for($i = 1;$i<=$t;$i++){
    		$sql = "CREATE TABLE `game_world_user_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    		$sql = "CREATE TABLE `game_world_eudemon_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
    		`playerid` bigint(20) NOT NULL COMMENT '玩家id',
    		`eud` int(11) NOT NULL COMMENT '精灵模板编号',
    		`ex1` int(11) NOT NULL COMMENT '精灵个体总值',
    		`ex2` int(11) NOT NULL COMMENT '精灵努力总值',
    		`intilv` int(11) NOT NULL COMMENT '亲密等级',
    		`booklv` int(11) NOT NULL COMMENT '图鉴等级',
    		`serverid` int(11) NOT NULL,
    		PRIMARY KEY (`id`),
    		KEY `idx_no` (`playerid`,`serverid`) USING BTREE
    		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    }
    
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php AutoRunMonth run
     */
    public function run()
    {
        $dbsdk = $this->load->database('sdk', true);
    	$Ym = date('Ym',strtotime(" +1 month"));
    	$t = date("t",strtotime(" +1 month")); //下月末
    	//活跃玩家数据
    	
    	$sql = "CREATE TABLE `game_adventure_leve_$Ym` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adventurelev` int(11) NOT NULL,
  `active_tasknum` int(11) NOT NULL,
  `rank1_num` int(11) NOT NULL,
  `rank2_num` int(11) NOT NULL,
  `rank3_num` int(11) NOT NULL,
  `rank4_num` int(11) NOT NULL,
  `rank5_num` int(11) NOT NULL,
  `rank6_num` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    	$dbsdk->query($sql);
    	echo json_encode($dbsdk->error());
    	$sql = "CREATE TABLE `game_currency_$Ym` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `currency1` int(11) unsigned NOT NULL DEFAULT '0',
  `currency2` int(11) unsigned NOT NULL DEFAULT '0',
  `currency3` int(11) unsigned NOT NULL DEFAULT '0',
  `currency4` int(11) unsigned NOT NULL DEFAULT '0',
  `currency5` int(11) unsigned NOT NULL DEFAULT '0',
  `currency6` int(11) unsigned NOT NULL DEFAULT '0',
  `currency7` int(11) unsigned NOT NULL DEFAULT '0',
  `currency8` int(11) unsigned NOT NULL DEFAULT '0',
  `currency9` int(11) unsigned NOT NULL DEFAULT '0',
  `currency10` int(11) unsigned NOT NULL DEFAULT '0',
  `currency11` int(11) unsigned NOT NULL DEFAULT '0',
  `currency12` int(11) unsigned NOT NULL DEFAULT '0',
  `currency13` int(11) unsigned NOT NULL DEFAULT '0',
  `currency14` int(11) unsigned NOT NULL DEFAULT '0',
  `currency15` int(11) unsigned NOT NULL DEFAULT '0',
  `currency16` int(11) unsigned NOT NULL DEFAULT '0',
  `currency17` int(11) unsigned NOT NULL DEFAULT '0',
  `currency18` int(11) unsigned NOT NULL DEFAULT '0',
  `currency19` int(11) unsigned NOT NULL DEFAULT '0',
  `currency20` int(11) unsigned NOT NULL DEFAULT '0',
  `currency21` int(11) unsigned NOT NULL DEFAULT '0',
  `currency22` int(11) unsigned NOT NULL DEFAULT '0',
  `currency23` int(11) unsigned NOT NULL DEFAULT '0',
  `currency24` int(11) unsigned NOT NULL DEFAULT '0',
  `currency25` int(11) unsigned NOT NULL DEFAULT '0',
  `currency26` int(11) unsigned NOT NULL DEFAULT '0',
  `currency27` int(11) unsigned NOT NULL DEFAULT '0',
  `currency28` int(11) unsigned NOT NULL DEFAULT '0',
  `currency29` int(11) unsigned NOT NULL DEFAULT '0',
  `currency30` int(11) unsigned NOT NULL DEFAULT '0',
  `currency31` int(11) unsigned NOT NULL DEFAULT '0',
  `currency32` int(11) unsigned NOT NULL DEFAULT '0',
  `currency33` int(11) unsigned NOT NULL DEFAULT '0',
  `currency34` int(11) unsigned NOT NULL DEFAULT '0',
  `currency35` int(11) unsigned NOT NULL DEFAULT '0',
  `currency36` int(11) unsigned NOT NULL DEFAULT '0',
  `currency37` int(11) unsigned NOT NULL DEFAULT '0',
  `currency38` int(11) unsigned NOT NULL DEFAULT '0',
  `currency39` int(11) unsigned NOT NULL DEFAULT '0',
  `currency40` int(11) unsigned NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`account_id`,`serverid`,`logdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    	$dbsdk->query($sql);
    	echo json_encode($dbsdk->error());
    	$sql = "CREATE TABLE `game_synscience_$Ym` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `level` smallint(6) NOT NULL,
  `account_id` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
 UNIQUE KEY `uk` (`player_id`,`group_id`,`serverid`,`logdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    	$dbsdk->query($sql);
    	echo json_encode($dbsdk->error());
    	
    	
    	
    	//战斗匹配时长
    	$sql = "CREATE TABLE `game_match_$Ym` (
    	  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matchtime` int(11) NOT NULL COMMENT '匹配时间',
  `type` int(11) NOT NULL COMMENT '1是练习2是普通3是精英',
  `dan` int(11) NOT NULL COMMENT '段位',
  `serverid` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='战斗匹配时长';";
    	$dbsdk->query($sql);
    	echo json_encode($dbsdk->error());
    	
    	//渠道登录
    	$sql = "CREATE TABLE `ad_login_$Ym` (
    	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `accountid` varchar(50) NOT NULL COMMENT '设备号',
  `used` int(11) NOT NULL DEFAULT '0' COMMENT '是否记录',
  `logdate` int(11) NOT NULL DEFAULT '0' COMMENT '记录日期',
  `media_source` varchar(50) NOT NULL DEFAULT 'Organic' COMMENT '广告渠道',
  `bundle_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`accountid`,`logdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    	$dbsdk->query($sql);
    	echo json_encode($dbsdk->error());
    	//新手注册流程分表
    	$sql = "CREATE TABLE `u_game_process_$Ym` (
    	`id` bigint(20) NOT NULL AUTO_INCREMENT,
    	`accountid` int(10) NOT NULL,
    	`server_name` varchar(20) NOT NULL DEFAULT '',
    	`userid` int(10) NOT NULL,
    	`serverid` int(10) NOT NULL,
    	`channel` int(10) NOT NULL,
    	`appid` int(10) NOT NULL,
    	`created_at` int(10) NOT NULL,
    	`vip_level` int(10) NOT NULL,
    	`client_time` int(10) NOT NULL,
    	`client_type` varchar(255) NOT NULL DEFAULT '',
    	`client_version` varchar(255) NOT NULL DEFAULT '',
    	`user_lev` int(10) NOT NULL,
    	`process_index` int(10) NOT NULL COMMENT '事件id',
    	`process_result` tinyint(1) NOT NULL COMMENT '事件结果',
    	PRIMARY KEY (`id`),
    	UNIQUE KEY `uk_account_server` (`accountid`,`serverid`,`process_index`)
    	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='游戏流程统计';";
    	$dbsdk->query($sql);
    	echo json_encode($dbsdk->error());
    	//段位分析
    	for($i = 1;$i<=$t;$i++){
    		$sql = "CREATE TABLE `game_world_user_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    		$sql = "CREATE TABLE `game_world_eudemon_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
    		`playerid` bigint(20) NOT NULL COMMENT '玩家id',
    		`eud` int(11) NOT NULL COMMENT '精灵模板编号',
    		`ex1` int(11) NOT NULL COMMENT '精灵个体总值',
    		`ex2` int(11) NOT NULL COMMENT '精灵努力总值',
    		`intilv` int(11) NOT NULL COMMENT '亲密等级',
    		`booklv` int(11) NOT NULL COMMENT '图鉴等级',
    		`serverid` int(11) NOT NULL,
    		PRIMARY KEY (`id`),
    		KEY `idx_no` (`playerid`,`serverid`) USING BTREE
    		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	//精灵推荐阵容分表
    	$sql = "CREATE TABLE `game_user_data_{$Ym}` (
    	`id` int(11) NOT NULL AUTO_INCREMENT,
    	`gameid` int(11) NOT NULL,
    	`endTime` int(11) NOT NULL,
    	`type` int(11) NOT NULL COMMENT '比赛类型',
    	`serverid1` int(11) NOT NULL,
    	`accountid1` bigint(20) NOT NULL,
    	`userid1` int(11) NOT NULL,
    	`name1` varchar(50) NOT NULL,
    	`status1` int(11) NOT NULL COMMENT '胜负0胜1负',
    	`dan1` int(11) NOT NULL COMMENT '段位',
    	`viplevel1` int(11) DEFAULT NULL COMMENT 'vip等级',
    	`level1` int(11) DEFAULT NULL COMMENT '用户等级',
    	`eudemon11` bigint(20) DEFAULT NULL,
    	`estatus11` int(11) DEFAULT NULL,
    	`eudemon12` bigint(20) DEFAULT NULL,
    	`eudemon13` bigint(20) DEFAULT NULL,
    	`eudemon14` bigint(20) DEFAULT NULL,
    	`eudemon15` bigint(20) DEFAULT NULL,
    	`eudemon16` bigint(20) DEFAULT NULL,
    	`estatus12` int(11) DEFAULT NULL,
    	`estatus13` int(11) DEFAULT NULL,
    	`estatus14` int(11) DEFAULT NULL,
    	`estatus15` int(11) DEFAULT NULL,
    	`estatus16` int(11) DEFAULT NULL,
    	`serverid2` int(11) DEFAULT NULL,
    	`accountid2` bigint(20) DEFAULT NULL,
    	`userid2` int(11) DEFAULT NULL,
    	`name2` varchar(50) DEFAULT NULL,
    	`status2` int(11) DEFAULT NULL,
    	`dan2` int(11) DEFAULT NULL,
    	`viplevel2` int(11) DEFAULT NULL,
    	`level2` int(11) DEFAULT NULL,
    	`eudemon21` bigint(20) DEFAULT NULL,
    	`eudemon22` bigint(20) DEFAULT NULL,
    	`eudemon23` bigint(20) DEFAULT NULL,
    	`eudemon24` bigint(20) DEFAULT NULL,
    	`eudemon25` bigint(20) DEFAULT NULL,
    	`eudemon26` bigint(20) DEFAULT NULL,
    	`estatus21` int(11) DEFAULT NULL,
    	`estatus22` int(11) DEFAULT NULL,
    	`estatus23` int(11) DEFAULT NULL,
    	`estatus24` int(11) DEFAULT NULL,
    	`estatus25` int(11) DEFAULT NULL,
    	`estatus26` int(11) DEFAULT NULL,
    	PRIMARY KEY (`id`)
    	) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    	$dbsdk->query($sql);
    	$sql = "CREATE TABLE `game_data_{$Ym}` (
    	`id` int(20) NOT NULL AUTO_INCREMENT,
    	`endTime` int(11) NOT NULL COMMENT '结束时间',
    	`type` int(11) NOT NULL COMMENT '类型0普通1练习2天梯普通3天梯神兽场4排位',
    	`createTime` int(11) NOT NULL COMMENT '入库时间',
    	`btype` int(11) NOT NULL DEFAULT '1' COMMENT '1全球对战4冠军之夜',
    	`continuous` int(11) NOT NULL DEFAULT '0' COMMENT '持续回合',
    	`gameround` int(11) NOT NULL DEFAULT '0' COMMENT '多少轮',
    	PRIMARY KEY (`id`)
    	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    	$dbsdk->query($sql);
    	//系统参与度
    	$sql = "CREATE TABLE `sum_join_{$Ym}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL,
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '0',
  `act_count` int(11) NOT NULL COMMENT '行为次数',
  `act_account` int(11) NOT NULL COMMENT '参与人数',
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  `serverid` int(11) NOT NULL,
  `mysort` int(11) NOT NULL COMMENT '排序',
  `vip_level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`act_id`,`param`,`logdate`,`serverid`,`vip_level`) USING BTREE,
  KEY `idx_time` (`act_id`,`param`,`logdate`,`serverid`,`vip_level`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='参与度统计表';";
    	$dbsdk->query($sql);
    	echo json_encode($dbsdk->error());
    	$sql = "CREATE TABLE `game_user_".$Ym."` (
    	`id` int(11) NOT NULL AUTO_INCREMENT,
    	`serverid` int(11) NOT NULL,
    	`accountid` int(11) NOT NULL,
    		`userid` int(11) NOT NULL,
    	    		`name` varchar(50) NOT NULL,
    		`gameid` int(11) NOT NULL,
    		`status` int(11) NOT NULL COMMENT '胜负1胜0负',
    		`dan` int(11) NOT NULL COMMENT '段位',
    		`viplevel` int(11) DEFAULT NULL COMMENT 'vip等级',
    		`level` int(11) DEFAULT NULL COMMENT '用户等级',
    		`power` int(11) NOT NULL COMMENT '战力',
    		`communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团id',
    		PRIMARY KEY (`id`)
    		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    		$sql = "CREATE TABLE `game_user_eudemon_".$Ym."` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eudemon` int(11) NOT NULL COMMENT '精灵id',
  `status` int(11) NOT NULL COMMENT '0死亡 1存活  2未上场',
  `gameuserid` int(11) NOT NULL COMMENT '该场比赛所属用户',
  `hp` int(11) DEFAULT '0' COMMENT '剩余体力',
  `skills1` int(11) DEFAULT '0',
  `skills2` int(11) DEFAULT '0',
  `skills3` int(11) DEFAULT '0',
  `skills4` int(11) DEFAULT '0',
  `pp1` int(11) DEFAULT '0',
  `pp2` int(11) DEFAULT '0',
  `pp3` int(11) DEFAULT '0',
  `pp4` int(11) DEFAULT '0',
  `abilities` bigint(20) DEFAULT NULL,
  `fruit` bigint(20) DEFAULT NULL,
  `equip` bigint(20) DEFAULT NULL,
  `kidney` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	for($i = 1;$i<=$t;$i++){
    		$sql = "CREATE TABLE `game_process_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	//精灵塔通关阵容分表
    	$sql = "CREATE TABLE `game_tower_{$Ym}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logdate` int(11) NOT NULL,
  `tower` int(11) NOT NULL COMMENT '层数',
  `integral` int(11) NOT NULL COMMENT '积分',
  `serverid` int(11) NOT NULL,
  `playerid` bigint(20) NOT NULL,
  `eudemon` bigint(20) NOT NULL,
  `hp` int(11) NOT NULL,
  `skills1` int(11) NOT NULL COMMENT '技能1',
  `skills2` int(11) DEFAULT NULL,
  `skills3` int(11) DEFAULT NULL,
  `skills4` int(11) DEFAULT NULL,
  `pp1` int(11) NOT NULL COMMENT '技能使用的pp值',
  `pp2` int(11) DEFAULT NULL,
  `pp3` int(11) DEFAULT NULL,
  `pp4` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;";
    	$dbsdk->query($sql);
    	//行为分表
    	for($i = 1;$i<=$t;$i++){
    		//$sql .= "DROP TABLE IF EXISTS `u_behavior_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`;";
    		$sql = "CREATE TABLE `u_behavior_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
    		`id` bigint(20) NOT NULL AUTO_INCREMENT,
  			`accountid` int(10) NOT NULL DEFAULT '0'  COMMENT '用户账号',
  			`userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  			`serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  			`channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  			`created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  			`vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
    		`act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',		
    		`param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',		
 			 `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
    		`user_level` int(10)  DEFAULT '0' COMMENT '用户等级',		
    		`communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
    		`communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
    		`param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',    		
    		PRIMARY KEY (`id`),
    		KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
    		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户行为表';";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	for($i = 1;$i<=$t;$i++){
    		//$sql .= "DROP TABLE IF EXISTS `item_trading_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."`;";
    		$sql = "CREATE TABLE `item_trading_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
    		`id` bigint(20) NOT NULL AUTO_INCREMENT,
    		`table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  			`behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  			`type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
    		`item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  			`item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  			`created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
    		PRIMARY KEY (`id`)
    		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='物品产销表';";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	//登录日志分表
    	for($i = 1;$i<=$t;$i++){ 
    		$sql = "CREATE TABLE `u_login_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
    		`id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
   `position` int(10) DEFAULT '0' COMMENT '0,无值，1成员，2精英, 3副社长,4.社长',
   `communityid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE,
  KEY `idx_mac` (`mac`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	//注册流程分表
    	for($i = 1;$i<=$t;$i++){ 
    		$sql = "CREATE TABLE `u_register_process_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` (
    		`id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	
    	
    //亲密度	     
    $sql="	CREATE TABLE `intimacy_".$Ym."` (
    			`id` bigint(20) NOT NULL AUTO_INCREMENT,
    			`accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
    			`userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
    			`serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
    			`channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
    			`created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
    			`vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
    			`attack_avg` int(10) DEFAULT NULL COMMENT '物攻属性平均价值',
    			`defend_avg` int(10) DEFAULT NULL COMMENT '物防属性平均价值',
    			`special_attack_avg` int(10) DEFAULT NULL COMMENT '特攻属性平均价值',
    			`life_avg` int(10) DEFAULT '0' COMMENT '生命属性平均价值',
    			`special_defend_avg` int(10) DEFAULT NULL COMMENT '特防属性平均价值',
    			`rotom_grade` int(10) DEFAULT NULL COMMENT '洛托姆等级',
    			`speed_avg` int(10) DEFAULT NULL COMMENT '速度属性平均价值',
                `Rotom_class` int(10) DEFAULT NULL COMMENT '洛托姆阶级',
                `Rotom_intensify` int(10) DEFAULT NULL COMMENT '洛托姆强化等级',
                `user_level` int(10) DEFAULT NULL COMMENT 'user_level',        
    			`logdate` int(10) DEFAULT NULL,
    			PRIMARY KEY (`id`),
                UNIQUE KEY `index_account` (`accountid`,`serverid`,`logdate`) USING BTREE,
    			KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`) USING BTREE
    			) ENGINE=InnoDB AUTO_INCREMENT=99621 DEFAULT CHARSET=utf8 COMMENT='亲密度';";
    			 
    $dbsdk->query($sql);    	
    	
    
  
    
    
    //点击
    for($i = 1;$i<=$t;$i++){
    $sql="	CREATE TABLE `activity_click_$Ym".str_pad($i,2,0,STR_PAD_LEFT)."` (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) DEFAULT '0' COMMENT '所属渠道',
  `viplev` int(10) DEFAULT '0' COMMENT 'vip等级',
  `lev` int(10) DEFAULT NULL COMMENT '等级',
  `type` int(10) DEFAULT NULL COMMENT '物防属性平均价值',
  `param` int(10) DEFAULT NULL,
  `created_at` int(10) DEFAULT '0' COMMENT '记录时间',
  `logdate` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户行为表';";
    $dbsdk->query($sql);
    echo json_encode($dbsdk->error());
    }
//苹果用户登录数据
    $sql = "CREATE TABLE `u_apple_login_".$Ym."` (
    		`id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
   `position` int(10) DEFAULT '0' COMMENT '0,无值，1成员，2精英, 3副社长,4.社长',
   `communityid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE,
  KEY `idx_mac` (`mac`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    $dbsdk->query($sql);
     
        //$dbsdk->execute($sql);
        unset($dbsdk);

        parent::log('month running createTable');
    }
    
    
    function alteradd(){
    	$dbsdk = $this->load->database('sdk', true);
    	/*$Ym = date('Ym');
    	$t = date("t");*/
    	$Ym = date('Ym',strtotime(" +1 month"));
    	$t = date("t",strtotime(" +1 month")); //下月末
    	/*$sql = "alter table `game_data_".$Ym."` add gameround int(11) NOT NULL DEFAULT '0' COMMENT '多少轮'";
    	 $dbsdk->query($sql);*/
    	for($i = 1;$i<=$t;$i++){
    		$sql = "ALTER TABLE `u_login_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` ADD INDEX idx_mac (`mac` )";
    		//$sql = "alter table `u_behavior_".$Ym.str_pad($i,2,0,STR_PAD_LEFT)."` add `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动'";
    		$dbsdk->query($sql);
    		echo json_encode($dbsdk->error());
    	}
    	unset($dbsdk);
    }
}
