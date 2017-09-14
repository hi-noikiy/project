


# U591 Data Analysis API List

### 修改日志

| 修改时间       | 修改内容                                     |
| ---------- | ---------------------------------------- |
| 2016-01-11 | 增加“每日获取元宝”接口                             |
| 2016-01-26 | 增加“GiveEmoney”接口名称;                      |
|            | 玩家在消费记录（rmb）接口增加viplev，lev两个参数           |
|            | 新增“日常行为”（DailyAction）接口                  |
| 2016-01-27 | 新增"设备激活(DeviceActive)"接口                 |
| 2016-01-28 | 接口"养成&强化(Develop)"添加equip_id参数           |
| 2016-02-17 | 新增"角色创建(CreateRole)"接口                   |
| 2016-02-24 | "设备激活(DeviceActive)"接口添加"client_type"字段  |
| 2016-02-26 | 新增批量提交功能                                 |
| 2016-03-20 | 副本接口(CopyProgress)添加"副本ID(copy_id)"字段;关卡进度接口(LevelProcess)添加"关卡ID(level_id)"字段;成就进度接口(SuccessProcess)添加"成就ID(success_id)"字段; |
| 2016-03-26 | 道具接口(Props) 添加字段"道具名称(prop_name)         |
| 2016-07-25 | 新增`RegisterProcess`,注册流程                 |
| 2016-07-26 | 新增`GetRegisterProcess`,注册流程              |
| 2016-07-27 | `Login`新增`userid`字段，`CreateRole`新增`username`字段 |
| 2016-08-29 | `FirstLogin` |
| 2016-10-14 | `Login`接口新增`trainer_lev`,`Register`接口新增`regway`,新增`Recharge`接口,新增`Logout`接口 |
| 2016-10-18 | `RegisterProcess`接口新增`client_version` |
| 2016-10-26 | 接口`GameProcess`新增 |

> 提交数据格式说明：
>
> 00.关于认证：使用access token的方式验证。
>
> 01.提交方式：POST，提交数据放在data字段中。
>
> 02.请求地址：http://host/api/接口名称
>
> 所有提交的数据使用base64编码后的json格式提交。



|           参数 | 说明         | 类型     |
| -----------: | ---------- | :----- |
| access_token | 与sdk交互获得的  | string |
|         data | 每个接口所封装的数据 | string |

举例如下：

数据封装:

``` php
$json_data    = {"userid":1222,"lev":112,"viplev":10};
$request_data = base64_encode($json);//最终提交的数据
```

数据提交：

``` shell
curl -d "accesstoken=1123232xxxx&data=eyJ1c2VyaWQiOjEwMDEwMzUsICJzZXJ2ZXJpZCI6OTI0MiwgImRheXRpbWUiOiAxMTUxMDIyLCAiZW1vbmV5IjogMjUsICJjaGFubmVsIjogNjAwMTYsICJsZXYiOiA4MCwgInR5cGUiOiAzNDYxMDAwMDM1fQ" "http://123.59.74.49:8088/api/Login"
```

## 加密方式

### 方式1:简单加密
- appid_MD5（每个app分配一个key + 提交参数data ）,下划线分隔
  appid是做不同游戏区分用,下划线之后的数据为md5(提交的数据).
  举例说明: 10002_220466675e31b9d20c051d5e57974150

### 方式2:获取accesstoken 方式
- 获取accesstoken

access_token是SDK的全局唯一票据，SDK调用各接口时都需使用access_token。开发者需要进行妥善保存。access_token的存储至少要保留512个字符空间。access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效。

**接口调用请求说明**

``` 
http请求方式: GET

http://ip host/api/AccessToken?appid＝APPID&secret=APP密钥
```

**参数说明**

| 参数     | 是否必须 | 说明                  |
| ------ | ---- | ------------------- |
| appid  | 是    | 用户唯一凭证              |
| secret | 是    | 用户唯一凭证密钥，即appsecret |

