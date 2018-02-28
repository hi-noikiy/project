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
                [
                    'title'     =>'汇总(按渠道)',
                    'controller'=>'Home/summary_by_channel',
                ],
            	[
            		'title'     =>'汇总(按平台)',
            		'controller'=>'Home/summary_by_platform',
            	],
				[
                    'title'     => '渠道注册数据排行',
                    'controller'=> 'Home/ChannelRegisterRanking',
                ],
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
                 [
                'title'     =>'神兽来袭拍卖',
                'controller'=>'HomeNew/auction',
                ], 
            		
            	[
            		'title'     =>'MAC查询',
            		'controller'=>'HomeNew/mac',
            	],
            	[
            		'title'     =>'VIP人数统计',
            		'controller'=>'Home/vip',
            	]
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
                [
                'title'     =>'V12玩家的信息统计',
                'controller'=>'PlayerAnalysisNew/userVip',
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
            	[
            		'title'     =>'付费平均值',
            		'controller'=>'PayAnalysis/Payavg',
            	],
                [
					'title'     =>'活跃玩家充值积分统计',
					'controller'=>'PayAnalysis/bonusPoint',
                ],
				[
					'title'     =>'充值档位数据',
					'controller'=>'PayAnalysis/GearPosition',
				],
				[
					'title'     =>'首冲数据统计',
					'controller'=>'PayAnalysis/FirstRecord',
				],
				[
					'title'     =>'新增账号付费',
					'controller'=>'PayAnalysis/PayNewAccounts',
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
            ],
        ],
        [
            'title'=>'系统功能统计',
            'controller'=>'SystemFunction',
            'menus'=>[
                [
                    'title'     =>'活跃度统计',
                    'controller'=>'SystemFunction/PlayerActive',
                ],
                [
                    'title'     =>'关卡进度统计',
                    'controller'=>'SystemFunction/LevelProgress',
                ],
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
                [
                'title'     =>'社团争霸赛',
                'controller'=>'SystemFunction/hegemony',
                ],
                [
                'title'     =>'菁英挑战统计',
                'controller'=>'SystemFunction/elite',
                ],
                [
                'title'     =>'周任务链接统计',
                'controller'=>'SystemFunction/mission',
                ],
                [
                'title'     =>' 精灵塔简单模式 ',
                'controller'=>'SystemFunction/fairyTower',
                ],                
                [
                'title'     =>'洛奇亚的爆诞活动 ',
                'controller'=>'SystemFunction/Lugia',
                ],
                [
                'title'     =>'全球匹配时间',
                'controller'=>'SystemFunction/matchTime',
                ],       
           
                [
                'title'     =>'-冠军之夜对战数据统计',
                'controller'=>'SystemFunction/champion',
                ],
                [
                'title'     =>'多人对战-精灵使用率统计',
                'controller'=>'PlayerAnalysisNew/pvpCombat',
                ],
                [
                'title'     =>' 多人对战-匹配时间查询',
                'controller'=>'SystemFunctionNew/multiplayerMatchTime',
                ],
             
                [
                'title'     =>' 多人对战-战斗回合数统计',
                'controller'=>'SystemFunctionNew/multiplayerBout',
                ],
                
                [
                'title'     =>'多人对战-技能使用次数统计',
                'controller'=>'SystemFunctionNew/multiplayerSkill',
                ],
                [
                'title'     =>' 洛托姆强化',
                'controller'=>'SystemFunctionNew/intensify',
                ],
				[
					'title'     =>'一键狩猎统计',
					'controller'=>'SystemFunction/hunting',
				],
            	[
            		'title'     =>'黑卡查询',
            		'controller'=>'SystemFunctionNew/blackCard',
            	],
            	[
            		'title'     =>'全球对战-技能使用率统计',
            		'controller'=>'SystemFunctionNew/skillRate',
            	],
            		
            	[
            		'title'     =>'组队副本数据统计',
            		'controller'=>'SystemFunctionNew/transcript',
            	],            	
            	[
            		'title'     =>'全球段位分布统计',
            		'controller'=>'SystemFunctionNew/danGrading',
            	],
            	[
            		'title'     =>'全球段位分布查询',
            		'controller'=>'SystemFunctionNew/danSearch',
            	],
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
    			[
    				'title'     =>'精灵冒险任务栏扩容情况统计',
    				'controller'=>'PayAnalysis/dilatation',
    		   ],
    			[
    						'title'     =>'精灵冒险刷新付费统计',
    						'controller'=>'PayAnalysis/refresh',
    			],
    			[
    				'title'     =>'精灵冒险冒险称号统计',
    				'controller'=>'PlayerAnalysisNew/adventure',
    			],
    			[
    						'title'     =>'精灵冒险每日冒险称号统计',
    						'controller'=>'PlayerAnalysisNew/everyAdventure',
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
    		[
    		'title'=>'编辑页面',
    		'controller'=>'Tongyong',
    		'menus'=>[
    				[
    						'title'     =>'页面列表',
    						'controller'=>'Tongyong/showindex',
    				],
    		]
    	],
    		
    ]
);
