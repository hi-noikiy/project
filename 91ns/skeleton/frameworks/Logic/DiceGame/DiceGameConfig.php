<?php
/**
 * Desc: 骰子游戏--配置信息
 * Date: 2016/2/22
 */
return new \Phalcon\Config(array(

    //游戏状态
    'dice_game_status_00'=>0,//游戏未开始
    'dice_game_status_01'=>1,//游戏进行中
    'dice_game_status_02'=>2,//游戏已结束


    //押注类型 1：大，2：全围， 3：小，4：单押1,  5：单押2,  6：单押3,  7：单押4，8：单押5,  9：单押6
    'dice_game_type_num'=>9,//押注类型总数

    'dice_game_type_01'=>1,//大
    'dice_game_type_02'=>2,//全围
    'dice_game_type_03'=>3,//小
    'dice_game_type_04'=>4, //单押1
    'dice_game_type_05'=>5,//单押2
    'dice_game_type_06'=>6,//单押3
    'dice_game_type_07'=>7,//单押4
    'dice_game_type_08'=>8,//单押5
    'dice_game_type_09'=>9,//单押6

    //单押对应的骰子点数
    "points_list" => array(
        //dice_game_type_0
        '4' => 1,
        '5' => 2,
        '6' => 3,
        '7' => 4,
        '8' => 5,
        '9' => 6,
    ),


    //全围中奖倍数
    'dice_game_stake_all_same'=>32,

    //抢庄聊币下限
    'dice_game_declare_cash_limit'=>1000,

    //庄家继续坐庄的下限
    'dice_game_declare_continue_cash_limit'=>500,

    //抢庄聊币下限
    'dice_game_fax_limit'=>100,

    //税收百分比
    'dice_game_fax_percent'=>0.03,

    //玩家押注聊币可选值
    "stake_cash_list" => array(10, 50, 100, 1000),


    //获得聊币产生系统广播的下限值
    "dice_game_win_broadcast_limit"=>1000,

    //用户单局游戏最大下注上限
    'dice_one_game_max_stake_limit'=>10000,



));