正常情况下，接口会返回下述JSON数据包给开发者：

``` 
{"access_token":"ACCESS_TOKEN","expires_in":7200}
```

| 参数           | 说明          |
| ------------ | ----------- |
| access_token | 获取到的凭证      |
| expires_in   | 凭证有效时间，单位：秒 |

错误时接口会返回错误码等信息，JSON数据包示例如下（该示例为AppID无效错误）:

``` 
{"errcode":4001,"errmsg":"invalid appid"}
```

接口调用接口并不是无限制的。为了防止开发者的程序错误而引发接口服务器负载异常，默认情况下，每个开发者调用接口都不能超过一定限制，当超过一定限制时，调用对应接口会收到如下错误返回码：

``` 
{"errcode":4002,"errmsg":"api freq out of limit"}
```



---
## 批量提交
提交数据时将原有的json数据转变为用数组的方式即可，字段不变
```javascript
[
{"total_rmb":"45000","serverid":"1111","lev":"1","create_time":"0","userid":"201213","channel":"2222","accountid":"3333","viplev":"5","online":"3000"},
{"total_rmb":"45000","serverid":"1111","lev":"1","create_time":"0","userid":"201213","channel":"2222","accountid":"3333","viplev":"5","online":"3002"},
{"total_rmb":"45000","serverid":"1111","lev":"1","create_time":"0","userid":"201213","channel":"2222","accountid":"3333","viplev":"5","online":"3003"},
{"total_rmb":"45000","serverid":"1111","lev":"1","create_time":"0","userid":"201213","channel":"2222","accountid":"3333","viplev":"5","online":"3004"},
{"total_rmb":"45000","serverid":"1111","lev":"1","create_time":"0","userid":"201213","channel":"2222","accountid":"3333","viplev":"5","online":"3005"},
{"total_rmb":"45000","serverid":"1111","lev":"1","create_time":"0","userid":"201213","channel":"2222","accountid":"3333","viplev":"5","online":"3006"}
]
```

## 玩家登录

接口名称：Login

| 参数          | 类型     | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ----- | ------------------ |
| mac         | string | mac地址 |                    |
| userid      | int    | 角色ID  | T                  |
| accountid   | int    | 账号ID  |                    |
| serverid    | int    | 区服ID  |                    |
| channel     | int    | 渠道ID  |                    |
| viplev      | int    | vip等级 |                    |
| trainer_lev | int    | 训练师等级 |                    |
| lev         | int    | 角色等级  |                    |
| client_type | string | 客户端类型 | T                  |
| ip          | string | ip地址  |                    |

## 实时在线记录

接口名称：Online

| 参数             | 类型   | 说明       | 是否可为空,F:否,T:是（默认F） |
| -------------- | ---- | -------- | ------------------ |
| serverid       | int  | 区服ID     |                    |
| online         | int  | 在线人数     |                    |
| MaxOnline      | int  | 最大在线人数   |                    |
| WorldOnline    | int  | 全服在线人数   |                    |
| WorldMaxOnline | int  | 全服最多在线人数 |                    |
| daytime        | int  | 全服最多在线人数 |                    |

## 玩家在线数据（玩家下线时提交数据）

接口名称：DayOnline

| 参数          | 类型   | 说明             | 是否可为空,F:否,T:是（默认F） |
| ----------- | ---- | -------------- | ------------------ |
| serverid    | int  | 区服ID           |                    |
| userid      | int  | 角色ID           |                    |
| accountid   | int  | 账号ID           |                    |
| online      | int  | 在线时长（单位:**秒**) |                    |
| viplev      | int  | vip等级          | T                  |
| channel     | int  | 渠道             |                    |
| lev         | int  | 玩家等级           |                    |
| create_time | int  | 角色创建时间         | T                  |
| total_rmb   | int  | 账户金额           |                    |

## 玩家在消费记录

