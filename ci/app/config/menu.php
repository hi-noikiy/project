<?php
//菜单配置
defined('BASEPATH') OR exit('No direct script access allowed');
return array(
    10002=>[
        [
            'title'=>'数据汇总',
            'controller'=>'Home',
            'menus'=>[
                [
                    'title'     =>'汇总',
                    'controller'=>'Home/Summary',
                ],
                [
                'title'     =>'一天汇总详情',
                'controller'=>'Home/summaryByDay',
                ],
          /*   	[
            		'title'     =>'渠道推广数据',
            		'controller'=>'Home/summary_by_ad',
            	], */
                [
                    'title'     =>'汇总(按渠道)',
                    'controller'=>'Home/summary_by_channel',
                ],
            	/*[
            		'title'     =>'汇总(按区服)',
            		'controller'=>'Home/summary_by_server',
            	],*/
            	[
            		'title'     =>'汇总(按平台)',
            		'controller'=>'Home/summary_by_platform',
            	],
                /*[
                    'title'     => '渠道注册统计',
                    'controller'=> 'Home/ChannelRegisterProcess',
                ],*/
                [
                    'title'     => '注册流程统计',
                    'controller'=> 'Home/RegisterProcess',
                ],
                [
                    'title'     => '注册流程统计-每小时统计数',
                    'controller'=> 'Home/getRegisterProcessDetail',
                    'display'   => false,
                ],
                [
                    'title'     => '新手流程统计',
                    'controller'=> 'Home/FoolBird',
                ],
            	[
            		'title'     => '系统参与度统计',
            		'controller'=> 'Home/joinCount',
            	],
        		[
        			'title'     => '行为产销统计',
        			'controller'=> 'Home/ActionCount',
        		],
            	[
            		'title'     =>'道具产销',
            		'controller'=>'Home/item_use',
            	],
            	[
            		'title'     =>'运营活动道具产销',
            		'controller'=>'Home/itemact_use',
            	],
            	[
            		'title'     =>'商店销售统计',
            		'controller'=>'Home/shop_count',
            	],
                [
                    'title'     => 'Bug反馈',
                    'controller'=> 'Home/bugReport'
                ],
            	[
            		'title'     => '客户端Bug',
            		'controller'=> 'Home/clientBug'
            	],
            	[
            		'title'     => '服务器剩余钻石',
            		'controller'=> 'Home/lastEmoney'
            	],
            	[
            		'title'     => '服务器剩余金币',
            		'controller'=> 'Home/lastMoney'
            	],
            	[
            		'title'     =>'掉线情况统计',
            		'controller'=>'Home/Drops',
            	],     
            ]
        ],
        [
            'title'=>'实时概况',
            'controller'=>'RealTime',
            'menus'=>[
                [
                    'title'     =>'实时在线',
                    'controller'=>'RealTime/OnlineRt',
                ],
                [
                    'title'     =>'每小时时在线统计',
                    'controller'=>'RealTime/Online',
                ],
                [
                    'title'     =>'安装解压',
                    'controller'=>'RealTime/Device',
                ],
                [
                    'title'     =>'设备激活',
                    'controller'=>'RealTime/DeviceActive',
                ],
                /*[
                    'title'     =>'新增玩家',
                    'controller'=>'RealTime/NewPlayer',
                ],*/
            ],
        ],
        [
            'title'=>'玩家分析',
            'controller'=>'PlayerAnalysis',
            'menus'=>[
                [
                    'title'     =>'新增玩家',
                    'controller'=>'PlayerAnalysis/NewPlayer',
                ],
                [
                    'title'     =>'活跃角色',
                    'controller'=>'PlayerAnalysis/ActivePlayer',
                ],
                [
                    'title'     =>'活跃账号',
                    'controller'=>'PlayerAnalysis/ActiveAccounts',
                ],
                [
                    'title'     =>'活跃账号(按渠道)',
                    'controller'=>'PlayerAnalysis/ActiveAccountsByChannel',
                ],
                [
                    'title'     =>'留存统计',
                    'controller'=>'PlayerAnalysis/Remain',
                ],
            	[
            		'title'     =>'VIP玩家登录详情',
            		'controller'=>'PlayerAnalysis/VipLogin',
            	],
                [
                    'title'     =>'设备详情',
                    'controller'=>'PlayerAnalysis/DeviceDetail',
                ],
                /*[
                    'title'     =>'用户信息统计数据',
                    'controller'=>'PlayerAnalysis/Life',
                ],*/
            	[
            		'title'     =>'用户信息查询',
            		'controller'=>'PlayerAnalysis/user',
            	],
            	[
            		'title'     =>'合服流失查询',
            		'controller'=>'PlayerAnalysis/Lost',
            	],
                [
                'title'     =>'邀请好友统计需求',
                'controller'=>'PlayerAnalysis/inviteFriend',
                ],
                [
                'title'     =>'典型玩家数据',
                'controller'=>'PlayerAnalysis/tipical',
                ],
                
            ]
        ],
        [
            'title'=>'付费分析',
            'controller'=>'PayAnalysis',
            'menus'=>[
                [
                    'title'     =>'付费数据',
                    'controller'=>'PayAnalysis/PayData',
                ],
                [
                    'title'     =>'付费行为',
                    'controller'=>'PayAnalysis/PayBehavior',
                ],
                [
                    'title'     =>'付费排行',
                    'controller'=>'PayAnalysis/PayRank',
                ],
            	/* [
            		'title'     =>'付费等级分布',
            		'controller'=>'PayAnalysis/Paylevel',
            	], */
            	[
            		'title'     =>'付费平均值',
            		'controller'=>'PayAnalysis/Payavg',
            	],
                [
                'title'     =>'活跃玩家充值积分统计',
                'controller'=>'PayAnalysis/bonusPoint',
                ],
            ],
        ],
        [
            'title'=>'流失分析',
            'controller'=>'LostAnalysis',
            'menus'=>[
                [
                    'title'     =>'每日流失',
                    'controller'=>'LostAnalysis/Index',
                ],
            ],
        ],
        [
            'title'=>'在线分析',
            'controller'=>'OnlineAnalysis',
            'menus'=>[
                [
                    'title'     =>'在线时长',
                    'controller'=>'OnlineAnalysis/Index',
                ],
        /*     	[
            		'title'     =>'在线时段分析',
            		'controller'=>'OnlineAnalysis/online',
            	], */
        /*         [
                    'title'     =>'在线习惯',
                    'controller'=>'OnlineAnalysis/Habit',
                ], */
            ],
        ],
        /*[
            'title'=>'系统分析',
            'controller'=>'SystemAnalysis',
            'menus'=>[
                [
                    'title'     =>'虚拟币统计',
                    'controller'=>'SystemAnalysis/Emoney',
                ],
                [
                    'title'     =>'道具分析',
                    'controller'=>'SystemAnalysis/Props',
                ],
                [
                    'title'     =>'副本记录',
                    'controller'=>'SystemAnalysis/Copy',
                ],

                [
                    'title'     =>'销售产品',
                    'controller'=>'Home/Wait',
                ],
                [
                    'title'     =>'养成&强化',
                    'controller'=>'Home/Wait',
                ],
                [
                    'title'     =>'日常行为',
                    'controller'=>'Home/Wait',
                ],
                [
                    'title'     =>'关卡进度',
                    'controller'=>'SystemAnalysis/Level',
                ],
                [
                    'title'     =>'成就进度',
                    'controller'=>'SystemAnalysis/Success',
                ],
                [
                    'title'     =>'升级历程',
                    'controller'=>'SystemAnalysis/Upgrade',
                ],
            ],
        ],*/
        [
            'title'=>'系统功能统计',
            'controller'=>'SystemFunction',
            'menus'=>[
               /*  [
                    'title'     =>'玩家养成情况',
                    'controller'=>'SystemFunction/PlayerDevelop',
                ], */
               /*  [
                    'title'     =>'捕捉统计数据',
                    'controller'=>'SystemFunction/Capture',
                ], */
            /*     [
                    'title'     =>'货币消耗',
                    'controller'=>'SystemFunction/money_use',
                ], */
                /* [
                    'title'     =>'道具商店统计数据',
                    'controller'=>'SystemFunction/props_shop',
                ], */
        /*         [
                    'title'     =>'行为产销记录',
                    'controller'=>'SystemFunction/BehaviorProduceSale',
                ], */
               /*  [
                    'title'     =>'钻石获取渠道统计',
                    'controller'=>'SystemFunction/Diamond',
                ], */
               /*  [
                    'title'     =>'钻石消耗渠道统计',
                    'controller'=>'SystemFunction/Diamond_use',
                ], */
                /*[
                    'title'     =>'商店销售统计',
                    'controller'=>'SystemFunction/ShopSaleCount',
                ],*/
                [
                    'title'     =>'活跃度统计',
                    'controller'=>'SystemFunction/PlayerActive',
                ],
               /*  [
                    'title'     =>'玩法次数统计',
                    'controller'=>'SystemFunction/PlayingMethod',
                ], */
            	/* [
            		'title'     =>'通用货币统计',
            		'controller'=>'SystemFunction/CommonCurrencyNew',
            	], */
                /*[
                    'title'     =>'通用货币获取',
                    'controller'=>'SystemFunction/CommonCurrency',
                ],
                [
                    'title'     =>'通用货币消耗',
                    'controller'=>'SystemFunction/CommonCurrency_use',
                ],*/
               /*  [
                    'title'     =>'精灵星级',
                    'controller'=>'SystemFunction/ElfStarLev',
                ], */
               /*  [
                    'title'     =>'图鉴等级',
                    'controller'=>'SystemFunction/PhotoLevel',
                ], */
                [
                    'title'     =>'关卡进度统计',
                    'controller'=>'SystemFunction/LevelProgress',
                ],
               /*  [
                    'title'     =>'关卡难易程度统计',
                    'controller'=>'SystemFunction/LevelDifficulty',
                ], */
            	[
            		'title'     =>'固定交换统计',
            		'controller'=>'SystemFunction/Fixchange',
            	],
            	[
            		'title'     =>'精灵觉醒',
            		'controller'=>'SystemFunction/elvesAwake',
            	],
                [
            		'title'     =>'全球对战-精灵使用率',
            		'controller'=>'SystemFunction/eudemonData',
            	],
            	[
            		'title'     =>'全球对战-详情',
            		'controller'=>'SystemFunction/worldData',
            	],
            	[
            		'title'     =>'剩余道具查询',
            		'controller'=>'SystemFunction/itemData',
            	],
            	[
            		'title'     =>'精灵塔',
            		'controller'=>'SystemFunction/Elftower',
            	],
            	[
            		'title'     =>'精灵塔推荐阵容',
            		'controller'=>'SystemFunction/Squad',
            	],
            	[
            		'title'     =>'植树节活动',
            		'controller'=>'SystemFunction/Tree',
            	],
            	[
            		'title'     =>'神兽来袭',
            		'controller'=>'SystemFunction/Beast',
            	],
            	[
            		'title'     =>'全球段位分布统计',
            		'controller'=>'SystemFunction/Dan',
            	],
            	[
            		'title'     =>'全球精灵养成统计',
            		'controller'=>'SystemFunction/EdumonDevelop',
            	],
            	[
            		'title'     =>'全球对战匹配时间查询',
            		'controller'=>'SystemFunction/Match',
            	],
            	 		
            	[
            		'title'     =>'全球对战-战斗回合数统计',
            		'controller'=>'SystemFunction/combatBout',
            	],
                [
                'title'     =>'精灵推荐配招',
                'controller'=>'SystemFunction/recommend',
                ],
                [
                'title'     =>'技能专精',
                'controller'=>'SystemFunction/mastery',
                ],
           /*      [
                'title'     =>'创世徽章',
                'controller'=>'SystemFunction/badge',
                ], */
                [
                'title'     =>'社团争霸赛',
                'controller'=>'SystemFunction/hegemony',
                ],
           /*      [
                'title'     =>'远古宝藏统计',
                'controller'=>'SystemFunction/ancient',
                ], */
                [
                'title'     =>'菁英挑战统计',
                'controller'=>'SystemFunction/elite',
                ],
                [
                'title'     =>'周任务链接统计',
                'controller'=>'SystemFunction/mission',
                ],
            ]
        ],
        [
            'title'=>'游服数据统计', 
            'controller'=>'SystemAnalysis',
            'menus'=>[
            	[
            		'title'     =>'区服查询',
            		'controller'=>'SystemAnalysis/GetServer',
            	],
            		[
            		'title'     =>'用户信息查询',
            		'controller'=>'SystemAnalysis/getUserInfo',
            		],
            		[
            		'title'     =>'跨服操作配置',
            		'controller'=>'SystemAnalysis/crossServer',
            		],
            		[
            		'title'     =>'获取精灵数据',
            		'controller'=>'ActiveAnalysis/getelves',
            		],
            ]
        ],
    	[
    		'title'=>'数据分析',
    		'controller'=>'DataAnalysis',
    		'menus'=>[
            	[
    				'title'     =>'技能专精养成等级统计',
    				'controller'=>'DataAnalysis/Synscience',
    			],
    			[
    				'title'     =>'技能专精持有货币统计',
    				'controller'=>'DataAnalysis/Currency',
    			],
    			[
    				'title'     =>'创世徽章养成等级统计',
    				'controller'=>'DataAnalysis/Stone',
    			],
    			[
    				'title'     =>'创世徽章持有/消耗货币统计',
    				'controller'=>'DataAnalysis/Stoneresume',
    			],
    			[
    				'title'     =>'亲密度购买统计',
    				'controller'=>'DataAnalysis/Intimacy',
    			],
    		    [
    				'title'     =>'技能属性精华购买统计',
    				'controller'=>'DataAnalysis/propertyBuy',
    			],
    			[
    				'title'     =>'活跃玩家钻石途径',
    				'controller'=>'DataAnalysis/diamandDistribute',
    			],
    			[
    				'title'     =>'生命周期价值统计',
    				'controller'=>'DataAnalysis/lifePeriod',
    			],
    			[
    				'title'     =>'高级波伏蕾购买统计',
    				'controller'=>'DataAnalysis/wavePurchase',
    			],
    			[
    				'title'     =>'霸主精灵统计',
    				'controller'=>'DataAnalysis/spirit',
    			],
    			
    			[
    				'title'     =>'行为产销统计(多天)',
    				'controller'=>'DataAnalysis/behavior',
    			],
    			[
    				'title'     =>'创世元神养成统计',
    				'controller'=>'DataAnalysis/genesis',
    			],    				
    			[
    				'title'     =>'亲密度珍肴养成统计',
    				'controller'=>'DataAnalysis/intimacyCultivate',
    			],
    				
    			[
    				'title'     =>'福利活动各档次活动点击',
    				'controller'=>'DataAnalysis/activityClick',
    			],
    			[
    				'title'     =>'洛托姆养成统计',
    				'controller'=>'DataAnalysis/rotomCultivate',
    			],
    			[
    				'title'     =>'蛋糕工坊统计',
    				'controller'=>'ActiveAnalysis/cakeData',
    			],
    			[
    				'title'     =>'月卡数据统计',
    				'controller'=>'ActiveAnalysis/monthCard',
    			],    		   
    		    [
    		    'title'     =>' 活跃玩家社团VIP分布',
    		    'controller'=>'DataAnalysis/community',
    		    ],
    		    [
    		    'title'     =>' 每个服务器的活跃VIP分布',
    		    'controller'=>'DataAnalysis/activeVip',
    		    ],
    			[
    				'title'     =>' 坐骑数据统计',
    				'controller'=>'ActiveAnalysis/horse',
    			],
    		    [
    		    'title'     =>'精灵拥有数统计',
    		    'controller'=>'ActiveAnalysis/elves',
    		    ], 
    		
    		]
    	],
    	[
    		'title'=>'社团统计',
    		'controller'=>'SystemFunction',
    		'menus'=>[
    				[
            		'title'     =>'社团副本',
            		'controller'=>'SystemFunction/The',
            	],
            	[
            		'title'     =>'社团副本宝箱',
            		'controller'=>'SystemFunction/theTreasure',
            	],
            	[
            		'title'     =>'社团入侵',
            		'controller'=>'SystemFunction/Invasion',
            	],
            	[
            		'title'     =>'椰蛋树活动',
            		'controller'=>'SystemFunction/Egg',
            	],       
    				[
    				'title'     =>'社团每日捐献统计',
    				'controller'=>'SystemFunction/donate',
    				],
    		]
    	],
    	[
    		'title'=>'我的VIP用户',
    		'controller'=>'VipUser',
    		'menus'=>[
    				[
    						'title'     =>'我的用户',
    						'controller'=>'VipUser/getUserInfo',
    				],
            		[
    						'title'     =>'用户反馈',
    						'controller'=>'VipUser/backInfo',
    				],
    		]
    	],
    		
    		
    ]
);
