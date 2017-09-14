<?php

namespace Micro\Frameworks\Logic\Configs;

use Micro\Models\QuestionConfigs;
use Phalcon\DI\FactoryDefault;
use Micro\Models\BaseConfigs;
use Micro\Models\TypeConfig;
use Micro\Models\VipConfigs;
use Micro\Models\AnchorConfigs;
use Micro\Models\RicherConfigs;
use Micro\Models\FansConfigs;
use Micro\Models\GuardConfigs;
use Micro\Models\GiftConfigs;
use Micro\Models\CarConfigs;
use Micro\Models\FoodConfigs;
use Micro\Models\NoticeConfigs;
use Micro\Models\UserInfo;
use Micro\Models\BannerConfig;
use Micro\Models\VipRight;
use Micro\Models\VipRights;
use Micro\Models\GuardRight;
use Micro\Models\GuardRights;
use Micro\Models\EventConfig;
use Micro\Models\Rooms;
use Micro\Models\AnnouncementList;
use Micro\Models\AppVersionConfig;
use Micro\Frameworks\Logic\User\UserFactory;
class ConfigMgr
{

    protected $di;
    protected $status;
    protected $validator;
    protected $typeConfigData;
    protected $config;
    protected $request;
    protected $pathGenerator;
    protected $comm;
    protected $baseCode;
    protected $modelsManager;
    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->validator = $this->di->get('validator');
        $this->config = $this->di->get('config');
        $this->request = $this->di->get('request');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->storage = $this->di->get('storage');
        $this->comm = $this->di->get('comm');
        $this->baseCode = $this->di->get('baseCode');
        $this->baseConfigData = array();
        $this->modelsManager = $this->di->get('modelsManager');
        $this->initBaseConfig();
        $this->typeConfigData = array();
        $this->initTypeConfig();
    }

    public function errLog($errInfo)
    {
        $logger = $this->di->get('logger');
        $logger->error('【ConfigMgr】 error : ' . $errInfo);
    }

    //查询基础表是否有记录，如果无记录，则要添加基础配置信息
    private function initBaseConfig()
    {
        return;
        $config = $this->di->get('config');
        try {
            $baseConfigCount = count($config->dbBaseConfigName);
            $count = BaseConfigs::count();
            if ($count < $baseConfigCount) {
                //将旧数据删除，添加新数据，目前先采用手动删除后台配置数据的方式
                $this->modelsManager = $this->di->get('modelsManager');
                $phql = "DELETE FROM Micro\Models\BaseConfigs";
                $query = $this->modelsManager->createQuery($phql);
                $query->execute();

                //添加新数据
                foreach ($config->dbBaseConfigName as $key => $value) {
                    $dbdata = new BaseConfigs();
                    $dbdata->key = $key;
                    $dbdata->value = $value;
                    $dbdata->save();
                }
            }
        } catch (\Exception $e) {
            $this->errLog('initBaseConfig error ' . $e->getMessage());
        }
    }

    //查询基础类型表是否有记录，如果无记录，则要添加基础类型配置信息
    private function initTypeConfig()
    {
        //return;
        //$config = $this->di->get('config');
        try {
            /*$typeConfigCount = count($config->dbTypeConfigName);
            $count = TypeConfig::count("parentTypeId = 0");
            if ($count < $typeConfigCount) {
                //添加新数据
                foreach ($config->dbTypeConfigName as $key => $value) {
                    $dbdata = new TypeConfig();
                    $dbdata->name = $value[0];
                    $dbdata->typeId = $value[1];
                    $dbdata->parentTypeId = 0;
                    $dbdata->createTime = time();
                    $dbdata->save();
                }
            }*/

            $configDataList = TypeConfig::find("parentTypeId = 0");
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['typeId'] = $configData->typeId;
                    $data['parentTypeId'] = $configData->parentTypeId;

                    $this->typeConfigData[$data['name']] = $data;
                }
            }
        } catch (\Exception $e) {
            $this->errLog('initTypeConfig error ' . $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  基础配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getBaseConfigList()
    {

    }

    public function getBaseConfigValue($key)
    {
        try {
            $baseConfigData = BaseConfigs::findFirst(
                array(
                    "conditions" => "key = '" . $key . "'",
                )
            );

            if (empty($baseConfigData)) {
                $this->errLog('getBaseConfigValue key = ' . $key . ' not exist ');
                return null;
            }

            return $baseConfigData->value;
        } catch (\Exception $e) {
            $this->errLog('getBaseConfigValue key = ' . $key . ' error ' . $e->getMessage());
            return null;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  基础类型配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getTypeConfigList($keyName, $skip = 0, $limit = 100)
    {
        try {
            $count = TypeConfig::count("parentTypeId = " . $this->typeConfigData[$keyName]['typeId']);
            $configDataList = TypeConfig::find(
                array(
                    "conditions" => "parentTypeId = " . $this->typeConfigData[$keyName]['typeId'],// . " and showStatus = 0" and sellStatus = 0
                    "order" => "id asc",
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );


            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['typeId'] = $configData->typeId;
                    $data['parentTypeId'] = $configData->parentTypeId;
                    $data['createTime'] = $configData->createTime;
                    $data['description'] = $configData->description;
                    $data['roomAnimate'] = $configData->roomAnimate;
                    $data['showStatus'] = $configData->showStatus;
                    $data['sellStatus'] = $configData->sellStatus;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addTypeConfig($keyName, $name, $typeId, $description, $roomAnimate)
    {
        try {
            $dbdata = new TypeConfig();
            $dbdata->name = $name;
            $dbdata->typeId = $typeId;
            $dbdata->parentTypeId = $this->typeConfigData[$keyName]['typeId'];
            $dbdata->description = $description;
            $dbdata->roomAnimate = $roomAnimate;
            $dbdata->createTime = time();
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delTypeConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = TypeConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateTypeConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = TypeConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getTypeConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = TypeConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['typeId'] = $configData->typeId;
            $data['parentTypeId'] = $configData->parentTypeId;
            $data['createTime'] = $configData->createTime;
            $data['description'] = $configData->description;
            $data['roomAnimate'] = $configData->roomAnimate;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  VIP配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getVipConfigList($skip, $limit)
    {
        try {
            $count = VipConfigs::count();
            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET " . $skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT " . $limit . " " . $cond;
            }

            $modelsManager = $this->di->get('modelsManager');
            $phql = "SELECT * FROM Micro\Models\VipConfigs ORDER BY level " . $cond;
            $query = $modelsManager->createQuery($phql);
            $configDataList = $query->execute();

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['level'] = $configData->level;
                    $data['lower'] = $configData->lower;
                    $data['higher'] = $configData->higher;
                    $data['carId'] = $configData->carId;
                    $data['description'] = $configData->description;

                    array_push($dataList, $data);
                }
            }
            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获取最大VIP等级
     * */
    public function getMaxVipLevel()
    {
        try {
            $vipConfig = VipConfigs::findfirst(
                array(
                    "columns" => "MAX(level) as maxLevel",
                )
            );
            if ($vipConfig) {
                return $vipConfig->maxLevel;
            }

            return 0;
        } catch (\Exception $e) {
            $this->errLog('getMaxVipLevel error ' . $e->getMessage());
            return 0;
        }
    }

    public function addVipConfig($level, $lower, $higher, $description, $carId, $rightlist)
    {
        try {
            $dbdata = new VipConfigs();
            $dbdata->level = $level;
            $dbdata->lower = $lower;
            $dbdata->higher = $higher;
            $dbdata->carId = $carId;
            $dbdata->description = $description;
            $dbdata->rightlist = $rightlist;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delVipConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = VipConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateVipConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = VipConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获取VIP信息
     * */
    public function getVipInfo($level)
    {
        $postData['level'] = $level;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = VipConfigs::findFirst('level = ' . $level);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['level'] = $configData->level;
            $data['lower'] = $configData->lower;
            $data['higher'] = $configData->higher;
            $data['carId'] = $configData->carId;
            $data['description'] = $configData->description;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getVipConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = VipConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['level'] = $configData->level;
            $data['lower'] = $configData->lower;
            $data['higher'] = $configData->higher;
            $data['carId'] = $configData->carId;
            $data['description'] = $configData->description;
            $data['rightlist'] = $configData->rightlist;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取VIP配置信息，高级接口
     * @param $conditions "uid = :uid:"
     * @param $parameters array("uid" => $this->uid)
     * @param $columns "column1, column2"
     * @return
     */
    public function getVipConfigInfoEx($conditions, $parameters, $columns = null)
    {
        try {
            $findParam = array($conditions, "bind" => $parameters);
            if ($columns != null) {
                $findParam['columns'] = $columns;
            }
            $configData = VipConfigs::findFirst($findParam);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data = $configData->toArray();
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  主播配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getAnchorConfigList($skip, $limit)
    {
        try {
            $count = AnchorConfigs::count();
            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET " . $skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT " . $limit . " " . $cond;
            }

            $modelsManager = $this->di->get('modelsManager');
            $phql = "SELECT * FROM Micro\Models\AnchorConfigs ORDER BY level " . $cond;
            $query = $modelsManager->createQuery($phql);
            $configDataList = $query->execute();

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['higher'] = $configData->higher;
                    $data['lower'] = $configData->lower;
                    $data['level'] = $configData->level;
                    $data['roomLimitNum'] = $configData->roomLimitNum;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addAnchorConfig($name, $higher, $lower, $level, $roomLimitNum)
    {
        try {
            $dbdata = new AnchorConfigs();
            $dbdata->name = $name;
            $dbdata->higher = $higher;
            $dbdata->lower = $lower;
            $dbdata->level = $level;
            $dbdata->roomLimitNum = $roomLimitNum;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delAnchorConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = AnchorConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateAnchorConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = AnchorConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getAnchorConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = AnchorConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['higher'] = $configData->higher;
            $data['lower'] = $configData->lower;
            $data['level'] = $configData->level;
            $data['roomLimitNum'] = $configData->roomLimitNum;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取主播配置信息，高级接口
     * @param $conditions "uid = :uid:"
     * @param $parameters array("uid" => $this->uid)
     * @param $columns "column1, column2"
     * @return
     */
    public function getAnchorConfigInfoEx($conditions, $parameters, $columns = null)
    {
        try {
            $findParam = array($conditions, "bind" => $parameters);
            if ($columns != null) {
                $findParam['columns'] = $columns;
            }
            $configData = AnchorConfigs::findFirst($findParam);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data = $configData->toArray();
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取主播粉丝配置信息，高级接口
     * @param $conditions "uid = :uid:"
     * @param $parameters array("uid" => $this->uid)
     * @param $columns "column1, column2"
     * @return
     */
    public function getFansConfigInfoEx($conditions, $parameters, $columns = null)
    {
        try {
            $findParam = array($conditions, "bind" => $parameters);
            if ($columns != null) {
                $findParam['columns'] = $columns;
            }
            $configData = FansConfigs::findFirst($findParam);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data = $configData->toArray();
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  富豪配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getRicherConfigList($skip, $limit)
    {
        try {
            $count = RicherConfigs::count();
            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET " . $skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT " . $limit . " " . $cond;
            }

            $modelsManager = $this->di->get('modelsManager');
            $phql = "SELECT * FROM Micro\Models\RicherConfigs ORDER BY level " . $cond;
            $query = $modelsManager->createQuery($phql);
            $configDataList = $query->execute();

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['higher'] = $configData->higher;
                    $data['lower'] = $configData->lower;
                    $data['level'] = $configData->level;
                    $data['carId'] = $configData->carId;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addRicherConfig($name, $higher, $lower, $level, $carId)
    {
        try {
            $dbdata = new RicherConfigs();
            $dbdata->name = $name;
            $dbdata->higher = $higher;
            $dbdata->lower = $lower;
            $dbdata->level = $level;
            $dbdata->carId = $carId;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delRicherConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = RicherConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateRicherConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = RicherConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getRicherConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = RicherConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['higher'] = $configData->higher;
            $data['lower'] = $configData->lower;
            $data['level'] = $configData->level;
            $data['carId'] = $configData->carId;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取富豪配置信息，高级接口
     * @param $conditions "uid = :uid:"
     * @param $parameters array("uid" => $this->uid)
     * @param $columns "column1, column2"
     * @return
     */
    public function getRicherConfigInfoEx($conditions, $parameters, $columns = null)
    {
        try {
            $findParam = array($conditions, "bind" => $parameters);
            if ($columns != null) {
                $findParam['columns'] = $columns;
            }
            $configData = RicherConfigs::findFirst($findParam);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data = $configData->toArray();
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  粉丝配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getFansConfigList($skip, $limit)
    {
        try {
            $count = FansConfigs::count();
            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET " . $skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT " . $limit . " " . $cond;
            }

            $modelsManager = $this->di->get('modelsManager');
            $phql = "SELECT * FROM Micro\Models\FansConfigs ORDER BY level " . $cond;
            $query = $modelsManager->createQuery($phql);
            $configDataList = $query->execute();

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['higher'] = $configData->higher;
                    $data['lower'] = $configData->lower;
                    $data['level'] = $configData->level;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addFansConfig($name, $higher, $lower, $level)
    {
        try {
            $dbdata = new FansConfigs();
            $dbdata->name = $name;
            $dbdata->higher = $higher;
            $dbdata->lower = $lower;
            $dbdata->level = $level;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delFansConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = FansConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateFansConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = FansConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getFansConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = FansConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['higher'] = $configData->higher;
            $data['lower'] = $configData->lower;
            $data['level'] = $configData->level;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //守护配置信息
    public function getGuardConfigs(){
        try {

            $data = array('configList'=>array(),'rightList'=>array(),'bannerUrl'=>'');

            $sql = 'select gc.id,gc.name,gc.level,gc.description,gr.orderType,gr.img '
                . ' from \Micro\Models\GuardConfigs as gc left join \Micro\Models\GuardRights as gr on gc.level = gr.lastTime '
                . ' where gr.type = 0 order by gr.orderType asc ';
            $modelsManager = $this->di->get('modelsManager');
            $query = $modelsManager->createQuery($sql);
            $configDatas = $query->execute();

            // $configList = array();
            $guardBuyConfig = $this->config->wordAndColor->guard->buy->toArray();
            if ($configDatas->valid()) {
                foreach ($configDatas as $key => $configData) {
                    $tmp = array();
                    $tmp['id'] = $configData->id;
                    $tmp['name'] = $configData->name;            //守护类型
                    $tmp['level'] = $configData->level;            //等级
                    $tmp['img'] = $configData->img;            //图标地址
                    $tmp['buyConfigs'] = $this->config->buyGuardConfig[$configData->level]->toArray();
                    $tmp['wordAndColor'] = $guardBuyConfig[$configData->level];
                    array_push($data['configList'], $tmp);
                    unset($tmp);
                    /*if($configData->level == 3){
                        $configList[0] = $tmp;
                    }else if($configData->level == 1){
                        $configList[1] = $tmp;
                    }else if($configData->level == 2){
                        $configList[2] = $tmp;
                    }*/
                }
                // $data['configList'] = $configList;
            }
            

            $rightsDatas = GuardRights::find(
                ' (type = 1 or type = 5) order by orderType,id '
            );
            // $rightList = array();
            if($rightsDatas->valid()){
                foreach ($rightsDatas as $key => $rightsData) {
                    if($rightsData->type == 5){
                        $data['bannerUrl'] = $rightsData->img;
                        continue;
                    }
                    $tmp = array();
                    $tmp['id'] = $rightsData->id;
                    $tmp['img'] = $rightsData->img;
                    $tmp['name'] = $rightsData->name;
                    $tmp['desc'] = $rightsData->desc;
                    $tmp['orderType'] = $rightsData->orderType;
                    $tmp['type'] = $rightsData->type;
                    array_push($data['rightList'], $tmp);
                    unset($tmp);
                }
                // $data['rightList'] = $rightList;
            }

            // $vipBuyConfig = $this->config->wordAndColor->vip->buy->toArray();
            // $data['wordAndColor'] = $vipBuyConfig;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //vip配置信息
    public function getVipConfigs(){
        try {
            // $res = $this->getVipByLevel();var_dump($res);die;

            $data = array('configList'=>array(),'rightList'=>array(),'bannerUrl'=>'');

            $sql = 'select vc.id,vc.level,vr.orderType,vr.img '
                . ' from \Micro\Models\VipConfigs as vc left join \Micro\Models\VipRights as vr on vc.level = vr.lastTime '
                . ' where vr.type = 6 order by vr.orderType asc ';
            $modelsManager = $this->di->get('modelsManager');
            $query = $modelsManager->createQuery($sql);
            $configDatas = $query->execute();

            // $configList = array();
            $vipBuyConfig = $this->config->wordAndColor->vip->buy->toArray();
            if ($configDatas->valid()) {
                foreach ($configDatas as $key => $configData) {
                    $tmp = array();
                    $tmp['id'] = $configData->id;
                    $tmp['name'] = $configData->level == 1 ? '普通VIP' : '至尊VIP';            //守护类型
                    $tmp['level'] = $configData->level;            //等级
                    $tmp['img'] = $configData->img;            //图片
                    $tmp['buyConfigs'] = $this->config->buyVipConfig[$configData->level]->toArray();
                    $tmp['wordAndColor'] = $vipBuyConfig[$configData->level];

                    array_push($data['configList'], $tmp);
                    unset($tmp);
                    /*if($configData->level == 3){
                        $configList[0] = $tmp;
                    }else if($configData->level == 1){
                        $configList[1] = $tmp;
                    }else if($configData->level == 2){
                        $configList[2] = $tmp;
                    }*/
                }
                // $data['configList'] = $configList;
            }
            

            $rightsDatas = VipRights::find(
                ' (type = 0 or type = 5) order by orderType,id '
            );
            // $rightList = array();
            if($rightsDatas->valid()){
                foreach ($rightsDatas as $key => $rightsData) {
                    if($rightsData->type == 5){
                        $data['bannerUrl'] = $rightsData->img;
                        continue;
                    }
                    $tmp = array();
                    $tmp['id'] = $rightsData->id;
                    $tmp['img'] = $rightsData->img;
                    $tmp['name'] = $rightsData->name;
                    $tmp['desc'] = $rightsData->desc;
                    $tmp['orderType'] = $rightsData->orderType;
                    $tmp['type'] = $rightsData->type;
                    array_push($data['rightList'], $tmp);
                    unset($tmp);
                }
                // $data['rightList'] = $rightList;
            }

            
            // $data['wordAndColor'] = $vipBuyConfig;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getVipByLevel(){
        try {
            $rightsDatas = VipRights::find(
                ' (lastTime = 1 or lastTime = 2) order by lastTime desc,orderType asc,id asc '
            );
            $data = array();

            if($rightsDatas->valid()){
                foreach ($rightsDatas as $key => $rightsData) {
                    if($rightsData->type == 6){
                        $data[$rightsData->lastTime]['bigImg'] = $rightsData->img;
                        continue;
                    }
                    $tmp['id'] = $rightsData->id;
                    $tmp['img'] = $rightsData->img;
                    $tmp['name'] = $rightsData->name;
                    $tmp['desc'] = $rightsData->desc;
                    $tmp['orderType'] = $rightsData->orderType;
                    $tmp['type'] = $rightsData->type;
                    $data[$rightsData->type]['rights'][] = $tmp;
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  守护配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getAllGuardConfigList($skip, $limit)
    {
        try {

            $count = GuardConfigs::count();
            $configDataList = GuardConfigs::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip),
                )
            );


            $dataList = array();
            if ($configDataList->valid()) {

                foreach ($configDataList as $key => $configData) {
                    if (!empty($configData->carId)) {
                        $carConfig = carConfigs::find('id = ' . $configData->carId)->toArray();
                        if (count($carConfig)) {
                            $data['car'] = $carConfig[0];
                        }
                    }
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;            //守护类型
                    $data['level'] = $configData->level;            //等级
                    $data['description'] = $configData->description;            //描述

                    /*  $data['duration'] = $this->config->goldGuard;		//持续时间
                    $data['giveDuration'] = $configData->giveDuration;
                    $data['price'] = $configData->price;   */
                    array_push($dataList, $data);
                }
            }
            $resultData = array();
            foreach ($dataList as $val) {
                $level = $val['level'];
                if (!isset($resultData[$level])) {
                    $data['type'] = array();
                    $data['level'] = $level;
                    if ($val['car']) {
                        $data['carInfo'] = $val['car'];
                    }
                    $resultData[$level] = $data;
                }
                $type['gid'] = $val['id'];
                /*   $type['duration'] = $val['duration'];
                  $type['giveDuration'] = $val['giveDuration'];
                  $type['price'] = $val['price']; */
                array_push($resultData[$level]['type'], $type);
            }

            $result['count'] = $count;
            $result['list'] = $resultData;
            $result['list']['goldGuard'] = $this->config->goldGuard->toArray();
            $result['list']['silverGuard'] = $this->config->silverGuard->toArray();
            $result['list']['boGuard'] = $this->config->boGuard->toArray();

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getGuardConfigList($skip, $limit)
    {
        try {
            $count = GuardConfigs::count();
            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET " . $skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT " . $limit . " " . $cond;
            }
            $modelsManager = $this->di->get('modelsManager');
            $phql = "SELECT * FROM Micro\Models\GuardConfigs ORDER BY level " . $cond;
            $query = $modelsManager->createQuery($phql);
            $configDataList = $query->execute();

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['level'] = $configData->level;
                    $data['carId'] = $configData->carId;
                    $data['description'] = $configData->description;
                    $data['rightlist'] = $configData->rightlist;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    public function addGuardConfig($level, $name, $carId, $description, $rightlist)
    {
        try {
            $dbdata = new GuardConfigs();
            $dbdata->name = $name;
            $dbdata->level = $level;
            $dbdata->carId = $carId;
            $dbdata->description = $description;
            $dbdata->rightlist = $rightlist;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delGuardConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GuardConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateGuardConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GuardConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getGuardConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GuardConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['level'] = $configData->level;
            $data['carId'] = $configData->carId;
            $data['description'] = $configData->description;
            $data['rightlist'] = $configData->rightlist;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  礼物配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getAllGiftConfigList($uid = 0, $isApp = 0)
    {
        try {
            $resultData = array();

            $typeResultData = $this->getTypeConfigList($this->config->dbTypeConfigName->gift[0]);
            if ($typeResultData['code'] != $this->status->getCode('OK')) {
                return $typeResultData;
            }

            foreach ($typeResultData['data']['list'] as $typeResult) {
                $typeId = $typeResult['typeId'];
                $typeData['typeId'] = $typeId;
                $typeData['typeName'] = $typeResult['name'];
                $typeData['showStatus'] = $typeResult['showStatus'];
                $typeData['sellStatus'] = $typeResult['sellStatus'];
                $typeData['dataList'] = array();
                $resultData[$typeId] = $typeData;
            }

            $configDataList = GiftConfigs::find(array('order'=>'typeId desc,orderType asc'));
            $newData = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['typeId'] = $configData->typeId;              //礼物类别
                    $data['name'] = $configData->name;                  //礼物名称
                    $data['coin'] = $configData->coin;                  //聊豆购买价格（虚拟币）
                    $data['cash'] = $configData->cash;                  //聊币购买价格
                    $data['recvCoin'] = $configData->recvCoin;          //对方接受的聊币数量
                    $data['vipLevel'] = $configData->vipLevel;          //最低VIP等级
                    $data['richerLevel'] = $configData->richerLevel;    //最低富豪等级
                    $data['discount'] = $configData->discount;          //打折数(0表示不打折，1-9表示1折到9折)
                    $data['freeCount'] = $configData->freeCount;        //免费次数
                    $data['littleFlag'] = $configData->littleFlag;      //是否小礼物
                    $data['orderType'] = $configData->orderType;        //排序
                    $data['configName'] = $configData->configName;      //配置名称，索引图片别名用
                    $data['guardFlag'] = $configData->guardFlag;         //是否需要守护
                    $data['description'] = $configData->description ? $configData->description : '';      //礼物的描述
                    $data['tagPic'] = $configData->tagPic ? $configData->tagPic : '';      //礼物的标签图片
                    $data['isDefault'] = $configData->isDefault ? $configData->isDefault : 0;      //礼物的是否选中
                    $data['tagDesc'] = $configData->tagDesc ? $configData->tagDesc : '';      //礼物的标签说明
                    $data['littleSwf'] = $configData->littleSwf;      //是否小礼物动画

                    //红包活动
                    if ($data['id'] == $this->config->redPacketConfigs->redGiftId && $this->config->redPacketConfigs->enable) {
                        $data['redPacket'] = 1;
                        $data['redPacketType'] = 1;//普通红包
                        $data['typeId'] = 1;
                        $data['redUrl'] = $this->config->webType[$this->config->channelType]->mDomain.$this->config->redPacketConfigs->redUrl;
                        $data['vip'] = $this->config->redPacketConfigs->vip;
                        $data['guard'] = $this->config->redPacketConfigs->guard;
                        $data['admin'] = $this->config->redPacketConfigs->admin;
                    }
                    
                    //猴年春节红包活动
                    if ($data['id'] == $this->config->redPacketConfigs->monkeyRedPacket->giftId && time() > $this->config->redPacketConfigs->monkeyRedPacket->startTime && time() < $this->config->redPacketConfigs->monkeyRedPacket->endTime) {
                        $data['redPacket'] = 1;
                        $data['redPacketType'] = 2;//猴年春节红包
                        $data['typeId'] = 1;
                        $data['redUrl'] = $this->config->webType[$this->config->channelType]->mDomain . $this->config->redPacketConfigs->redUrl;
                        $data['vip'] = $this->config->redPacketConfigs->vip;
                        $data['guard'] = $this->config->redPacketConfigs->guard;
                        $data['admin'] = $this->config->redPacketConfigs->admin;
                    }

                    if ($data['typeId'] == 7) {
                        $data['lucky'] = 1; //幸运礼物标签
                    }
                    //幸运桃花 放入热门
                    if ($data['id'] == 61 || $data['id'] == 79 || $data['id'] == 89) {
                       // $refuseThAnchors = $this->config->refuseThAnchors->toArray();
                        $data['typeId'] = 1;
                       /* if($uid && in_array($uid, $refuseThAnchors)){
                            $data['typeId'] = 11;
                        }*/
                    }

                    if($data['id'] == $this->config->anchorMovie->giftId && time() > $this->config->anchorMovie->startTime && time() <= $this->config->anchorMovie->endTime){
                        $res = \Micro\Models\ActivityAnchors::findFirst('type = 1 and uid = ' . $uid);
                        if($res && $isApp){
                            $data['typeId'] = 1;
                        }
                    }
                    
                    //春节礼物 放入热门
                    if ($data['id'] == 81 || $data['id'] == 82|| $data['id'] == 83) {
                        if (time() > $this->config->springFestival->startTime && time() < $this->config->springFestival->endTime) {
                            $data['typeId'] = 1; //热门
                            $data['springFestival'] = 1;
                        }
                    }
                    
                    //情人节礼物 放入热门
                    if ($data['id'] == 85 && time() > $this->config->valentineDay->startTime && time() < $this->config->valentineDay->endTime) {
                        $data['typeId'] = 1; //热门
                        $data['valentineDay'] = 1;
                    }


                    if ($data['typeId'] == 1) {
                        // $sortData[] = $data;
                        $sort[] = $data['orderType'];
                    }
                    if (array_key_exists($data['typeId'], $resultData)) {
                        array_push($resultData[$data['typeId']]['dataList'], $data);
                    }
                    unset($data);
                }
                array_multisort($sort, SORT_ASC, $resultData[1]['dataList']);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $resultData);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getGiftConfigList($skip = 0, $limit = 500)
    {
        try {
            $count = GiftConfigs::count();

            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET " . $skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT " . $limit . " " . $cond;
            }
            $modelsManager = $this->di->get('modelsManager');
//            $phql = "SELECT a.*, b.* FROM Micro\Models\GiftConfigs a, Micro\Models\TypeConfig b WHERE".
//                    " b.parentTypeId = ".$this->typeConfigData['giftType']['typeId'].
//                    " AND a.typeId = b.typeId ORDER BY b.typeId, a.cash ".$cond;
            $phql = "SELECT a.*, b.* FROM Micro\Models\GiftConfigs a LEFT JOIN Micro\Models\TypeConfig b ON a.typeId = b.typeId WHERE" .
                " b.parentTypeId = " . $this->typeConfigData['giftType']['typeId'] .
                "  ORDER BY b.typeId, a.cash " . $cond;

            $query = $modelsManager->createQuery($phql);
            $configDataList = $query->execute();

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->a->id;
                    $data['typeId'] = $configData->a->typeId;              //礼物类别
                    $data['typeName'] = $configData->b->name;
                    $data['name'] = $configData->a->name;                  //礼物名称
                    $data['coin'] = $configData->a->coin;                  //聊豆购买价格（虚拟币）
                    $data['cash'] = $configData->a->cash;                  //聊币购买价格
                    $data['recvCoin'] = $configData->a->recvCoin;          //对方接受的聊币数量
                    $data['vipLevel'] = $configData->a->vipLevel;          //最低VIP等级
                    $data['richerLevel'] = $configData->a->richerLevel;    //最低富豪等级
                    $data['discount'] = $configData->a->discount;          //打折数(0表示不打折，1-9表示1折到9折)
                    $data['freeCount'] = $configData->a->freeCount;        //免费次数
                    $data['littleFlag'] = $configData->a->littleFlag;      //是否小礼物
                    $data['orderType'] = $configData->a->orderType;        //排序
                    $data['guardFlag'] = $configData->a->guardFlag;        //是否需要守护
                    $data['configName'] = $configData->a->configName;      //配置名称，索引图片别名用
                    $data['description'] = $configData->a->description;      //礼物的描述

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addGiftConfig($typeId, $name, $coin, $cash, $recvCoin, $vipLevel, $richerLevel, $discount, $freeCount, $littleFlag, $orderType, $guardFlag, $configName, $description)
    {
        try {
            $dbdata = new GiftConfigs();
            $dbdata->typeId = $typeId;
            $dbdata->name = $name;
            $dbdata->coin = $coin;
            $dbdata->cash = $cash;
            $dbdata->recvCoin = $recvCoin;
            $dbdata->vipLevel = $vipLevel;
            $dbdata->richerLevel = $richerLevel;
            $dbdata->discount = $discount;
            $dbdata->freeCount = $freeCount;
            $dbdata->littleFlag = $littleFlag;
            $dbdata->orderType = $orderType;
            $dbdata->configName = $configName;
            $dbdata->description = $description;
            $dbdata->guardFlag = $guardFlag;
            $dbdata->createTime = time();
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delGiftConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GiftConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateGiftConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GiftConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getGiftConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GiftConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['typeId'] = $configData->typeId;
            $data['name'] = $configData->name;
            $data['coin'] = $configData->coin;
            $data['cash'] = $configData->cash;
            $data['recvCoin'] = $configData->recvCoin;
            $data['vipLevel'] = $configData->vipLevel;
            $data['richerLevel'] = $configData->richerLevel;
            $data['discount'] = $configData->discount;
            $data['freeCount'] = $configData->freeCount;
            $data['littleFlag'] = $configData->littleFlag;
            $data['orderType'] = $configData->orderType;
            $data['guardFlag'] = $configData->guardFlag;
            $data['configName'] = $configData->configName;
            $data['description'] = $configData->description;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  座驾配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getAllCarConfigList()
    {
        try {
            $count = typeConfig::count();
            $tempTypeDate = TypeConfig::find(
                array(
                    "conditions" => "parentTypeId = " . $this->typeConfigData['carType']['typeId']// . " and showStatus = 0",   //这里需要修改一下!!!
                )
            );
            if ($tempTypeDate) {
                $dataList = array();
                foreach ($tempTypeDate as $key => $typeConfig) {

                    $data['typeId'] = $typeConfig->typeId;
                    $data['typeName'] = $typeConfig->name;
                    $data['description'] = $typeConfig->description;
                    $data['showStatus'] = $typeConfig->showStatus;
                    $data['sellStatus'] = $typeConfig->sellStatus;
                    $tempCar = CarConfigs::find(array('typeId = ' . $typeConfig->typeId . ' ORDER BY sort DESC,price DESC'));
                    $data['info'] = $tempCar->toArray();

                    array_push($dataList, $data);
                }

                $result['count'] = $count;
                $result['list'] = $dataList;
                return $this->status->retFromFramework($this->status->getCode('OK'), $result);
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getCarConfigList($skip, $limit)
    {
        try {
            $count = CarConfigs::count();

            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET " . $skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT " . $limit . " " . $cond;
            }
            $modelsManager = $this->di->get('modelsManager');
//            $phql = "SELECT a.*, b.* FROM Micro\Models\CarConfigs a, Micro\Models\TypeConfig b WHERE".
//                    " b.parentTypeId = ".$this->typeConfigData['carType']['typeId'].
//                    " AND a.typeId = b.typeId ORDER BY b.typeId, a.price DESC ".$cond;
            $phql = "SELECT a.*, b.* FROM Micro\Models\CarConfigs a LEFT JOIN Micro\Models\TypeConfig b ON a.typeId = b.typeId WHERE" .
                " b.parentTypeId = " . $this->typeConfigData['carType']['typeId'] .
                "  ORDER BY b.typeId,a.sort DESC,a.price DESC " . $cond;
            $query = $modelsManager->createQuery($phql);
            $configDataList = $query->execute();

            $buyConfigs = $this->config->buyConfigs;

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $key => $configData) {
                    $data['id'] = $configData->a->id;
                    $data['typeId'] = $configData->a->typeId;              //类别
                    $data['typeName'] = $configData->b->name;
                    $data['name'] = $configData->a->name;                  //名称
                    $data['configName'] = $configData->a->configName;      //配置名称
                    $data['price'] = $configData->a->price;                //价格
                    $data['description'] = $configData->a->description;    //描述信息
                    $data['orderType'] = $configData->a->orderType;        //排序
                    $data['status'] = $configData->a->status;              //状态
                    $data['hasBigCar'] = $configData->a->hasBigCar;              //是否有大座驾
                    $data['positionX1'] = $configData->a->positionX1;              //x轴位置
                    $data['positionY1'] = $configData->a->positionY1;              //y轴位置
                    $data['sort'] = $configData->a->sort;              //排序
                    $data['positionX2'] = $configData->a->positionX2;              //排序
                    $data['positionY2'] = $configData->a->positionY2;              //排序
                    $data['appSpecial'] = $configData->a->appSpecial;              //APP高级座驾进场显示
                    $data['buyConfigs'] = array();
                    foreach ($buyConfigs as $k => $value) {
                        // $data['buyConfigs'][$k] = $value;
                        // $data['buyConfigs'][$k]['ttlPrice'] = intval($data['price']) * intval($value['num']);
                        $tmp = array();
                        array_push($tmp, $value['days']);
                        array_push($tmp, $data['price'] * $value['num']);
                        array_push($tmp, $value['num']);
                        $data['buyConfigs'][$value['num']] = $tmp;
                        unset($tmp);
                    }
                    array_push($dataList, $data);
                }
            }
            $result['count'] = $count;
            $result['list'] = $dataList;
            $result['carBanner'] = $this->config->appShopBanner->carBanner;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addCarConfig($typeId, $name, $price, $description, $orderType, $status, $configName, $hasBigCar = 0, $sort = 1, $positionX1 = 0, $positionY1 = 0, $positionX2 = 0, $positionY2 = 0)
    {
        try {
            $dbdata = new CarConfigs();
            $dbdata->typeId = $typeId;
            $dbdata->name = $name;
            $dbdata->price = $price;
            $dbdata->description = $description;
            $dbdata->orderType = $orderType;
            $dbdata->status = $status;
            $dbdata->configName = $configName;
            $dbdata->hasBigCar = $hasBigCar;
            $dbdata->sort = $sort;
            $dbdata->positionX1 = $positionX1;
            $dbdata->positionY1 = $positionY1;
            $dbdata->positionX2 = $positionX2;
            $dbdata->positionY2 = $positionY2;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delCarConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = CarConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateCarConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = CarConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getCarConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = CarConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['typeId'] = $configData->typeId;              //类别
            $data['name'] = $configData->name;                  //名称
            $data['price'] = $configData->price;                //价格
            $data['description'] = $configData->description;    //描述信息
            $data['orderType'] = $configData->orderType;        //排序
            $data['status'] = $configData->status;              //状态
            $data['configName'] = $configData->configName;
            $data['hasBigCar'] = $configData->hasBigCar;
            $data['sort'] = $configData->sort;
            $data['positionX1'] = $configData->positionX1;
            $data['positionY1'] = $configData->positionY1;
            $data['positionX2'] = $configData->positionX2;
            $data['positionY2'] = $configData->positionY2;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  酒水配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getFoodConfigList($skip, $limit)
    {
        try {
            $count = FoodConfigs::count();
            $configDataList = FoodConfigs::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['typeId'] = $configData->typeId;              //类别
                    $data['name'] = $configData->name;                  //名称
                    $data['price'] = $configData->price;                //价格
                    $data['description'] = $configData->description;    //描述信息
                    $data['orderType'] = $configData->orderType;        //排序
                    $data['status'] = $configData->status;              //状态
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addFoodConfig($typeId, $name, $price, $description, $orderType, $status)
    {
        try {
            $dbdata = new FoodConfigs();
            $dbdata->typeId = $typeId;
            $dbdata->name = $name;
            $dbdata->price = $price;
            $dbdata->description = $description;
            $dbdata->orderType = $orderType;
            $dbdata->status = $status;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delFoodConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = FoodConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateFoodConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = FoodConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getFoodConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = FoodConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['typeId'] = $configData->typeId;              //类别
            $data['name'] = $configData->name;                  //名称
            $data['price'] = $configData->price;                //价格
            $data['description'] = $configData->description;    //描述信息
            $data['orderType'] = $configData->orderType;        //排序
            $data['status'] = $configData->status;              //状态

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  公告配置
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getNoticeConfigList($skip, $limit)
    {
        try {
            $count = NoticeConfigs::count();
            $configDataList = NoticeConfigs::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['title'] = $configData->title;
                    $data['contents'] = $configData->contents;
                    $data['image'] = $configData->image;
                    $data['createTime'] = $configData->createTime;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addNoticeConfig($title, $contents, $image)
    {
        try {
            $dbdata = new NoticeConfigs();
            $dbdata->title = $title;
            $dbdata->contents = $contents;
            $dbdata->image = $image;
            $dbdata->createTime = time();
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delNoticeConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = NoticeConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateNoticeConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = NoticeConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }
            $configData->createTime = time();

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getNoticeConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = NoticeConfigs::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['title'] = $configData->title;
            $data['contents'] = $configData->contents;
            $data['image'] = $configData->image;
            $data['createTime'] = $configData->createTime;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  安全问题
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getQuestionsConfigs()
    {
        try {
            $configData = QuestionConfigs::find('status = 1');
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $configData = $configData->toArray();

            return $this->status->retFromFramework($this->status->getCode('OK'), $configData);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getGiftCarList($skip = 0, $limit = 100)
    {
        try {
            $count = TypeConfig::count();
            // var_dump($count);
            // exit;
            $configDataList = TypeConfig::find(
                array(
                    "conditions" => "parentTypeId != 0",
                    "order" => "id asc",
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );


            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['typeId'] = $configData->typeId;
                    $data['parentTypeId'] = $configData->parentTypeId;
                    $data['createTime'] = $configData->createTime;
                    $data['description'] = $configData->description;
                    $data['roomAnimate'] = $configData->roomAnimate;
                    $data['showStatus'] = $configData->showStatus;
                    $data['sellStatus'] = $configData->sellStatus;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function setShowStatus($id, $showStatus)
    {
        try {
            $TypeConfig = TypeConfig::findFirst("id=" . $id);
            if (!$TypeConfig) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $TypeConfig->showStatus = $showStatus;
            $TypeConfig->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function setSellStatus($id, $sellStatus)
    {
        try {
            $TypeConfig = TypeConfig::findFirst("id=" . $id);
            if (!$TypeConfig) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $TypeConfig->sellStatus = $sellStatus;
            $TypeConfig->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询渠道礼包
    public function getSourceGiftPackage($action, $utm_source, $utm_medium = '')
    {
        $giftPackageId = 0;
        try {
            $info = \Micro\Models\SourceGiftConfigs::findfirst("action=" . $action . " and utm_source='" . $utm_source . "' and (utm_medium='" . $utm_medium . "'  or utm_medium='') and status=1 order by utm_medium desc");
            if ($info != false) {
                return $info->giftPackageId;
            }
        } catch (\Exception $e) {
            $this->errLog('getSourceGiftPackage error ' . $e->getMessage());
        }
        return $giftPackageId;
    }

    //查询礼包信息
    public function getgiftPackageBaseConfig($id, $isNeedDetail = 0)
    {
        try {
            $result = array();
            $info = \Micro\Models\GiftPackageConfigs::findfirst($id);
            $items = json_decode($info->items, true); //物品
            $return['name'] = $info->name;
            foreach ($items as $vi) {
                $data = array();
                switch ($vi['type']) {
                    case $this->config->itemType->vip://vip
                        $data['id'] = $vi['id'];
                        $data['type'] = $vi['type'];
                        $data['num'] = 1;
                        $data['img'] = isset($vi['img']) ? $vi['img'] : '';
                        if ($isNeedDetail) {
                            $data['name'] = 'VIP';
                        }
                        break;
                    case $this->config->itemType->gift://给礼物
                        $data['id'] = $vi['id'];
                        $data['type'] = $vi['type'];
                        $data['num'] = $vi['num'];
                        $data['validity'] = $vi['validity'];
                        $data['img'] = isset($vi['img']) ? $vi['img'] : '';
                        if ($isNeedDetail) {
                            $itemInfo = \Micro\Models\GiftConfigs::findfirst($vi['id']);
                            $data['name'] = $itemInfo->name;
                            $data['typeName'] = '礼物';
                            $data['configName'] = $itemInfo->configName;
                        }
                        break;
                    case $this->config->itemType->car://给座驾
                        $data['id'] = $vi['id'];
                        $data['type'] = $vi['type'];
                        $data['num'] = $vi['num'];
                        $data['validity'] = $vi['validity'];
                        if ($isNeedDetail) {
                            $itemInfo = \Micro\Models\CarConfigs::findfirst($vi['id']);
                            $data['name'] = $itemInfo->name;
                            $data['typeName'] = '座驾';
                            $data['configName'] = $itemInfo->configName;
                        }
                        $data['img'] = isset($vi['img']) ? $vi['img'] : '';
                        break;
                    case $this->config->itemType->item://给道具
                        $data['id'] = $vi['id'];
                        $data['type'] = $vi['type'];
                        $data['num'] = $vi['num'];
                        $data['validity'] = $vi['validity'];
                        if ($isNeedDetail) {
                            $itemInfo = \Micro\Models\ItemConfigs::findfirst($vi['id']);
                            $data['name'] = $itemInfo->name;
                            $data['typeName'] = '道具';
                            $data['configName'] = $itemInfo->configName;
                        }
                        $data['img'] = isset($vi['img']) ? $vi['img'] : '';
                        break;
                    default :
                        $data['type'] = $vi['type'];
                        if (isset($vi['coin'])) {
                            $data['coin'] = $vi['coin'];
                            $data['name'] = '聊豆';
                            $data['num'] = $vi['coin'];
                            $data['img'] = isset($vi['img']) ? $vi['img'] : '';
                        }
                        if (isset($vi['cash'])) {
                            $data['cash'] = $vi['cash'];
                            $data['name'] = '聊币';
                            $data['num'] = $vi['cash'];
                            $data['img'] = isset($vi['img']) ? $vi['img'] : '';
                        }
                        if (isset($vi['points'])) {
                            $data['points'] = $vi['points'];
                            $data['name'] = '积分';
                            $data['num'] = $vi['points'];
                            $data['img'] = isset($vi['img']) ? $vi['img'] : '';
                        }
                        break;
                }
                array_push($result, $data);
            }
            $return['list'] = $result;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     *
     * banner管理
     *
     *
     */


    public function getBannerConfigList($skip, $limit)
    {
        try {
            $count = BannerConfig::count();
            $configDataList = BannerConfig::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip),
                    'order' => " border desc",
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['btype'] = $configData->btype;
                    $data['title'] = $configData->title;
                    $data['bannerurl'] = $configData->bannerurl;
                    $data['backgroundcolor'] = $configData->backgroundcolor;
                    $data['extracontent'] = $configData->extracontent;
                    $data['description'] = $configData->description;
                    $data['status'] = $configData->status;
                    $data['time'] = $configData->time;
                    $data['border'] = $configData->border;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getBannerList($btype = 0, $skip, $limit)
    {
        try {
            $count = BannerConfig::count("status=1 AND btype={$btype}");
            $configDataList = BannerConfig::find(
                array(
                    'conditions' => "status=1 AND btype={$btype}",
                    "limit" => array("number" => $limit, "offset" => $skip),
                    "order" => ' border desc'
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['btype'] = $configData->btype;
                    $data['title'] = $configData->title;
                    $data['bannerurl'] = $configData->bannerurl;
                    $data['backgroundcolor'] = $configData->backgroundcolor;
                    $data['extracontent'] = $configData->extracontent;
                    $data['description'] = $configData->description;
                    $data['status'] = $configData->status;
                    $data['time'] = $configData->time;
                    $data['border'] = $configData->border;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addBannerConfig($bannerurl, $backgroundcolor, $extracontent, $description, $status, $btype, $time, $order, $title)
    {
        try {
            $dbdata = new BannerConfig();
            $dbdata->bannerurl = $bannerurl;
            $dbdata->btype = $btype;
            $dbdata->backgroundcolor = $backgroundcolor;
            $dbdata->extracontent = $extracontent;
            $dbdata->description = $description;
            $dbdata->status = $status ? $status : 0;
            $dbdata->time = $time ? $time : 0;
            $dbdata->border = $order ? $order : 0;
            $dbdata->title = $title;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delBannerConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = BannerConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateBannerConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = BannerConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getBannerConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = BannerConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['btype'] = $configData->btype;
            $data['title'] = $configData->title;
            $data['bannerurl'] = $configData->bannerurl;
            $data['backgroundcolor'] = $configData->backgroundcolor;
            $data['extracontent'] = $configData->extracontent;
            $data['description'] = $configData->description;
            $data['status'] = $configData->status;
            $data['time'] = $configData->time;
            $data['border'] = $configData->border;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function uploadBannerImg()
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $this->pathGenerator->getInvAccountPath('banner');
                        $fileName = time() . '.' . $fileExt;
                        $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $banner = $this->pathGenerator->getFullInvAccountPath('banner', $fileName);
                            return $this->status->retFromFramework($this->status->getCode('OK'), $banner);
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }
        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }

    /**
     *
     * vip特权管理
     *
     *
     */


    public function getVipRightConfigList($skip, $limit)
    {
        try {
            $count = VipRight::count();
            $configDataList = VipRight::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['img'] = $configData->img;
                    $data['name'] = $configData->name;
                    $data['des'] = $configData->des;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getVipRightList($skip, $limit)
    {
        try {
            $count = VipRight::count();
            $configDataList = VipRight::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['img'] = $configData->img;
                    $data['name'] = $configData->name;
                    $data['des'] = $configData->des;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addVipRightConfig($name, $des, $img)
    {
        try {
            $dbdata = new VipRight();
            $dbdata->name = $name;
            $dbdata->des = $des;
            $dbdata->img = $img;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delVipRightConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = VipRight::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateVipRightConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = VipRight::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getVipRightConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = VipRight::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['des'] = $configData->des;
            $data['img'] = $configData->img;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function uploadVipImg()
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $this->pathGenerator->getInvAccountPath('vip');
                        $fileName = $file->getName();
                        $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $vip = $this->pathGenerator->getFullInvAccountPath('vip', $fileName);
                            return $this->status->retFromFramework($this->status->getCode('OK'), $vip . '?v=' . time());
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }
        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }

    /**
     *
     * vip特权管理
     *
     *
     */


    public function getGuardRightConfigList($skip, $limit)
    {
        try {
            $count = GuardRight::count();
            $configDataList = GuardRight::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['img'] = $configData->img;
                    $data['name'] = $configData->name;
                    $data['des'] = $configData->des;
                    $data['type'] = $configData->type;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取守护特权
    public function getVipRights($type = 0)
    {
        try {
            $type = intval($type);
            $count = VipRights::count('type = ' . $type);
            $configDataList = VipRights::find(
                'type = ' . $type . ' order by orderType,id '
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['img'] = $configData->img;
                    $data['name'] = $configData->name;
                    $data['desc'] = $configData->desc;
                    $data['orderType'] = $configData->orderType;
                    $data['type'] = $configData->type;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            $result['vipBanner'] = $this->config->appShopBanner->vipBanner;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取守护特权
    public function getGuardRights()
    {
        try {
            $count = GuardRights::count();
            $configDataList = GuardRights::find(
                ' type = 1 order by orderType,id '
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['img'] = $configData->img;
                    $data['name'] = $configData->name;
                    $data['desc'] = $configData->desc;
                    $data['orderType'] = $configData->orderType;
                    $data['type'] = $configData->type;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getGuardRightList($skip, $limit)
    {
        try {
            $count = GuardRight::count();
            $configDataList = GuardRight::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip)
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['img'] = $configData->img;
                    $data['name'] = $configData->name;
                    $data['des'] = $configData->des;
                    $data['type'] = $configData->type;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addGuardRightConfig($name, $des, $img, $type)
    {
        try {
            $dbdata = new GuardRight();
            $dbdata->name = $name;
            $dbdata->des = $des;
            $dbdata->img = $img;
            $dbdata->type = $type;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delGuardRightConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GuardRight::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateGuardRightConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GuardRight::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getGuardRightConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = GuardRight::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['des'] = $configData->des;
            $data['img'] = $configData->img;
            $data['type'] = $configData->type;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function uploadGuardImg()
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $this->pathGenerator->getInvAccountPath('guard');
                        $fileName = $file->getName();
                        $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $guard = $this->pathGenerator->getFullInvAccountPath('guard', $fileName);
                            return $this->status->retFromFramework($this->status->getCode('OK'), $guard . '?v=' . time());
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }
        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }

    /**
     * 活动管理
     */
    public function getEventConfigList($skip, $limit)
    {
        try {
            $count = EventConfig::count();
            $configDataList = EventConfig::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip),
                    'order' => ' eorder desc',
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['bannerurl'] = $configData->bannerurl;
                    $data['etype'] = $configData->etype;
                    $data['extracontent'] = $configData->extracontent;
                    $data['description'] = $configData->description;
                    $data['status'] = $configData->status;
                    $data['title'] = $configData->title;
                    $data['eorder'] = $configData->eorder;
                    $data['eventstarttime'] = date('Y年m月d日', $configData->eventstarttime);
                    $data['eventendtime'] = date('Y年m月d日', $configData->eventendtime);
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getEventListForHeader($type = 0, $skip, $limit)
    {
        try {
            $count = EventConfig::count("status=1 and etype={$type}");
            $configDataList = EventConfig::find(
                array(
                    'conditions' => "status=1 and etype={$type} and eventendtime>=" . time(),
//                    "limit" => array("number"=>$limit, "offset"=>$skip),
//                    'order' => 'eorder desc,eventstarttime desc,addtime desc'
                )
            );

            $dataList = array();
            $dataList1 = array(); // 未开始
            $dataList2 = array(); // 进行中
            $dataList3 = array(); // 已结束
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['bannerurl'] = $configData->bannerurl;
                    $data['etype'] = $configData->etype;
                    $data['extracontent'] = $configData->extracontent;
                    $data['description'] = $configData->description;
                    $data['status'] = $configData->status;
                    $data['title'] = $configData->title;
                    $data['eventstarttime'] = $configData->eventstarttime;
                    $data['eventendtime'] = $configData->eventendtime;
                    $data['addtime'] = $configData->addtime;
                    $data['eorder'] = $configData->eorder;
//                    array_push($dataList, $data);
                    $t = time();
                    if ($configData->eventstarttime > $t) {
                        // 未开始
                        array_push($dataList1, $data);
                    } elseif ($configData->eventstarttime < $t && $t < $configData->eventendtime) {
                        // 进行中
                        array_push($dataList2, $data);
                    } else {
                        array_push($dataList3, $data);
                    }

                }

                if ($dataList1) {
                    $dataList1 = $this->baseCode->arrayMultiSort($dataList1, 'addtime', TRUE);
                }

                if ($dataList2) {
                    $dataList2 = $this->baseCode->arrayMultiSort($dataList2, 'addtime', TRUE);
                }

                if ($dataList3) {
                    $dataList3 = $this->baseCode->arrayMultiSort($dataList3, 'eventendtime', TRUE);
                }

                $dataList = array_merge($dataList1, $dataList2);
                $dataList = array_merge($dataList, $dataList3);
            }

            if ($dataList) {
                $dataList = array_slice($dataList, $skip, $limit);
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getEventList($type = 0, $skip, $limit)
    {
        try {
            $count = EventConfig::count("status=1 and etype={$type}");
            $configDataList = EventConfig::find(
                array(
                    'conditions' => "status=1 and etype={$type}",
//                    "limit" => array("number"=>$limit, "offset"=>$skip),
//                    'order' => 'eorder desc,eventstarttime desc,addtime desc'
                )
            );

            $dataList = array();
            $dataList1 = array(); // 未开始
            $dataList2 = array(); // 进行中
            $dataList3 = array(); // 已结束
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['bannerurl'] = $configData->bannerurl;
                    $data['etype'] = $configData->etype;
                    $data['extracontent'] = $configData->extracontent;
                    $data['description'] = $configData->description;
                    $data['status'] = $configData->status;
                    $data['title'] = $configData->title;
                    $data['eventstarttime'] = $configData->eventstarttime;
                    $data['eventendtime'] = $configData->eventendtime;
                    $data['addtime'] = $configData->addtime;
                    $data['eorder'] = $configData->eorder;
//                    array_push($dataList, $data);
                    $t = time();
                    if ($configData->eventstarttime > $t) {
                        // 未开始
                        array_push($dataList1, $data);
                    } elseif ($configData->eventstarttime < $t && $t < $configData->eventendtime) {
                        // 进行中
                        array_push($dataList2, $data);
                    } else {
                        array_push($dataList3, $data);
                    }

                }

                if ($dataList1) {
                    $dataList1 = $this->baseCode->arrayMultiSort($dataList1, 'addtime', TRUE);
                }

                if ($dataList2) {
                    $dataList2 = $this->baseCode->arrayMultiSort($dataList2, 'addtime', TRUE);
                }

                if ($dataList3) {
                    $dataList3 = $this->baseCode->arrayMultiSort($dataList3, 'eventendtime', TRUE);
                }

                $dataList = array_merge($dataList1, $dataList2);
                $dataList = array_merge($dataList, $dataList3);
            }

            if ($dataList) {
                $dataList = array_slice($dataList, $skip, $limit);
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addEventConfig($bannerurl, $extracontent, $description, $status, $title, $eventstarttime, $eventendtime, $etype, $order)
    {
        try {
            $dbdata = new EventConfig();
            $dbdata->bannerurl = $bannerurl;
            $dbdata->etype = $etype;
            $dbdata->extracontent = $extracontent;
            $dbdata->description = $description;
            $dbdata->title = $title;
            $dbdata->eventstarttime = $eventstarttime;
            $dbdata->eventendtime = $eventendtime;
            $dbdata->addtime = time();
            $dbdata->status = $status ? $status : 0;
            $dbdata->eorder = $order ? $order : 0;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delEventConfig($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = EventConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updateEventConfig($id, $updateData)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = EventConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach ($list as $key => $val) {
                if (isset($updateData[$key])) {
                    if ($key == 'eventstarttime' || $key == 'eventendtime') {
                        $updateData[$key] = strtotime($updateData[$key]);
                    }

                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getEventConfigInfo($id)
    {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = EventConfig::findFirst($id);
            if (empty($configData)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['bannerurl'] = $configData->bannerurl;
            $data['etype'] = $configData->etype;
            $data['extracontent'] = $configData->extracontent;
            $data['description'] = $configData->description;
            $data['status'] = $configData->status;
            $data['title'] = $configData->title;
            $data['eventstarttime'] = date('Y年m月d日', $configData->eventstarttime);
            $data['eventendtime'] = date('Y年m月d日', $configData->eventendtime);
            $data['eorder'] = $configData->eorder;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function uploadEventImg()
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $this->pathGenerator->getInvAccountPath('event');
                        $fileName = time() . '.' . $fileExt;
                        $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $event = $this->pathGenerator->getFullInvAccountPath('event', $fileName);
                            return $this->status->retFromFramework($this->status->getCode('OK'), $event);
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }
        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }

    public function addAnchorRecommendConfig($uid, $pos)
    {
        // 判断是否有这个主播
        $roomInfo = Rooms::findFirst("uid={$uid}");
        if (empty($roomInfo)) {
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }

        if ($roomInfo->isRecommend > 0 && $roomInfo->isRecommend != $pos) {
            return $this->status->retFromFramework($this->status->getCode('OTHER_POS_RECOMMOND'));
        }

        // 判断位置是否被占用
        $res = Rooms::findFirst("uid!={$uid} and isRecommend={$pos}");
        if (!empty($res)) {
            return $this->status->retFromFramework($this->status->getCode('POS_HAS_RECOMMOND'));
        }

        $roomInfo->isRecommend = $pos;
        $ret = $roomInfo->save();
        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function delAnchorRecommendConfig($uid)
    {
        $roomInfo = Rooms::findFirst("uid={$uid}");
        if (empty($roomInfo)) {
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }

        if ($roomInfo->isRecommend > 0) {
            $roomInfo->isRecommend = 0;
            $ret = $roomInfo->save();
        } else {
            $ret = TRUE;
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function updateAnchorRecommendConfig($uid, $pos)
    {
        $roomInfo = Rooms::findFirst("uid={$uid}");
        if (empty($roomInfo)) {
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }

        // 判断位置是否被占用
        $res = Rooms::findFirst("uid!={$uid} and isRecommend={$pos}");
        if (!empty($res)) {
            // 占用的推荐置0
            $res->isRecommend = 0;
            $res->save();
        }

        $roomInfo->isRecommend = $pos;
        $ret = $roomInfo->save();
        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function getAnchorRecommendConfigInfo($uid)
    {
        $roomInfo = Rooms::findFirst("uid={$uid}");
        if (empty($roomInfo)) {
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }

        $data['uid'] = $roomInfo->uid;
        $user = UserFactory::getInstance($roomInfo->uid);
        $data['nickName'] = $user->getUserInfoObject()->getNickName();
        $data['pos'] = $roomInfo->isRecommend;
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    public function getAnchorRecommendConfigList($skip, $limit)
    {
        try {
            $count = Rooms::count('isRecommend>0');
            $configDataList = Rooms::find(
                array(
                    'conditions' => 'isRecommend>0',
                    "limit" => array("number" => $limit, "offset" => $skip),
                    'order' => 'isRecommend asc',
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['uid'] = $configData->uid;
                    $user = UserFactory::getInstance($configData->uid);
                    $data['nickName'] = $user->getUserInfoObject()->getNickName();
                    $data['pos'] = $configData->isRecommend;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 系统公告
     *
     * @param $uid
     * @param $pos
     * @return mixed
     */

    public function addAnnouncement($content, $url, $status = 0, $runNum = 1)
    {
        // 判断是否有这个主播
        $count = AnnouncementList::count("status=0");
        if ($count >= 10) {
            return $this->status->retFromFramework($this->status->getCode('CONTENT_IS_TOOlONG'));
        }

        !$runNum && $runNum = 1;
        !$status && $status = 0;

        $model = new AnnouncementList();
        $model->content = $content;
        $model->url = $url;
        $model->runNum = $runNum;
        $model->status = $status;
        $model->addtime = time();
        $ret = $model->save();
        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function delAnnouncement($id)
    {
        $info = AnnouncementList::findFirst("id={$id}");
        if (empty($info)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }

        $ret = $info->delete();
        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function updateAnnouncement($id, $content, $url, $status, $runNum = 1)
    {
        $info = AnnouncementList::findFirst("id={$id}");
        if (empty($info)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }
        !$runNum && $runNum = 1;
        !$status && $status = 0;

        $info->content = $content;
        $info->url = $url;
        $info->status = $status;
        $info->runNum = $runNum;
        $info->addtime = time();
        $ret = $info->save();
        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function getAnnouncementInfo($id)
    {
        $info = AnnouncementList::findFirst("id={$id}");
        if (empty($info)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }

        $data = $info->toArray();
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    public function getAnnouncementList($skip, $limit)
    {
        try {
            // $count = AnnouncementList::count('status=0');
            /*array(
                    "limit" => array("number" => $limit, "offset" => $skip),
                    'order' => 'id asc',
                )*/
            $configDataList = AnnouncementList::find('status = 0');

            $dataList = array();
            if ($configDataList->valid()) {
                $dataList = $configDataList->toArray();
            }

            // $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function sendAnnouncement($count = 0, $rate = 300)
    {
        set_time_limit(0);
        $result = $this->getAnnouncementList(0, 10);
        if ($result['code'] == $this->status->getCode('OK')) {
            $list = $result['data']['list'];
        } else {
            return $this->status->retFromFramework($result['code'], $result['data']);
        }

        if ($count > 0 && !empty($list)) {
            $roomModule = $this->di->get('roomModule');
            $announceNum = count($list);

            $res = \Micro\Models\AnnouncementLog::findFirst();
            if(!$res){
                $res = new \Micro\Models\AnnouncementLog();
                
            }
            $res->status = 1;
            $res->startTime = time();
            $res->runHours = $count;
            $res->seconds = $rate;
            $res->save();

            $endTime = $res->runHours * 3600 + $res->startTime;
            while (time() < $endTime) {
                $ArraySubData['controltype'] = "sysAnnouncement";
                $ArraySubData['data'] = $list;
                $res = \Micro\Models\AnnouncementLog::findFirst();
                if($res && $res->status == 1){
                    $internal = $res->seconds;
                    $this->errLog('sendAnnouncement start: time = ' . time() . ' internal = ' . $internal);
                    $roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);
                    $this->errLog('sendAnnouncement end: internal = ' . $internal);
                    sleep(30);
                }else{
                    break;
                }
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //获取系统公告信息
    public function getAnnouncementData(){
        $data = array('status'=>0);
        try {
            $res = \Micro\Models\AnnouncementLog::findFirst();
            if($res && $res->status == 1 && (time() < ($res->startTime + $res->runHours * 3600))){
                $nowTime = time();
                $data['status'] = $res->status;
                $data['runHours'] = $res->runHours;
                $data['seconds'] = $res->seconds;
                $data['hasRunTimes'] = $this->dealTime($nowTime, $res->startTime);
                $endTime = $res->startTime + 3600 * $res->runHours;
                $data['leftTimes'] = $this->dealTime($endTime, $nowTime);
            }
            
            return $data;
        } catch (\Exception $e) {
            $this->errLog('getAnnouncementData errorMessage = ' . $e->getMessage());
            return $data;
        }
    }

    private function dealTime($time, $createTime){
        $intervalTime = $time - $createTime;
        if($intervalTime < 60){
            return '0小时0分钟' . $intervalTime . '秒';
        }else if($intervalTime < 3600){
            $minutes = floor($intervalTime / 60);
            $seconds = $intervalTime - 60 * $minutes;
            return '0小时' . $minutes . '分' . $seconds . '秒';
        }else{
            $hours = floor($intervalTime / 3600);
            $leftTime = $intervalTime - 3600 * $hours;
            $minutes = floor($leftTime / 60);
            $seconds = $leftTime - 60 * $minutes;
            return $hours . '小时' . $minutes . '分' . $seconds . '秒';
        }
    }

    //关闭系统广播
    public function stopAnnouncement(){
        try {
            $res = \Micro\Models\AnnouncementLog::findFirst();
            if($res){
                $res->status = 0;
                $res->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getAppDownload($skip, $limit)
    {
        try {
            $count = AppVersionConfig::count();
            $configDataList = AppVersionConfig::find(
                array(
                    "limit" => array("number" => $limit, "offset" => $skip),
                    'order' => 'id desc',
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['updateContent'] = $configData->updateContent;
                    $data['size'] = $configData->size;
                    $data['version'] = $configData->version;
                    $data['addtime'] = date('Y-m-d H:i:s', $configData->addtime);
                    $data['id'] = $configData->id;
                    $data['device'] = $configData->device;
                    $data['status'] = $configData->status;
                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addAppDownload($version, $size, $updateContent, $status, $device){
        try{
            $t = time();
//            $sql = "update \Micro\Models\AppVersionConfig set status=0,addtime={$t}";
//            $query = $this->modelsManager->createQuery($sql);
//            $query->execute();

            $model = new AppVersionConfig();
            $model->version = $version;
            $model->updateContent = $updateContent;
            $model->size = $size;
            $model->addtime = time();
            $model->status = $status;
            $model->device = $device;
            $res = $model->save();
            if($res){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else{
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }
        }catch(\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delAppDownload($id){
        $info = AppVersionConfig::findFirst("id={$id}");
        if (empty($info)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }

        $ret = $info->delete();
        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function updateAppDownload($id, $version, $size, $updateContent, $status, $device){
        $info = AppVersionConfig::findFirst("id={$id}");
        if (empty($info)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }

        $info->version = $version;
        $info->updateContent = $updateContent;
        $info->size = $size;
        $info->addtime = time();
        $info->status = $status;
        $info->device = $device;
        $ret = $info->save();
        return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
    }

    public function getAppDownloadInfo($id)
    {
        $info = AppVersionConfig::findFirst("id={$id}");
        if (empty($info)) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }

        $data = $info->toArray();
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    public function getAppdownloadConfig($device = 0){
        $data = array();
        switch($device){
            case 0:
                // pc
                $info = AppVersionConfig::findFirst("device = 0 order by id desc");
                if (!empty($info)) {
                    $data['android'] = "http://cdn.91ns.com/download/android/NSLive_V{$info->version}.apk";
                    $data['version_android'] = $info->version;
                }

                $info = AppVersionConfig::findFirst("device = 1 order by id desc");
                if (!empty($info)) {
                    $data['pcios'] = "http://cdn.91ns.com/download/ios/91NSv{$info->version}.ipa";
                    //$data['ios'] = "itms-services://?action=download-manifest&url=https://cdnhttps-91ns-com.alikunlun.com/91ns_ios_v{$info->version}.plist";
                    //$data['ios'] = "itms-apps://itunes.apple.com/app/id958809157";//跳转到ios商城
                    $data['ios'] = "https://itunes.apple.com/cn/app/91ns-zhi-bo-xiu/id958809157?mt=8";
                    $data['version_pcios'] = $info->version;
                    $data['version_ios'] = $info->version;
                }
            break;
            case 1:
                // android
                $info = AppVersionConfig::findFirst("device = 0 order by id desc");
                if (!empty($info)) {
                    $data['version'] = $info->version;
                    $data['forced'] = $info->status > 0 ? TRUE : FALSE;
                    $data['url'] = "http://cdn.91ns.com/download/android/NSLive_V{$info->version}.apk";
                    $data['updateContent'] = $info->updateContent;
                }

                break;
            case 2:
                // ios
                $info = AppVersionConfig::findFirst("device = 1 order by id desc");
                if (!empty($info)) {
                    $data['version'] = $info->version;
                    $data['forced'] = $info->status > 0 ? TRUE : FALSE;
                    $data['url'] = "itms-services://?action=download-manifest&url=https://cdnhttps-91ns-com.alikunlun.com/91ns_ios_v{$info->version}.plist";
                    $data['updateContent'] = $info->updateContent;
                }

                break;
            case 3:
                // h5
                $info = AppVersionConfig::findFirst("device = 0 order by id desc");
                if (!empty($info)) {
                    $data['android'] = array(
                        'url' => "http://cdn.91ns.com/download/android/NSLive_V{$info->version}.apk",
                        'version' => $info->version,
                        'size' => $info->size,
                        'addtime' => $info->addtime,
                    );
                }

                $info = AppVersionConfig::findFirst("device = 1 order by id desc");
                if (!empty($info)) {
                    $data['pcios'] = array(
                        'url' => "http://cdn.91ns.com/download/ios/91NSv{$info->version}.ipa",
                        'version' => $info->version,
                        'size' => $info->size,
                        'addtime' => $info->addtime,
                    );
                }

                break;
        }

        return $data;
    }
}