接口名称：Rmb

| 参数        | 类型   | 说明      | 是否可为空,F:否,T:是（默认F） |
| --------- | ---- | ------- | ------------------ |
| serverid  | int  | 区服ID    |                    |
| userid    | int  | 角色ID    |                    |
| accountid | int  | 账号ID    |                    |
| channel   | int  | 渠道      |                    |
| emoney    | int  | 消费的元宝   |                    |
| type      | int  | 消费类型    |                    |
| itemtype  | int  | 消费的商品ID |                    |
| viplev    | int  | vip等级   |                    |
| lev       | int  | 会员等级    |                    |

## 玩家获得元宝记录

接口名称：GiveEmoney

| 参数        | 类型   | 说明     | 是否可为空,F:否,T:是（默认F） |
| --------- | ---- | ------ | ------------------ |
| serverid  | int  | 区服ID   |                    |
| userid    | int  | 角色ID   |                    |
| accountid | int  | 账号ID   |                    |
| channel   | int  | 渠道     |                    |
| emoney    | int  | 获得的元宝数 |                    |
| item_type | int  | 资源类型   |                    |
|           |      |        |                    |

## 玩家注册

接口名称：Register

| 参数          | 类型     | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ----- | ------------------ |
| serverid    | int    | 区服ID  |                    |
| channel     | int    | 渠道ID  |                    |
| mac         | string | mac地址 |                    |
| client_type | string | 客户端类型 | T                  |
| accountid   | int    | 账号ID  |                    |
| ip          | string | ip地址  |                    |
| regway      | int    | 注册方式：1游客登录，2手机登录，3邮箱登录  |                    |

## 玩家基本信息

接口名称：PlayerBasicInfo(新增)／UpdatePlayer(更新)

| 参数          | 类型     | 说明         | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ---------- | ------------------ |
| serverid    | int    | 区服ID       |                    |
| channel     | int    | 渠道ID       |                    |
| userid      | int    | 角色ID       |                    |
| accountid   | int    | 账号ID       |                    |
| lev         | int    | 玩家等级       |                    |
| user_name   | string | 玩家昵称       |                    |
| gender      | int    | 玩家性别(0男1女) |                    |
| client_type | string | 客户端类型      | T                  |

## 玩家副本记录

接口名称：CopyProgress

| 参数         | 类型     | 说明     | 是否可为空,F:否,T:是（默认F） |
| ---------- | ------ | ------ | ------------------ |
| serverid   | int    | 区服ID   |                    |
| channel    | int    | 渠道ID   |                    |
| userid     | int    | 角色ID   |                    |
| accountid  | int    | 账号ID   |                    |
| lev        | int    | 副本等级   |                    |
| type       | int    | 副本类型   |                    |
| copy_id    | string | 副本ID   |                    |
| title      | string | 副本名称   |                    |
| is_success | int    | 成功1失败0 |                    |

## 养成&强化

接口名称：Develop

| 参数        | 类型   | 说明      | 是否可为空,F:否,T:是（默认F） |
| --------- | ---- | ------- | ------------------ |
| serverid  | int  | 区服ID    |                    |
| channel   | int  | 渠道ID    |                    |
| userid    | int  | 角色ID    |                    |
| accountid | int  | 账号ID    |                    |
| lev       | int  | 角色等级    |                    |
| viplev    | int  | 会员等级    |                    |
| type      | int  | 养成/强化种类 |                    |
| progress  | int  | 养成/强化进度 |                    |
| equip_id  | int  | 装备ID    |                    |

## 道具

接口名称：Props

