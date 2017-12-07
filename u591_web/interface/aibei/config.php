<?php
define('ROOT_PATH', str_replace('interface/aibei/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

//爱贝商户后台接入url
// $coolyunCpUrl="http://pay.coolyun.com:6988";
$iapppayCpUrl="http://ipay.iapppay.com:9999";
//登录令牌认证接口 url
$tokenCheckUrl=$iapppayCpUrl . "/openid/openidcheck";

//下单接口 url
// $orderUrl=$coolyunCpUrl . "/payapi/order";
$orderUrl=$iapppayCpUrl . "/payapi/order";

//支付结果查询接口 url
$queryResultUrl=$iapppayCpUrl ."/payapi/queryresult";

//契约查询接口url
$querysubsUrl=$iapppayCpUrl."/payapi/subsquery";

//契约鉴权接口Url
$ContractAuthenticationUrl=$iapppayCpUrl."/payapi/subsauth";

//取消契约接口Url
$subcancel=$iapppayCpUrl."/payapi/subcancel";
//H5和PC跳转版支付接口Url
$h5url="https://web.iapppay.com/h5/gateway?";
$pcurl="https://web.iapppay.com/pc/gateway?";

$karr = array(
	'android'=>array(
			'appid'=>'3013149615',
			'appkey'=>"MIICXQIBAAKBgQC+dI7SPSg4iwwHrbced5kMDL3ybasqPNzZRjJrvKCGTOii2elCeD7PTImS0j2RrXhBqUjstmcb9c53OEDaneRMYjlJ3La9UXatDwulBhrZdUmkPnoUeBMld9mGN7qnFUGP/jEho/1ZeFlbRErymkaHHILZ7zScg7AA2aqTsfrn+wIDAQABAoGBAKuyVJdAPyRKZjv95Zn34+ezDQKiz64yVD8kQ4xQ7r5kU02M+fbMhINJ0rSkCJFuO32maXHNNsNEJC/ibMUploeeHvDNDs+xuzkK1ExfE9UyTJHdPt5QCfvhEZs4J6uxqOBs9+vUHkLRWMn8tfK/hra3SwNJG59EBFYhFrhHLSohAkEA8nOUc5UGpetCxfy9x4JgwT5+5IVDgWNnlhhfUsNE4x1qCvRIHZDmPTPAYbTfUvZL/5Hx/5BK7+tqoxd+YkiK6QJBAMkZI+AMEk0bqi3S9u66xSCjIM12EgX/dbvGJGq/OqOKfg6nuRsPpsBr+94bMeONu15g61C2rh0OIAnazh8IBUMCQDE2wuB/VJ0jVyeHOSKhVXCEdg9++YfvHJy8D8Vl+7q6Qsc8dNMDkajEs31h0J8vfZfAEUZWDNAMklXOtAnRPdkCQQC25iyg25nRk7XGE7gPDMO5mWR+OkLOPbgA3ofEpsRYdd+MyLMsj6GkJRJgwe8v/XpKrm+Xa28UcgJltCIASzUtAkBc4wptB25KBxGJ+ifW6I+IQUaQCVqB/sYvaG3FhX/gSqJFW9i2PxGdzcf90rg2KhHdsiD8q8xgDF8FGQvwNN9B",
			'platpkey'=>"MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCBpcCquaxMYoc41B9f4mWH/u8/tlqU82A1M4vnaI29kZtPYTyqglDjBcR1CASWO6GRZO2/v/oaayVqtfHuCmFJfA5AIsVrHMgRpLvD3HocjEO1vIH9MNVmwRc+EQIAcYYTokdrE3nNbgCgvo6B4Do4FuPvPDi5l5BVr50wzc8AuQIDAQAB",
	),
		'android1'=>array(
				'appid'=>'3015222253',
				'appkey'=>"MIICXQIBAAKBgQCuwW+iZWsgge7ppFZFub5m5yHqjD34SbtjwxRyprLxQJLoK/eZjfKG6ngQTHOT1liiM/BA9oBpfrPHhIdfETOjUAgAHZe3IfP971dG/ygWsVH0FgjG7M2ruY7a9nBlQ2qxWRSGRzQ+PA4SbEqktc/FaWwaGMvqo8Fq2QZGvFXzHQIDAQABAoGAdlGZl8Y65eOTMrWFg+fitiE/oWIagwTIzx7BtW6QMS+bR36dQWYOErKlX8OjbTDvCcNIo3NCNisG5cshLIilAIii4ZIyv+uzlijY1NELFp39lvHS7YEKxXr40nogG+qq0lUZ1kPah9jHzOY5Ky8bLrxL40iKw1WHR1njC7sMLzECQQDyG+W5YP99aqHE3RKmTU0FidAkoU4mIM7PMHlLpYyeD2jWu2AXNvr5KioqHSOPtzJCIwJYQbMVXVE4D6TZ3nN3AkEAuMhANZ5BIAEw/DZqefLQQ9bKDx4K7UJqGCQxyYRPLfu8c5nFKM69cCurQLBYsTG4AuxSvGuX/vLUgSv0lS4rCwJBAIAnKChCIbq/MLXF11NT44sk8ZoSEA26m/2ryOizzoTz3yiasjhXj+1H1l3IYsR10hKT+W/CBwsuBAnKrhXs2+0CQASAtLfZxGKARO5QcSXJmfOdP01BASko7ibqcuAjPj6znLMAc0J8Tzd1iPXJlk+zE3XWxmlbAdgvmKgRJyOffWMCQQCT1s2cDRUflxkI4Lv5leuBLKbnuCgcvNRc/ZBuf+0agbkjiX7eedC1EFglb99eIFNi2ln1xwWKJ5vP839MdgMx",
				'platpkey'=>"MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCCf+eMJvvCqAMpEw4os2hP5bvtrFEELPpsh1dbTvvRxFiQUisnyvHjUzfOK0vtndZ/5OOyURQbJKwmChY51EhRdtU29mlCxzCC5UMN5EmxCkMz7umtJrvIb0KnGUovv+01hUMPJmXjtFxyIjMN7QWoZ2KVJAGKKEAcWUp6LefdHQIDAQAB",
		),
);
/*//应用编号
$appid="3013149615";
//应用私钥
$appkey="MIICXQIBAAKBgQC+dI7SPSg4iwwHrbced5kMDL3ybasqPNzZRjJrvKCGTOii2elCeD7PTImS0j2RrXhBqUjstmcb9c53OEDaneRMYjlJ3La9UXatDwulBhrZdUmkPnoUeBMld9mGN7qnFUGP/jEho/1ZeFlbRErymkaHHILZ7zScg7AA2aqTsfrn+wIDAQABAoGBAKuyVJdAPyRKZjv95Zn34+ezDQKiz64yVD8kQ4xQ7r5kU02M+fbMhINJ0rSkCJFuO32maXHNNsNEJC/ibMUploeeHvDNDs+xuzkK1ExfE9UyTJHdPt5QCfvhEZs4J6uxqOBs9+vUHkLRWMn8tfK/hra3SwNJG59EBFYhFrhHLSohAkEA8nOUc5UGpetCxfy9x4JgwT5+5IVDgWNnlhhfUsNE4x1qCvRIHZDmPTPAYbTfUvZL/5Hx/5BK7+tqoxd+YkiK6QJBAMkZI+AMEk0bqi3S9u66xSCjIM12EgX/dbvGJGq/OqOKfg6nuRsPpsBr+94bMeONu15g61C2rh0OIAnazh8IBUMCQDE2wuB/VJ0jVyeHOSKhVXCEdg9++YfvHJy8D8Vl+7q6Qsc8dNMDkajEs31h0J8vfZfAEUZWDNAMklXOtAnRPdkCQQC25iyg25nRk7XGE7gPDMO5mWR+OkLOPbgA3ofEpsRYdd+MyLMsj6GkJRJgwe8v/XpKrm+Xa28UcgJltCIASzUtAkBc4wptB25KBxGJ+ifW6I+IQUaQCVqB/sYvaG3FhX/gSqJFW9i2PxGdzcf90rg2KhHdsiD8q8xgDF8FGQvwNN9B";
//平台公钥
$platpkey="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCBpcCquaxMYoc41B9f4mWH/u8/tlqU82A1M4vnaI29kZtPYTyqglDjBcR1CASWO6GRZO2/v/oaayVqtfHuCmFJfA5AIsVrHMgRpLvD3HocjEO1vIH9MNVmwRc+EQIAcYYTokdrE3nNbgCgvo6B4Do4FuPvPDi5l5BVr50wzc8AuQIDAQAB";
*/
?>
