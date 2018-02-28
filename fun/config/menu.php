<?php
//菜单配置
defined('BASEPATH') OR exit('No direct script access allowed');
return array(
    10003=>[
        [
            'title'=>'数据汇总',
            'controller'=>'Home',
            'menus'=>[
                [
                    'title'     =>'汇总',
                    'controller'=>'Home/Summary',
                ],
                [
                    'title'     => '新手流程统计',
                    'controller'=> 'Home/FoolBird',
                ],
                [
                    'title'     => '注册流程统计',
                    'controller'=> 'Home/RegisterProcess',
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
            ]
        ],
        [
            'title'=>'实时概况',
            'controller'=>'RealTime',
            'menus'=>[
                [
                    'title'     =>'设备激活',
                    'controller'=>'RealTime/DeviceActive',
                ],
                [
                    'title'     =>'新增玩家',
                    'controller'=>'RealTime/NewPlayer',
                ],
				 [
                'title'     =>'实时在线',
                'controller'=>'RealTime/OnlineRt',
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
                    'title'     =>'留存统计',
                    'controller'=>'PlayerAnalysis/Remain',
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
                'title'     =>'旅行任务',
                'controller'=>'PlayerAnalysis/travel',
                ],
               /* [
                'title'     =>'玩家等级分布',
                'controller'=>'PlayerAnalysis/classDistribution',
                ],
				*/
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
            		'title'     =>'付费等级分布',
            		'controller'=>'PayAnalysis/Paylevel',
            	],
             	[
            		'title'     =>'付费平均值',
            		'controller'=>'PayAnalysis/Payavg',
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
            	[
            		'title'     =>'在线时段分析',
            		'controller'=>'OnlineAnalysis/online',
            	],
            ],
        ],
        [
            'title'=>'系统分析',
            'controller'=>'SystemAnalysis',
            'menus'=>[
                [
                    'title'     =>'钻石消费统计',
                    'controller'=>'SystemAnalysis/Emoney',
                ],
            	[
            		'title'     =>'任务统计',
            		'controller'=>'SystemAnalysis/Task',
            	],
            	[
            		'title'     =>'投票统计',
            		'controller'=>'SystemAnalysis/Vote',
            	],
            	[
            		'title'     =>'商店统计',
            		'controller'=>'SystemAnalysis/Shop',
            	],
            	[
            		'title'     =>'衣服销售排行',
            		'controller'=>'SystemAnalysis/Shoprank',
            	],
            	[
            		'title'     =>'赛事基础数据统计',
            		'controller'=>'SystemAnalysis/Game',
            	],
            	/*[
            		'title'     =>'分享基础数据统计',
            		'controller'=>'SystemAnalysis/Share',
            	],*/
            	[
            		'title'     =>'分享大数据统计',
            		'controller'=>'SystemAnalysis/Sharedata',
            	],
            	[
            		'title'     =>'借衣饰基础数据统计',
            		'controller'=>'SystemAnalysis/Borrow',
            	],
            	[
            		'title'     =>'借衣饰大数据统计',
            		'controller'=>'SystemAnalysis/Borrowdata',
            	],
            ],
        ],

    ]
);