| 参数        | 类型     | 说明                | 是否可为空,F:否,T:是（默认F） |
| --------- | ------ | ----------------- | ------------------ |
| serverid  | int    | 区服ID              |                    |
| channel   | int    | 渠道ID              |                    |
| userid    | int    | 角色ID              |                    |
| accountid | int    | 账号ID              |                    |
| action    | int    | 消耗or获取，0代表获取1代表消耗 |                    |
| prop_type | int    | 道具类型（需后台配置）       |                    |
| prop_id   | int    | 道具ID              |                    |
| prop_name | string | 道具名称              |                    |
| amounts   | int    | 获取or使用数量          |                    |
| gain_way  | int    | 获取获取途径（需后台配置）     | F                  |

## 关卡进度

接口名称：LevelProcess

| 参数            | 类型     | 说明       | 是否可为空,F:否,T:是（默认F） |
| ------------- | ------ | -------- | ------------------ |
| serverid      | int    | 区服ID     |                    |
| channel       | int    | 渠道ID     |                    |
| userid        | int    | 角色ID     |                    |
| accountid     | int    | 账号ID     |                    |
| lev           | int    | 角色等级     |                    |
| viplev        | int    | 会员等级     |                    |
| level_type    | int    | 关卡类型     |                    |
| level_id      | int    | 关卡ID     |                    |
| highest_level | string | 通关最高关卡名称 |                    |

## 成就进度

接口名称：SuccessProcess

| 参数              | 类型     | 说明               | 是否可为空,F:否,T:是（默认F） |
| --------------- | ------ | ---------------- | ------------------ |
| serverid        | int    | 区服ID             |                    |
| channel         | int    | 渠道ID             |                    |
| userid          | int    | 角色ID             |                    |
| accountid       | int    | 账号ID             |                    |
| lev             | int    | 角色等级             |                    |
| viplev          | int    | 会员等级             |                    |
| success_type    | int    | 成就类型             |                    |
| success_id      | int    | 成就ID             |                    |
| highest_success | string | 完成最高成就名称(是系统配置？) |                    |

## 日常行为

接口名称：DailyActions

| 参数          | 类型   | 说明         | 是否可为空,F:否,T:是（默认F） |
| ----------- | ---- | ---------- | ------------------ |
| serverid    | int  | 区服ID       |                    |
| channel     | int  | 渠道ID       |                    |
| userid      | int  | 角色ID       |                    |
| accountid   | int  | 账号ID       |                    |
| action_type | int  | 行为类型       |                    |
| viplev      | int  | vip等级      |                    |
| lev         | int  | 角色等级       |                    |
| use_time    | int  | 行为耗时,单位"秒" |                    |

## 升级历程

接口名称：UpgradeProcess

| 参数        | 类型   | 说明   | 是否可为空,F:否,T:是（默认F） |
| --------- | ---- | ---- | ------------------ |
| serverid  | int  | 区服ID |                    |
| channel   | int  | 渠道ID |                    |
| userid    | int  | 角色ID |                    |
| accountid | int  | 账号ID |                    |
| lev       | int  | 角色等级 |                    |

## 充值

接口名称：PaylogProcess

| 参数        | 类型   | 说明     | 是否可为空,F:否,T:是（默认F） |
| --------- | ---- | ------ | ------------------ |
| accountid | int  | 账号ID   |                    |
| orderid   | int  | 订单ID   |                    |
| serverid  | int  | 区服ID   |                    |
| channel   | int  | 渠道ID   |                    |
| money     | int  | RMB    |                    |
| paytime   | int  | 支付时间   |                    |
| lev       | int  | 角色等级   |                    |
| is_new    | int  | 0老用户1新 |                    |

## 设备激活

接口名称：DeviceActive

| 参数          | 类型     | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ----- | ------------------ |
| serverid    | int    | 区服ID  |                    |
| channel     | int    | 渠道ID  |                    |
| client_type | sting  | 客户端类型 |                    |
| mac         | string | mac地址 |                    |

## 创建角色

接口名称：CreateRole

