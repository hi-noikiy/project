<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 下午2:08
 * 语言包
 */
$lang = array();
//header
$lang['h_webtitle']        = '海牛游戏——运营后台管理';
$lang['h_setting']         = '设置';
$lang['h_logout']          = '退出';
//nav
$lang['n_datacount']       = '数据统计';

$lang['n_alldata']         = '总数据';
$lang['n_online']          = '在线统计';
$lang['n_online_rt']       = '在线统计(实时)';
$lang['n_online_time']     = '在线时长';
$lang['n_level']           = '等级分布';
$lang['n_user_remain']     = '留存统计';
$lang['n_user_lost']       = '流失统计';
$lang['n_user_info']       = '用户信息';
$lang['n_reg_trans']       = '注册转化';
$lang['n_behavior_pay']    = '消费行为';
$lang['n_rmb_use']         = '元宝消耗';
$lang['n_task']            = '任务统计';
$lang['n_market_pay']      = '商城消费';
$lang['n_activity_result'] = '活动效果';

$lang['n_apple']           = 'Apple 统计';
$lang['n_apple_data']      = '数据统计';
$lang['n_apple_data_rt']   = '数据统计(实时)';
$lang['n_apple_data_dt']   = '渠道分析';


$lang['n_log']             = '日志查询';
$lang['n_log_player']      = '用户查询';
$lang['n_log_faction']     = '帮派查询';
$lang['n_user_manage']     = '用户管理';
$lang['n_user_forbid']     = '账号禁封';
$lang['n_user_open']       = '账号解封';
$lang['n_user_faction']    = '帮派管理';

$lang['n_op']              = '操作管理';
$lang['n_op_notice']       = '系统公告';
$lang['n_op_email']        = '系统邮件';
$lang['n_op_emoney']       = '元宝道具发放';
$lang['n_op_package']      = '礼包生成';

$lang['n_pannel']          = '后台管理';
$lang['n_pannel_power']    = '权限分配';
$lang['n_pannel_pwd']      = '密码修改';
$lang['n_pannel_oplog']    = '操作日志';

//System Admin
$lang['umsg_op_ok']        = '操作成功';//Disable OK
$lang['umsg_op_fail']      = '操作失败';//Disable OK
$lang['umsg_grp']          = '所属用户组必选';
$lang['umsg_ant']          = '登录账号必填';
$lang['umsg_name']         = '用户名必填';
$lang['umsg_pwd']          = '密码不能为空且不能小于6个字符长度';
$lang['umsg_pwd_nomatch']  = '两次密码输入不一致';
$lang['umsg_exist']        = '账号已存在！';
$lang['umsg_no_exist']     = '用户不存在！';
$lang['umsg_add_ok']       = '账号添加成功！';
$lang['umsg_update_ok']    = '更新成功！';
$lang['umsg_update_fail']  = '更新失败,原因:';
$lang['umsg_err_oldpwd']   = '旧密码错误！';
$lang['umsg_resetpwd_ok']  = '密码修改成功';
$lang['umsg_resetpwd_fail']= '密码修改失败';
$lang['umsg_login_forbid'] = '账号已被禁用，无法登录。';
$lang['umsg_login_errpwd'] = '账号或密码错误';
$lang['umsg_addgrp_ok']    = '添加用户组成功';
$lang['umsg_addgrp_fail']  = '添加用户组失败';
$lang['umsg_upgrp_ok']     = '用户组更新成功';
$lang['umsg_upgrp_fail']   = '用户组更新失败';
$lang['umsg_unlogin']      = '登录超时，请重新登录。';
$lang['umsg_per_dny']      = '权限不足';//Permission Denied

//统计页面
$lang['s_datetime']         = '时间';
$lang['s_reg_num']          = '注册数';
$lang['s_cre_num']          = '创建数';
$lang['s_cre_rate']         = '创建率';
$lang['s_new_login']        = '新增登录人数';
$lang['s_pay_money']        = '充值金额';
$lang['s_pay_nop']          = '充值人数';
$lang['s_pay_nop_new']      = '首充人数';
$lang['s_pay_nop_new_money']= '首充金额';
$lang['s_pay_times']        = '充值次数';
$lang['s_pay_rate']         = '付费率';
$lang['s_pay_arpu']         = '充值ARPU';
$lang['s_reg_arpu']         = '注册ARPU';
$lang['s_remain_day1']      = '次日留存';
$lang['s_remain_day3']      = '3日留存';
$lang['s_remain_day7']      = '7日留存';
$lang['s_remain_day15']     = '15日留存';
$lang['s_remain_day30']     = '30日留存';
$lang['s_online_max']       = '最高在线';
$lang['s_online_avg']       = '平均在线';

//global
$lang['server']                 = '区服';
$lang['channel']                = '渠道';
$lang['date']                   = '日期';
$lang['time']                   = '时间';
$lang['no_data']                = '没有相关数据。';
//在线统计
$lang['online_cnt']             = '在线';
$lang['online_avg']             = '平均在线';
$lang['online_sum']             = '总在线';
$lang['online_max']             = '最高在线';
//任务分布
$lang['task_id']                = '任务编号';
$lang['task_name']              = '任务名称';
$lang['task_min_level']         = '等级下限';
$lang['task_max_level']         = '等级上限';
$lang['task_people']            = '人数';
$lang['task_people_scale']      = '人数分布比例';

//用户查询
$lang['player_account']         = '账号';
$lang['player_lev']             = 'vip等级';
$lang['player_points_total']    = '总积分';
$lang['player_points_left']     = '剩余积分';
$lang['player_channel_id']      = '分包id';
$lang['player_channel_account'] = '渠道账号';
$lang['player_mac']             = '手机标识';
$lang['player_lastlogin_date']  = '最后登录';
$lang['player_reg_date']        = '注册日期';

//在线
$lang['online_rt']              = '实时在线数';
$lang['online_rt_total']        = '总在线数';

//等级
$lang['lev_t']                    = '等级';
$lang['lev_p']                    = '角色数';
$lang['lev_pr']                   = '等级比重';
$lang['lev_warn_msg']             = '由于玩家数量巨大，请尽量选择具体区服、渠道进行查询以减少您的等待时间。';

//流失
$lang['lost_unlogin_1_day']       = '次日未登录人数';
$lang['lost_unlogin_3_day']       = '3日未登录人数';
$lang['lost_rate_1_day']          = '次日流失率';//'Churn Rate';
$lang['lost_rate_3_day']          = '3日流失率';

//用户信息
$lang['user_reg_date']            = '注册时间';
$lang['user_reg_ip']              = '注册IP';
$lang['user_account_id']          = '账号ID';
$lang['user_role_name']           = '角色名';
$lang['user_role_id']             = '角色ID';
$lang['user_last_login']          = '最后登录';
$lang['user_last_login_ip']       = '最后登录IP';
$lang['user_mac']                 = '设备号';
$lang['client_type']              = '机型';

//注册转化
$lang['prof'] = '职业';//profession


//充值
$lang['n_pay'] = '充值';
$lang['n_analysis'] = '数据分析';
$lang['n_develope_statistical'] = '养成统计';
$lang['p_analysis'] = '关卡统计';





