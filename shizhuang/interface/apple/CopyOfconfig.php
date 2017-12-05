<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午2:07
 */
define('ROOT_PATH', str_replace('interface/apple/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$appleIdVal = array(
    'com.u776.poke.ios.product6'    =>'6',
    'com.u776.poke.ios.product18'   =>'18',

    'com.niuniu.vs_60'              =>'6',
    'com.niuniu.vs_250'             =>'25',
    'com.niuniu.vs_300'             =>'30',
    'com.niuniu.vs_1280'            =>'128',
    'com.niuniu.vs_3280'            =>'328',
    'com.niuniu.vs_6480'            =>'648',

		
		'jlzt_sv2_6'                 =>'6',
		'jlzt_sv2_25'                =>'25',
		'jlzt_sv2_30'                =>'30',
		'jlzt_sv2_98'                =>'98',
		'jlzt_sv2_128'               =>'128',
		'jlzt_sv2_328'               =>'328',
		'jlzt_sv2_648'               =>'648',
		
    'jldmx_vs7_6'              =>'6',
        'jldmx_vs7_25'             =>'25',
        'jldmx_vs7_30'             =>'30',
        'jldmx_vs7_128'            =>'128',
        'jldmx_vs7_328'            =>'328',
        'jldmx_vs7_648'            =>'648',
        
        'jlxcw_sv8_6'              =>'6',
        'jlxcw_sv8_25'             =>'25',
        'jlxcw_sv8_30'             =>'30',
        'jlxcw_sv8_128'            =>'128',
        'jlxcw_sv8_328'            =>'328',
        'jlxcw_sv8_648'            =>'648',
        
        'ygkd_vs9_6'              =>'6',
        'ygkd_vs9_25'             =>'25',
        'ygkd_vs9_30'             =>'30',
        'ygkd_vs9_128'            =>'128',
        'ygkd_vs9_328'            =>'328',
        'ygkd_vs9_648'            =>'648',
        
        'jldg_vs2_6'              =>'6',
        'jldg_vs2_25'             =>'25',
        'jldg_vs2_30'             =>'30',
        'jldg_vs2_128'            =>'128',
        'jldg_vs2_328'            =>'328',
        'jldg_vs2_648'            =>'648',
        
        'kdlm_vs3_6'              =>'6',
        'kdlm_vs3_25'             =>'25',
        'kdlm_vs3_30'             =>'30',
        'kdlm_vs3_128'            =>'128',
        'kdlm_vs3_328'            =>'328',
        'kdlm_vs3_648'            =>'648',

    'appBuyItem_zhuxian_1'          =>'6',
    'appBuyItem_zhuxian_2'          =>'30',
    'appBuyItem_zhuxian_3'          =>'128',
    'appBuyItem_zhuxian_4'          =>'328',
    'appBuyItem_zhuxian_5'          =>'648',
    'appBuyItem_zhuxian_6'          =>'25',

    'kdysy_sx1_6'                   =>'6',
    'kdysy_sx1_25'                  =>'25',
    'kdysy_sx1_30'                  =>'30',
    'kdysy_sx1_128'                 =>'128',
    'kdysy_sx1_328'                 =>'328',
    'kdysy_sx1_648'                 =>'648',

    'jlsj_vs1_6'                    =>'6',
    'jlsj_vs1_25'                   =>'25',
    'jlsj_vs1_30'                   =>'30',
    'jlsj_vs1_98'                   =>'98',
    'jlsj_vs1_128'                  =>'128',
    'jlsj_vs1_328'                  =>'328',
    'jlsj_vs1_648'                  =>'648',

    'kdjh_vs2_6'                    =>'6',
    'kdjh_vs2_25'                   =>'25',
    'kdjh_vs2_30'                   =>'30',
    'kdjh_vs2_98'                   =>'98',
    'kdjh_vs2_128'                  =>'128',
    'kdjh_vs2_328'                  =>'328',
    'kdjh_vs2_648'                  =>'648',

    'jldg_vs3_6'                    =>'6',
    'jldg_vs3_25'                   =>'25',
    'jldg_vs3_30'                   =>'30',
    'jldg_vs3_98'                   =>'98',
    'jldg_vs3_128'                  =>'128',
    'jldg_vs3_328'                  =>'328',
    'jldg_vs3_648'                  =>'648',

    'kdygsy_sx1_6'                  =>'6',
    'kdygsy_sx1_25'                 =>'25',
    'kdygsy_sx1_30'                 =>'30',
    'kdygsy_sx1_98'                 =>'98',
    'kdygsy_sx1_128'                =>'128',
    'kdygsy_sx1_328'                =>'328',
    'kdygsy_sx1_648'                =>'648',

    'xkdjl_sx2_6'                   =>'6',
    'xkdjl_sx2_25'                  =>'25',
    'xkdjl_sx2_30'                  =>'30',
    'xkdjl_sx2_98'                  =>'98',
    'xkdjl_sx2_128'                 =>'128',
    'xkdjl_sx2_328'                 =>'328',
    'xkdjl_sx2_648'                 =>'648',

    'xkdfk_sx3_6'                   =>'6',
    'xkdfk_sx2_25'                  =>'25',
    'xkdfk_sx2_30'                  =>'30',
    'xkdfk_sx2_98'                  =>'98',
    'xkdfk_sx2_128'                 =>'128',
    'xkdfk_sx2_328'                 =>'328',
    'xkdfk_sx2_648'                 =>'648',

    'zdbyg_sx4_6'                   =>'6',
    'zdbyg_sx4_25'                  =>'25',
    'zdbyg_sx4_30'                  =>'30',
    'zdbyg_sx4_98'                  =>'98',
    'zdbyg_sx4_128'                 =>'128',
    'zdbyg_sx4_328'                 =>'328',
    'zdbyg_sx4_648'                 =>'648',

    'bljlq_sx5_6'                   =>'6',
    'bljlq_sx5_25'                  =>'25',
    'bljlq_sx5_30'                  =>'30',
    'bljlq_sx5_98'                  =>'98',
    'bljlq_sx5_128'                 =>'128',
    'bljlq_sx5_328'                 =>'328',
    'bljlq_sx5_648'                 =>'648',

    'sqhkzbl_vs4_6'                 =>'6',
    'sqhkzbl_vs4_25'                =>'25',
    'sqhkzbl_vs4_30'                =>'30',
    'sqhkzbl_vs4_98'                =>'98',
    'sqhkzbl_vs4_128'               =>'128',
    'sqhkzbl_vs4_328'               =>'328',
    'sqhkzbl_vs4_648'               =>'648',


    'sqjlznl_vs4_6'                 =>'6',
    'sqjlznl_vs4_25'                =>'25',
    'sqjlznl_vs4_30'                =>'30',
    'sqjlznl_vs4_98'                =>'98',
    'sqjlznl_vs4_128'               =>'128',
    'sqjlznl_vs4_328'               =>'328',
    'sqjlznl_vs4_648'               =>'648',


    'acdzz_vs5_6'                 =>'6',
    'acdzz_vs5_25'                =>'25',
    'acdzz_vs5_30'                =>'30',
    'acdzz_vs5_98'                =>'98',
    'acdzz_vs5_128'               =>'128',
    'acdzz_vs5_328'               =>'328',
    'acdzz_vs5_648'               =>'648',

    'cfbxz_vs6_6'                 =>'6',
    'cfbxz_vs6_25'                =>'25',
    'cfbxz_vs6_30'                =>'30',
    'cfbxz_vs6_98'                =>'98',
    'cfbxz_vs6_128'               =>'128',
    'cfbxz_vs6_328'               =>'328',
    'cfbxz_vs6_648'               =>'648',
		
		'xkdjl_sx2_6'                 =>'6',
		'xkdjl_sx2_25'                =>'25',
		'xkdjl_sx2_30'                =>'30',
		'xkdjl_sx2_98'                =>'98',
		'xkdjl_sx2_128'               =>'128',
		'xkdjl_sx2_328'               =>'328',
		'xkdjl_sx2_645'               =>'648',
		
		'xkdfk_sx3_6'                 =>'6',
		'xkdfk_sx3_25'                =>'25',
		'xkdfk_sx3_30'                =>'30',
		'xkdfk_sx3_98'                =>'98',
		'xkdfk_sx3_128'               =>'128',
		'xkdfk_sx3_328'               =>'328',
		'xkdfk_sx3_645'               =>'648',
		
		'zdbyg_sx4_6'                 =>'6',
		'zdbyg_sx4_25'                =>'25',
		'zdbyg_sx4_30'                =>'30',
		'zdbyg_sx4_98'                =>'98',
		'zdbyg_sx4_128'               =>'128',
		'zdbyg_sx4_328'               =>'328',
		'zdbyg_sx4_645'               =>'648',
		
		'bljlq_sx5_6'                 =>'6',
		'bljlq_sx5_25'                =>'25',
		'bljlq_sx5_30'                =>'30',
		'bljlq_sx5_98'                =>'98',
		'bljlq_sx5_128'               =>'128',
		'bljlq_sx5_328'               =>'328',
		'bljlq_sx5_645'               =>'648',
		


    'scgpokevs_80'      => array('0.99','80', 'USD'), //美元 元宝
	'scgpokevs_84'      => array('0.99','80', 'USD'), //美元 元宝
    'scgpokevs_425'     => array('4.99', '430', 'USD'),
    'scgpokevs_851'     => array('9.99', '850', 'USD'),
    'scgpokevs_1703'    => array('19.99', '1700', 'USD'),
    'scgpokevs_4259'    => array('49.99', '4260', 'USD'),
    'scgpokevs_8519'    => array('99.99', '8520', 'USD'),

);