| 参数               | 类型     | 说明             | 是否可为空,F:否,T:是（默认F） |
| ---------------- | ------ | -------------- | ------------------ |
| serverid         | int    | 区服ID           |                    |
| channel          | int    | 渠道ID           |                    |
| mac              | string | mac地址          |                    |
| userid           | int    | 角色ID           |                    |
| username         | int    | 角色名称           | T                  |
| accountid        | int    | 账号id           |                    |
| role_create_time | int    | 角色创建时间,UNIX时间戳 |                    |

## Bug收集

接口名称：BugReport

| 参数          | 类型    | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ----- | ----- | ------------------ |
| userid      | int   | 角色ID  |                    |
| username    | sting | 角色名称  |                    |
| accountid   | int   | 账号id  |                    |
| client_type | sting | 客户端类型 |                    |
| content     | sting | BUG内容 |                    |


## 记录首次登录数据

接口名称：FirstLogin

| 参数          | 类型    | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ----- | ----- | ------------------ |
| accountid   | int   | 账号id  |       否          |
| mac         | sting | mac |     否          |


## 注册流程统计

接口名称：RegisterProcess

| 参数        | 类型   | 说明                                  | 是否可为空,F:否,T:是（默认F） |
| --------- | ---- | ----------------------------------- | ------------------ |
| type_id   | int  | 类型id,可以通过`GetRegisterProcess`接口读取配置 | 否                  |
| reason_id | int  | 原因ID,预留字段                           | 是                  |
| mac       | string  | mac地址                           | 是                  |
| client_version | string  | 客户端版本                           | 是                  |

## 获取注册流程类型列表

接口名称：GetRegisterProcess
提交方式: Get

> eg:通过请求:http://guntj.u591.com:8080/Api/GetRegisterProcess?appid=10002获取配置

| 参数    | 类型   | 说明   | 是否可为空,F:否,T:是（默认F） |
| ----- | ---- | ---- | ------------------ |
| appid | int  |      | 否                  |


## 从指定渠道打开游戏

接口名称：GameStartChannel

| 参数          | 类型     | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ----- | ------------------ |
| eventid     | int    | 事件id，此处写死为1  |
| channel     | int    | 渠道ID  |                    |
| mac         | string | mac地址 |                    |

## 充值

接口名称：Recharge

| 参数          | 类型     | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ----- | ------------------ |
| serverid         | int    | 区服ID           |                    |
| channel     | int    | 渠道ID  |        T  |
| rmb         | int    | 充值金额，单位：元，不能有小数点 |    F |
| accountid   | int   | 账号id  |     F |
| userid           | int    | 角色ID           |       F  |
| viplev      | int    | vip等级 |     F   |
| trainer_lev      | int    | 训练师等级 |   F   |
| client_timestamp      | int    | 请求时间,10位数unix时间戳 |   T   |

## 登出

接口名称：Logout

| 参数          | 类型     | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ----- | ------------------ |
| serverid         | int    | 区服ID           |                    |
| accountid   | int   | 账号id  |     F |
| userid           | int    | 角色ID           |       F  |
| online      | int    | 本次在线时长，单位：秒 |    F |
| viplev      | int    | vip等级 |     F   |
| trainer_lev      | int    | 训练师等级 |   F   |
| client_timestamp      | int    | 请求时间,10位数unix时间戳 |   T   |

## 游戏流程统计

接口名称：GameProcess


| 参数          | 类型     | 说明    | 是否可为空,F:否,T:是（默认F） |
| ----------- | ------ | ----- | ------------------ |
| server_name         | string    | 区服           |                    |
| serverid         | int    | 区服ID           |                    |
| accountid   | int   | 账号id  |     F |
| userid           | int    | 角色ID           |       F  |
| vip_level      | int    | vip等级 |     F   |
| user_lev      | int    | user等级 |     F   |
| client_time      | int    | 请求时间,10位数unix时间戳 |   T   |
| process_index      | int    | 请求时间,10位数unix时间戳 |   T   |
| process_result      | int    | 请求时间,10位数unix时间戳 |   T   |